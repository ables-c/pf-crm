-- Calculate total loyalty points for customer with id=xxx
SELECT TRUNCATE(SUM(total)/10,0) + TRUNCATE(COUNT(*)/10,0) FROM purchase_history
WHERE customer_id=xxx AND purchase_date>='2022-01-01';


-- Get data by month
SELECT
  CONCAT(MONTHNAME(purchase_date),' ',YEAR(purchase_date)) "Month",
  COUNT(DISTINCT customer_id) "Num Customers",
  COUNT(id) "Num Purchases",
  CONCAT('$',SUM(total)) "Total Spent",
  CONCAT('$',ROUND(SUM(total)/COUNT(DISTINCT customer_id),2)) "Avg/Customer",
  getLoyaltyPointsMonth(purchase_date) "Loyalty Points"
FROM
  purchase_history ph
GROUP BY
  YEAR(purchase_date),
  MONTH(purchase_date)
ORDER BY
  YEAR(purchase_date),
  MONTH(purchase_date);
