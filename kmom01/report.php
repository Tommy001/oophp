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

$kabyssen['title'] = "Redovisning";

$kabyssen['main'] = <<<EOD
<aside class="right">
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
</aside>
<article class="readable">
    <h1>Redovisning</h1>
    <h2><a name="kmom01">Kmom01: Kom igång med programmering i PHP</a></h2>
    <p>
        Klar med kmom01 - oophp. Jag kommer direkt från htmlphp-kursen och använder de rekommenderade programmen JEdit, Filezilla och wampserver. Guiden "20 steg för att komma igång med php" var också välbekant. 
    <p>
        Jag döpte min webbmall till Kabyssen. Tänker mig att webbmallen är som ett kök med skafferi och skåp där det går att förvara matnyttiga saker. Jag är ju dessutom båtintresserad, så Kabyssen föll sig naturligt.
    <p>    
        Den här me-sidan har ett upplägg som är snarlikt kmom01 - htmlphp, men det gäller inte strukturen! Det tog ett tag att förstå det här indirekta sättet att tänka, där innehåll först läggs i arrayer. Trixade ett tag med den dynamiska menyn och fick till slut ihop ett mellanting mellan koden i guiden “Create a dynamic menu / navigation bar with PHP” och kursmomentets mos-kod. 
    <p>
        Det jag hade mest svårigheter med den här gången var arrayerna och fick ständiga varningar om "string to array conversion", men efter att ha kämpat en hel del, speciellt med menyn, så börjar jag faktiskt se ljuset i tunneln. Jag har tagit till mig att "felmeddelandena alltid har rätt" och börjar inse ett de är en nödvändig del av felsökningen. (Snarare än bara irriterande).
    <p>    
        Det är lite svårt att ha några synpunkter på om det är bättre eller sämre att skriva koden på det här sättet. Webbmallen är ju basen för hela oophp om jag förstår det rätt, så jag får återkomma med synpunkter, när jag har ett bättre grepp om helheten.
    <p>
        Jag integrerade också källkodsvisningen i en sidkontroller (view_source.php) för att det skulle se lite snyggare ut och för att man hela tiden ska ha tillgång till sidans menyval utan att behöva trycka på bakåtpilen. 
    <p>
        Avslutningsvis har jag installerat 'git' och skaffat ett konto på 'github'. Lite förvirrande först, men efter att ha läst om bakgrunden och vad git egentligen är för något på git-scm.com så klarnade det. Jag har skapat ett repo som omfattar hela den kommande oophp-kursen och har pushat detta till github. Det kan ju vara en bra övning att se hur det här med versionshantering fungerar i praktiken.
    </p>
    <hr>
        <h2><a name="kmom02">Kmom02: OO-programmering i PHP</a></h2>
    <p>
        Coming soon... 
    </p>
    <hr>
    
        <h2><a name="kmom03">Kmom03: SQL och databasen MySQL</a></h2>
    <p>
        Coming soon... 
    </p>
    <hr>
            <h2><a name="kmom04">Kmom04: PHP PDO och MySQL</a></h2>
    <p>
        Coming soon... 
    </p>
    <hr>
            <h2><a name="kmom05">Kmom05: Lagra innehåll i databasen</a></h2>
    <p>
        Coming soon... 
    </p>
    <hr>
            <h2><a name="kmom06">Kmom06: Bildbearbetning och galleri</a></h2>
    <p>
        Coming soon... 
    </p>
    <hr>
            <h2><a name="kmom07_10">Kmom07-10: Projekt och examination</a></h2>
    <p>
        Coming soon... 
    </p>

</article>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
