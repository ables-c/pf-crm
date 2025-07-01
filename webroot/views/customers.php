<?php
// views/customers.php
require_once 'Customer.php';

$filter_sql = "SELECT * FROM v_customers WHERE 1";
$params = [];

if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $filter_sql .= " AND created_at BETWEEN ? AND ?";
    $params[] = $_GET['start_date'];
    $params[] = $_GET['end_date'];
}
if (!empty($_GET['min_spending'])) {
    $filter_sql .= " AND loyalty_points >= ?";
    $params[] = $_GET['min_spending'];
}

$stmt = $conn->prepare($filter_sql);
$stmt->execute($params);
$customers = [];
while ($row = $stmt->fetch()) {
    $customers[] = new Customer($row);
}
?>

<h2>Customer List</h2>
<form method="get">
    <input type="hidden" name="page" value="customers">
    <label>Start Date:</label><input type="date" name="start_date">
    <label>End Date:</label><input type="date" name="end_date">
    <label>Min Loyalty Points:</label><input type="number" name="min_spending">
    <input type="submit" value="Filter">
</form>

<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Created At</th><th>Loyalty Points</th></tr>
    <?php foreach ($customers as $cust): ?>
        <tr>
            <td><?= htmlspecialchars($cust->id) ?></td>
            <td><?= htmlspecialchars($cust->name) ?></td>
            <td><?= htmlspecialchars($cust->email) ?></td>
            <td><?= htmlspecialchars($cust->phone_number) ?></td>
            <td><?= htmlspecialchars($cust->created_at) ?></td>
            <td><?= htmlspecialchars($cust->loyalty_points) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
