<?php
require 'app/Config/Database.php';
$db = new \Config\Database();
$cfg = $db->default;
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
$res = $mysqli->query("SHOW COLUMNS FROM design_targets");
while($row = $res->fetch_assoc()) echo $row['Field'] . ' ' . $row['Type'] . "\n";
