<?php
$db = new PDO('mysql:host=localhost;dbname=stuh8812_pasangin_db', 'root', '');
$stmt = $db->query("SHOW COLUMNS FROM construction_targets LIKE 'status'");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
