# db_schema = """
# Tables:

# 1. customers(id, date, name, email, phone1, phone2, city, address, credit_limit, opening_bal, isdeleted, created_at, updated_at, op_bal_id)
# 2. cust_ledgers(id, date, dr, cr, desc, adjustment, sale_id, customer_id, type, isdeleted, created_at, updated_at)
# 3. dips(id, pro_id, qty, sighn, desc, change_in_qty, date, isdeleted, created_at, updated_at)
# 4. expenses(id, date, desc, amount, isdeleted, created_at, updated_at, exp_type_id)
# 5. exp_types(id, name, type, desc, isdeleted, created_at, updated_at)
# 6. fuel_backups(id, pro_id, pur_id, sku, qty, fqty, stock_capacity, desc, isdeleted, created_at, updated_at)
# 7. prices(id, date, cost_price, retail_price, comments, pro_id, created_at, updated_at)
# 8. products(id, name, sku, alert_qty, cost_Price, retail_price, desc, isdeleted, created_at, updated_at)
# 9. purchases(id, date, inv_no, desc, sup_bill_no, total_qty, retail_amount, cost_amount, adjustment, isdeleted, created_at, updated_at, sup_id)
# 10. purchase_items(id, date, pro_id, pur_id, sku, pur_type, qty, cost_price, retail_price, sub_total, isdeleted, created_at, updated_at)
# 11. sales(id, date, invoice_no, cost_amount, retail_amount, desc, total_qty, adjustment, isdeleted, created_at, updated_at, customer_id)
# 12. sales_items(id, date, sale_id, pro_id, sku, qty, cost_price, subtotal, retail_price, desc, isdeleted, created_at, updated_at)
# 13. stocks(id, pro_id, sku, desc, qty, stock_capacity, created_at, updated_at, dip_id)
# 14. suppliers(id, date, company, name, email, phone1, phone2, city, address, opening_bal, isdeleted, created_at, updated_at, op_bal_id)
# 15. sup_ledgers(id, date, dr, cr, adjustment, desc, pur_id, sup_id, type, isdeleted, created_at, updated_at)
# 16. users(id, name, email, contact, logo, account_type, email_verified_at, password, remember_token, isactive, isdeleted, created_at, updated_at)
# """

# db_relations = """
# Relationships (foreign keys):

# - customers.op_bal_id → cust_ledgers.id
# - cust_ledgers.customer_id → customers.id
# - cust_ledgers.sale_id → sales.id
# - sales.customer_id → customers.id
# - sales_items.sale_id → sales.id
# - sales_items.pro_id → products.id
# - purchase_items.pro_id → products.id
# - purchase_items.pur_id → purchases.id
# - purchases.sup_id → suppliers.id
# - sup_ledgers.sup_id → suppliers.id
# - sup_ledgers.pur_id → purchases.id
# - dips.pro_id → products.id
# - stocks.pro_id → products.id
# - stocks.dip_id → dips.id
# - fuel_backups.pro_id → products.id
# - fuel_backups.pur_id → purchases.id
# """


