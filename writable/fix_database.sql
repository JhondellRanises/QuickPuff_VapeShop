-- FIX: Add missing columns to products table
-- Run this SQL in your database (phpMyAdmin or MySQL command line)

ALTER TABLE products 
ADD COLUMN flavor VARCHAR(100) NULL AFTER brand,
ADD COLUMN flavor_category VARCHAR(50) NULL AFTER flavor,
ADD COLUMN puffs INT NULL AFTER flavor_category;

-- Verify columns were added
SHOW COLUMNS FROM products;
