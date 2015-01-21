<?php

class CImage {
    
    // medlemsvariabler
    private $pathToImage; // sökväg till originalbildens mapp
    private $fileExtension=array();
    private $newHeight; // inkommande ny bredd
    private $newWidth; // inkommande ny höjd
    private $width; // orginalbildens bredd
    private $height; // originalbildens höjd
    private $maxWidth; // begränsning på originalbild
    private $maxHeight; // begränsning på originalbild    
    private $cropWidth; // beskuren bredd om crop-to-fit är begärd
    private $cropHeight; // beskuren höjd om crop-to-fit är begärd
    private $quality = 60; // återgivningskvalitet, 60 är förvalt
    private $verbose; // true eller null, används för debugging
    private $img_path; // originalbildens sökväg inkl bildfilens namn
    private $cache_path; // sökväg till cache-mappen inkl.bildfilens namn
    private $filesize; // originalbildens filstorlek
    private $src; // inkommande argument, bildfil, t.ex. me.jpg
    private $saveAs; // inkommande argument &save-as=png t.ex.
    private $cropToFit; // bredd och höjd måste anges också
    private $verboseString; // här stoppar vi in debug-strängen
    private $sharpen; // true = gör bilden skarpare
    private $cacheFileName; // cachade bildens filnamn
    private $cacheFilesize; // cachade bildens filstorlek
    private $ignoreCache; // true = strunta i om det redan finns en cachad bild
    private $grey; // true = gör bilden till gråskala med imagefilter()
    
        public function __construct($basics) {
        $this->img_path = $basics['img_path'];
        $this->cache_path = $basics['cache_path'];
        $this->maxWidth = isset($basics['maxWidth']) ? $basics['maxWidth'] : 2000;
        $this->maxHeight = isset($basics['maxHeight']) ? $basics['maxHeight'] : 2000;    
    }    
 

