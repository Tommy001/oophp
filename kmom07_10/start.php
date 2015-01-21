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

// ny instans av CVinylPage
$page = new CVinylPage();
$music = new CVinylMusic();
$ccontent = new CVinylContent();
$chtml = new CVinylHTML();

// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);

$start = $page->Page(array('start'));
$nyheter = $page->Page(array('nyheter'));
$blogg = $page->Page(array('blogg'));
$genrer = $page->Page(array('genrer'));
$params = array('post', 3,);
$posts = $ccontent->GetLatestPosts($params);
$active_genres = $chtml->GetActiveGenres_Start();
$latest_records = $music->GetLatestRecords();

$kabyssen['title'] = "Start";
$kabyssen['main'] = <<<EOD
<h2>{$start['title']}</h2>
<div id='kolumner' class='bg'>
    {$start['data']}
    <div class='left'>
        <img src='img.php?src=music/Black_Sabbath_SbS.jpg&amp;width=100&amp;height=100&amp;crop-to-fit' alt=SBS>
        <img src='img.php?src=music/synd.jpg&amp;width=100&amp;height=100&amp;crop-to-fit' alt=synd>
        <img src='img.php?src=music/wishyouwerehere.jpg&amp;width=100&amp;height=100&amp;crop-to-fit' alt=wish>
        <img src='img.php?src=music/darkside.jpg&amp;width=100&amp;height=100&amp;crop-to-fit' alt=darkside>
        <img src='img.php?src=music/raindogs.jpg&amp;width=100&amp;height=100&amp;crop-to-fit' alt=raindogs>
    </div>
    <div class='senast center'>
        <h3 class='margin-bottom'><strong>Senast sålda skiva...</strong></h3>
        <img src='img.php?src=music/diamondlife.png&amp;width=140&amp;height=140&amp;crop-to-fit' alt=living>
    </div>    
    <div id='kol1'>
        <h2>{$nyheter['title']}</h2>
        {$nyheter['data']}
        <ul>{$latest_records}</ul>
    </div>
    <div id='kol2yttre'>
        <div id='kol2mitten'>
            <h2>{$blogg['title']}</h2>
            {$blogg['data']}
            <ul>{$posts}</ul>    
        </div>
        <div id='kol2sidan'>
        <h2>{$genrer['title']}</h2>
            {$genrer['data']}
            {$active_genres}
        </div>
    </div>
</div>
<div class='grans'></div>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
