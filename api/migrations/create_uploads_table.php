<?php

namespace Api\Migrations;

use Api\Core\Database;
use Api\Models\Upload;

class UploadsTable {
    public static function createTable() {
        if ($dbConnect = Database::getDbConnect()) {
            $table = Upload::TABLE_NAME;
            if ($dbConnect->query("CREATE TABLE IF NOT EXISTS `" . $table . "` (
                    `upload_id` int(11) NOT NULL AUTO_INCREMENT,
                    `original_file_name` varchar(210) NOT NULL,
                    `file_size` varchar(30) NOT NULL,
                    `file_ext` varchar(30) NOT NULL,
                    `hash` varchar(210) NOT NULL,
                    PRIMARY KEY (`upload_id`),
                    UNIQUE KEY(`original_file_name`, `hash`)
                    ) Engine=InnoDB DEFAULT CHARSET=utf8;")) {
                return '"' . $table . '"' . ' table created successfully';
            }
        }

        return $table . ' failed to create table';
    }
}

//echo createTable();