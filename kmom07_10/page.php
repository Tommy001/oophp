<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Lite stajling
$kabyssen['stylesheets'][]        = 'css/typography.css';
$kabyssen['stylesheets'][]        = 'css/navbar.css';

$login = true;
$message = null;
$user = new CVinylUser();
// Kolla om anv vill logga ut
if(isset($_POST['logout'])) {
    unset($_SESSION['vinyl_user']);
unset($_SESSION['userid']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;  
} 
$page = new CVinylPage();

// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$url = isset($_GET['url']) ? $_GET['url'] : null; 

$content = $page->Page(array($url));

// stuva grejorna i kabyssen
$kabyssen['title'] = "Visa sida";
$kabyssen['main'] = <<<EOD
<h2>{$content['title']}</h2>
<article class='me bg'>{$content['data']}</article>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
