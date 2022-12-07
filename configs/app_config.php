<?php

function getAppConfigParam($param)
{
	$params = [
		'uploads_dir_path' => 'api/storage/uploads'
	];

	if (!empty($params[$param])) {
		return $params[$param];
	} else {
		return null;
	}
}

