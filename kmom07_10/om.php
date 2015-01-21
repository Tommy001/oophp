<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/typography.css';
$kabyssen['stylesheets'][]        = 'css/navbar.css';

$login = true;
$user = new CVinylUser();
// Kolla om anv vill logga ut
if(isset($_POST['logout'])) {
    unset($_SESSION['vinyl_user']);
unset($_SESSION['userid']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;  
} 
// ny instans av CVinylPage
$page = new CVinylPage();
// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);
// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$content = $page->Page(array('om'));

$kabyssen['title'] = "Om oss";

$kabyssen['main'] = <<<EOD
<h1>{$content['title']}</h1>
<article class='me bg'>
<img class='image_right' src='img.php?src=LP-bakgrund.gif&amp;width=300' alt='om'>
<p>{$content['data']}</p>
<footer>
{$content['editlink']}
</footer>
</article>
<br>
{$kabyssen['byline']}

EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
