<?php
// Simple database test script
try {
    // Load CodeIgniter
    require_once '../vendor/autoload.php';
    require_once '../app/Config/Database.php';
    
    $db = \Config\Database::connect();
    
    // Check if columns exist
    $result = $db->query("SHOW COLUMNS FROM products LIKE 'flavor'");
    $flavorExists = $result->getNumRows() > 0;
    
    $result = $db->query("SHOW COLUMNS FROM products LIKE 'flavor_category'");
    $flavorCategoryExists = $result->getNumRows() > 0;
    
    $result = $db->query("SHOW COLUMNS FROM products LIKE 'puffs'");
    $puffsExists = $result->getNumRows() > 0;
    
    echo "Database Connection: OK\n";
    echo "Flavor column exists: " . ($flavorExists ? "YES" : "NO") . "\n";
    echo "Flavor Category column exists: " . ($flavorCategoryExists ? "YES" : "NO") . "\n";
    echo "Puffs column exists: " . ($puffsExists ? "YES" : "NO") . "\n";
    
    if (!$flavorExists || !$flavorCategoryExists || !$puffsExists) {
        echo "\nWARNING: Some columns are missing. Please run the SQL script:\n";
        echo "File: writable/add_flavor_puffs_columns.sql\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
