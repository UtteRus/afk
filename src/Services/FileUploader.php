<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string
     */
    private $uploadPath;

    /**
     * FileUploader constructor.
     */
    public function __construct(string $uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    public function uploadImageHero(UploadedFile $file) {
        $path = $this->uploadPath.'img/hero';
        $nameFile = uniqid().'-'.$file->getClientOriginalName();
        $file->move($path, $nameFile);
        return $nameFile;
    }

}