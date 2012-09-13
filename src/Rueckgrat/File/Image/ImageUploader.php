<?php

namespace Rueckgrat\File\Image;

class ImageUploader
{
    protected $originalPath = null;
    protected $uploadPath = null;
    protected $allowedMimeTypes = array(
        'image/gif',
        'image/png',
        'image/jpeg'
    );
    protected $allowedExtensions = array(
        'gif',
        'jpeg',
        'png'
    );
    protected $deliveryMethod = 'private';
    protected $file = null;
    protected $fileName = null;
    protected $maxSize = 5;
    protected $maxWidth = null;
    protected $maxHeight = null;

    public function __construct($inputName, $originalPath, $uploadPath)
    {
        $this->setOriginalPath($originalPath);
        $this->setUploadPath($uploadPath);
        if (is_string($inputName) == true) {
            $this->file = $_FILES[$inputName];
        } else {
            throw new Exception('The input name is not a string.');
        }

    }

    /**
     *
     * @param  string    $fileName
     * @param  boolean   $upscale
     * @throws Exception
     */
    public function upload($fileName)
    {
        $this->fileName = $fileName;
        if ($this->checkMimeType() == true && $this->checkFileSize() == true) {
            $extension = $this->getExtension();
            if ($extension == 'jpg') {
                $extension = 'jpeg';
            }
            $imageCreateMethod = 'imagecreatefrom' . $extension;
            $image = $imageCreateMethod($this->file['tmp_name']);
            $imageMethod = 'image' . $extension;
            $imagePath = $this->originalPath . $this->fileName . '.' . $extension;
            $imageMethod($image, $imagePath, 100);
            imagedestroy($image);
            $width = $this->getWidth($imagePath);
            $height = $this->getHeight($imagePath);
            $resizedImagePath = $this->uploadPath . $fileName . '_' . 'large.' . $extension;
            $resizedImage = $this->resize($imagePath, $resizedImagePath, $width, $height, $this->getMaxScale($width, $height));

        }

    }

    public function setDeliveryMethod($method)
    {
        if (is_string($method) == true) {
            $this->deliveryMethod = $method;
        } else {
            throw new Exception('The delivery method is not a string.');
        }

    }

    public function setMaxHeight($height)
    {
        if (is_numeric($height) == true) {
            $this->maxHeight = $height;
            $this->maxWidth = null;
        } else {
            throw new Exception('The max height is not numeric.');
        }

    }

    public function setMaxWidth($width)
    {
        if (is_numeric($width) == true) {
            $this->maxWidth = $width;
            $this->maxHeight = null;
        } else {
            throw new Exception('The max width is not numeric.');
        }

    }

    public function setOriginalPath($path)
    {
        if (is_string($path) == true) {
            if (substr($path, -1) != '/') {
                $path = $path . '/';
            }
            $this->originalPath = $path;
        } else {
            throw new Exception('Original path provided, is not a String.');
        }

    }

    public function setUploadPath($path)
    {
        if (is_string($path) == true) {
            if (substr($path, -1) != '/') {
                $path = $path . '/';
            }
            $this->uploadPath = $path;
        } else {
            throw new Exception('Public path provided, is not a String.');
        }

    }

    /**
     *
     * @return boolean
     * @throws Exception
     */
    protected function checkFileSize()
    {
        if ($this->file['size'] <= ($this->maxSize*1048576)) {
            return true;
        } else {
            throw new Exception('Images must be under '.$this->maxSize . 'MB in size');
        }

    }

    protected function checkMimeType()
    {
        $imageInfo = getimagesize($this->file['tmp_name']);
        if (in_array($imageInfo['mime'], $this->allowedMimeTypes) == true) {
            return true;
        } else {
            throw new Exception('The Mime type of the uploaded image ' . $imageInfo . ', only ' . implode(', ', $this->allowedExtensions) . ' is allowed');
        }

    }
    protected function getExtension()
    {
        $fileName = basename($this->file['name']);
    $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

        return $extension;

    }

    protected function getHeight($image)
    {
        $size = getimagesize($image);
        $height = $size[1];

        return $height;

    }

    protected function getMaxScale($width, $height)
    {
        if ($this->maxWidth != null && $width > $this->maxWidth) {
            $scale = $this->maxWidth/$width;
        } elseif ($this->maxHeight != null && $height > $this->maxHeight) {
            $scale = $this->maxHeight/$height;
        } elseif ($this->maxWidth != null && $this->maxHeight != null) {
            $scale = 1;
        } else {
            throw new Exception('No max dimension provided. Set a max height, or max width.');
        }

        return $scale;
    }

    protected function getWidth($image)
    {
        $size = getimagesize($image);
        $width = $size[0];

        return $width;

    }

    protected function resize($sourcePath, $destinationPath, $width, $height, $scale)
    {
    list($imagewidth, $imageheight, $imageType) = getimagesize($sourcePath);
    $imageType = image_type_to_mime_type($imageType);
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
    switch ($imageType) {
        case "image/gif":
            $source=imagecreatefromgif($sourcePath);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source=imagecreatefromjpeg($sourcePath);
            break;
        case "image/png":
        case "image/x-png":
            $source=imagecreatefrompng($sourcePath);
            break;
      }
    imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

    switch ($imageType) {
        case "image/gif":
              imagegif($newImage,$destinationPath);
            break;
          case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
              imagejpeg($newImage,$destinationPath,90);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage,$destinationPath);
            break;
    }

    }

}
