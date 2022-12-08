<?php

namespace Api\Controllers;

use Api\Core\File;
use Api\Models\Upload;

class IndexController
{
    function actionIndex()
    {
        include 'front/views/layouts/main.php';
    }

    function actionProcess()
    {

        $uploadService = new \Api\Services\FileUpload();
        try {

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $this->sendApiResponse(['error' => 'The request method should be POST'], 405);
            }

            $uploadInfo = $uploadService->upload($_FILES['file']);
            $response = [
                'success' => 'File was uploaded',
                'hash' => $uploadInfo->hash
            ];
            $this->sendApiResponse($response, 200);
        }catch (\Exception $ex) {
            $response = [
                'error' => $ex->getMessage(),
            ];
            $this->sendApiResponse($response, 422);
        }
    }

    protected function sendApiResponse($data=[], $statusCode = 200)
    {
        $http = [
            100 => 'HTTP/1.1 100 Continue',
            101 => 'HTTP/1.1 101 Switching Protocols',
            200 => 'HTTP/1.1 200 OK',
            201 => 'HTTP/1.1 201 Created',
            202 => 'HTTP/1.1 202 Accepted',
            203 => 'HTTP/1.1 203 Non-Authoritative Information',
            204 => 'HTTP/1.1 204 No Content',
            205 => 'HTTP/1.1 205 Reset Content',
            206 => 'HTTP/1.1 206 Partial Content',
            300 => 'HTTP/1.1 300 Multiple Choices',
            301 => 'HTTP/1.1 301 Moved Permanently',
            302 => 'HTTP/1.1 302 Found',
            303 => 'HTTP/1.1 303 See Other',
            304 => 'HTTP/1.1 304 Not Modified',
            305 => 'HTTP/1.1 305 Use Proxy',
            307 => 'HTTP/1.1 307 Temporary Redirect',
            400 => 'HTTP/1.1 400 Bad Request',
            401 => 'HTTP/1.1 401 Unauthorized',
            402 => 'HTTP/1.1 402 Payment Required',
            403 => 'HTTP/1.1 403 Forbidden',
            404 => 'HTTP/1.1 404 Not Found',
            405 => 'HTTP/1.1 405 Method Not Allowed',
            406 => 'HTTP/1.1 406 Not Acceptable',
            407 => 'HTTP/1.1 407 Proxy Authentication Required',
            408 => 'HTTP/1.1 408 Request Time-out',
            409 => 'HTTP/1.1 409 Conflict',
            410 => 'HTTP/1.1 410 Gone',
            411 => 'HTTP/1.1 411 Length Required',
            412 => 'HTTP/1.1 412 Precondition Failed',
            413 => 'HTTP/1.1 413 Request Entity Too Large',
            414 => 'HTTP/1.1 414 Request-URI Too Large',
            415 => 'HTTP/1.1 415 Unsupported Media Type',
            416 => 'HTTP/1.1 416 Requested Range Not Satisfiable',
            417 => 'HTTP/1.1 417 Expectation Failed',
            500 => 'HTTP/1.1 500 Internal Server Error',
            501 => 'HTTP/1.1 501 Not Implemented',
            502 => 'HTTP/1.1 502 Bad Gateway',
            503 => 'HTTP/1.1 503 Service Unavailable',
            504 => 'HTTP/1.1 504 Gateway Time-out',
            505 => 'HTTP/1.1 505 HTTP Version Not Supported',
        ];

        header($http[$statusCode]);
        header('Content-Type: application/json; charset=utf-8');
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        if(!empty($data)) {
            echo json_encode($data);
        }
        die;
    }

    function actionUpload()
    {
        $success = false;
        $message = null;

        if (!$_SERVER['REQUEST_METHOD']) {
            $message = 'The request method should be POST.';
        } else if (empty($_FILES[0])) {
            $message = 'The file uploading is required.';
        } else {
            $validExtensions = ['jpg', 'pdf'];
            if ($fileInfo = File::getFileInfo($_FILES[0])) {
                $uploadsDirPath = getAppConfigParam('uploads_dir_path');
                if (0 < $_FILES[0]['error']) {
                    $message = $_FILES[0]['error'];
                } else if ($_FILES[0]['name']) {
                    if (in_array($fileInfo['extension'], $validExtensions)) {
                        if (!file_exists($uploadsDirPath)) {
                            mkdir($uploadsDirPath, 0700);
                        }
                        $fileSizeInMb = $fileInfo['size'] / 1000000;
                        if ($fileSizeInMb > 5) {
                            $message = 'The file size has exceeded 5MB.';
                        } else {
                            $fileName = time() . '_' . $_FILES[0]['name'];
                            $hash = hash('sha256', $fileName);
                            $fileUploadsDirPath = $uploadsDirPath . '/' . $fileInfo['extension'];
                            if (!file_exists($fileUploadsDirPath)) {
                                mkdir($fileUploadsDirPath, 0700);
                            }
                            $filePath = $fileUploadsDirPath . '/' . $hash . '.' . $fileInfo['extension'];
                            if (move_uploaded_file($_FILES[0]['tmp_name'], $filePath)) {
                                $upload = new Upload();
                                $upload->originalFileName = $fileName;
                                $upload->fileSize = $fileInfo['size'];
                                $upload->fileExt = $fileInfo['extension'];
                                $upload->hash = $hash;
                                try {
                                    $upload->save();
                                    $success = true;
                                    $message = 'File uploaded successfully.';
                                } catch (\Exception $e) {
 ;                                  unlink($filePath);
                                    $message = $e->getMessage();
                                }
                            }
                        }
                    } else {
                        $message = 'Invalid file format.';
                    }
                }
            } else {
                $message = 'Fail getting to file info.';
            }
        }

        if ($success) {
            header('status: 201');
            echo json_encode([
                'success' => $message,
                'hash' => $hash
            ]);
        } else {
            header('status: 422');
            echo json_encode([
                'error' => $message
            ]);
        }
    }
}