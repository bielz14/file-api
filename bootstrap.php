<?php

//TODO: need ignor bootstrap file to browser by URL

use Api\Migrations\UploadsTable;;
use Api\Core\Database;
use Api\Models\Upload;

//TODO: it will be possible in the future to remake it to call migrations using the command line by running method bootstrapExecute() through it adding a requirement for classes that are used in the current class but are currently being required via file index.php
function loadingMigrations()
{
	//$dir = "api\migrations";//tied to windows path delimiter
    $dir = implode(DIRECTORY_SEPARATOR, ['api', 'migrations']);

	$catalog = opendir($dir);

	while ($filename = readdir($catalog) )
	{
		$filepath = $dir . "/" . $filename;

		if ($filename != '.' && $filename != '..') {
			require_once($filepath);
		}
	}

	closedir($catalog);
}

function executeMigrations()
{
    $dbConnect = Database::getDbConnect();
    if (!empty($dbConnect)) {
        $query = $dbConnect->prepare("SHOW TABLES FROM :db_name LIKE :table_name");
        $dbName = getDbConfigParam('db_name');
        $tableName = Upload::TABLE_NAME;
        $query->bindParam(':db_name', $dbName);
        $query->bindParam(':table_name', $tableName);
        if (!$query->execute()) {
            UploadsTable::createTable();
        }
    }
}

function setupAppDebugMode()
{
    ini_set( 'display_errors', 0 );

    if (getAppConfigParam('debug')) {
        error_reporting(E_ALL);

        ini_set('log_errors', 1);
        ini_set('error_log', getAppConfigParam('log_path'));
    } else {
        error_reporting(0);
    }
}


function bootstrapExecute()
{
    setupAppDebugMode();
	loadingMigrations();
	executeMigrations();
}

//bootstrapExecute(); //TODO: need will be uncomment to console command