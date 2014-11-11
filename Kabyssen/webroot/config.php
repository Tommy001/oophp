<?php
/**
 * Create the Kabyssen variable.
 *
 */
$kabyssen = array();

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

/**
 * Config-file for Kabyssen. Change settings here to affect installation.
 *
 */
 
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
define('KABYSSEN_THEME_PATH', KABYSSEN_INSTALL_PATH . '/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(KABYSSEN_INSTALL_PATH . '/src/bootstrap.php');
 
 
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
