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
$genre = null;
$genres = null;
$sql = null;
$res = null;
$html_links = null;

// Get parameters for sorting
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';
// kolla inkommande
in_array($orderby, array('id', 'title', 'year')) or die('Check: Not valid column.');
in_array($order, array('asc', 'desc')) or die('Check: Not valid sort order.');
 
// Do SELECT from a table
$sql = "SELECT * FROM op_k4_VMovie ORDER BY $orderby $order;";
$sth = $pdo->prepare($sql);
$sth->execute(array($orderby, $order));
$res = $sth->fetchAll();

  
$html_tabell = "<article class='me'>SQL-frågan: <code>$sql</code><br>";
//$html_tabell .= "print_r() = ".htmlentities(print_r($html_links, 1));

if ($res) {
$html_tabell .= "<table class='film_table'><tr class='film_coltitle'>
<td>Rad</td><td>id " . orderby('id') . "</td><td>Titel " . orderby('title') . "</td><td>Genre</td><td>År " . orderby('year') . "</td><td>Bild</td>
</tr>";

// det går inte att loopa ut alla värden, vi ska bara ha ett urval
if($res) {
    $rad = 0;
    foreach ($res as $val) {
        $rad++; 
        $id = $val["id"];
        $title = $val["title"];
        $genre = $val["genre"];        
        $year = $val["YEAR"];
        $image = $val["image"];
        $html_tabell .= "<tr>
        <td>$rad</td><td>$id</td><td>$title</td><td>$genre</td><td>$year</td>
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
{$html_links}
{$html_tabell}
EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
