<?php
// Customer class; Uses database view v_customer (includes loyalty points)
class Customer {
    public $id;
    public $name;
    public $email;
    public $phone_number;
    public $created_at;
    public $loyalty_points;

    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->phone_number = $row['phone_number'];
        $this->created_at = $row['created_at'];
        $this->loyalty_points = $row['loyalty_points'] ?? 0;
    }
}
?>
