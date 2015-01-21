<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Lite stajling
$kabyssen['stylesheets'][]        = 'css/typography.css';
$kabyssen['stylesheets'][]        = 'css/navbar.css';

$login = true;

$user = new CVinylUser();
$music = new CVinylMusic();

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

$id = isset($_GET['id']) ? $_GET['id'] : null; 

$lp = $music->GetInfo(array($id));

$wikipedia = !empty($lp->wikipedia) ? "<p><a href=" . $lp->wikipedia . ">Läs mer på Wikipedia...</a></p>" : null;
$youtube = !empty($lp->youtube) ? "<div class='center'><iframe width='560' height='335' src='//www.youtube.com/embed/" . $lp->youtube . "'>
 frameborder='0' allowfullscreen></iframe></div>" : null;
    

// stuva grejorna i kabyssen
$kabyssen['title'] = "Visa sida";
$kabyssen['main'] = <<<EOD
<div class='me bg relative'>
<h1 class='center'>{$lp->artist}</h1>
<h2 class='center'>{$lp->title}</h2>
<div class='prislapp'><img src='img.php?src=music/red_prislapp.png&amp;width=100' alt='prislapp'></div>
<div class='pris'><h3>{$lp->pris}</h3></div>
<figure class='center'><img src='img.php?src={$lp->image}' alt='{$lp->title}'></figure>
<article class='me'>{$lp->beskrivning}</article>
{$wikipedia}<br><br>
{$youtube}
</div>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
