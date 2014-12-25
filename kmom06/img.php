<?php 
/**
 * This is a PHP skript to process images using PHP GD.
 *
 */
// Egentligen behövs ju bara en inkludering av CImage här... kolla om det funkar
include(__DIR__.'/config.php');  

//
// Ensure error reporting is on
//
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly

// Use DIRECTORY_SEPARATOR to make it work on both windows and unix.
$basics =  array(
    'img_path' => __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR, 
    'cache_path' => __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR,
    'maxWidth' => 2000,
    'maxHeight' => 2000,
    );
// 
// Instans av CImage, skicka med värdena ovan
//
$img = new CImage($basics);

//
// Ta emot inkommande querystring och stoppa in eventuella värden 
//  i medlemsvariabler i CImage
//
if(isset($_GET['src'])) {
    $img->set_src($_GET['src']); 
}
if(isset($_GET['save-as'])) {
    $img->set_saveAs($_GET['save-as']); 
}
if(isset($_GET['verbose'])) {
    $img->set_verbose(true); 
}
if(isset($_GET['quality'])) {
    $img->set_quality($_GET['quality']); 
}
if(isset($_GET['no-cache'])) {
    $img->set_ignore_cache(true); 
}
if(isset($_GET['width'])) {
    $img->set_new_width($_GET['width']); 
}        
if(isset($_GET['height'])) {
    $img->set_new_height($_GET['height']); 
}    
if(isset($_GET['crop-to-fit'])) {
    $img->set_crop_to_fit(true); 
}
if(isset($_GET['sharpen'])) {
    $img->set_sharpen(true); 
}
if(isset($_GET['grey'])) {
    $img->set_grey(true); 
} 

//
// Visa bilden
//
$img->Image();
