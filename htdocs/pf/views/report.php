<?php
// views/report.php
// Query the custom view, retrieving the columns for report
$sql = "SELECT Month, TotalSpent, AvgPerCustomer, LoyaltyPoints FROM v_monthly_report";
$results = $conn->query($sql)->fetchAll();
?>

<h2>Monthly Report</h2>
<table border="1" cellspacing="0" cellpadding="4">
    <tr>
        <th>Month</th><th>Total Spent</th><th>Avg per Customer</th><th>Loyalty Points</th>
    </tr>
    <?php foreach ($results as $row): ?>
        <tr>
            <td align="center"><?= htmlspecialchars($row['Month']) ?></td>
            <td align="center"><?= htmlspecialchars($row['TotalSpent']) ?></td>
            <td align="center"><?= htmlspecialchars($row['AvgPerCustomer']) ?></td>
            <td align="center"><?= htmlspecialchars($row['LoyaltyPoints']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
