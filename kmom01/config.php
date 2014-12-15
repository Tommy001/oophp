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
$kabyssen['title_append'] = ' | Kabyssen en webbtemplate';

$class = 'navmenu';
$menu = array(
  'callback' => 'modifyNavbar',
  'items' => array(
    'me'  => array('text'=>'Me',  'url'=>'me.php', 'class'=>null),
    'report'  => array('text'=>'Redovisning',  'url'=>'report.php', 'class'=>null),
    'source' => array('text'=>'Källkod', 'url'=>'view_source.php', 'class'=>null),
  ),
);

$navigation = array('menu' => call_user_func('GenerateMenu', $menu, $class));


$kabyssen['above_header'] = <<<EOD
<nav class="related">
<a href="../kmom01/me.php">kmom01</a>
</nav>
EOD;
$kabyssen['header'] = <<<EOD
<a href="me.php"><img src="img/logo.gif" alt="htmlphp logo" width="72" height="70"></a>
<a style="text-decoration:none" href="me.php"><span class='sitetitle'>OOPHP</span></a>
<a style="text-decoration:none" href="me.php"><span class='siteslogan'>Min Me-sida i kursen Databaser och Objektorienterad PHP-programmering</span></a>
<br>
{$navigation['menu']}
EOD;

$kabyssen['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Tommy Johansson | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;

$kabyssen['byline'] = <<<EOD
<footer class="byline">
	<figure class="right top bylinebild"><img src="img/Tommy_nyklippt.jpg" alt="Bylinebild på Tommy" height="50"></figure>
		<p>Tommy är språkkonsult med fordonsindustrin som största uppdragsgivare. Sedan 1991 har han jobbat i egen regi och har genom åren tagit emot uppdrag från många olika kunder. De sista åren har dock konsultandet blivit mer specialiserat och framåt är siktet inställt på en kombination av webbutveckling och språktjänster.</p>
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
$kabyssen['favicon']    = 'favicon.ico';



