<?php
namespace App\Http\Services;

use Flow\Config;
use Flow\File;
use Illuminate\Support\Facades\File as FileSystem;
use Intervention\Image\Facades\Image;

class FileManipulationService
{
    /**
     * @var Config
     */
    private $config;
    private $file;

    //default values
    private $localStorage = 'temp';
    private $flowFileName = 'temp.jpg';

    //status constants used to identify different situations
    const BAD_REQUEST = "bad_request";
    const FILE_SAVED = "file_saved";
    const CHUNK_NOT_FINAL = "chunk_not_final";

    /**
     * Sets the local folder to be used
     *
     * @param mixed $localStorage
     */
    public function setLocalStorage($localStorage)
    {
        $this->localStorage = $localStorage;
        return $this;
    }

    /**
     * Sets the file name to be used
     *
     * @param array|string $flowFileName
     */
    public function setFlowFileName($flowFileName)
    {
        $this->flowFileName = $flowFileName;
        return $this;
    }

    /**
     * Returns the generated path from the inserted
     *
     * @return string
     */
    public function getStoragePath()
    {
        $fullDirPath = storage_path($this->localStorage);

        if (!FileSystem::isDirectory($fullDirPath)) {
            FileSystem::makeDirectory($fullDirPath,0755,true);
        };

        if ($this->file) {
            return $fullDirPath . "/". $this->flowFileName;
        }

        return $fullDirPath;
    }

    /**
     * Initializes file object for receiving multi-part data
     *
     * @return $this
     */
    public function init()
    {
        $this->config = new Config();
        $this->config->setTempDir($this->getStoragePath());
        $this->file = new File($this->config);
        return $this;
    }

    /**
     * Checks if a chunk already exists
     *
     * @return bool
     */
    public function checkChunk()
    {
        if ($this->file->checkChunk()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Saves the incoming chunk and returns different status
     *
     * @return string
     */
    public function saveMultiPartFile()
    {
        if ($this->saveChunk($this->file)) {
            if ($this->file->validateFile() && $this->file->save($this->getStoragePath()))
            {
                return FileManipulationService::FILE_SAVED;
            } else {
                return FileManipulationService::CHUNK_NOT_FINAL;
            }
        } else {
            return FileManipulationService::BAD_REQUEST;
        }
    }

    //Saves chunk
    public function saveChunk($file)
    {
        if ($file->validateChunk()) {
            $file->saveChunk();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete the file from disk
     *
     * @param null $path
     * @return bool
     */
    public function delete($path = null)
    {
        $path = ($path)?$path:$this->getStoragePath();
        return unlink($path);
    }

    /**
     * Used for resizing images from a specified path based on the width and height provided
     *
     * @param $width
     * @param $height
     * @param $path
     */
    public function resize($width, $height, $path)
    {
        $img = Image::make($path);

        // resize the image with keeping the aspect ratio and preventing upsize of the image
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        //delete the big image which we don't need anymore
        $this->delete($path);

        // save the image with quality 100%
        $img->save($path,100);

        //release resources
        $img->destroy();
    }
}