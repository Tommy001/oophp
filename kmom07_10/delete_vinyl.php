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
$music = new CVinylMusic();
$user = new CVinylUser();

// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$id = isset($_GET['id']) ? $_GET['id'] : null; 
$delete = isset($_GET['delete']) ? true : false; 

if($delete) {
    $message = $music->DeleteVinyl(array($id));
}
$admin = $user->Check_User_Admin();

if($admin && !$delete) {
    // stuva grejorna i kabyssen
    $lp = $music->GetInfo(array($id));
    $kabyssen['title'] = "Ta bort skiva";
    $kabyssen['main'] = <<<EOD
    <h1 class='center'>{$lp->artist}</h1>
    <h2 class='center'>{$lp->title}</h2>
    <figure class='center'><img src='img.php?src={$lp->image}' alt='{$lp->title}'></figure>
    <div class='center'><form action='?delete&amp;id=$id' method='post'><input type='submit' value='Ta bort skivan'></form>
EOD;
} else if($admin && $delete) {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Ta bort skiva";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'>
    <h2>$message</h2>
    </article>
EOD;
} else {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Ta bort skiva";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'>
    <p>Du måste vara inloggad som administratör för att ta bort skivor.</p>
    </article>
EOD;
}   

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
