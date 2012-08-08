<?php

class ImageCropper {
    
    public function __construct() {
        $this->x1 = $_POST["x1"];
	$this->y1 = $_POST["y1"];
	$this->x2 = $_POST["x2"];
	$this->y2 = $_POST["y2"];
	$this->w = $_POST["w"];
	$this->h = $_POST["h"];
    }
    
    public function crop($destinationPath,$sourcePath, $width) {
        
        $scale = $width/$this->w;
        
        return $this->resize($destinationPath,$sourcePath, $this->w, $this->h,$this->x1,$this->y1,$scale );
        
    }
    
    function resize($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	return $thumb_image_name;
}
    
}
