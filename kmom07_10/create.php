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

// ta hand om inkommande
$create = isset($_POST['create']) ? true : false;
$type = isset($_POST['type']) ? strip_tags($_POST['type']) : null;
$kategori = isset($_POST['kategori']) ? strip_tags($_POST['kategori']) : null;
$filter = isset($_POST['filter']) ? strip_tags($_POST['filter']) : null;
$filter .= isset($_POST['link']) ? strip_tags($_POST['link']) : null;
$filter .= isset($_POST['nl2br']) ? strip_tags($_POST['nl2br']) : null;
$filter .= isset($_POST['typo']) ? strip_tags($_POST['typo']) : null;
$data = isset($_POST['data']) ? strip_tags($_POST['data']) : null;
$title = isset($_POST['title']) ? strip_tags($_POST['title']) : null;


$c = new CVinylContent();

if(isset($acronym)) { 
    if($create) {
        $message = $c->InsertNewContent($title, $type, $data, $filter);
        $message2 = $c->InsertCategory(array($kategori));
    }  
    


    // uppdateringsformuläret
    $html_form = "
    <h2>Nytt inlägg i bloggen</h2>
    <div class='me two_col'>    
    <div class='bg'><form method=post><fieldset>    
    <p>Titel</p>
    <input placeholder='Skriv titeln här' class='textfield' type=text name=title value=''><br>
    <p>Text</p>
    <textarea placeholder='Skriv texten här' class='textarea' name=data></textarea><br>
    </div>
    <div class='bg'>    
    <p>Kategori</p>    
    <input type='radio' name='kategori' value='1' checked>Nyheter
    <input type='radio' name='kategori' value='2'>Rekrytering
    <input type='radio' name='kategori' value='3'>Event
    <input type='radio' name='kategori' value='4'>Medlemmar<br>
    <p>Textfilter</p>
    <input type='radio' name='filter' value='markdown,' checked>Markdown
    <input type='radio' name='filter' value='bbcode,' checked>BBcode<br> 
    <input type='checkbox' name='link' value='link,' checked>Länkar
    <input type='checkbox' name='nl2br' value='nl2br,' checked>Radbrytningar
    <input type='checkbox' name='typo' value='typo,' checked>Smartypants Typographer<br>   
    <input type='hidden' name='type' value='post'><br>
    <input type=submit name=create value='Spara'><br>
    <h4>{$message}</h4>
    </fieldset></form></div></div>";
} else {
    $html_form = "Du måste vara inloggad för att skapa sidor eller poster.";
}  


// stuva grejorna i kabyssen
$kabyssen['title'] = "Ändra";
$kabyssen['main'] = <<<EOD
{$html_form}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
