<?php
namespace Api\Services;

use Api\Core\File;
use Api\Models\Upload;

class FileUpload
{
    /**
     * @param $file php $_FILE
     * Array
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] =>
            [size] =>
     * @throws \Exception
     */
    public function upload($file)
    {
        $validMimeExtensions = getAppConfigParam('allowed_upload_mime_types'); //allowed mime types
        $uploadsDirPath = getAppConfigParam('uploads_dir_path');
        $maxUploadFileSize = getAppConfigParam('max_upload_file_size');

        $fileInfo = File::getFileInfo($file);

        if (!in_array($fileInfo['mime'], $validMimeExtensions)) {
            throw new \Exception('Invalid file format. Allowed ext: ' . getAppConfigParam('allowed_upload_ext'));
        }

        if($fileInfo['size'] > $maxUploadFileSize ) {
            throw new \Exception('The file size has exceeded ' . $maxUploadFileSize/1024/1024 . 'MB');//TODO add bites to MB convertion
        }

        if (!file_exists($uploadsDirPath)) {
            mkdir($uploadsDirPath, 0700);
        }

        $fileName = time() . '_' . $file['name'];
        //time() - returns the number of seconds, bm not unique. Invent unique generator, or do uniq file name generation until file exist in directory
        $hash = hash('sha256', $fileName);
        $fileUploadsDirPath = $uploadsDirPath . '/' . $fileInfo['extension'];
        if (!file_exists($fileUploadsDirPath)) {
            mkdir($fileUploadsDirPath, 0700);
        }
        $filePath = $fileUploadsDirPath . '/' . $hash . '.' . $fileInfo['extension'];
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $upload = new Upload();
            $upload->originalFileName = $fileName;
            $upload->fileSize = $fileInfo['size'];
            $upload->fileExt = $fileInfo['extension'];
            $upload->hash = $hash;
            $upload->save();

            return $upload;
        } else {
            throw new \Exception('File could not store on disk');
        }

    }
}