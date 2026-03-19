-- Add flavor columns to products table
ALTER TABLE products 
ADD COLUMN flavor VARCHAR(100) NULL AFTER brand,
ADD COLUMN flavor_category VARCHAR(50) NULL AFTER flavor,
ADD COLUMN puffs INT NULL AFTER flavor_category;
