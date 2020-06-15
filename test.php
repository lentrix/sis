<?php
include "config/dbc.php";

$query = $db->query("SELECT * FROM sub_enrol WHERE class_code = 6604");

$text = "Sta. Moninca";

echo ucwords(strtolower($text));