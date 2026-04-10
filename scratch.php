<?php
$db = new mysqli('127.0.0.1', 'root', '', 'stuh8812_pasangin_db');
if ($db->connect_error) die('Connection failed');

$db->query("ALTER TABLE construction_progress ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
echo "Altered. \n";
$res = $db->query("DESCRIBE construction_progress");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}
