<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class FileUploadS3Service
{
    /**
     * Upload local file from disk to s3
     *
     * @param $filePath
     * @return array|bool
     */
    public function uploadFromDisk($filePath)
    {
        $file  = new File($filePath);

        $extension = $file->getExtension();
        $originalName = $file->getFilename();
        $generatedName = substr(md5(date('Y-m-d H:i:s:u')), 0, 20).'.'.$extension;
        $type = $file->getMimeType();
        $fileContent = file_get_contents($filePath);

        $uploaded = $this->upload($generatedName, $fileContent);

        if ($uploaded) {
            return array(
                'original_name' => $originalName,
                'generated_name'  => $generatedName,
                'type' => $type
            );
        }

        return false;
    }

    /**
     * Upload file to s3.
     *
     * @param $fileName
     * @param $fileContent
     * @return bool
     */
    public function upload($fileName, $fileContent)
    {
        //put file to s3
        Storage::disk('s3')->put($fileName,  $fileContent);
        if(Storage::disk('s3')->exists($fileName)){
            return true;
        }
        return false;
    }

    /**
     * Delete file from s3.
     *
     * @param $fileName
     * @return bool
     */
    public function delete($fileName)
    {
        if(Storage::disk('s3')->exists($fileName)){
            Storage::disk('s3')->delete($fileName);
            return true;
        }
        return false;
    }
}