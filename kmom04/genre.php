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
$genre = null;
$genres = null;
$sql = null;
$res = null;
$html_links = null;

$genre = isset($_GET['genre']) ? $_GET['genre'] : null;

$sql = 'SELECT DISTINCT G.name
    FROM op_k4_Genre AS G
    INNER JOIN op_k4_Movie2Genre AS M2G
    ON G.id = M2G.idGenre;';

$sth = $pdo->prepare($sql);
$sth->execute();
$res = $sth->fetchAll();

$html_links = '<article class=\'me\'><fieldset>
<legend>Sök</legend>
<p>Sök efter genre: ';
foreach($res as $genres) {
    $html_links .= '<a href=?genre='.$genres['name'].'>'.$genres['name'].'</a> | ';
}
$html_links .= '</p><p><a href=\'?\'>Visa alla</a></p>';
$html_links .= '</fieldset></article>';

//$sql = null;
//$res = null;
if($genre) {
    $sql = 'SELECT 
    M.*,
    G.name AS genre
    FROM op_k4_Movie AS M
    LEFT OUTER JOIN op_k4_Movie2Genre AS M2G
    ON M.id = M2G.idMovie
    LEFT OUTER JOIN op_k4_Genre AS G
    ON M2G.idGenre = G.id
    WHERE G.name = ?';
    $params = array($genre); 
} else {
  $sql = "SELECT * FROM op_k4_VMovie;";
  $params = null;
}

$sth = $pdo->prepare($sql);
$sth->execute($params);
$res = $sth->fetchAll();
    
$html_tabell = "<article class='me'>SQL-frågan: <code>$sql</code><br>";
//$html_tabell .= "print_r() = ".htmlentities(print_r($html_links, 1));

if ($res) {
$html_tabell .= "<table class='film_table'><tr class='film_coltitle'>
<td>Rad</td><td>id</td><td>Titel</td><td>Genre</td><td>År</td><td>Bild</td>
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
