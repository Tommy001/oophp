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

// Do it and store it all in variables in the Kabyssen container.
$kabyssen['title'] = "Musik";
$login = true;
// anslutning databas, objekt för sökfunktioner, formulär och tabell
$db = new CDatabase($kabyssen['database']);
$search = new CVinylSearch($db);
$chtml = new CVinylHTML($db);
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
// Ta emot parametrar
$artist   = isset($_GET['artist']) ? $_GET['artist'] : null;
$title    = isset($_GET['title']) ? $_GET['title'] : null;
$genre    = isset($_GET['genre']) ? $_GET['genre'] : null;
$hits     = isset($_GET['hits'])  ? $_GET['hits']  : 20;
$page     = isset($_GET['page'])  ? $_GET['page']  : 1;
$year1    = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2    = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';
$advanced  = isset($_GET['advanced']) ? true : null;

// Kolla inkommande
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');
is_numeric($year1) || !isset($year1)  or die('Check: Year must be numeric or not set.');
is_numeric($year2) || !isset($year2)  or die('Check: Year must be numeric or not set.');

$genres = $chtml->GetActiveGenres($genre);
$tr = $chtml->PrepareQuery($artist, $title, $genre, $hits, $page, $year1, $year2, $orderby, $order);

$maxrows[] = $chtml->GetMaxPages();
$max = $maxrows[0][0];
$rows = $maxrows[0][1];

$hitsPerPage = $search->getHitsPerPage(array(5, 10, 15, 20), $hits);
$navigatePage = $search->getPageNavigation($hits, $page, $max);

$html_form = $advanced ? $chtml->GetHTML_form($artist, $genre, $genres, $hits, $year1, $year2) : null;

$html_table = $chtml->GetHTML_table($rows, $hitsPerPage, $tr, $navigatePage); 

$html_SearchForm = $chtml->GetHTML_SearchForm($title, $advanced);



$kabyssen['header'] = substr_replace($kabyssen['header'], $html_SearchForm, -19);


// dump($kabyssen['header']);
// dump($html_SearchForm);

$kabyssen['main'] = <<<EOD
<article class='me'>
<h2>Upptäck vårt sortiment!</h2>
<p><strong>Hitta albumet du söker genom att använda den enkla eller avancerade sökfunktionen ovan eller sortera tabellen alfabetiskt eller efter årtal.</strong></p>
</article>
{$html_form}
{$html_table}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
