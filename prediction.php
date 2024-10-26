<?php
include 'db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current date and calculate the last 6 months
$currentMonth = date("Y-m-01 00:00:00");
$sixMonthsAgo = date("Y-m-d H:i:s", strtotime("-6 months", strtotime($currentMonth)));

// Query to get payment data from the last 6 months
$sql = "SELECT DATE_FORMAT(date_created, '%Y-%m') AS payment_month, SUM(amount) AS total_income
        FROM payments
        WHERE date_created >= '$sixMonthsAgo'
        GROUP BY payment_month
        ORDER BY payment_month ASC";

$result = $conn->query($sql);

$monthsData = [];
$previousIncome = null;
$growthRates = [];
$totalGrowth = 0;
$monthsCount = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $monthsData[] = $row;

        // Calculate growth rate between consecutive months
        if ($previousIncome !== null) {
            $growthRate = ($row['total_income'] - $previousIncome) / $previousIncome;
            $growthRates[] = $growthRate;
            $totalGrowth += $growthRate;
            $monthsCount++;
        }

        $previousIncome = $row['total_income'];
    }
}

// Calculate the average growth rate (if data exists)
$averageGrowthRate = ($monthsCount > 0) ? $totalGrowth / $monthsCount : 0;

// Predict income for the next 6 months based on the last month's income and average growth rate
$lastMonthIncome = $previousIncome;
$predictedIncomes = [];
$totalPredictedIncome = 0; // Variable to store the total predicted income

for ($i = 1; $i <= 6; $i++) {
    $predictedMonth = date("Y-m", strtotime("+$i months", strtotime($currentMonth)));
    $nextMonthIncome = $lastMonthIncome * (1 + $averageGrowthRate);  // Apply growth rate to last month’s income
    $predictedIncomes[] = [
        'month' => $predictedMonth,
        'predicted_income' => $nextMonthIncome
    ];
    $totalPredictedIncome += $nextMonthIncome;  // Add to total predicted income
    $lastMonthIncome = $nextMonthIncome;  // Update for the next iteration
}

// Close connection
$conn->close();
?>

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

            <div class="col-md-12">
                <h2>Income Prediction Graph</h2>
                <canvas id="incomePredictionChart"></canvas> <!-- Canvas for the chart -->
            </div>
        </div>
    </div>

    <script>
        // Prepare the data for the chart
        var ctx = document.getElementById('incomePredictionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line', // Type of chart: line
            data: {
                labels: <?php echo json_encode(array_column($predictedIncomes, 'month')); ?>, // Months (X-axis)
                datasets: [{
                    label: 'Predicted Income',
                    data: <?php echo json_encode(array_column($predictedIncomes, 'predicted_income')); ?>, // Income (Y-axis)
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light blue background
                    borderColor: 'rgba(75, 192, 192, 1)', // Blue line
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