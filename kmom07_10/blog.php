<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/typography.css';
$kabyssen['stylesheets'][]        = 'css/navbar.css';
$login = true;
$user = new CVinylUser();
// Kolla om anv vill logga ut
if(isset($_POST['logout'])) {
    unset($_SESSION['vinyl_user']);
unset($_SESSION['userid']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;  
} 
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);
// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$kabyssen['title'] = "Blogg";

$blog = new CVinylBlog();

$slug = isset($_GET['slug']) ? $_GET['slug'] : null; // slug
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;

// stuva allt i kabyssen, i en slinga dessutom för att visa alla poster
$kabyssen['main'] = $blog->GetBlogPost($slug, $kategori);


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
