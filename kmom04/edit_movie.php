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

// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$year   = isset($_POST['year'])  ? strip_tags($_POST['year'])  : null;
$image  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$genre  = isset($_POST['genre']) ? $_POST['genre'] : array();
$save   = isset($_POST['save'])  ? true : false;

// Select information on the movie 
$sql = 'SELECT * FROM op_k4_Movie WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
 
if(isset($res[0])) {
  $movie = $res[0];
}
else {
  die('Failed: There is no movie with that id');
}


$html_form = "<article class='me'><form method=post>
  <fieldset>
  <legend>Uppdatera info om film</legend>
  <input type='hidden' name='id' value='{$id}'/>
  <p><label>Titel:<br/><input type='text' name='title' value='{$movie->title}'/></label></p>
  <p><label>År:<br/><input type='text' name='year' value='{$movie->YEAR}'/></label></p>
  <p><label>Bild:<br/><input type='text' name='image' value='{$movie->image}'/></label></p>
  <p><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>
  </fieldset>
</form></article>";

 
// Check that incoming parameters are valid
is_numeric($id) or die('Check: Id must be numeric.');
is_array($genre) or die('Check: Genre must be array.');

// Check if form was submitted
$output = null;
if($save) {
  $sql = '
    UPDATE op_k4_Movie SET
      title = ?,
      year = ?
    WHERE 
      id = ?
  ';
  $params = array($title, $year, $id);
  dump($params);
  $db->ExecuteQuery($sql, $params);
  $output = 'Informationen sparades.';
  header('Location: update.php');
}

$kabyssen['main'] = <<<EOD
{$navigation_film['menu']}
{$html_form}
{$output}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
