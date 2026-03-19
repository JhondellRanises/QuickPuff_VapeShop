-- Add flavor and puffs columns to products table
ALTER TABLE products ADD COLUMN flavor VARCHAR(100) NULL AFTER brand;
ALTER TABLE products ADD COLUMN flavor_category VARCHAR(50) NULL AFTER flavor;
ALTER TABLE products ADD COLUMN puffs INT NULL AFTER flavor_category;

-- Add indexes for better performance
CREATE INDEX idx_products_flavor ON products(flavor);
CREATE INDEX idx_products_flavor_category ON products(flavor_category);
CREATE INDEX idx_products_puffs ON products(puffs);

-- Add comment
ALTER TABLE products COMMENT = 'Updated with flavor, flavor_category and puffs fields for vape products';
