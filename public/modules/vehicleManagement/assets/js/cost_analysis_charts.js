/**
 * Vehicle Cost Analysis Charts
 * Handles initialization and rendering of cost analysis charts
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts when Cost Analysis tab is clicked
    const costAnalysisTab = document.querySelector('a[href="#costAnalysis"]');
    if (costAnalysisTab) {
        costAnalysisTab.addEventListener('click', function() {
            // Delay to ensure tab is active and DOM is ready
            setTimeout(initializeVehicleCostCharts, 500);
        });
        
        // Also initialize on tab show event
        costAnalysisTab.addEventListener('shown.bs.tab', function() {
            setTimeout(initializeVehicleCostCharts, 300);
        });
    }
    
    // Auto-initialize if Cost Analysis tab is already active
    if (document.querySelector('#costAnalysis.active')) {
        setTimeout(initializeVehicleCostCharts, 1000);
    }
});

function initializeVehicleCostCharts() {
    try {
        console.log('Initializing Vehicle Cost Charts...');
        
        // Parse JSON data if it's a string
        let costSummary = window.costSummary;
        let fuelCostAnalysis = window.fuelCostAnalysis;
        let maintenanceAnalysis = window.maintenanceAnalysis;
        
        if (typeof costSummary === 'string') {
            costSummary = JSON.parse(costSummary);
        }
        if (typeof fuelCostAnalysis === 'string') {
            fuelCostAnalysis = JSON.parse(fuelCostAnalysis);
        }
        if (typeof maintenanceAnalysis === 'string') {
            maintenanceAnalysis = JSON.parse(maintenanceAnalysis);
        }
        
        // Wait for DOM to be ready
        if (document.readyState !== 'complete') {
            console.log('DOM not ready, retrying in 100ms...');
            setTimeout(initializeVehicleCostCharts, 100);
            return;
        }
        
        // Check retry counter to prevent infinite loops
        window.chartInitRetryCount = (window.chartInitRetryCount || 0) + 1;
        if (window.chartInitRetryCount > 5) {
            console.log('Max retries reached, stopping initialization');
            return;
        }
        
        console.log('Cost Summary:', costSummary);
        console.log('Fuel Analysis:', fuelCostAnalysis);
        console.log('Maintenance Analysis:', maintenanceAnalysis);
        
        // Cost Distribution Chart
        const costDistributionCtx = document.getElementById('vehicleCostDistributionChart');
        if (costDistributionCtx) {
            // Check if canvas is properly rendered
            try {
                const ctx = costDistributionCtx.getContext('2d');
                if (!ctx) {
                    console.log('Canvas context not available, retrying...');
                    setTimeout(initializeVehicleCostCharts, 200);
                    return;
                }
            } catch (e) {
                console.log('Canvas context error:', e);
                setTimeout(initializeVehicleCostCharts, 200);
                return;
            }
                // Destroy existing chart if it exists
                if (costDistributionCtx.chart) {
                    costDistributionCtx.chart.destroy();
                    costDistributionCtx.chart = null;
                }
                
                if (costSummary) {
                    const fuelPercentage = parseFloat(costSummary?.cost_breakdown?.fuel_percentage || 0);
                    const maintenancePercentage = parseFloat(costSummary?.cost_breakdown?.maintenance_percentage || 0);

                    console.log('Fuel Percentage:', fuelPercentage);
                    console.log('Maintenance Percentage:', maintenancePercentage);

                    if (fuelPercentage > 0 || maintenancePercentage > 0) {
                        costDistributionCtx.chart = new Chart(costDistributionCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Fuel Cost', 'Maintenance Cost'],
                            datasets: [{
                                data: [fuelPercentage, maintenancePercentage],
                                backgroundColor: [
                                    'rgba(25, 135, 84, 0.9)',
                                    'rgba(255, 193, 7, 0.9)'
                                ],
                                borderColor: [
                                    'rgba(25, 135, 84, 1)',
                                    'rgba(255, 193, 7, 1)'
                                ],
                                borderWidth: 2,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: '#ddd',
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: true,
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed.toFixed(1) + '%';
                                        }
                                    }
                                }
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            }
                        }
                    });
                    console.log('Cost Distribution Chart initialized successfully');
                }
            }
        }

        // Monthly Trends Chart
        const trendsCtx = document.getElementById('vehicleMonthlyTrendsChart');
        if (trendsCtx) {
            // Check if canvas is properly rendered
            try {
                const ctx = trendsCtx.getContext('2d');
                if (!ctx) {
                    console.log('Monthly trends canvas context not available, retrying...');
                    setTimeout(initializeVehicleCostCharts, 200);
                    return;
                }
            } catch (e) {
                console.log('Monthly trends canvas context error:', e);
                setTimeout(initializeVehicleCostCharts, 200);
                return;
            }
                // Destroy existing chart if it exists
                if (trendsCtx.chart) {
                    trendsCtx.chart.destroy();
                    trendsCtx.chart = null;
                }
                
                if (fuelCostAnalysis && maintenanceAnalysis) {
                const fuelData = fuelCostAnalysis || [];
                const maintenanceData = maintenanceAnalysis || [];
                
                console.log('Fuel Data:', fuelData);
                console.log('Maintenance Data:', maintenanceData);
                
                // Get all unique periods
                const periods = [...new Set([
                    ...fuelData.map(item => item.period),
                    ...maintenanceData.map(item => item.period)
                ])].sort();

                const fuelCosts = periods.map(period => {
                    const item = fuelData.find(f => f.period === period);
                    return item ? parseFloat(item.total_cost || 0) : 0;
                });

                const maintenanceCosts = periods.map(period => {
                    const item = maintenanceData.find(m => m.period === period);
                    return item ? parseFloat(item.total_cost || 0) : 0;
                });

                console.log('Periods:', periods);
                console.log('Fuel Costs:', fuelCosts);
                console.log('Maintenance Costs:', maintenanceCosts);

                if (periods.length > 0) {
                    trendsCtx.chart = new Chart(trendsCtx, {
                        type: 'line',
                        data: {
                            labels: periods,
                            datasets: [
                                {
                                    label: 'Fuel Cost',
                                    data: fuelCosts,
                                    borderColor: 'rgba(25, 135, 84, 1)',
                                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: 'rgba(25, 135, 84, 1)',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 5,
                                    pointHoverRadius: 7
                                },
                                {
                                    label: 'Maintenance Cost',
                                    data: maintenanceCosts,
                                    borderColor: 'rgba(255, 193, 7, 1)',
                                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: 'rgba(255, 193, 7, 1)',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 5,
                                    pointHoverRadius: 7
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            size: 12,
                                            weight: '600'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: '#ddd',
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: true,
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ZMW ' + context.parsed.y.toFixed(2);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return 'ZMW ' + value.toLocaleString();
                                        },
                                        font: {
                                            size: 11
                                        },
                                        color: '#666'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        },
                                        color: '#666'
                                    }
                                }
                            },
                            animation: {
                                duration: 1000,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });
                    console.log('Monthly Trends Chart initialized successfully');
                }
                }
            }
        }
    } catch (error) {
        console.error('Error initializing vehicle cost charts:', error);
    }
}