db_schema = """
--
-- Database: `fuel_pump`
--
CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` int DEFAULT NULL,
  `opening_bal` int DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `op_bal_id` bigint UNSIGNED DEFAULT NULL
);

CREATE TABLE `cust_ledgers` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `dr` double(255,2) DEFAULT NULL, 
  `cr` double(255,2) DEFAULT NULL, 
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `adjustment` double(255,2) DEFAULT NULL,
  `sale_id` bigint UNSIGNED DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `type` enum('sale','payment','opbl') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sale',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

Important Notes for Calculations:
1. Column meanings:
   - dr = credit = amount the customer owes (customer payable / due)
   - cr = cash = amount received from the customer
   - adjustment = any discount or manual adjustment applied to the ledger (can be + or -)
2. Calculation for each customer ledger:
   - cash_received = SUM(cr)
   - total_due = SUM(dr)
   - total_adjustment = SUM(adjustment)  # represents discounts, not extra credit
   - subtotal = cash_received + total_due + total_adjustment
   - credit_available = total_due  # total amount customer owes, adjustments do not increase credit
Example query for due or credit 
SELECT
  c.id AS customer_id,
  c.name AS customer_name,
  SUM(cl.dr) AS total_due
FROM customers AS c
JOIN cust_ledgers AS cl
  ON c.id = cl.customer_id
WHERE
  cl.isdeleted = 0
GROUP BY
  c.id,
  c.name
HAVING
  SUM(cl.dr) > 0;



CREATE TABLE `dips` (
  `id` bigint UNSIGNED NOT NULL,
  `pro_id` bigint UNSIGNED NOT NULL,
  `qty` double(255,2) NOT NULL,
  `sighn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_in_qty` double(255,2) DEFAULT NULL,
  `date` timestamp NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` int NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `exp_type_id` bigint UNSIGNED NOT NULL
);

CREATE TABLE `exp_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);


CREATE TABLE `fuel_backups` (
  `id` bigint UNSIGNED NOT NULL,
  `pro_id` bigint UNSIGNED NOT NULL,
  `pur_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` double(255,2) NOT NULL DEFAULT '0.00',
  `fqty` double(255,2) NOT NULL DEFAULT '0.00',
  `stock_capacity` double(255,2) NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `mywallets` (
  `id` bigint UNSIGNED NOT NULL,
  `money` bigint NOT NULL,
  `type` enum('sale','purchase','expense','deposit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);


CREATE TABLE `prices` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `cost_price` double(8,2) NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pro_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alert_qty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_Price` double(8,2) NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `inv_no` bigint NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sup_bill_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_qty` bigint NOT NULL,
  `retail_amount` bigint DEFAULT NULL,
  `cost_amount` bigint NOT NULL,
  `adjustment` bigint DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sup_id` bigint UNSIGNED NOT NULL
);

CREATE TABLE `purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `pro_id` bigint UNSIGNED NOT NULL,
  `pur_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pur_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` bigint NOT NULL,
  `cost_price` bigint NOT NULL,
  `retail_price` bigint NOT NULL,
  `sub_total` bigint NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `invoice_no` bigint NOT NULL,
  `cost_amount` double(255,2) NOT NULL,
  `retail_amount` double(255,2) NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_qty` double(255,2) NOT NULL,
  `adjustment` double(255,2) DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL
);

CREATE TABLE `sales_items` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `pro_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` double(8,2) NOT NULL,
  `cost_price` double(8,2) DEFAULT NULL,
  `subtotal` bigint NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `pro_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `qty` double(255,2) NOT NULL,
  `stock_capacity` double(255,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dip_id` bigint UNSIGNED DEFAULT NULL
);

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_bal` int DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `op_bal_id` bigint UNSIGNED DEFAULT NULL
);

CREATE TABLE `sup_ledgers` (
  `id` bigint UNSIGNED NOT NULL,
  `date` timestamp NOT NULL,
  `dr` double(255,2) DEFAULT NULL, -- dr means amount paid to supplier
  `cr` double(255,2) DEFAULT NULL, -- cr means amount due
  `adjustment` double(255,2) DEFAULT NULL, -- adjustment is added in dr at the end  it is used for discount or increment in total payable or recevieable
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pur_id` bigint UNSIGNED DEFAULT NULL,
  `sup_id` bigint UNSIGNED NOT NULL,
  `type` enum('purchase','payment','opbl') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purchase',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);



CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `account_type` enum('admin','manager','staff','customer','supplier') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);
"""
db_constraints = """

  
  
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_op_bal_id_foreign` FOREIGN KEY (`op_bal_id`) REFERENCES `cust_ledgers` (`id`);

ALTER TABLE `cust_ledgers`
  ADD CONSTRAINT `cust_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `cust_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);

ALTER TABLE `dips`
  ADD CONSTRAINT `dips_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`);

ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_exp_type_id_foreign` FOREIGN KEY (`exp_type_id`) REFERENCES `exp_types` (`id`);

ALTER TABLE `fuel_backups`
  ADD CONSTRAINT `fuel_backups_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fuel_backups_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`);

ALTER TABLE `prices`
  ADD CONSTRAINT `prices_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`);

ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_sup_id_foreign` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`id`);

ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_items_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`);

ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);

ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_dip_id_foreign` FOREIGN KEY (`dip_id`) REFERENCES `dips` (`id`),
  ADD CONSTRAINT `stocks_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`);

ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_op_bal_id_foreign` FOREIGN KEY (`op_bal_id`) REFERENCES `sup_ledgers` (`id`);

ALTER TABLE `sup_ledgers`
  ADD CONSTRAINT `sup_ledgers_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `sup_ledgers_sup_id_foreign` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`id`);
"""

db_keys = """
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_name_unique` (`name`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD UNIQUE KEY `customers_phone1_unique` (`phone1`),
  ADD KEY `customers_op_bal_id_foreign` (`op_bal_id`);

ALTER TABLE `cust_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cust_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `cust_ledgers_customer_id_foreign` (`customer_id`);

ALTER TABLE `dips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dips_pro_id_foreign` (`pro_id`);

ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_exp_type_id_foreign` (`exp_type_id`);

ALTER TABLE `exp_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fuel_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuel_backups_pro_id_foreign` (`pro_id`),
  ADD KEY `fuel_backups_pur_id_foreign` (`pur_id`);

ALTER TABLE `mywallets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prices_pro_id_foreign` (`pro_id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`);

ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_sup_id_foreign` (`sup_id`);

ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_pro_id_foreign` (`pro_id`),
  ADD KEY `purchase_items_pur_id_foreign` (`pur_id`);

ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`);

ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sales_items_pro_id_foreign` (`pro_id`);

ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_pro_id_foreign` (`pro_id`),
  ADD KEY `stocks_dip_id_foreign` (`dip_id`);

ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_name_unique` (`name`),
  ADD UNIQUE KEY `suppliers_email_unique` (`email`),
  ADD UNIQUE KEY `suppliers_phone1_unique` (`phone1`),
  ADD KEY `suppliers_op_bal_id_foreign` (`op_bal_id`);

ALTER TABLE `sup_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sup_ledgers_pur_id_foreign` (`pur_id`),
  ADD KEY `sup_ledgers_sup_id_foreign` (`sup_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

  
"""