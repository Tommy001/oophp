<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// inkludera config-filen som bla innehåller kabyssen-variabeln och sql-frågor
include(__DIR__.'/config.php'); 

// stuva lite stajling i kabyssen
$kabyssen['stylesheets'][]        = 'css/typography.css';
$kabyssen['stylesheets'][]        = 'css/navbar.css';

$login = true;

$music = new CVinylMusic();
$user = new CVinylUser();
// Kolla om anv vill logga ut
if(isset($_POST['logout'])) {
    unset($_SESSION['vinyl_user']);
unset($_SESSION['userid']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;  
} 
// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

// ta hand om inkommande
if (isset($_POST['genres'])) {
    $genreArray = $_POST['genres'];
    for ($i=0; $i<count($genreArray); $i++); 
} else {
    $genreArray = false;
}
$id = isset($_GET['id']) ? $_GET['id'] : null; 
$save = isset($_POST['save']) ? true : null;
$title = isset($_POST['title']) ? strip_tags($_POST['title']) : false;
$artist = isset($_POST['artist']) ? strip_tags($_POST['artist']) : false;
$genre = isset($_POST['genre']) ? strip_tags($_POST['genre']) : null;
$pris = isset($_POST['pris']) ? $_POST['pris'] : false;
$year = isset($_POST['year']) ? strip_tags($_POST['year']) : null;
$image = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$wikipedia = isset($_POST['wikipedia']) ? strip_tags($_POST['wikipedia']) : null;
$youtube = isset($_POST['youtube']) ? strip_tags($_POST['youtube']) : false;
$beskrivning = isset($_POST['beskrivning']) ? strip_tags($_POST['beskrivning']) : null;

$admin = $user->Check_User_Admin();

// stuva grejorna i kabyssen
$kabyssen['title'] = "Ändra i skivtabellen";
$kabyssen['main'] = $music->EditMusicForm($id, $genreArray, $admin, $save, $title, $artist, $pris, $year, $image, $wikipedia, $youtube, $beskrivning);

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
