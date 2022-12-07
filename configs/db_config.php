<?php

function getDbConfigParam($param)
{
	$params = [
		'db_host' => 'localhost',//database hostname
		'db_username' => 'root',//database username
		'db_password' => '',//database password
		'db_name' => 'api'//database name
	];

	if (!empty($params[$param])) {
		return $params[$param];
	} else {
		return null;
	}
}