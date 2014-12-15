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

$id = null;
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
} else if(isset($_GET['id'])) {
    $id = $_GET['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
}   
 
// kolla först om anv är inloggad
$delete = isset($_POST['delete']) && ($_POST['id']) ? true : false;
if($delete) {
    $message = $c->DeleteContent(array($id));
}    

$html_form = "<article class='me'><form method=post><fieldset>
<legend>Ta bort en sida eller en post</legend>
<input type=hidden name=id value='{$id}'>
<p>OBS! Posten eller sidan med id = {$id} kommer att raderas definitivt.</p><br>
<input type=submit name='delete' value='Ta bort'>
</fieldset></form></article>";

// stuva grejorna i kabyssen
$kabyssen['title'] = "Ta bort";
$kabyssen['main'] = <<<EOD
<article class='me'>{$html_form}</article>
<footer>
{$message}
</footer>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
