@extends('layouts.app')
@section('title', 'Price & Demand Analysis - AgriConnect')
@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">🌱 Crop Price & Demand Prediction</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Prediction Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Select Parameters</h2>
            <form id="predictForm" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Crop Type</label>
                    <select name="crop" id="crop" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600" required>
                        <option value="">Select a crop...</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop }}">{{ ucfirst($crop) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Forecast Period</label>
                    <select name="days" id="days" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600" required>
                        <option value="7">7 Days</option>
                        <option value="15">15 Days</option>
                        <option value="30" selected>30 Days</option>
                        <option value="60">60 Days</option>
                    </select>
                </div>

                <button type="submit" id="predictBtn" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Generate Prediction
                </button>

                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm">
                    <p class="text-blue-800 dark:text-blue-300">
                        <strong>ℹ️ Demo Mode:</strong> Using default economic parameters for prediction.
                    </p>
                </div>
            </form>
        </div>

        <!-- Results Display -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Loading State -->
            <div id="loading" class="hidden bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-b-4 border-green-600"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400 text-lg">Analyzing crop data...</p>
            </div>

            <!-- Error State -->
            <div id="error" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-red-800 dark:text-red-300">Prediction Error</h3>
                        <p class="text-red-700 dark:text-red-300 mt-1" id="errorMessage"></p>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div id="results" class="hidden space-y-6">
                <!-- Predicted Price Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg shadow p-6 border-2 border-green-200 dark:border-green-700">
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-300 mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Predicted Price for <span id="cropName" class="text-green-600 dark:text-green-400"></span>
                    </h3>
                    <div class="flex items-baseline gap-2">
                        <p class="text-5xl font-bold text-green-600 dark:text-green-400">LKR <span id="predictedPrice">--</span></p>
                        <p class="text-sm text-green-700 dark:text-green-500">per kg</p>
                    </div>
                </div>

                <!-- Demand Forecast Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Demand Forecast (<span id="forecastDays">30</span> Days)
                    </h3>
                    <canvas id="demandChart" class="w-full" style="max-height: 400px;"></canvas>
                </div>

                <!-- Demand Data Table -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detailed Forecast Data</h3>
                    <div class="max-h-96 overflow-y-auto">
                        <table class="w-full">
                            <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Date</th>
                                    <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Predicted Demand (kg)</th>
                                    <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Range</th>
                                </tr>
                            </thead>
                            <tbody id="demandTable" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Initial Message -->
            <div id="initialMessage" class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Ready to Predict</h3>
                <p class="text-gray-600 dark:text-gray-400">Select a crop and forecast period to get started</p>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
let demandChart = null;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('predictForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';
        
        // Show loading, hide others
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('results').classList.add('hidden');
        document.getElementById('error').classList.add('hidden');
        document.getElementById('initialMessage').classList.add('hidden');
        document.getElementById('predictBtn').disabled = true;
        document.getElementById('predictBtn').innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
        
        try {
            const response = await fetch('{{ route("analysis.predict") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                displayResults(result, data.crop, data.days);
            } else {
                throw new Error(result.message || result.error || 'Prediction failed');
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('error').classList.remove('hidden');
            document.getElementById('errorMessage').textContent = error.message || 'Failed to get prediction. Make sure FastAPI server is running on port 8001.';
        } finally {
            document.getElementById('predictBtn').disabled = false;
            document.getElementById('predictBtn').innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Generate Prediction';
        }
    });
});

function displayResults(result, cropName, days) {
    // Update price
    document.getElementById('predictedPrice').textContent = result.predicted_price.toFixed(2);
    document.getElementById('cropName').textContent = cropName.charAt(0).toUpperCase() + cropName.slice(1);
    document.getElementById('forecastDays').textContent = days;
    
    // Prepare chart data
    const labels = result.demand_forecast.map(item => item.ds);
    const demandData = result.demand_forecast.map(item => item.yhat);
    const lowerBound = result.demand_forecast.map(item => item.yhat_lower);
    const upperBound = result.demand_forecast.map(item => item.yhat_upper);
    
    // Destroy existing chart if any
    if (demandChart) {
        demandChart.destroy();
    }
    
    // Create new chart
    const ctx = document.getElementById('demandChart').getContext('2d');
    demandChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Predicted Demand',
                data: demandData,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return 'Demand: ' + context.parsed.y.toFixed(2) + ' kg';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151',
                        callback: function(value) {
                            return value.toFixed(0) + ' kg';
                        }
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    }
                },
                x: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151',
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    }
                }
            }
        }
    });
    
    // Update table
    const tableBody = document.getElementById('demandTable');
    tableBody.innerHTML = result.demand_forecast.map(item => `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${item.ds}</td>
            <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">${item.yhat.toFixed(2)} kg</td>
            <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">${item.yhat_lower.toFixed(2)} - ${item.yhat_upper.toFixed(2)}</td>
        </tr>
    `).join('');
    
    // Show results
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('results').classList.remove('hidden');
}
</script>
@endsection