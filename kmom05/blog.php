<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';
$kabyssen['title'] = "Visa bloggposter";

$login = true;
$message = null;
$user = new CUser();
// Check if user is authenticated.
$acronym = $user->Check_User();

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$blog = new CBlog();

$slug = isset($_GET['slug']) ? $_GET['slug'] : null; // slug

// stuva allt i kabyssen, i en slinga dessutom för att visa alla poster
$kabyssen['main'] = $blog->GetBlogPost($slug);

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
