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
$c = new CVinylContent();

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

$admin = $user->Check_User_Admin();

$id = null;
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
} else if(isset($_GET['id'])) {
    $id = $_GET['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
}   

$delete = isset($_POST['delete']) && ($_POST['id']) ? true : false;
    
// stuva grejorna i kabyssen
$kabyssen['title'] = "Ta bort";
$kabyssen['main'] = $c->GetDeleteContentForm($id, $admin, $delete);

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
