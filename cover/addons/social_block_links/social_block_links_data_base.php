<?php
$configurations = require $_SERVER['DOCUMENT_ROOT'] . '/security/config.php';

//Retrieving data from the database
$prefix = $configurations['prefix'];
$databaseScript = "CREATE TABLE IF NOT EXISTS {$prefix['table_prefix']}_social_block_links (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    profiles_name TEXT DEFAULT NULL,
    social_profiles_link_url TEXT NOT NULL,
    fa_icone_code TEXT NOT NULL,
    menu_id int(11) DEFAULT NULL,
    place_id int(11) DEFAULT NULL,
    addon_id int(11) DEFAULT NULL,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$databaseDropScript = "DROP TABLE IF EXISTS {$prefix['table_prefix']}_social_block_links";