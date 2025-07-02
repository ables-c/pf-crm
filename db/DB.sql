-- --------------------------------------------------------------------------------------
-- Create Database
-- --------------------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS pf_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE pf_crm;

-- --------------------------------------------------------------------------------------
-- Create Tables
-- --------------------------------------------------------------------------------------
CREATE TABLE customers (
  id            INT            NOT NULL AUTO_INCREMENT,
  name          VARCHAR(50)    NOT NULL,
  email         VARCHAR(50)    NOT NULL,
  phone_number  VARCHAR(12)    DEFAULT NULL,
  created_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE  KEY (email)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE purchase_history (
  id            INT            NOT NULL AUTO_INCREMENT,
  customer_id   INT            NOT NULL,
  purchasable   VARCHAR(50)    NOT NULL,
  price         DECIMAL(8,2),
  quantity      INT,
  total         DECIMAL(10,2),
  purchase_date DATE           NOT NULL,
  PRIMARY KEY (id),
  KEY customer_id (customer_id),
  CONSTRAINT customer_id_fk FOREIGN KEY (customer_id) REFERENCES customers (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------------------------------
-- Create Role & User
-- --------------------------------------------------------------------------------------
CREATE ROLE pf_role;
GRANT ALL ON pf_crm.* TO pf_role;
CREATE USER pf_user IDENTIFIED BY 'pf_user' DEFAULT ROLE pf_role PASSWORD EXPIRE NEVER;

-- --------------------------------------------------------------------------------------
-- Set Configuration Options
-- --------------------------------------------------------------------------------------
SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
SET PERSIST sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

-- -------------------------------
-- Connection Info
-- -------------------------------
-- Host:   127.0.0.1  (localhost)
-- Port:   3306
-- User:   pf_user
-- Pass:   pf_user
-- Schema: pf_crm
-- -------------------------------

-- --------------------------------------------------------------------------------------
-- Create function to calculate loyalty points for a customer
-- --------------------------------------------------------------------------------------
DROP FUNCTION IF EXISTS getLoyaltyPointsCustomer;
DELIMITER $$
CREATE FUNCTION getLoyaltyPointsCustomer (
  customerId INT)
RETURNS INT
DETERMINISTIC
BEGIN
  DECLARE totalPoints INT;
  SELECT TRUNCATE(SUM(total)/10,0) + TRUNCATE(COUNT(*)/10,0)
    INTO totalPoints
    FROM purchase_history
    WHERE customer_id=customerId AND purchase_date>='2022-01-01';
  RETURN totalPoints;
END $$
DELIMITER ;
COMMIT;

-- --------------------------------------------------------------------------------------
-- Create function to get loyalty points awarded in a month
-- --------------------------------------------------------------------------------------
DROP FUNCTION IF EXISTS getLoyaltyPointsMonth;
DELIMITER $$
CREATE FUNCTION getLoyaltyPointsMonth (
  dateInMonth DATE)
RETURNS INT
DETERMINISTIC
BEGIN
  DECLARE dtFrom      DATE;
  DECLARE dtTo        DATE;
  DECLARE totalPoints INT;
  IF (dateInMonth < '2022-01-01') THEN
    RETURN 0;
  ELSE
    SET dtFrom = CONCAT(YEAR(dateInMonth),'-',MONTH(dateInMonth),'-01');
    SET dtTo = DATE_ADD(dtFrom, INTERVAL 1 MONTH);
    SELECT TRUNCATE(SUM(total)/10,0) + TRUNCATE(COUNT(*)/10,0)
      INTO totalPoints
      FROM purchase_history
      WHERE purchase_date>=dtFrom AND purchase_date<dtTo;
    RETURN totalPoints;
  END IF;
END $$
DELIMITER ;
COMMIT;

-- --------------------------------------------------------------------------------------
-- Create view of customers table that includes loyalty points
-- --------------------------------------------------------------------------------------
CREATE OR REPLACE VIEW v_customers AS
  SELECT
    id,
    name,
    email,
    phone_number,
    DATE_FORMAT(created_at,'%Y-%m-%d') created_at,
    getLoyaltyPointsCustomer(id) loyalty_points
  FROM customers;
COMMIT;

-- --------------------------------------------------------------------------------------
-- Create view for custom monthly report
-- --------------------------------------------------------------------------------------
CREATE OR REPLACE VIEW v_monthly_report AS
  SELECT
    DATE_FORMAT(purchase_date,'%Y-%m') "Month",
    -- COUNT(DISTINCT customer_id) "NumCustomers",
    -- COUNT(id) "NumPurchases",
    CONCAT('$',SUM(total)) "TotalSpent",
    CONCAT('$',ROUND(SUM(total)/COUNT(DISTINCT customer_id),2)) "AvgPerCustomer",
    getLoyaltyPointsMonth(purchase_date) "LoyaltyPoints"
  FROM
    purchase_history
  GROUP BY
    YEAR(purchase_date),
    MONTH(purchase_date)
  ORDER BY
    YEAR(purchase_date),
    MONTH(purchase_date);
