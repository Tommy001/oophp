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
$dsn      = 'mysql:host=localhost;dbname=op_k4_Movie;';
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

// Do SELECT from a table
// $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$sql = "SELECT * FROM op_k4_Movie;";
$sth = $pdo->prepare($sql);
$sth->execute();
$res = $sth->fetchAll();

$html_tabell = "Resultat från SQL-frågan: <code>$sql</code>";

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
{$html_tabell}
EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
