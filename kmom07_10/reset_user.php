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
$user = new CVinylUser();

// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);
$admin = $user->Check_User_Admin();
// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$reset = isset($_POST['reset']) ? true : false; 

if($reset && $admin) {
    $message = $user->ResetUserDatabase();

    
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ användardatabasen";
    $kabyssen['main'] = <<<EOD
     <h2>Återställ användardatabasen</h2>
    <article class='me bg'>     
    <p>1. {$message['createuserdatabase']}</p>
    <p>2. {$message['insertuserdatabase']}</p>
    </article>

EOD;
} else if($admin && !$reset) {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ användardatabasen";
    $kabyssen['main'] = <<<EOD
    <h2>Återställ användardatabasen</h2>
    <article class='me bg'><form method=post><fieldset>
    <p>OBS! Användardatabasen kommer att återställas med standardinnehåll.</p><br>
    <input type=submit name='reset' value='Återställ'>
    </fieldset></form></article>
EOD;
} else {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ användardatabasen";
    $kabyssen['main'] = <<<EOD
    <h2>Återställ användardatabasen</h2>
    <article class='me bg'>
    <p>Du måste vara inloggad som administratör för att återställa databasen.</p>
    </article>
EOD;
}
// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
