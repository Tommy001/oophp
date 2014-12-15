<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Lite stajling
$kabyssen['stylesheets'][]        = 'css/style.css';
$login = true;
$message = null;
$user = new CUser();
$page = new CPage();

// Check if user is authenticated.
$acronym = $user->Check_User();

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$url = isset($_GET['url']) ? $_GET['url'] : null; 

$content = $page->Page(array($url));

// stuva grejorna i kabyssen
$kabyssen['title'] = "Visa sida";
$kabyssen['main'] = <<<EOD
<h1>{$content['title']}</h1>
<article class='me'>{$content['data']}</article>
<footer>
{$content['editlink']}
</footer>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
