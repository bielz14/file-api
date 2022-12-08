<?php

namespace Api\Core;

class File
{
    /**
     * @param null $file
     * @return array
     *
     * @throws \Exception
     */
    public static function getFileInfo($file)
    {
        if (!is_file($file['tmp_name'])) {
            throw new \Exception($file['name'] . " is not a file");
        }

        $data = getimagesize($file['tmp_name']);
        $filesize = filesize($file['tmp_name']);
        if (!$data && !$filesize) {
            throw new \Exception('Unable to get file info');
        }

        //? is useless
        $extensions = [
            1 => 'pdf',
            2 => 'jpg'
        ];

        $info = new \SplFileInfo($file['name']);

        if (!$data && $info) {
            $result = [
                'extension' => $info->getExtension(),
                'size' => $filesize,
                'mime' => mime_content_type($file['tmp_name'])
            ];
        } else {
            $result = [
                'width' => $data[0],
                'height' => $data[1],
                'extension' => $info->getExtension(),
                'size' => $filesize,
                'mime' => $data['mime']
            ];
        }

        return $result;
    }
}
