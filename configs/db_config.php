<?php

function getDbConfigParam($param)
{
	$params = [
		'db_host' => 'localhost',//database hostname
		'db_username' => 'root',//database username
		'db_password' => 'pass',//database password
		'db_name' => 'file_api'//database name
	];

	if (!empty($params[$param])) {
		return $params[$param];
	} else {
		return null;
	}
}