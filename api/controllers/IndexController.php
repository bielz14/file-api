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