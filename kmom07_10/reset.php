<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// inkludera config-filen som bla innehåller kabyssen-variabeln och sql-frågor
include(__DIR__.'/config.php'); 

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

$cvinyl = new CVinylContent();
$user = new CVinylUser();
$admin = $user->Check_User_Admin();
$reset = isset($_POST['reset']) ? true : false;

if($reset && $admin) {
    $message = $cvinyl->ResetDatabase();

    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ webbdatabasen";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'>
     <h2>Återställ webbdatabasen</h2>
     <p>1. {$message['createtable']}</p>
     <p>2. {$message['insertcontent']}</p>
     <p>3. {$message['createcont2cat']}</p>
     <p>4. {$message['insertcont2cat']}</p>
     </article>

EOD;
} else if($admin && !$reset) {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ webbdatabasen";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'><form method=post><fieldset>
    <h2>Återställ webbdatabasen</h2>
    <p>OBS! webbdatabasen kommer att återställas med standardinnehåll.</p><br>
    <input type=submit name='reset' value='Återställ'>
    </fieldset></form></article>
EOD;
} else {
    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Återställ webbdatabasen";
    $kabyssen['main'] = <<<EOD
    <article class='me bg'>
    <p>Du måste vara inloggad som administratör för att återställa databasen.</p>
    </article>
EOD;
}
// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
