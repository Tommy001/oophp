<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$kabyssen['stylesheets'][]        = 'css/style.css';
//$kabyssen['stylesheets'][]        = 'css/navbar.css';

// Do it and store it all in variables in the Kabyssen container.

$kabyssen['title'] = "Redovisning";

$kabyssen['main'] = <<<EOD
{$kmom_meny}
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
        Jag ska tillägga att jag fortsätter att använda git och har nu pushat repot (hela oophp-mappen) till github. Så nu har jag en up-to-date branch, med meddelandet "Nothing to commit, working directory clean".
    <p>
        Efter den här tuffa inledningen på oophp-kursen med först webbmallen och sedan oop-guide plus tärningsspel, känner jag mig lätt vimmelkantig. Så nu ska det faktiskt bli lite skönt att vila hjärnan någon dag. Sedan är dags för nästa kursmoment.
    </p>
    <hr>
    
        <h2><a name="kmom03">Kmom03: SQL och databasen MySQL</a></h2>
    <p>
        <b>Klar med kmom03.</b> Inledningsvis var det här ett rätt jobbigt moment. Jag syftar då på kurslitteraturen. Något träigare än en bok om databasteknik får man faktiskt leta efter... Databasavsnitten i "Beginning PHP and MySQL: From Novice to Professional" var väl helt OK att läsa, men "Databasteknik" av Thomas Padron-McCarthy och Tore Risch fungerar nog bättre som uppslagsverk, än som spänningsroman. Puh.
     <p>
        Nåväl, tacka vet jag övningsuppgifterna i kursen. Här flöt arbetet på alldeles galant faktiskt. Det uppstod inte en enda bekymrad rynka i pannan förrän i slutet av guiden "Kom igång med SQL". Där tog det en stund att greppa det här med att foga samman tabeller med INNER JOIN och RIGHT OUTER JOIN etc. Men jag tror att jag har förstått principen. Det verkar ju vara tillåtet att använda WHERE för att joina också. Mycket lärorikt med vyerna också, det har jag inte använt förut.
    <p>
        Jag har ingen stor vana från databaser, men jag har byggt ett par egna sajter tidigare och då har det varit med MySQL. Min erfarenhet av klienter begränsar sig till "phpMyAdmin", så en stor nyhet för mig var MySQL Workbench. Den visade sig vara väldigt användbar. Jag roade mig med att bygga ett ER-diagram av exemplet med filmsajten i "Kokbok för databasmodellering" (punkt 5) och använde sedan "Forward Engineer" för att se hur de genererade SQL-satserna såg ut. Det gav dessutom lite mer övning i det här med främmande nycklar (körde för säkerhets skull ett par tutorials på Youtube i ämnet också).
    <p>
        En annan nyttig sak med workbenchen var den automatiska kontexthjälpen. Markera t.ex. CREATE så visas motsvarande hjälpavsnitt automatiskt (nåja, ibland får man klicka på en knapp) till höger på skärmen. Dessutom går det att spara egna kodsnuttar i s.k. "snippets" för återanvändning. Jättebra.
    <p>
        Lokalt kör jag WAMP-server och kan alltså använda phpMyAdmin i min egen burk. Jag tror dock att den passar bättre för kontroller och administration, medan MySQL Workbench är perfekt under utvecklingsfasen. Rena terminalfönster med SSH-tunnel via t.ex. Putty känns inte direkt användbart i jämförelse. 
    <p>    
        I workbenchen har jag nu en fungerande anslutning till BTH-driftsmiljön. Hade lite problem först med det, men fick hjälp via chatten.
    <p>
        I mappen kmom03 har jag lagt en textfil med "snippets" från övningarna. Den heter "SQL-snippets.txt". Lade även ett par skärmdumpar i kmom03/img från MySQL Workbench.
    </p>
    <hr>
            <h2><a name="kmom04">Kmom04: PHP PDO och MySQL</a></h2>
    <p>
        <strong>Klar med kmom04.</strong> Upplägget med att först gå igenom en en eller flera guider och sedan göra uppgifter känns bra. Jag tog ganska lång tid på mig att köra igenom guiden “Kom igång med PHP PDO och MySQL” och läste de angivna kapitlen i kurslitteraturen mer eller mindre samtidigt. PDO var ju dessutom bekant sedan htmlphp-kursen.
    <p>
        PHP PDO är en smart PHP-klass som kan användas mot flera olika databaser, så det känns helt klart vettigt att lära sig använda den. Den ger ju dessutom ett visst skydd mot SQL-injektioner. Jag har inte haft några problem alls med just den här biten.
    <p>
        Jag gjorde hela guiden med filmdatabasen. När det gäller MySQL har jag inte haft några större problem än så länge. Fick bland annat hjälp att bättre förstå INNER/OUTER JOIN via chatten. Har kommit igång bra med MySQL Workbench och på det hela taget känns det ganska bekvämt att jobba med databaser. Med tanke på kommande övningar, så lade jag till prefixet op_k4_ framför alla tabellnamn, eftersom man bara kan ha en enda databas per student. Det kan köra ihop sig rätt bra annars misstänker jag, om man råkar använda samma namn på andra tabeller längre fram i kursen. 
    <p>
        Eftersom jag är helt nykläckt på det här med OOP, så försökte jag inte ta ut svängarna alltför mycket. Klassen CDatabase fick man till skänks och de övriga klasserna fick man bra tips om också. Jag följde i stort sett råden och skapade övriga klasser enligt uppgiften. Jag avvek lite från coachens tips genom att låta CDatabase vara en del av både CHTMLTable och CMovieSerach, för att ha tillgång till databasen överallt. Gjorde sedan likadant med CUser, för att kunna lägga ut maximalt med kod i dessa klasser.
    <p>
        Jag har vant mig något mer vid det här med Anax, eller Kabyssen som jag kallar den. Har utökat mallen med en 'above_header' för att vara fri att laborera med saker ovanför headern. I det här momentet har jag lagt till en statusindikering för inloggningen där, i stället för att göra en separat sidkontroller för det. Se där, en liten avvikelse till. Men jag tyckte att det var snyggare att hela tiden se om man är inloggad eller inte, i stället för att det ska visas på en viss sida. Kika uppe till höger när in- eller utloggningssidan visas, så syns statusen. 
    <p>
        <strong>Extra.</strong> Jag har läst båda navbar-guiderna och även om det nu skulle ha varit fullt möjligt att lägga in drop-down-menyer, så nöjde jag mig med att bara uppgradera me-sidans navbar, som nu är i version 2.0. och enkel att komplettera och ändra.
    </p>
    <hr>
            <h2><a name="kmom05">Kmom05: Lagra innehåll i databasen</a></h2>
    <p>
        <strong>Klar med kmom05.</strong> Vid första anblicken kändes momentet lite abstrakt, vilket förstås beror på att jag inte har några tidigare erfarenheter av objektorienterad programmering alls. Men efter att ha läst <a href='http://dbwebb.se/forum/viewtopic.php?t=1680'>förtydligandet</a> och kommit igång med de första metoderna i CContent löpte det på rätt så bra.
    <p>
        Jag jobbade igenom guiden och skrev till och med ut den och läste som kvällslektyr. Har börjat inse att det kan vara bra att läsa guiderna flera gånger, från början till slut, i olika faser av arbetet. Ibland kan det stå saker i slutet som jag hade behövt veta i början, men det beror säkert på att mina förkunskaper är ojämna. Vissa "svåra" saker kan trots allt vara enkla att förstå och andra saker som kanske borde vara "lätta" har jag ingen susning om etc.
   <p>     
        Jag hade en del bryderier med var SQL-frågor skulle deklareras någonstans och placerade dem först som medlemsvariabler i CContent. Sedan flyttade jag dem till config-filen för att det skulle vara smidigare att modifiera dem där... men till slut kändes det trots allt bäst att stuva in dem i klassens metoder. 
    <p>
        Ett annat litet problem jag hade var att doFilter-funktionen gav upphov till ett felmeddelande, när inget filter anges (alltså om man lämnar det fältet tomt i filen edit.php eller create.php). Jag ändrade doFilter() så att den bara returnerar samma text som den tar emot, om variabeln som innehåller filternamnet råkar vara tom. Det måste ju vara tillåtet att inte ange något filter, tycker jag.    
   <p>
        Jag gjorde uppgifterna enligt instruktionerna och skapade alltså CContent, CTextFilter, CPage och CBlog. Det börjar bli väldigt många moduler i webbmodulen Kabyssen som min variant heter. Jag förstår att det är ett effektivt sätt att jobba på och det känns bra att lära sig grunderna inom objektorienterad programmering på det här sättet. Mitt mål är ju att lära mig så mycket som möjligt.

    <p>
        Det klarnar mer och mer hur man kan strukturera koden i olika klasser. Den här gången fungerade CDatabase mer som en övergripande abstrakt klass, eftersom det inte finns någon instans av den i sidkontrollerna (fast det finns ju en instans i CContent, så riktigt stämmer det ju inte). Formulär och ren html känns som en naturlig del av sidkontrollern och därför lät jag sådant till stor del vara kvar där. Jag tolkar coachens tips om att lösa uppgfiten på eget vis, som att det trots allt var tillåtet att göra så.
    <p>
        Kabyssen börjar som sagt bli fullpackad, men något som hittills har lyst med sin frånvaro är bildhantering, bilduppladdning etc. Men jag ser att det kanske kommer i nästa kursmoment. Det ser jag fram emot....
    <p>
        <strong>Extra.</strong> Jag har implementerat Slugify-funktionen och jag har återanvänt inloggningen från det förra kursmomentet. Jag kontrollerar om användaren är inloggad med hjälp av CUser etc. Man måste logga in för att kunna ändra, lägga till, ta bort och återställa databasen. Sedan har jag lagt till filtret SmartyPants Typographer (skriv "typo" i filterfältet). Lade dessutom till lite kod så att det inte går att bara skriva exempelvis 'edit.php?id=5' i adressraden, utan att vara inloggad. Och, just det... hela kursrepot är pushat till github.
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
