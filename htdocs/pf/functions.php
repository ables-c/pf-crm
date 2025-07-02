<?php
// Validation and data handling functions

function validateCustomer($name, $email, $phone, $created_at) {
    if (!preg_match('/^[A-Za-z ]+$/', $name)) return false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone)) return false;
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $created_at)) return false;
    return true;
}

function sanitizeCustomer($name, $email) {
    // FILTER_SANITIZE_STRING has been deprecated
    // $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = strtolower(trim($email));
    return [$name, $email];
}

function getCustomerIdByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    return $row ? $row['id'] : null;
}

function insertCustomer($conn, $name, $email, $phone, $created_at) {
    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone_number, created_at) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), phone_number=VALUES(phone_number)");
    $stmt->execute([$name, $email, $phone, $created_at]);
}

function insertPurchase($conn, $customer_id, $purchasable, $price, $quantity, $total, $date) {
    $stmt = $conn->prepare("INSERT INTO purchase_history (customer_id, purchasable, price, quantity, total, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$customer_id, $purchasable, $price, $quantity, $total, $date]);
}
