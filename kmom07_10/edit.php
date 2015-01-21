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
$content = new CVinylContent();

// Kolla om anv vill logga ut
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

// type kan antingen komma från view.php via GET
// eller via POST från formuläret nedan
$type = null;
if(isset($_POST['type'])) {
    $type = strip_tags($_POST['type']);
} else if(isset($_GET['type'])) {
    $type = strip_tags($_GET['type']);
}

// ta hand om fler inkommande

$title = isset($_POST['title']) ? $_POST['title'] : null;
$kategori = isset($_POST['kategori']) ? strip_tags($_POST['kategori']) : null;
$slug = isset($_POST['slug']) ? $_POST['slug'] : null;
$url = isset($_POST['url']) ? strip_tags($_POST['url']) : null;
$data = isset($_POST['data']) ? $_POST['data'] : null;
$filter = isset($_POST['filter']) ? $_POST['filter'] : null;
$filter .= isset($_POST['link']) ? $_POST['link'] : null;
$filter .= isset($_POST['nl2br']) ? $_POST['nl2br'] : null;
$filter .= isset($_POST['typo']) ? $_POST['typo'] : null;
$pub = isset($_POST['published']) ? $_POST['published'] : null;
$save = isset($_POST['save']) ? true : false;

$admin = $user->Check_User_Admin();

// stuva grejorna i kabyssen
$kabyssen['title'] = "Ändra post eller sida";
$kabyssen['main'] = $content->GetContentEditForm($id, $type, $admin, $save, $title, $slug, $kategori, $url, $data, $filter, $pub, $save);

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
