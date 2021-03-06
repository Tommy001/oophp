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
$message = null;
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
$admin = $user->Check_User_Admin();

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

// ta hand om fler inkommande
$username = isset($_POST['acronym']) ? $_POST['acronym'] : null;
$fornamn = isset($_POST['fornamn']) ? $_POST['fornamn'] : null;
$efternamn = isset($_POST['efternamn']) ? strip_tags($_POST['efternamn']) : null;
$adress = isset($_POST['adress']) ? $_POST['adress'] : null;
$postnr = isset($_POST['postnr']) ? strip_tags($_POST['postnr']) : null;
$ort = isset($_POST['ort']) ? $_POST['ort'] : null;
$epost = isset($_POST['epost']) ? $_POST['epost'] : null;
$rattighet = isset($_POST['rattighet']) ? $_POST['rattighet'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$save = isset($_POST['save']) ? true : false;


// stuva grejorna i kabyssen
$kabyssen['title'] = "Lägg till användare";
$kabyssen['main'] = $user->NewUserForm($admin, $save, $password, $username, $fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $username);

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