    // anropas från img.php för att visa bilden
    public function Image() {
        // sökväg till bildfilen
        $this->pathToImage = realpath($this->img_path . $this->src);

        // validera inkommande argument
        $this->validateIncoming();

        // om verbose är satt så startar vi insamlingen av data
        if(isset($this->verbose)) {
            $this->displayVerbose();
        }

        // hämta info om filen
        $this->getImageInfo();

        // beräkna nya bildmått, om det krävs
        $this->new_Width_Height();

        // skapa ett filnamn för cache-filen
        $this->createCacheName();

        // om det redan finns en giltig bild i cachen -- använd den
        $this->outputValidCache();

        // öppna originalbilden från filen
        $image = $this->openOriginalImage();

        // ge vid behov bilden en ny storlek med imageresized och imagecopyresampled
        $image = $this->reziseImage($image);

        // Apply filters and postprocessing of image
        if($this->sharpen) {
            $image = $this->sharpenImage($image);
        }
        if($this->grey) {
            $image = $this->greyImage($image);
        }        

        // spara bilden
        $this->saveImage($image);

        // Output the resulting image
        $this->outputImage();
    }     
    
    
    // stoppa in alla inkommande värden från querystring i medlemsvariabler
    public function set_src($comingIn) {
        $this->src = $comingIn;
    }
    public function set_saveAs($comingIn) {
        $this->saveAs = $comingIn;
    }    
    public function set_verbose($comingIn) {
        $this->verbose = $comingIn ? true : null;
    }
    public function set_quality($comingIn) {
        $this->quality = $comingIn;
    }
    public function set_ignore_cache($comingIn) {
        $this->ignoreCache = $comingIn ? true : null;
    }    
    public function set_new_width($comingIn) {
        $this->newWidth = $comingIn;
    }
    public function set_new_height($comingIn) {
        $this->newHeight = $comingIn;
    }
    public function set_crop_to_fit($comingIn) {
        $this->cropToFit = $comingIn ? true : null;
    }    
    public function set_sharpen($comingIn) {
        $this->sharpen = $comingIn ? true : null;
    }
    public function set_grey($comingIn) {
        $this->grey = $comingIn ? true : null;
    }    
    
    
    // validera inkommande argument
    private function validateIncoming() {
        is_dir($this->img_path) or $this->errorMessage('The image dir is not a valid directory.');
        is_writable($this->cache_path) or $this->errorMessage('The cache dir is not a writable directory.');
        isset($this->src) or $this->errorMessage('Must set src-attribute.');
        preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $this->src) or $this->errorMessage('Filename contains invalid characters.');
        substr_compare($this->img_path, $this->pathToImage, 0, strlen($this->img_path)) == 0 or $this->errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
        is_null($this->saveAs) or in_array($this->saveAs, array('png', 'jpg', 'jpeg', 'gif')) or $this->errorMessage('Not a valid extension to save image as');
        is_null($this->quality) or (is_numeric($this->quality) and $this->quality > 0 and $this->quality <= 100) or $this->errorMessage('Quality out of range');
        is_null($this->newWidth) or (is_numeric($this->newWidth) and $this->newWidth > 0 and $this->newWidth <= $this->maxWidth) or $this->errorMessage('Width out of range');
        is_null($this->newHeight) or (is_numeric($this->newHeight) and $this->newHeight > 0 and $this->newHeight <= $this->maxHeight) or $this->errorMessage('Height out of range');
        is_null($this->cropToFit) or ($this->cropToFit and $this->newWidth and $this->newHeight) or $this->errorMessage('Crop to fit needs both width and height to work');
    }
    
    
    /**
    * Display error message.
    *
    * @param string $message the error message to display.
    */    
    function errorMessage($message) {
        header("Status: 404 Not Found");
        die('img.php says 404 - ' . htmlentities($message));
    }
    
    
    /**
    * Display log message.
    *
    * @param string $message the log message to display.
    */
    function verbose($message) {
        $this->verboseString .= "<p>" . htmlentities($message) . "</p>";
    }


    /**
    * Output an image together with last modified header.
    *
    * @param string $file as path to the image.
    * @param boolean $verbose if verbose mode is on or off.
    */
    function outputImage() {
        $info = getimagesize($this->cacheFileName);
        !empty($info) or $this->errorMessage("The file doesn't seem to be an image.");
        $mime   = $info['mime'];

        $lastModified = filemtime($this->cacheFileName);  
        $gmdate = gmdate("D, d M Y H:i:s", $lastModified);

        if($this->verbose) {
            $this->verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
            $this->verbose("Memory limit: " . ini_get('memory_limit'));
            $this->verbose("Time is {$gmdate} GMT.");
        }

        if(!$this->verbose) header('Last-Modified: ' . $gmdate . ' GMT');
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
            if($this->verbose) { 
                $this->verbose("Would send header 304 Not Modified, but its verbose mode."); 
                echo $this->verboseString;
            exit; 
            }
            header('HTTP/1.0 304 Not Modified');
        } else {  
            if($this->verbose) { 
                $this->verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); 
                echo $this->verboseString;
            exit; 
            }
            header('Content-type: ' . $mime);  
            readfile($this->cacheFileName);
        }
        exit;
    }
    
 //
// Start displaying log if verbose mode & create url to current image
//
    public function displayVerbose() {
        if($this->verbose) {
            $query = array();
            parse_str($_SERVER['QUERY_STRING'], $query);
            unset($query['verbose']);
            $url = '?' . http_build_query($query);

            $this->verboseString = <<<EOD
            <html lang='en'>
            <meta charset='UTF-8'/>
            <title>img.php verbose mode</title>
            <h1>Verbose mode</h1>
            <p><a href=$url><code>$url</code></a><br>
            <img src='{$url}' /></p>
EOD;
        }
    }    


