<?php 
// Add style for csource
$kabyssen['stylesheets'][] = 'css/source.css';
 

/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kabyssen container.
$kabyssen['title'] = "Hello World";
 
$kabyssen['header'] = <<<EOD
<img class='sitelogo' src='img/kabyssen.png' alt='Kabyssen Logo'/>
<span class='sitetitle'>Kabyssen webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;
 
$kabyssen['main'] = <<<EOD
<h1>Hej Världen</h1>
<p>Detta är en exempelsida som visar hur Kabyssen ser ut och fungerar.</p>
EOD;
 
$kabyssen['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Mikael Roos (me@mikaelroos.se) | <a href='https://github.com/mosbth/Kabyssen-base'>Kabyssen på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;
 
 
// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
