<?php

include("config/dbc.php");

$db->query("CREATE TABLE transcript_grad_tag (
    `id` INTEGER UNSIGNED PRIMARY KEY,
    `transcript_sem_id` INTEGER UNSIGNED NOT NULL,
    `degree` VARCHAR(191),
    `date` DATE,
    `so` VARCHAR(255),
    `remarks` VARCHAR(30),

    FOREIGN KEY (`transcript_sem_id`) REFERENCES transcript_sem(`id`) ON DELETE CASCADE
)");

if (mysqli_error($db)) {
    echo mysqli_error($db);
} else {
    echo "Successfully created ext_trans table";
}