//
// Get information on the image... kolla verbose
//
    public function getImageInfo() {
        $imgInfo = list($this->width, $this->height, $type, $attr) = getimagesize($this->pathToImage);
        !empty($imgInfo) or $this->errorMessage("The file doesn't seem to be an image.");
        $mime = $imgInfo['mime'];

        if($this->verbose) {
            $this->filesize = filesize($this->pathToImage);
            $this->verbose("Image file: {$this->pathToImage}");
            $this->verbose("Image information: " . print_r($imgInfo, true));
            $this->verbose("Image width x height (type): {$this->width} x {$this->height} ({$type}).");
            $this->verbose("Image file size: {$this->filesize} bytes.");
            $this->verbose("Image mime type: {$mime}.");
        }
    }    

    //
    // Calculate new width and height for the image
    //
    
    public function new_Width_Height() {
        $aspectRatio = $this->width / $this->height;

        if($this->cropToFit && $this->newWidth && $this->newHeight) {
            $targetRatio = $this->newWidth / $this->newHeight;
            $this->cropWidth   = $targetRatio > $aspectRatio ? $this->width : round($this->height * $targetRatio);
            $this->cropHeight  = $targetRatio > $aspectRatio ? round($this->width  / $targetRatio) : $this->height;
            if($this->verbose) { $this->verbose("Crop to fit into box of  {$this->newWidth}x{$this->newHeight}. Cropping dimensions: {$this->cropWidth}x{$this->cropHeight}."); }
        }
        else if($this->newWidth && !$this->newHeight) {
            $this->newHeight = round($this->newWidth / $aspectRatio);
            if($this->verbose) { $this->verbose("New width is known {$this->newWidth}, height is calculated to {$this->newHeight}."); }
        }
        else if(!$this->newWidth && $this->newHeight) {
            $this->newWidth = round($this->newHeight * $aspectRatio);
            if($this->verbose) { $this->verbose("New height is known {$this->newHeight}, width is calculated to {$this->newWidth}."); }
        }
        else if($this->newWidth && $this->newHeight) {
            $ratioWidth  = $this->width  / $this->newWidth;
            $ratioHeight = $this->height / $this->newHeight;
            $ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
            $this->newWidth  = round($this->width  / $ratio);
            $this->newHeight = round($this->height / $ratio);
            if($this->verbose) { $this->verbose("New width & height is requested, keeping aspect ratio results in {$this->newWidth}x{$this->newHeight}."); }
        } else {
            $this->newWidth = $this->width;
            $this->newHeight = $this->height;
            if($this->verbose) { $this->verbose("Keeping original width & heigth."); }
        }
    }
    
    //
    // Creating a filename for the cache
    //
    public function createCacheName() {
        $parts          = pathinfo($this->pathToImage);
        $this->fileExtension  = $parts['extension'];
        $saveAs   = is_null($this->saveAs) ? $this->fileExtension : $this->saveAs;
        $quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
        $cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
        $sharpen_       = is_null($this->sharpen) ? null : "_s";
        $grey_       = is_null($this->grey) ? null : "_gr";        
        $dirName        = preg_replace('/\//', '-', dirname($this->src));
        $this->cacheFileName = $this->cache_path . "-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}{$grey_}.{$saveAs}";
        $this->cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $this->cacheFileName);

        if($this->verbose) { 
            $this->verbose("Cache file is: {$this->cacheFileName}"); 
        }
    }


