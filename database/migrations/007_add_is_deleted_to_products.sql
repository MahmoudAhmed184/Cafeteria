-- Add is_deleted column to products table for proper soft-deletion
ALTER TABLE products ADD COLUMN is_deleted TINYINT(1) NOT NULL DEFAULT 0;
CREATE INDEX idx_products_is_deleted ON products(is_deleted);
