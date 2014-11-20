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
        Klar med kmom02. Jag är helt ny på objektorienterad programmering och genomgången av guiden "Kom igång med..." var det svettigaste hittills i kurspaketet. Jag läste boken samtidigt och gjorde alla uppgifter i guiden grundligt. Det fick ta sin tid helt enkelt. Därför gjorde jag inte några extrauppgifter den här gången. Känner att jag behöver smälta det här lite och ta det stegvis. 
    <p>
        Hur jobbig guiden än var, så gick det rätt snabbt att få till spelet trots allt. Det jag fick kämpa med var att få fram rätt poäng från metoderna. Efter flera fruktlösa timmar, skrev jag till slut ut all kod och satte mig vid köksbordet med märkpenna och gick genom allt steg för steg. Det hjälpte faktiskt. Det blir lätt kodsoppa om man bara kör på lite för entusiastiskt, har jag märkt. Struktur är bra.
    <p>
        Lärdomen är att man nog bör använda penna och papper innan man börjar koda. Definiera vilka olika delar det finns och hur de ska samspela. Vilka metoder som ska finnas i respektive klass och hur de ska samverka.
        Diagramexempelet som fanns i guiden var ett bra tips för hur man kan göra.
    <p>
        Hur som helst, i tärningsspelet använder jag tre klasser, CDice,  CDiceGameBoard och CDiceGameRound. Den första skapar ett slumptal dvs. den kastar tärningen, den andra skapar spelplanen och den sista innehåller logiken kring själva spelrundan. Jag tycker att det är en logisk uppdelning. 
    <p>
        För själva spelplanens utseende använde jag CSS och skapade ett i mitt tycke tydligt och enkelt bildspråk med flytande divvar och bildsymboler i stället för en massa text. Självklart finns det ändå en enkel textinstruktion.
    <p>
        Jag ska tillägga att jag fortsätter att anväda git och har nu pushat repot (hela oophp-mappen) till github.    
    <p>
        Efter den här tuffa inledningen på oophp-kursen med först webbmallen och sedan oop-guide plus tärningsspel, känner jag mig lätt vimmelkantig. Så nu ska det faktiskt bli lite skönt att vila hjärnan någon dag. Sedan är dags för nästa kursmoment.
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
