<?php

namespace Api\Core;

class File
{
    public static function getFileInfo($file = null)
    {
        if (!is_file($file['tmp_name'])) {
            return false;
        }

        $data = getimagesize($file['tmp_name']);
        $filesize = filesize($file['tmp_name']);
        if (!$data && !$filesize) {
            return false;
        }

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
            $result = ['width' => $data[0],
                'height' => $data[1],
                'extension' => $extensions[$data[2]],
                'size' => $filesize,
                'mime' => $data['mime']
            ];
        }

        return $result;
    }
}
