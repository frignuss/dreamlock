<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart.js Test - DreamLock</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            padding: 20px;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: #111111;
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #ee819f;
        }
        .chart-container {
            height: 300px;
            margin: 20px 0;
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .success { background: #0f2a0f; color: #b6fcb6; }
        .error { background: #2a0f0f; color: #fcb6b6; }
        .info { background: #0f1a2a; color: #b6c8fc; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>Chart.js Test Page</h1>
        
        <div id="status" class="status info">Testing Chart.js...</div>
        
        <div class="chart-container">
            <canvas id="testChart"></canvas>
        </div>
        
        <div class="chart-container">
            <canvas id="testChart2"></canvas>
        </div>
        
        <div class="chart-container">
            <canvas id="testChart3"></canvas>
        </div>
    </div>

    <script>
        // Test Chart.js availability
        const statusDiv = document.getElementById('status');
        
        if (typeof Chart === 'undefined') {
            statusDiv.className = 'status error';
            statusDiv.textContent = '❌ Chart.js is not loaded!';
        } else {
            statusDiv.className = 'status success';
            statusDiv.textContent = '✅ Chart.js is loaded successfully!';
            
            // Create test charts
            try {
                // Test Chart 1 - Line Chart
                const ctx1 = document.getElementById('testChart').getContext('2d');
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                        datasets: [{
                            label: 'Test Data',
                            data: [12, 19, 3, 5, 2],
                            borderColor: '#ee819f',
                            backgroundColor: 'rgba(238, 129, 159, 0.1)',
                            borderWidth: 3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Test Line Chart',
                                color: '#ee819f'
                            }
                        }
                    }
                });
                
                // Test Chart 2 - Bar Chart
                const ctx2 = document.getElementById('testChart2').getContext('2d');
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['A', 'B', 'C', 'D', 'E'],
                        datasets: [{
                            label: 'Test Bars',
                            data: [65, 59, 80, 81, 56],
                            backgroundColor: 'rgba(238, 129, 159, 0.8)',
                            borderColor: '#ee819f',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Test Bar Chart',
                                color: '#ee819f'
                            }
                        }
                    }
                });
                
                // Test Chart 3 - Doughnut Chart
                const ctx3 = document.getElementById('testChart3').getContext('2d');
                new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: ['Red', 'Blue', 'Yellow'],
                        datasets: [{
                            data: [300, 50, 100],
                            backgroundColor: ['#ee819f', '#4ade80', '#fbbf24'],
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Test Doughnut Chart',
                                color: '#ee819f'
                            }
                        }
                    }
                });
                
                console.log('Chart.js test completed successfully!');
                
            } catch (error) {
                statusDiv.className = 'status error';
                statusDiv.textContent = '❌ Error creating charts: ' + error.message;
                console.error('Chart creation error:', error);
            }
        }
    </script>
</body>
</html>

