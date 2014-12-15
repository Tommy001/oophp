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
$title = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$create = isset($_POST['create'])  ? true : false;

// Select information on the movie 
// Check if form was submitted
if($create) {
  $sql = 'INSERT INTO op_k4_Movie (title) VALUES (?)';
  $db->ExecuteQuery($sql, array($title));
  $db->SaveDebug();
  header('Location: edit_movie.php?id=' . $db->LastInsertId());
  exit;
}
 

$html_form = "<article class='me'><form method=post>
  <fieldset>
  <legend>Skapa ny film</legend>
  <p><input type='text' name='title' value=''/></label></p>
  <p><input type='submit' name='create' value='Skapa'/></p>
  </fieldset>
</form></article>";



$kabyssen['main'] = <<<EOD
{$navigation_film['menu']}
{$html_form}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
