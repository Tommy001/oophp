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

$kabyssen['title'] = "Min filmdatabas";
$acronym = null;
$params = null;
$login = false;

$db = new CDatabase($kabyssen['database']);
$user = new CUser($db);
   
// Check if user is authenticated.
$acronym = $user->Check_User();

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

if(isset($_POST['logout'])) {
  unset($_SESSION['user']);
  header('Location: logout.php');
}

if(isset($_POST['acronym']) && ($_POST['password'])) {
$params = array($_POST['acronym'], $_POST['password']);
}
$html_form = $user->Get_Logout_Form();

$kabyssen['main'] = <<<EOD
{$html_form}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);

