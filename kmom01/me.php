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

$kabyssen['title'] = "Om mig";

$kabyssen['main'] = <<<EOD
<article class="me">
<h1>Om mig</h1>
  <figure class="right">
  <img src="img/me.jpg" alt="Tommy" width="332" height="221">
  </figure>
  <p>Jag heter Tommy Johansson, har hunnit bli 55 år gammal och har jobbat som teknisk översättare i över 20 år. Det är inte klokt vad tiden går. Jag översätter främst från franska (min lilla logga i sidhuvudet ska föreställa en fransman med basker och en penna i stället för baguette). Jag började min bana som TV-tekniker på 80-talet, tröttnade och flyttade till Frankrike i ett år, pluggade sedan språk på Göteborgs Universitet och sadlade om till översättare.
  <p>När det gäller hemsidor har jag fuskat med HTML, CSS, PHP och MySQL på egen hand i något år och har nu avslutat "htmlphp" som är den första delen i ett kurspaket. Jag inser att det är bättre att lära sig hantverket från grunden och i rätt ordning och det här kurspaketet passar ju som hand i handske.
  <p>Jag bor i Torslanda utanför Göteborg med fru, två barn i tonåren och två hundar. Jobbet sköter jag från ett litet kontor på tomten, så det är nära till arbetet! 
  <p>På fritiden har vi börjat lära oss att segla och njuter av skärgården så mycket vi kan varje år. För något år sedan dammade jag också av elgitarren som stått i en garderob i säkert 30 år och försöker mig på lite blues också, när ingen lyssnar. Det går inget vidare än så länge, men skam den som ger sig :-).
  </p>

{$kabyssen['byline']}

</article>

EOD;


// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
