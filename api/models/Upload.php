<?php

namespace Api\Models;

class Upload extends BaseModel
{
    public const TABLE_NAME = 'uploads';

    public $uploadId;
    public $originalFileName;
    public $fileSize;
    public $fileExt;
    public $hash;

    function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        $queryStr = "INSERT INTO UPLOADS (original_file_name, file_size, file_ext, hash) VALUES (:original_file_name, :file_size, :file_ext, :hash)";
        $query = $this->dbConnect->prepare($queryStr);
        $query->bindParam(':original_file_name', $this->originalFileName);
        $query->bindParam(':file_size', $this->fileSize);
        $query->bindParam(':file_ext', $this->fileExt);
        $query->bindParam(':hash', $this->hash);

        if (!$query->execute()) {
            throw new \Exception($query->errorInfo()[2]);
        }

        return true;
    }
}