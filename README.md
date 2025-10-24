# AgriConnect – Farm-to-Market Platform

![Laravel](https://img.shields.io/badge/Laravel-9.x-red?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.x-blue?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.x-orange?style=flat&logo=mysql)
![Python](https://img.shields.io/badge/Python-3.10-blue?style=flat&logo=python)
![FastAPI](https://img.shields.io/badge/FastAPI-0.100.0-green?style=flat)
![GitHub](https://img.shields.io/badge/GitHub-Repo-black?style=flat&logo=github)

**AgriConnect** is a web and ML-powered platform designed to connect farmers directly with buyers, enabling a seamless marketplace for agricultural products. It provides tools for listing products, predicting demand and prices, and managing buyer and seller dashboards.

---

## Table of Contents
- [Project Overview](#project-overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation & Setup](#installation--setup)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Team](#team)
- [License](#license)

---

## Project Overview
AgriConnect bridges the gap between farmers and buyers by providing a digital marketplace. It leverages Laravel for backend management and machine learning APIs for demand and price prediction, offering an integrated experience for both sellers (farmers) and buyers.

---

## Features
### General
- User registration & login
- Profile management
- Contact support

### Marketplace
- Browse agricultural products
- Add, update, and delete listings (Farmer dashboard)
- Purchase products (Buyer dashboard)
- Real-time notifications for orders

### ML Integration
- Demand prediction for products
- Price prediction using historical data
- Analytics dashboard for farmers

### Admin
- Manage users, products, and orders
- View platform analytics

---

## Technology Stack
- **Backend:** Laravel PHP Framework (MVC architecture)
- **Frontend:** Blade templates, HTML5, CSS3, JavaScript
- **Database:** MySQL
- **Machine Learning:** Python FastAPI / ML models for demand & price prediction
- **Version Control:** Git & GitHub

---

## Installation & Setup

### Prerequisites
- PHP >= 8.x
- Composer
- MySQL
- Node.js & npm
- Python >= 3.10 (for ML services)

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/Department-of-IT-FMSC-USJ/group-project-group-12.git
````

2. Navigate to the project:

   ```bash
   cd group-project-group-12
   ```
3. Install PHP dependencies:

   ```bash
   composer install
   ```
4. Configure `.env` file (database, app URL, keys)
5. Run migrations:

   ```bash
   php artisan migrate
   ```
6. Serve the application:

   ```bash
   php artisan serve
   ```
7. Start the ML API server (from `ML` folder):

   ```bash
   uvicorn main:app --reload --port 8001
   ```

---

## Usage

* Register as a buyer or farmer.
* Farmers can list products and view predictions.
* Buyers can browse products, place orders, and track them.
* Admin can manage all users and products.

---

## Team

* Dilutha Weerasigha
* Methsani Yowinma
* Kavya Witharana
* Hasinika Samarasinghe
* Thanushi Rathnajeewa

---

## License

This project is for academic purposes. For inquiries, contact the team.

