<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 


// Add style for csource
$kabyssen['stylesheets'][] = 'css/source.css';


// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));


// Do it and store it all in variables in the Kabyssen container.
$kabyssen['title'] = "Visa källkod";

$kabyssen['main'] = "<h1>Visa källkod</h1>\n" . $source->View();


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
