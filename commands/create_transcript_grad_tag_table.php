<?php

include("config/dbc.php");

$db->query("CREATE TABLE transcript_grad_tag (
    `idnum` INTEGER UNSIGNED,
    `ordinal` INTEGER,
    `degree` VARCHAR(191),
    `date` DATE,
    `so` VARCHAR(255),
    `remarks` VARCHAR(30),

    PRIMARY KEY (`idnum`,`ordinal`)
)");

if (mysqli_error($db)) {
    echo mysqli_error($db);
} else {
    echo "Successfully created ext_trans table";
}