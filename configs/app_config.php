<?php

function getAppConfigParam($param)
{
	$params = [
		'uploads_dir_path' => implode(DIRECTORY_SEPARATOR, ['api', 'storage', 'uploads']),
        'debug' => true,
        'log_path' => implode(DIRECTORY_SEPARATOR, ['api', 'logs', 'app.log']),
        'allowed_upload_mime_types' => ['image/jpeg' , 'application/pdf'],
        'allowed_upload_ext' => 'jpeg, pdf',
        'max_upload_file_size' => 2 * 1024 * 1024, //filesize returns the size of the file in bytes
	];

	if (!empty($params[$param])) {
		return $params[$param];
	} else {
		return null;
	}
}

