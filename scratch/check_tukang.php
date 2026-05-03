<?php
require 'app/Config/Database.php';
$db = \Config\Database::connect();
$fields = $db->getFieldNames('tukang');
print_r($fields);
