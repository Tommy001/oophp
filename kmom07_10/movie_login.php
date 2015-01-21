<?php 
/**
 * This is a login controller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 

$user = new CVinylUser();

if(isset($_POST['acronym']) && ($_POST['password'])) {
$params = array($_POST['acronym'], $_POST['password']);
}

// Check if user and password is okey
if(isset($params)) {
    $user->Check_Login($params);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;

