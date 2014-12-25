<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';
$kabyssen['stylesheets'][]        = 'css/gallery.css';
$kabyssen['stylesheets'][]        = 'css/figure.css';
$kabyssen['stylesheets'][]        = 'css/breadcrumb.css';

// Define the basedir for the gallery

$basics = array(
    'gallery_path' => __DIR__ . DIRECTORY_SEPARATOR . 'img'
    );

$gall = new CGallery($basics);

// Get incoming parameters
if(isset($_GET['path'])) {
    $gall->set_path($_GET['path']);
}    
 
// hämta galleriet
$gallery = $gall->Gallery();

// hämta navigeringssökvägen
$breadcrumb = $gall->createBreadcrumb();
 
$kabyssen['title'] = "Mitt galleri";
$kabyssen['main'] = <<<EOD
<h1>{$kabyssen['title']}</h1>
 
{$breadcrumb}
 
{$gallery}
 
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
