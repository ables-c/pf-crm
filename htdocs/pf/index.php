<?php
// index.php - Entry point, routes everything

require_once 'config.php';
require_once 'Customer.php';
require_once 'Database.php';
require_once 'functions.php';

// Connect to the database
$db = new Database();
$conn = $db->getConnection();

// Basic Routing
// Get value of 'page' argument; use 'home' if null
$page = $_GET['page'] ?? 'home';

// Include the contents of the desired view file
switch ($page) {
    case 'import':
        include 'views/import.php';
        break;
    case 'customers':
        include 'views/customers.php';
        break;
    case 'report':
        include 'views/report.php';
        break;
    default:
        include 'views/home.php';
}
