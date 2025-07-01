SELECT
  c.id "ID",
  c.name "Name",
  c.email "Email",
  c.phone_number "Phone #",
  DATE_FORMAT(c.created_at,'%Y-%m-%d') "Created",
  ph.purchasable "Purchasable",
  ph.price "Price",
  ph.quantity "Quantity",
  ph.total "Total",
  ph.purchase_date "Purchased"
FROM
  customers c,
  purchase_history ph
WHERE
  c.id=ph.customer_id
ORDER BY
  c.name ASC,
  c.id ASC,
  ph.purchase_date DESC;


-- Calculate points for every $10 spent
SELECT TRUNCATE(SUM(total)/10,0) FROM purchase_history WHERE customer_id=xxx AND purchase_date>='2022-01-01';

-- Calculate points for every 10 purchases
SELECT TRUNCATE(COUNT(*)/10,0) FROM purchase_history WHERE customer_id=xxx AND purchase_date>='2022-01-01';

-- Calculate total loyalty points for a customer
SELECT TRUNCATE(SUM(total)/10,0) + TRUNCATE(COUNT(*)/10,0) FROM purchase_history WHERE customer_id=xxx AND purchase_date>='2022-01-01';


-- Get data by month
SELECT
  DATE_FORMAT(purchase_date,'%Y-%m') "Month",
  COUNT(DISTINCT customer_id) "NumCustomers",
  COUNT(id) "NumPurchases",
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

