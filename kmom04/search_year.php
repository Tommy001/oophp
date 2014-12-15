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
$dsn      = 'mysql:host=localhost;dbname=Movie;';
$login    = 'root';
$password = '';
$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
//$pdo = new PDO($dsn, $login, $password, $options);
try {
  $pdo = new PDO($dsn, $login, $password, $options);
}
catch(Exception $e) {
  //throw $e; // For debug purpose, shows all connection details
  throw new PDOException('Could not connect to database, hiding connection details.'); // Hide connection details.
}
$year1 = null;
$year2 = null;
$sql = null;

$params = array('');
$html_form = '<article class=\'me\'><form><fieldset>
<legend>Sök</legend>
<p>
    <label>Skapad mellan åren: 
    <input type=\'text\' name=\'year1\' value='.$year1.'>
    - 
    <input type=\'text\' name=\'year2\' value='.$year2.'>
    </label>
</p>
<p>
    <input type=\'submit\' name=\'submit\' value=\'Sök\'/>
</p>
<p>
    <a href=\'?\'>Visa alla</a>
</p>
</fieldset></form></article>';

$year1 = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2 = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;

// Do SELECT from a table
if($year1 && $year2) {
  $sql = "SELECT * FROM op_k4_Movie WHERE year >= ? AND year <= ?;";
  $params = array(
    $year1,
    $year2,
  );  
} 
elseif($year1) {
  $sql = "SELECT * FROM op_k4_Movie WHERE year >= ?;";
  $params = array(
    $year1,
  );  
} 
elseif($year2) {
  $sql = "SELECT * FROM op_k4_Movie WHERE year <= ?;";
  $params = array(
    $year2,
  );  
} else {
    $sql = "SELECT * FROM op_k4_Movie;";
}

$sth = $pdo->prepare($sql);
$sth->execute($params);
$res = $sth->fetchAll();

$html_tabell = "Resultat från SQL-frågan: <code>$sql</code><br>";
$html_tabell .= "print_r() = ".htmlentities(print_r($params, 1));

if ($res) {
$html_tabell .= "<article class='me'><table class='film_table'><tr class='film_coltitle'>
<td>Rad</td>
<td>id</td>
<td>Titel</td>
<td>År</td>
<td>Bild</td>
</tr>";

if($res) {
    $rad = 0;
    foreach ($res AS $val) {
        $rad++; 
        $id = $val["id"];
        $title = $val["title"];
        $year = $val["YEAR"];
        $image = $val["image"];
        $html_tabell .= "<tr>
        <td>$rad</td>
        <td>$id</td>
        <td>$title</td>
        <td>$year</td>
        <td><img src='$image' class='image_h_w' alt='Bild'></td>
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
