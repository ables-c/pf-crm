<?php
// views/report.php
$sql = "SELECT Month, TotalSpent, AvgPerCustomer, LoyaltyPoints FROM v_monthly_report";
$results = $conn->query($sql)->fetchAll();
?>

<h2>Monthly Report</h2>
<table border="1" cellpadding="3">
    <tr>
        <th>Month</th><th>Total Spent</th><th>Avg per Customer</th><th>Loyalty Points</th>
    </tr>
    <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['Month']) ?></td>
            <td><?= htmlspecialchars($row['TotalSpent']) ?></td>
            <td><?= htmlspecialchars($row['AvgPerCustomer']) ?></td>
            <td><?= htmlspecialchars($row['LoyaltyPoints']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
