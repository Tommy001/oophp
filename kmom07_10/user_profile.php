<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

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

if(isset($_GET['id'])) {
        $id = $_GET['id'];
} else {
    $id = isset($_SESSION['userid']) ? $_SESSION['userid'] : 1; // debug
}

$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);
// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$kabyssen['title'] = "Användarprofil";

// stuva allt i kabyssen
$kabyssen['main'] = $user->GetUserProfile($id, $acronym);


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
