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

// ta hand om inkommande
$create = isset($_POST['create']) ? true : false;
$type = isset($_POST['type']) ? strip_tags($_POST['type']) : null;
$title = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$data = isset($_POST['data']) ? strip_tags($_POST['data']) : null;

// instans av CDatabase skapas och skickas med till CContent
$c = new CContent();
if(isset($acronym)) { 
    if($create) {
        $message = $c->InsertNewContent($title, $type, $data);
    }    

    // uppdateringsformuläret
    $html_form = "<article class='me'><form method=post><fieldset>
    <legend>Skapa en ny bloggpost eller sida</legend>
    <p>Titel</p>
    <input class='textfield' type=text name=title value=''><br>
    <p>Text</p>
    <textarea class='textarea' name=data>Skriv textinnehållet här...</textarea><br>
    <p>Typ</p>
    <input class='textfield' type=text name=type value='page'><br><br>
    <input type=submit name=create value='Spara'>
    </fieldset></form></article>";
} else {
    $html_form = "Du måste vara inloggad för att skapa sidor eller poster.";
}  


// stuva grejorna i kabyssen
$kabyssen['title'] = "Ändra";
$kabyssen['main'] = <<<EOD
{$html_form}
{$message}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
