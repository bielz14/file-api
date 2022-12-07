<?php

namespace Api\Core;

class Database
{
    public static function getDbConnect()
    {   
        try {
            $dbHost = getDbConfigParam('db_host');
            $dbName = getDbConfigParam('db_name');
            $dbUsername = getDbConfigParam('db_username');
            $dbPassword = getDbConfigParam('db_password');

            return new \PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUsername, $dbPassword);// connecting to database
        } catch (PDOException $e) {
            //echo $e->getMessage();
        }

        return null;
    }
}
