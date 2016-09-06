
USE `k9homes_db`;
/*

DELETE FROM system_orders WHERE id < 24134;

DELETE FROM clientstock where id < 556951;

DELETE FROM contact_history where id < 26536;
*/


/*


ALTER TABLE client_prices ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT, ADD KEY(id);
ALTER TABLE client_prices ADD updated_at TIMESTAMP;
ALTER TABLE client_prices ADD created_at TIMESTAMP;
*/

ALTER TABLE clientstock ADD updated_at TIMESTAMP;
ALTER TABLE clientstock ADD created_at TIMESTAMP;

ALTER TABLE contact_history ADD updated_at TIMESTAMP;
ALTER TABLE contact_history ADD created_at TIMESTAMP;

ALTER TABLE notify_me ADD updated_at TIMESTAMP;
ALTER TABLE notify_me ADD created_at TIMESTAMP;
ALTER TABLE notify_me ADD product_id INT UNSIGNED;

ALTER TABLE products ADD updated_at TIMESTAMP;
ALTER TABLE products ADD created_at TIMESTAMP;


ALTER TABLE travel ADD updated_at TIMESTAMP;
ALTER TABLE travel ADD created_at TIMESTAMP;

ALTER TABLE `type` ADD updated_at TIMESTAMP;
ALTER TABLE `type` ADD created_at TIMESTAMP;

ALTER TABLE type_category ADD updated_at TIMESTAMP;
ALTER TABLE type_category ADD created_at TIMESTAMP;
ALTER TABLE type_category ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT, ADD KEY(id);

ALTER TABLE type_options ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT, ADD KEY(id);



/*
UPDATE system_order_items set system_order_id = CONVERT(SUBSTR(order_id,4,10),UNSIGNED INTEGER);

UPDATE system_order_items soi 
JOIN products p ON soi.product_code = p.product_code
SET soi.supplied_product_id = p.id, soi.product_id = p.id;
*/

/*
ALTER TABLE type_options drop primary key, add primary key(id);
ALTER TABLE type_options add UNIQUE KEY `typeid_optcode` (typeid,opt_code);
*/

/*
ALTER TABLE type_category drop primary key, add primary key(id);
ALTER TABLE type_category add UNIQUE KEY `type_category` (typeid,catid);
*/

/*
DELETE FROM system_order_items WHERE CONVERT(SUBSTR(order_id,4,10),UNSIGNED INTEGER) < 24134;
ALTER TABLE system_order_items ADD id INT UNSIGNED;
ALTER TABLE system_order_items ADD system_order_id INT UNSIGNED;
ALTER TABLE system_order_items ADD supplied_product_id INT UNSIGNED;
ALTER TABLE system_order_items ADD product_id INT UNSIGNED;
ALTER TABLE system_order_items ADD updated_at TIMESTAMP;
ALTER TABLE system_order_items ADD created_at TIMESTAMP;
ALTER TABLE system_order_items drop primary key, add primary key(id);
ALTER TABLE system_order_items add UNIQUE KEY `order_product` (order_id,product_code);
*/