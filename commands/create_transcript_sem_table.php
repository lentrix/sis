<?php 

include "config/dbc.php";

$create = "CREATE TABLE transcript_sem (
    `idnum` INTEGER UNSIGNED NOT NULL,
    `ordinal` INTEGER NOT NULL,
    `sy` VARCHAR(30) NOT NULL,
    `school` VARCHAR(101) NOT NULL,
    `address` VARCHAR(101) NOT NULL,
    `program` VARCHAR(30),
    `type` VARCHAR(3) DEFAULT('int')

    PRIMARY KEY (`idnum`,`ordinal`)
    )";

mysqli_query($db, $create);

if(mysqli_error($db)) {
    echo mysqli_error($db);
}else {
    echo "Successfully created ext_trans table";
}
