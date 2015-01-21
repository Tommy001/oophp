<?php
/**
 * Config-file for Kabyssen. Change settings here to affect installation.
 *
 */ 
 
/**
 * Create the Kabyssen variable.
 *
 */
$kabyssen = array();

 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 
 
/**
 * Define Kabyssen paths.
 *
 */
define('KABYSSEN_INSTALL_PATH', __DIR__ . '/..');
define('KABYSSEN_THEME_PATH', KABYSSEN_INSTALL_PATH . '/kabyssen/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(KABYSSEN_INSTALL_PATH . '/kabyssen/src/bootstrap.php');

/**
 * Include navigation functions.
 *
 */
include(KABYSSEN_INSTALL_PATH . '/kabyssen/src/navigation.php');

/**
 * Include common functions.
 *
 */
include(KABYSSEN_INSTALL_PATH . '/kabyssen/src/functions.php');
 
 
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();


/**
 * Site wide settings.
 *
 */
$kabyssen['lang']         = 'sv';
$kabyssen['title_append'] = ' | Vinyl Records';

/**
 * Define the menu as an array
 */

$admin = isset($_SESSION['vinyl_user']) ? array(
      'text'  =>'Administration',   
      'url'   =>'',  
      'title' => 'Administration', 
            // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(
 
        'items' => array(
          // This is a menu item of the submenu
          'item 1'  => array(
            'text'  => 'Webbplats',   
            'url'   => 'admin_webb.php',  
            'title' => 'Administrera nyhetsblogg och webbsidor'
          ),
 
          // This is a menu item of the submenu
          'item 2'  => array(
            'text'  => 'Skivor',   
            'url'   => 'admin_musik.php',  
            'title' => 'Administrera skivdatabasen'
          ),
          // This is a menu item of the submenu
          'item 3'  => array(
            'text'  => 'Användare',   
            'url'   => 'admin_user.php',  
            'title' => 'Administrera användare'
          ),          
        ),
      ),
    )  : null;
 
$menu = array(
  // Use for styling the menu
  'class' => 'navbar',
 
  // Here comes the menu strcture
  'items' => array(
    // This is a menu item
    'start'  => array(
      'text'  =>'Start',   
      'url'   =>'start.php',  
      'title' => 'Startsidan'
    ),
    // This is a menu item
    'musik'  => array(
      'text'  =>'Musik',   
      'url'   =>'musik.php',  
      'title' => 'Vinylsortiment'
    ),  
    
    // This is a menu item
    'blog'  => array(
      'text'  =>'Blogg',   
      'url'   =>'blog.php',  
      'title' => 'Läs vår blogg'
    ),  
 
    // This is a menu item
    'admin'  => $admin,
    
        // This is a menu item
    'om'  => array(
      'text'  =>'Om oss',   
      'url'   =>'om.php',  
      'title' => 'Om Vinyl Records', 
      ),
    ),
 
  // This is the callback tracing the current selected menu item base on scriptname
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);




$navigation = get_navbar($menu);
// $navigation = array('menu' => call_user_func('GenerateMenu', $menu, $class));

$kmom_meny = '<aside class="right">
    <nav class="vmenu">
        <h4>Kursmomentmeny</h4>
        <ul>
  	       <li><a href="#kmom01">kmom01</a>
  	       <li><a href="#kmom02">kmom02</a>
  	       <li><a href="#kmom03">kmom03</a>
  	       <li><a href="#kmom04">kmom04</a>
  	       <li><a href="#kmom05">kmom05</a>   
  	       <li><a href="#kmom06">kmom06</a> 
  	       <li><a href="#kmom07_10">kmom07-10</a>                  
  	   </ul>  
  </nav>
</aside>';
/**
 * Settings for the database.
 *
 */
define('DB_PASSWORD', 'b8nRR5(s'); // 
$kabyssen['database']['dsn'] = 'mysql:host=blu-ray.student.bth.se;dbname=toja14;'; //blu-ray.student.bth.se
// host=blu-ray.student.bth.se
$kabyssen['database']['username']       = 'toja14'; 
$kabyssen['database']['password']       = DB_PASSWORD; 
$kabyssen['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");


$kabyssen['above_header'] = <<<EOD
<nav class="related">
<br>
</nav>
EOD;
$kabyssen['header'] = <<<EOD
<a href="start.php"><img src="img.php?src=vinyl_logga.png&amp;width=110" alt="Vinyl Records Logo"></a>
<a style="text-decoration:none" href="start.php"><span class='sitetitle'>Vinyl Records</span></a>
<a style="text-decoration:none" href="start.php"><span class='siteslogan'>Din vinylbutik på nätet</span></a>
<br>
<div class='menyrad'>{$navigation}</div>
EOD;
$current_url = getCurrentUrl();
$kabyssen['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Vinyl Records | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a> | 
<a href='http://validator.w3.org/i18n-checker/check?uri=$current_url'>i18n</a> | 
  <a href='http://validator.w3.org/checklink?uri=$current_url'>Links</a> | <a href='view_source.php'>Källkod</a></span></footer>
EOD;

$kabyssen['byline'] = <<<EOD
<footer class="byline">
	<figure class="right top bylinebild"><img src="img/Tommy_nyklippt.jpg" alt="Bylinebild på Tommy" height="50"></figure>
		<p>Den här webbplatsen är skapad som examensprojekt i kursen “Databaser och objektorienterad programmering i PHP”. Jag som har gjort den heter Tommy Johansson och jag jobbar till vardags som konsult med teknisk översättning som specialitet. Framåt är dock siktet inställt på en kombination av webbutveckling och språktjänster.</p>
</footer>
EOD;

/**
 * Google analytics.
 *
 */
$kabyssen['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytic

/**
 * Settings for JavaScript.
 *
 */
$kabyssen['modernizr'] = 'js/modernizr.js';
$kabyssen['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
//$kabyssen['jquery'] = null; // To disable jQuery
$kabyssen['javascript_include'] = array();
//$kabyssen['javascript_include'] = array('js/main.js'); // To add extra javascript files
 
/**
 * Google analytics.
 *
 */
$kabyssen['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics

/**
 * Theme related settings.
 *
 */
$kabyssen['stylesheets'] = array('css/style.css');
$kabyssen['favicon']    = 'favicon.png';
