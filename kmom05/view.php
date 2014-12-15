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
$html_reset = isset($acronym) ? "<p><a href='reset.php'>Återställ databasen.</a></p>" : null;
$html_create = isset($acronym) ? "<p><a href='create.php'>Skapa en ny sida eller bloggpost.</a></p>" : null;
// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);
// anslutning databas, instans av CContent
$c = new CContent();

$items = $c->GetItAll();

// stuva grejorna i kabyssen
$kabyssen['title'] = "Visa alla";
$kabyssen['main'] = <<<EOD
<h1>Visa allt innehåll</h1>
<ul>{$items}</ul>
<p><a href='blog.php'>Visa alla bloggposter.</a></p>
{$html_reset}
{$html_create}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
