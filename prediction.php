<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Prediction Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Income Prediction Dashboard</h1>
        <div class="row">
            <!-- Table for last 6 months' income -->
            <div class="col-md-12">
                <h2>Last 6 Months Income</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthsData as $data): ?>
                            <tr>
                                <td><?php echo $data['payment_month']; ?></td>
                                <td><?php echo "Php" . number_format($data['total_income'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Table for predicted income for next 6 months -->
            <div class="col-md-12">
                <h2>Predicted Income for Next 6 Months</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Predicted Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($predictedIncomes as $predicted): ?>
                            <tr>
                                <td><?php echo $predicted['month']; ?></td>
                                <td><?php echo "Php" . number_format($predicted['predicted_income'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Total Predicted Income</th>
                            <th><?php echo "Php" . number_format($totalPredictedIncome, 2); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Chart for last 6 months' historical income -->
            <div class="col-md-12">
                <h2>Historical Income (Last 6 Months)</h2>
                <canvas id="historicalIncomeChart"></canvas> <!-- Canvas for historical chart -->
            </div>

            <!-- Chart for predicted income -->
            <div class="col-md-12">
                <h2>Predicted Income Graph</h2>
                <canvas id="incomePredictionChart"></canvas> <!-- Canvas for prediction chart -->
            </div>
        </div>
    </div>

    <script>
        // Data for historical income chart
        var historicalCtx = document.getElementById('historicalIncomeChart').getContext('2d');
        var historicalChart = new Chart(historicalCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthsData, 'payment_month')); ?>, // Months (X-axis)
                datasets: [{
                    label: 'Historical Income',
                    data: <?php echo json_encode(array_column($monthsData, 'total_income')); ?>, // Income (Y-axis)
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue background
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue line
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Data for predicted income chart
        var ctx = document.getElementById('incomePredictionChart').getContext('2d');
        var predictionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($predictedIncomes, 'month')); ?>, // Months (X-axis)
                datasets: [{
                    label: 'Predicted Income',
                    data: <?php echo json_encode(array_column($predictedIncomes, 'predicted_income')); ?>, // Income (Y-axis)
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light green background
                    borderColor: 'rgba(75, 192, 192, 1)', // Green line
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
