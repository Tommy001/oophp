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

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($kabyssen['database']);

$title = '';
$html_form = "<article class='me'><form><fieldset>
<legend>Sök</legend>
<p><label>Titel (använd % som ett eller flera valfria tecken): <input type='search' name='title' value=''></label></p>
<p><a href='?'>Visa alla</a></p>
</fieldset></form></article>";

$title = isset($_GET['title']) ? $_GET['title'] : null;

// Do SELECT from a table
if($title) {
    $sql = "SELECT * FROM op_k4_VMovie WHERE title LIKE ?;";
    } else {
    $sql = "SELECT * FROM op_k4_VMovie;";
}
$params = array($title,); 
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

$html_tabell = "Resultat från SQL-frågan: <code>$sql</code><br>";


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
{$html_form}
{$html_tabell}
EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
