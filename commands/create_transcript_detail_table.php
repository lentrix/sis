<?php

include "config/dbc.php";

$db->query("CREATE TABLE transcript_details (
    `idnum` INTEGER UNSIGNED PRIMARY KEY,
    `place_of_birth` VARCHAR(191),
    `nationality` VARCHAR(60),
    `religion` VARCHAR(60),
    `guardians` VARCHAR(191),
    `gd_address` VARCHAR(191),
    `dt_admitted` DATE,
    `entrance_data` VARCHAR(30),
    `college` VARCHAR(30),
    `orno` VARCHAR(15),
    `or_date` DATE,
    `remarks` VARCHAR(30),
    `date_issued` DATE,
    `elem` VARCHAR(60),
    `elem_sy` VARCHAR(10),
    `secn` VARCHAR(60),
    `secn_sy` VARCHAR(10),
    `tcry` VARCHAR(60),
    `tcry_sy` VARCHAR(10),
    `revised` INTEGER(4)
)");

if (mysqli_error($db)) {
    echo mysqli_error($db);
} else {
    echo "Successfully created ext_trans table";
}
