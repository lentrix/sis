<?php

include "config/dbc.php";

$create = "CREATE TABLE IF NOT EXISTS transcript_row (
    `id` INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transcript_sem_id` INTEGER UNSIGNED NOT NULL,
    `course` VARCHAR(50) NOT NULL,
    `description` VARCHAR(225) NOT NULL,
    `rating` DECIMAL(2,1),
    `units` DECIMAL(3,1),

    FOREIGN KEY (`transcript_sem_id`) REFERENCES transcript_sem(`id`) ON DELETE CASCADE
    )";

mysqli_query($db, $create);

if (mysqli_error($db)) {
    echo mysqli_error($db);
} else {
    echo "Successfully created ext_trans table";
}
