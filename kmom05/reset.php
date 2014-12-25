<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// inkludera config-filen som bla innehåller kabyssen-variabeln och sql-frågor
include(__DIR__.'/config.php'); 

// stuva lite stajling i kabyssen
$kabyssen['stylesheets'][]        = 'css/style.css';
$login = true;
$message = null;
$user = new CUser();
// Check if user is authenticated.
$acronym = $user->Check_User();

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$c = new CContent();
if(isset($acronym)) { 
// kolla först om anv är inloggad
$save = isset($_POST['reset']) ? true : false;
if($save) {
    $message = $c->ResetDatabase();
}    

$html_form = "<article class='me'><form method=post><fieldset>
<legend>Återställ databasen</legend>
<p>OBS! Tabellen kommer att raderas, skapas på nytt och fyllas med ett exempelinnehåll.</p><br>
<input type=submit name='reset' value='Återställ'>
</fieldset></form></article>";

} else {
    $html_form = "Du måste vara inloggad för att återställa databasen.";
} 

// stuva grejorna i kabyssen
$kabyssen['title'] = "Återställ databasen";
$kabyssen['main'] = <<<EOD
<article class='me'>{$html_form}</article>
<footer>
{$message['createtable']}
{$message['createcontent']}
</footer>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
