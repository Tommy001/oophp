<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 
include "../kabyssen/src/CSource/CSource.php";
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));

// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/source.css';

// Do it and store it all in variables in the Kabyssen container.

$kabyssen['title'] = "Visa källkod";

$kabyssen['main'] = <<<EOD
<article class="source">
    <h1>Visa källkod</h1>
    <p>
        Klicka för att öppna kataloger och granska filer.
    </p>
    {$source->View()}
</article>
EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);

