from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import pandas as pd
import joblib
from prophet import Prophet
import os
from typing import Optional
import logging

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(title="Crop Prediction API")

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://127.0.0.1:8000", "http://localhost:8000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Paths to your trained models
price_path = "/Users/diluthaweerasigha/Documents/WEB DB Project/ML/trained_models"
demand_path = "/Users/diluthaweerasigha/Documents/WEB DB Project/ML/trained_models-2"

# Cache for loaded models
price_models = {}
demand_models = {}

# Demo feature values
DEMO_FEATURES = {
    "inflation": 1.5,
    "fuel_price": 298.0,
    "exchange_rate": 303.62,
    "temperature": 27.0,
    "rainfall": 140.0,
    "GDP": 98.96,
    "Rice_Production": 3800.0,
    "lag1": 100.0,
    "lag7": 105.0,
    "lag30": 110.0,
    "category_code": 0,
    "month": 10,
    "quarter": 4
}

class PredictionRequest(BaseModel):
    crop: str
    days: int
    inflation: Optional[float] = None
    fuel_price: Optional[float] = None
    exchange_rate: Optional[float] = None
    temperature: Optional[float] = None
    rainfall: Optional[float] = None
    GDP: Optional[float] = None
    Rice_Production: Optional[float] = None
    lag1: Optional[float] = None
    lag7: Optional[float] = None
    lag30: Optional[float] = None
    category_code: Optional[int] = None
    month: Optional[int] = None
    quarter: Optional[int] = None

def load_price_model(crop_name):
    """Load and cache price model"""
    if crop_name not in price_models:
        model_file = os.path.join(price_path, f"{crop_name}_xgb.pkl")
        if not os.path.exists(model_file):
            return None
        price_models[crop_name] = joblib.load(model_file)
        logger.info(f"Loaded price model for {crop_name}")
    return price_models[crop_name]

def load_demand_model(crop_name):
    """Load and cache demand model"""
    if crop_name not in demand_models:
        model_file = os.path.join(demand_path, f"{crop_name}_prophet.pkl")
        if not os.path.exists(model_file):
            return None
        demand_models[crop_name] = joblib.load(model_file)
        logger.info(f"Loaded demand model for {crop_name}")
    return demand_models[crop_name]

@app.get("/")
def root():
    return {"message": "Crop Prediction API is running"}

@app.get("/crops")
def get_available_crops():
    """Get list of available crops with both models"""
    price_crops = set()
    demand_crops = set()
    
    try:
        if os.path.exists(price_path):
            for file in os.listdir(price_path):
                if file.endswith('_xgb.pkl'):
                    crop_name = file.replace('_xgb.pkl', '')
                    price_crops.add(crop_name)
        
        if os.path.exists(demand_path):
            for file in os.listdir(demand_path):
                if file.endswith('_prophet.pkl'):
                    crop_name = file.replace('_prophet.pkl', '')
                    demand_crops.add(crop_name)
        
        available_crops = sorted(list(price_crops.intersection(demand_crops)))
        logger.info(f"Found {len(available_crops)} crops with both models")
        
        return {"crops": available_crops, "count": len(available_crops)}
    except Exception as e:
        logger.error(f"Error getting crops: {e}")
        return {"crops": [], "count": 0, "error": str(e)}

@app.post("/predict")
async def predict(data: PredictionRequest):
    try:
        logger.info(f"Starting prediction for {data.crop}, {data.days} days")
        
        crop_lower = data.crop.lower()
        
        # Use demo features for any missing values
        features_dict = {}
        for key, default_value in DEMO_FEATURES.items():
            provided_value = getattr(data, key, None)
            features_dict[key] = provided_value if provided_value is not None else default_value
        
        # === PRICE PREDICTION ===
        logger.info("Loading price model...")
        price_model = load_price_model(crop_lower)
        
        if price_model is None:
            return {"success": False, "error": f"Price model not found for crop: {data.crop}"}
        
        logger.info("Predicting price...")
        features = [
            "inflation", "fuel_price", "exchange_rate", "temperature", "rainfall", "GDP",
            "Rice_Production", "lag1", "lag7", "lag30", "category_code", "month", "quarter"
        ]
        demo_features = pd.DataFrame([{f: features_dict[f] for f in features}])
        predicted_price = float(price_model.predict(demo_features)[0])
        logger.info(f"Price predicted: {predicted_price}")
        
        # === DEMAND FORECAST ===
        logger.info("Loading demand model...")
        demand_model = load_demand_model(crop_lower)
        
        if demand_model is None:
            return {"success": False, "error": f"Demand model not found for crop: {data.crop}"}
        
        logger.info(f"Forecasting demand for {data.days} days...")
        
        try:
            # Get the last date from training data
            last_date = demand_model.history['ds'].max()
            
            # Create future dataframe
            future_dates = pd.date_range(
                start=last_date + pd.Timedelta(days=1), 
                periods=data.days, 
                freq='D'
            )
            future = pd.DataFrame({'ds': future_dates})
            
            # Check if model has regressors and add them to future dataframe
            if hasattr(demand_model, 'extra_regressors') and demand_model.extra_regressors:
                logger.info(f"Model has regressors: {list(demand_model.extra_regressors.keys())}")
                
                # Add all regressors with demo values
                for regressor in demand_model.extra_regressors.keys():
                    if regressor in features_dict:
                        future[regressor] = features_dict[regressor]
                    else:
                        # Use a default value if not in features_dict
                        future[regressor] = 0
                        logger.warning(f"Regressor {regressor} not found, using default value 0")
            
            # Predict
            forecast = demand_model.predict(future)
            
            # Extract only what we need
            demand_forecast = forecast[['ds', 'yhat', 'yhat_lower', 'yhat_upper']].to_dict(orient='records')
            
            # Format dates
            for item in demand_forecast:
                item['ds'] = item['ds'].strftime('%Y-%m-%d')
                item['yhat'] = round(float(item['yhat']), 2)
                item['yhat_lower'] = round(float(item['yhat_lower']), 2)
                item['yhat_upper'] = round(float(item['yhat_upper']), 2)
            
            logger.info("Prediction completed successfully")
            
            return {
                "success": True,
                "crop": data.crop,
                "predicted_price": round(predicted_price, 2),
                "demand_forecast": demand_forecast,
                "forecast_days": len(demand_forecast)
            }
            
        except Exception as prophet_error:
            logger.error(f"Prophet prediction error: {str(prophet_error)}")
            
            # Fallback: Return price only if demand forecast fails
            return {
                "success": True,
                "crop": data.crop,
                "predicted_price": round(predicted_price, 2),
                "demand_forecast": [],
                "forecast_days": 0,
                "warning": f"Demand forecast unavailable: {str(prophet_error)}"
            }
        
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}", exc_info=True)
        return {"success": False, "error": str(e)}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8001, log_level="info")