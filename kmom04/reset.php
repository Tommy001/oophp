<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

$output = '';
// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';

// Do it and store it all in variables in the Kabyssen container.

$kabyssen['title'] = "Min filmdatabas";

    // Restore the database to its original settings
    $sql      = 'movie.sql';
    $mysql    = '/usr/local/bin/mysql';
    $host     = 'localhost';
    $login    = 'root';
    $password = '';

    // Use these settings on windows and WAMPServer,
    // but you must check - and change - your path to the executable mysql.exe
    $mysql    = 'C:\Users\Tommy\Documents\Webbutveckling\wamp\bin\mysql\mysql5.6.12\bin';
    $login    = 'root';
    $password = '';


    if(isset($_POST['restore']) || isset($_GET['restore'])) {

      // Use on Unix/Unix/Mac
      // $cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";

      // Use on Windows, remove password if its empty
      $cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";
      $cmd = "$mysql -h{$host} -u{$login} < $sql";

      $res = exec($cmd);
      $output = "<p>Databasen är återställd via kommandot<br/><code>{$cmd}</code></p><p>{$res}</p>";
    }

    // Do it and store it all in variables in the Kabyssen container.
$kabyssen['title'] = "Återställ databasen till ursprungligt skick";
$kabyssen['main'] = <<<EOD
<form method=post>
<input type=submit name=restore value='Återställ databasen'/>
<output>{$output}</output>
</form>
EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
