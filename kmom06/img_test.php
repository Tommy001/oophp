<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';

// Do it and store it all in variables in the Kabyssen container.

$kabyssen['title'] = "Om mig";

$image = "<img src=\"http://www.student.bth.se/~toja14/oophp/me/kmom06/img.php?src=logo.gif
\" alt=\"Test med img.php\">";

$kabyssen['main'] = <<<EOD
{$image}
EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
