<?php

include "config/dbc.php";

echo "fixing mgrade...\n";
$db->query("UPDATE sub_enrol SET mgrade='-' WHERE mgrade='NaN'");
echo "fixing fgrade...\n";
$db->query("UPDATE sub_enrol SET fgrade='-' WHERE fgrade='NaN'");
echo "fixing rating...\n";
$db->query("UPDATE sub_enrol SET rating='-' WHERE rating='NaN'");
echo "fixing dropped...\n";
$db->query("UPDATE sub_enrol SET rating='Dr' WHERE UPPER(mgrade)='DR' OR UPPER(fgrade)='DR'");
echo "Done.\n";