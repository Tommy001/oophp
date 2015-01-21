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


// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);
// anslutning databas, instans av CVinylContent
$c = new CVinylContent();
if(isset($acronym)) { 
    // returnerar html för antingen admin eller medlem
    $items = $c->GetItAll();
    
    // returnerar knappar för admin eller nothing för övriga
    $buttons = $c->GetButtons();

    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Administration";
    $kabyssen['main'] = <<<EOD
    <h2>Administrera webbplatsen</h2>
    <article class="me bg">
    <table>{$items}</table>
    <br>
    <table>{$buttons}</table>    
    </article>
EOD;
} else {
    $kabyssen['main'] = <<<EOD
    <h2>Administrera webbplatsen</h2>
    <article class='me bg'>
    <p>Du måste vara inloggad för att se den här sidan.</p>
    </article>
EOD;
}

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
