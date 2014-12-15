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
$sql=null;
$res=null;
$html_tabell=null;
$delete = null;

if(isset($_GET['p'])) {
    $delete = $_GET['p'];
} 
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} 
dump($delete);

// Check if form was submitted
$output = null;
if($delete) {
 
  $sql = 'DELETE FROM op_k4_Movie2Genre WHERE idMovie = ?';
  $db->ExecuteQuery($sql, array($id));
  $db->SaveDebug("Det raderades " . $db->RowCount() . " rader från databasen.");
 
  $sql = 'DELETE FROM op_k4_Movie WHERE id = ? LIMIT 1';
  $db->ExecuteQuery($sql, array($id));
  $db->SaveDebug("Det raderades " . $db->RowCount() . " rader från databasen.");
 
  header('Location: delete_movie.php');
}

$html_SQL = "<div class='me'>Resultat från SQL-frågan: <code>$sql</code></div>";
// Do SELECT from a table
$sql = "SELECT * FROM op_k4_Movie";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);
if ($res) {
$html_tabell = "<article class='me'><table class='film_table'><tr class='film_coltitle'>
<td>Rad</td><td>id</td><td>Titel</td><td>År</td><td>Bild</td><td>Ta bort</td></tr>";
if($res) {
    $rad = 0;
    foreach ($res AS $val) {
        $rad++; 
        $html_tabell .= "<tr>
        <td>$rad</td><td>{$val->id}</td><td>{$val->title}</td><td>{$val->YEAR}</td>
        <td><img src='{$val->image}' class='image_h_w' alt='Bild'></td>
        <td><a href='?p=true&amp;id={$val->id}'><img src='img/movie/delete.gif' alt='Ta bort'></a></td>
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
{$html_tabell}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
