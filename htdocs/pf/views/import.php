<?php
// views/import.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['customers_csv']) && $_FILES['customers_csv']['error'] === UPLOAD_ERR_OK) {
        $file = fopen($_FILES['customers_csv']['tmp_name'], 'r');
        fgetcsv($file); // Skip header
        while ($row = fgetcsv($file)) {
            list($name, $email, $phone) = $row;
            $created_at = date('Y-m-d');
            list($name, $email) = sanitizeCustomer($name, $email);
            if (validateCustomer($name, $email, $phone, $created_at)) {
                insertCustomer($conn, $name, $email, $phone, $created_at);
            }
        }
        fclose($file);
        echo "Customers imported successfully.<br>";
    }

    if (isset($_FILES['purchases_csv']) && $_FILES['purchases_csv']['error'] === UPLOAD_ERR_OK) {
        $file = fopen($_FILES['purchases_csv']['tmp_name'], 'r');
        fgetcsv($file); // Skip header
        while ($row = fgetcsv($file)) {
            list($customer_email, $purchasable, $price, $quantity, $total, $date) = $row;
            $customer_email = strtolower(trim($customer_email));
            $customer_id = getCustomerIdByEmail($conn, $customer_email);
            if ($customer_id) {
                insertPurchase($conn, $customer_id, $purchasable, $price, $quantity, $total, $date);
            }
        }
        fclose($file);
        echo "Purchases imported successfully.<br>";
    }
}
?>

<h2>Import CSV Data</h2>
<form method="post" enctype="multipart/form-data">
    <label>Customers CSV:</label>
    <input type="file" name="customers_csv" required><br><br>
    <label>Purchase History CSV:</label>
    <input type="file" name="purchases_csv" required><br><br>
    <input type="submit" value="Import">
</form>
