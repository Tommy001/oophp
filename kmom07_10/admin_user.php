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

$chtml = new CVinylHTML();
$search = new CVinylSearch();

// Ta emot parametrar
$hits     = isset($_GET['hits'])  ? $_GET['hits']  : 10;
$page     = isset($_GET['page'])  ? $_GET['page']  : 1;
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'acronym';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';

// Kolla inkommande
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');

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
// anslutning databas, instans av CVinylContent
$user = new CVinylUser();

// kolla om nån är inloggad
if(isset($acronym)) { 
    
    // GetAllUsers returnerar html för antingen "medlem" eller "admin"
    $items = $user->GetAllUsers($hits, $page, $orderby, $order);
    
    $maxrows[] = $user->GetMaxPages($hits);
    $max = $maxrows[0][0];
    $rows = $maxrows[0][1];

    $hitsPerPage = $search->getHitsPerPage(array(4, 6, 8, 10), $hits);
    $navigatePage = $search->getPageNavigation($hits, $page, $max);    

    
    // GetButtons returnerar knappar för admin eller nothing för överiga
    $buttons = $user->GetButtons();

    // stuva grejorna i kabyssen
    $kabyssen['title'] = "Administration";
    $kabyssen['main'] = <<<EOD
    <h2>Administrera användare</h2>
    <div class='dbtable'><div class='rows'>{$rows} träffar. {$hitsPerPage}</div></div>
    <div class="me bg">
    <table>{$items}</table>
    <div class='dbtable'><div class='pages'>{$navigatePage}</div>
    <br>
    <table>{$buttons}</table>    
    </div>
EOD;
} else {
    $kabyssen['title'] = "Administration";    
    $kabyssen['main'] = <<<EOD
    <h2>Administrera användare</h2>
    <article class='me bg'>
    <p>Du måste vara inloggad för att se den här sidan.</p>
    </article>
EOD;
}

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
