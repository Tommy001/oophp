<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// inkludera config-filen som bla innehåller kabyssen-variabeln och sql-frågor
include(__DIR__.'/config.php'); 

// stuva lite stajling i kabyssen
$kabyssen['stylesheets'][]        = 'css/typography.css';
$kabyssen['stylesheets'][]        = 'css/navbar.css';


$login = true;
$message = null;
$user = new CVinylUser();
// Kolla om anv vill logga ut
if(isset($_POST['logout'])) {
    unset($_SESSION['vinyl_user']);
unset($_SESSION['userid']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;  
} 
// Check if user is authenticated.
$acronym = $user->Check_User();
$kabyssen['header'] = $user->GetMemberLink($acronym, $kabyssen['header']);

// indikera inloggningsstatus uppe till höger ovanför headern 
$kabyssen['above_header'] = $user->User_Status($acronym, $kabyssen['above_header'], $login);


// ta hand om fler inkommande
$username = isset($_POST['acronym']) ? $_POST['acronym'] : null;
$fornamn = isset($_POST['fornamn']) ? $_POST['fornamn'] : null;
$efternamn = isset($_POST['efternamn']) ? strip_tags($_POST['efternamn']) : null;
$adress = isset($_POST['adress']) ? $_POST['adress'] : null;
$postnr = isset($_POST['postnr']) ? strip_tags($_POST['postnr']) : null;
$ort = isset($_POST['ort']) ? $_POST['ort'] : null;
$epost = isset($_POST['epost']) ? $_POST['epost'] : null;
$rattighet = isset($_POST['rattighet']) ? $_POST['rattighet'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$save = isset($_POST['save']) ? true : false;



if($save) {
    if(!empty($password && $username && $fornamn && $efternamn)) {
        $pass = isset($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
        $params = array($fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $username, $pass);
        $message = $user->InsertUser($params);
    } else {
        $message = "<h4>Du måste minst fylla i förnamn, efternamn, användarnamn och lösenord. Komplettera med det som saknas.</h4>";
    }
}   
    
 // uppdateringsformuläret

$html_form = "
<h2>Registrera dig som medlem</h2>
<div class='me two_col'>    
<div class='bg'><form method=post><fieldset>
<p>Förnamn*</p>
<input class='textfield' type=text name='fornamn' value=''><br>
<p>Efternamn*</p>
<input class='textfield' type=text name='efternamn' value=''><br>
<p>Adress</p>
<input class='textfield' type=text name='adress' value=''><br>
<p>Postnummer</p>
<input class='textfield' type=text name='postnr' value=''><br>  
<p>Ort</p>
<input class='textfield' type=text name='ort' value=''><br>
<p>E-post</p>
<input class='textfield' type=text name='epost' value=''><br>    
</div>
<div class='bg'>    
<input type='hidden' name='rattighet' value='medlem'>
<p>Användarnamn*</p>
<input class='textfield' type=text name='acronym' value=''><br>
<p>Lösenord*</p>
<input class='textfield' type=password name='password' value=''><br><br>
<input type=submit name=save value='Spara'><br>
<p>Obligatoriska fält är märkta med *</p>
{$message}
</fieldset></form></div></div>";
    

// stuva grejorna i kabyssen
$kabyssen['title'] = "Registrera dig";
$kabyssen['main'] = <<<EOD
{$html_form}
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
