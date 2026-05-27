CREATE TABLE IF NOT EXISTS product_variants (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  sku VARCHAR(80) NULL UNIQUE,
  duration VARCHAR(80) NULL,
  price BIGINT UNSIGNED NOT NULL DEFAULT 0,
  old_price BIGINT UNSIGNED NOT NULL DEFAULT 0,
  stock INT UNSIGNED NULL,
  description TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX product_variants_product_active_sort_index (product_id, is_active, sort_order)
);

ALTER TABLE order_items ADD COLUMN product_variant_id BIGINT UNSIGNED NULL AFTER product_id;
ALTER TABLE order_items ADD COLUMN variant_name VARCHAR(160) NULL AFTER product_name;
