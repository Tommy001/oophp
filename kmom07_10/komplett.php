<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';
$kabyssen['stylesheets'][]        = 'css/typography.css';

// Do it and store it all in variables in the Kabyssen container.
$kabyssen['title'] = "Min filmdatabas";
$login = true;
// anslutning databas, objekt för sökfunktioner, formulär och tabell
$db = new CDatabase($kabyssen['database']);
$movie = new CMovieSearch($db);
$chtml = new CHTMLTable($db);
$user = new CVinylUser();
// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);
// Ta emot parametrar
$title    = isset($_GET['title']) ? $_GET['title'] : null;
$genre    = isset($_GET['genre']) ? $_GET['genre'] : null;
$hits     = isset($_GET['hits'])  ? $_GET['hits']  : 8;
$page     = isset($_GET['page'])  ? $_GET['page']  : 1;
$year1    = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2    = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';

// Kolla inkommande
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');
is_numeric($year1) || !isset($year1)  or die('Check: Year must be numeric or not set.');
is_numeric($year2) || !isset($year2)  or die('Check: Year must be numeric or not set.');

$genres = $chtml->GetActiveGenres($genre);
$tr = $chtml->PrepareQuery($title, $genre, $hits, $page, $year1, $year2, $orderby, $order);

$maxrows[] = $chtml->GetMaxPages();
$max = $maxrows[0][0];
$rows = $maxrows[0][1];

$hitsPerPage = $movie->getHitsPerPage(array(2, 4, 8), $hits);
$navigatePage = $movie->getPageNavigation($hits, $page, $max);

$html_form = $chtml->GetHTML_form($title, $genre, $genres, $hits, $year1, $year2);

$html_table = $chtml->GetHTML_table($rows, $hitsPerPage, $tr, $navigatePage); 

$html_SearchForm = $chtml->GetHTML_SearchForm($title);

$kabyssen['header'] = substr_replace($kabyssen['header'], $html_SearchForm, -13);
// dump($kabyssen['header']);
// exit;
$kabyssen['main'] = <<<EOD
{$html_table}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
