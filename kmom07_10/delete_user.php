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

$id = null;
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
} else if(isset($_GET['id'])) {
    $id = $_GET['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
}  

$delete = isset($_POST['delete']) && ($_POST['id']) ? true : false;

$currentuser = false;
if(isset($_SESSION['userid'])) {
    $currentuser = $id == $_SESSION['userid'] ? true : false;
}
        
$kabyssen['title'] = "Ta bort användare";
// kolla först om anv är inloggad som admin
if($admin || $currentuser) {
    // stuva grejorna i kabyssen
    $kabyssen['main'] = $user->DeleteUser($delete, $id);
} else {
    $kabyssen['main'] = <<<EOD
    <h2>Ta bort användare</h2>
    <article class='me bg'>
    Du måste vara inloggad som administratör för att ta bort användare.
    </article>
EOD;
}

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
