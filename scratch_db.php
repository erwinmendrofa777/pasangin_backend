<?php
$conn = new mysqli("localhost", "root", "", "stuh8812_pasangin_db");
$result = $conn->query("SHOW COLUMNS FROM construction_progress");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
