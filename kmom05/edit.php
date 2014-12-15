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

// id kan antingen komma från page.php eller blog.php via GET
// eller via POST från formuläret nedan
$id = null;
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
} else if(isset($_GET['id'])) {
    $id = $_GET['id'];
    is_numeric($id) or die('Id ska vara numeriskt');
}    

// ta hand om inkommande
$title = isset($_POST['title']) ? $_POST['title'] : null;
$slug = isset($_POST['slug']) ? $_POST['slug'] : null;
$url = isset($_POST['url']) ? strip_tags($_POST['url']) : null;
$data = isset($_POST['data']) ? $_POST['data'] : null;
$type = isset($_POST['type']) ? strip_tags($_POST['type']) : null;
$filter = isset($_POST['filter']) ? $_POST['filter'] : null;
$pub = isset($_POST['published']) ? $_POST['published'] : null;
$save = isset($_POST['save']) ? true : false;

$content = new CContent();
if(isset($acronym)) { 
// uppdatera raden mha CContent
$url = empty($url) ? null : $url; // om url inte är satt ska den vara null
if($save) {
    $params = array($title, $slug, $url, $data, $type, $filter, $pub, $id);
    $message = $content->UpdateContent($params);
}    

// hämta raden mha CContent
$res = $content->ViewContent(array($id));

   
// uppdateringsformuläret
    $html_form = "<article class='me'><form method=post><fieldset>
    <legend>Ändra innehåll</legend>
    <input type=hidden name=id value='{$id}'>
    <p>Titel</p>
    <input class='textfield' type=text name='title' value='{$res['title']}'><br>
    <p>Slug</p>
    <input class='textfield' type=text name='slug' value='{$res['slug']}'><br>
    <p>URL</p>
    <input class='textfield' type=text name='url' value='{$res['url']}'><br>
    <p>Text</p>
    <textarea class='textarea' name='data'>{$res['data']}</textarea><br>
    <p>Typ</p>
    <input class='textfield' type=text name='type' value='{$res['type']}'><br>
    <p>Filter</p>
    <input class='textfield' type=text name='filter' value='{$res['filter']}'><br>
    <p>Publiceringsdatum</p>
    <input class='textfield' type=text name='published' value='{$res['pub']}'><br><br>
    <input type=submit name=save value='Spara'><br>
    </fieldset></form></article>";
} else {
    $html_form = "Du måste vara inloggad för att göra ändringar.";
}    

// stuva grejorna i kabyssen
$kabyssen['title'] = "Ändra";
$kabyssen['main'] = <<<EOD
{$html_form}
{$message}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
