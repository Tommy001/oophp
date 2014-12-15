<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
$html = null;
$last = 0;
$round = 0;
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 
if(isset($_GET['init'])) {
    destroySession();
}

// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';
$kabyssen['stylesheets'][]        = 'css/dice.css';

if(isset($_SESSION['play100'])) {
    $play100 = $_SESSION['play100'];
} else {
    $play100 = new CDiceGameRound();
    $_SESSION['play100'] = $play100;
}
$html = $play100->GetGameBoard(); // visa spelplanen
$roll = isset($_GET['roll']) ? true : false;
$save = isset($_GET['save']) ? true : false;
if ($roll && !$play100->Reach100()) { // tillåt kast om poängen < 100
    $html = $play100->IfRollDice();
}

if ($save) { // efter klick på Spara: 
    $html = $play100->SaveRound($round); // spara och få tillbaka spelplanen
}

// Do it and store it all in variables in the Kabyssen container.
$kabyssen['main'] = <<<EOD
<article>
<h1>Tärningsspelet 100</h1>
    <p>
        Kasta tärningen några gånger och spara poängen. Om kastet skulle råka bli en etta nollställs alla poäng som inte sparats. Det gäller att komma till 100 på så få kast som möjligt.
    <p>
        Klicka på tärningen för att kasta, på disketten för att spara och på förbudsskylten för att börja om från början.    
    </p>    
</article>
{$html}
EOD;
// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
