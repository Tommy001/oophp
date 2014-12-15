<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';

// Do it and store it all in variables in the Kabyssen container.

$kabyssen['title'] = "Min filmdatabas";

$db = new CDatabase($kabyssen['database']);

// Get parameters for sorting
$hits  = isset($_GET['hits']) ? $_GET['hits'] : 8;
$page  = isset($_GET['page']) ? $_GET['page'] : 1;

// Check that incoming is valid
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');

// Get max pages from table, for navigation
$sql = "SELECT COUNT(id) AS rows FROM op_k4_VMovie";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

// Get maximal pages
$max = ceil($res[0]->rows / $hits); // avrunda till närmast högre värde

// Do SELECT from a table
$sql = "SELECT * FROM op_k4_VMovie LIMIT $hits OFFSET " . (($page - 1) * $hits);
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

$hitsPerPage = getHitsPerPage(array(2, 4, 8));
$navigatePage = getPageNavigation($hits, $page, $max);

$html_SQL = "<div class='me'>Resultat från SQL-frågan: <code>$sql</code></div>";

if ($res) {
$html_tabell = "<article class='me'><table class='film_table'><tr class='film_coltitle'>
<td>Rad</td><td>id</td><td>Titel</td><td>Genre</td><td>År</td><td>Bild</td></tr>";
if($res) {
    $rad = 0;
    foreach ($res AS $val) {
        $rad++; 
        $html_tabell .= "<tr>
        <td>$rad</td><td>{$val->id}</td><td>{$val->title}</td><td>{$val->genre}</td><td>{$val->YEAR}</td>
        <td><img src='{$val->image}' class='image_h_w' alt='Bild'></td>
        </tr>";
    }
    $html_tabell .= "</table></article>";
} else {
    $html_tabell .= "Hittade ingen film";
}
}

$kabyssen['main'] = <<<EOD
{$navigation_film['menu']}
{$html_SQL}
<div class='dbtable right' style='width:25%;'>{$hitsPerPage}</div>
{$html_tabell}
<div class='dbtable right' style='width:60%;'>{$navigatePage}</div>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
