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

$reset = isset($_POST['reset']) ? true : false; 

$admin = $user->Check_User_Admin();

if($reset && $admin) {
    $message = $music->ResetMusicDatabase();

    
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ skivdatabas";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'>
     <h2>Återställ skivdatabasen</h2>
    <p>1. {$message['dropmusic2genre_table']}</p>
    <p>2. {$message['createmusic_table']}</p>
    <p>3. {$message['insertmusic_content']}</p>
    <p>4. {$message['createm2gtable']}</p>
    <p>5. {$message['insertm2gcontent']}</p>
    </article>
EOD;
} else if($admin && !$reset) {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ skivdatabas";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'><form method=post><fieldset>
    <h2>Återställ skivdatabasen</h2>
    <p>OBS! Skivdatabasen kommer att återställas med standardinnehåll.</p><br>
    <input type=submit name='reset' value='Återställ'>
    </fieldset></form></article>
EOD;
} else {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ skivdatabas";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'>
    <p>Du måste vara inloggad som administratör för att återställa databasen.</p>
    </article>
EOD;
}
// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