// Is there already a valid image in the cache directory, then use it and exit
    public function outputValidCache() {
        $imageModifiedTime = filemtime($this->pathToImage);
        $cacheModifiedTime = is_file($this->cacheFileName) ? filemtime($this->cacheFileName) : null;
        // If cached image is valid, output it. !!
        if(!$this->ignoreCache && is_file($this->cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
        if($this->verbose) { 
            $this->verbose("Cache file is valid, output it."); 
        }
        $this->outputImage($this->cacheFileName, $this->verbose); // skriv ut bilden
        }
        if($this->verbose) {
            $this->verbose("Cache is not valid, process image and create a cached version of it."); 
        }
    }    

    //
// Open up the original image from file
//
    public function openOriginalImage() {
        if($this->verbose) { 
            $this->verbose("File extension is: {$this->fileExtension}"); 
        }

        switch($this->fileExtension) {  
        case 'jpeg': 
            $image = imagecreatefromjpeg($this->pathToImage);
            if($this->verbose) { $this->verbose("Opened the image as a JPEG image."); }
            break;  
            
        case 'jpg': 
            $image = imagecreatefromjpeg($this->pathToImage);
            if($this->verbose) { $this->verbose("Opened the image as a JPG image."); }
            break;  
  
        case 'png':  
            $image = imagecreatefrompng($this->pathToImage); 
            if($this->verbose) { $this->verbose("Opened the image as a PNG image."); }
            break;  
            
        case 'gif':  
            $image = imagecreatefromgif($this->pathToImage); 
            if($this->verbose) { $this->verbose("Opened the image as a GIF image."); }
            break;             

            default: $this->errorMessage('No support for this file extension.');
        }
        return $image;
    }    

    //
// Resize the image if needed
//
    public function reziseImage($image) {
        if($this->cropToFit) {
            if($this->verbose) { 
                $this->verbose("Resizing, crop to fit."); 
            }
            $cropX = round(($this->width - $this->cropWidth) / 2);  
            $cropY = round(($this->height - $this->cropHeight) / 2);    
            $imageResized = $this->createImageKeepTransparency($this->newWidth, $this->newHeight);
            imagecopyresampled($imageResized, $image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $this->cropWidth, $this->cropHeight);
            $image = $imageResized;
            $this->width = $this->newWidth;
            $this->height = $this->newHeight;
        }
        else if(!($this->newWidth == $this->width && $this->newHeight == $this->height)) {
            if($this->verbose) {
                $this->verbose("Resizing, new height and/or width."); 
            }
            $imageResized = $this->createImageKeepTransparency($this->newWidth, $this->newHeight);
            imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
            $image  = $imageResized;
            $this->width  = $this->newWidth;
            $this->height = $this->newHeight;
        }
        return $image;
    }   
    
    /**
    * Create new image and keep transparency
    *
    * @param resource $image the image to apply this filter on.
    * @return resource $image as the processed image.
    */
    public function createImageKeepTransparency($width, $height) {
        $img = imagecreatetruecolor($width, $height);
        imagealphablending($img, false);
        imagesavealpha($img, true);  
        return $img;
    }
        

        /**
    * Sharpen image as http://php.net/manual/en/ref.image.php#56144
    * http://loriweb.pair.com/8udf-sharpen.html
    *
    * @param resource $image the image to apply this filter on.
    * @return resource $image as the processed image.
    */
    private function sharpenImage($image) {
        $matrix = array(
            array(-1,-1,-1,),
            array(-1,16,-1,),
            array(-1,-1,-1,)
            );
        $divisor = 8;
        $offset = 0;
        imageconvolution($image, $matrix, $divisor, $offset);
        return $image;
    }
    
    public function greyImage($image) {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        return $image;
    }    

//
// Save the image
//
    public function saveImage($image) {
        switch($this->fileExtension) {
        case 'jpeg':
        case 'jpg':
            if($this->verbose) { 
                $this->verbose("Saving image as JPEG to cache using quality = {$this->quality}.");
            }
            imagejpeg($image, $this->cacheFileName, $this->quality);
            break;  

        case 'png':  
            if($this->verbose) { 
                $this->verbose("Saving image as PNG to cache."); 
            }
           // Turn off alpha blending and set alpha flag
            imagealphablending($image, false);
            imagesavealpha($image, true);     
            imagepng($image, $this->cacheFileName);  
            break; 
            
        case 'gif':  
            if($this->verbose) { 
                $this->verbose("Saving image as GIF to cache."); 
            }
           // Turn off alpha blending and set alpha flag
            imagealphablending($image, false);
            imagesavealpha($image, true);     
            imagegif($image, $this->cacheFileName);  
            break;            

        default:
            $this->errorMessage('No support to save as this file extension.');
            break;
        }

        if($this->verbose) { 
            clearstatcache();
            $this->cacheFilesize = filesize($this->cacheFileName);
            $this->verbose("File size of cached file: {$this->cacheFilesize} bytes."); 
            $this->verbose("Cache file has a file size of " . round($this->cacheFilesize/$this->filesize*100) . "% of the original size.");
        }
    }    



}    
