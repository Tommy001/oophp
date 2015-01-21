<?php 
/**
 * This is a Kabyssen pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kabyssen variable with its defaults.
include(__DIR__.'/config_me.php'); 


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
        Eftersom jag är helt nykläckt på det här med OOP, så försökte jag inte ta ut svängarna alltför mycket. Klassen CDatabase fick man till skänks och de övriga klasserna fick man bra tips om också. Jag följde i stort sett råden och skapade övriga klasser enligt uppgiften. Jag avvek lite från coachens tips genom att låta CDatabase vara en del av både CHTMLTable och CMovieSerach, för att ha tillgång till databasen överallt. Gjorde sedan likadant med CVinylUser, för att kunna lägga ut maximalt med kod i dessa klasser.
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
        Det klarnar mer och mer hur man kan strukturera koden i olika klasser. Den här gången fungerade CDatabase mer som en övergripande abstrakt klass, eftersom det inte finns någon instans av den i sidkontrollerna (fast det finns ju en instans i CContent, så riktigt stämmer det ju inte). Formulär och ren html känns som en naturlig del av sidkontrollerna och därför lät jag sådant till stor del vara kvar där. Jag tolkar coachens tips om att lösa uppgfiten på eget vis, som att det trots allt var tillåtet att göra så.
    <p>
        Kabyssen börjar som sagt bli fullpackad, men något som hittills har lyst med sin frånvaro är bildhantering, bilduppladdning etc. Men jag ser att det kanske kommer i nästa kursmoment. Det ser jag fram emot....    
    <p>
        <strong>Extra.</strong> Jag har implementerat Slugify-funktionen och jag har återanvänt inloggningen från det förra kursmomentet. Jag kontrollerar om användaren är inloggad med hjälp av CVinylUser etc. Man måste logga in för att göra tillägg, ändra, ta bort och lägga till innehåll och förstås för att återställa databasen. Sedan har jag lagt till filtret SmartyPants Typographer (skriv "typo" i filterfältet). Lade dessutom till lite kod så att det inte går att göra ändringar genom att skriva exempelvis 'edit.php?id=5' i adressraden, utan att vara inloggad. Och, just det... hela kursrepot är "pushat" till github. Nothing to commit, working directory clean.
    </p>
    <hr>
            <h2><a name="kmom06">Kmom06: Bildbearbetning och galleri</a></h2>
    <p>
        <strong>Klar med kmom06.</strong> Bilder är kul! Jag har fuskat lite med Photoshop tidigare i mitt arbete som facköversättare (nyhetsbrev, kundkataloger etc.), främst för att frilägga bilder eller för att beskära dem. Jag har också gjort ett litet PHP-skript för uppladdning och hantering av bilder på ett par av mina webb-projekt, så jag var bekant sedan tidigare med de flesta PHP GD-funktionerna i detta moment. Så just den biten kändes hemtam. 
    <p>
        Jag jobbade igenom guiderna och som vanligt skrev jag ut dem och gick igenom koden med penna och papper för att förstå vad som händer i varje steg. Lite springande till datorn blev det förstås för att se exemplen.
    <p>
        Att jobba med img.php var en aha-upplevelse. Jag har alltid utgått från att man måste bearbeta alla bilder i exempelvis Photoshop före användning på webbplatsen. Att utgå från en och samma bild och bearbeta den "på plats" i webbsidan var något helt nytt för mig. Det kommer att underlätta betydligt framöver. Tack för det!
    <p>
        Jag krånglade till det rätt ordentligt i mitt första försök att skapa CImage. Det slutade med att jag slängde ut alltihop och började från början. Men på köpet lärde jag mig lite mer om felsökning och hur man inte ska göra. Felet jag gjorde var att bara flytta över lite kod i taget till CImage (tanken var väl att det skulle ge bättre kontroll), men det blev för svårt att jonglera med alla variabler som behövdes i både img.php och CImage. Till sist bestämde jag mig för att vara radikal och flytta över nästan allt som kunde flytttas på en gång. Därefter gick det faktiskt ganska lätt. Det vill säga, när alla felmeddelanden väl var åtgärdade, så fungerade det som det skulle. Tacka vet jag felmeddelanden!
    <p>
        I CGallery fick jag ett märkligt problem med sökvägarna. Galleriet visade alla mappar som fanns i hela datorn (nåja...) men inga bilder. Jag hittade en tråd på samma tema i forumet, som bland annat handlade om variabeln pathToGallery. Efter lite funderande provade jag att byta ut "pathToGallery" mot "path", en variabel som redan fanns parallellt med samma innehåll, såvitt jag förstår. Vips fungerade allt som det skulle, både lokalt och på BTH:s server.
    <p>
        Min webbmall Kabyssen är nu fullstuvad med matnyttiga saker. Kursens syfte är ju bland annat att lära ut objektorienterad webbprogrammering (man lär sig mycket annat också). Jag saknar en modul för uppladdning av filer, men det går ju att råda bot på. Jag kommer definitivt att ha användning för CImage, CVinylUser, CDatabase och flera stycken till i det kommande projektet.
    <p>
        <strong>Extra.</strong> Jag uppgraderade img.php till att hantera transparenta PNG-bilder och medan jag ändå var igång lade jag till stöd för GIF-bilder, även dem med transparens. Jag lade också till ett filter som gör om bilderna till gråskala. Skriv &grey i query-strängen. 
        Kursrepot är som vanligt pushat till github.
    </p>
    <hr>
            <h2><a name="kmom07_10">Kmom07-10: Projekt och examination</a></h2>
    <p>
        <strong>Klar med projektet!</strong> Jag valde att bygga en nätbutik som säljer vinylskivor i stället för filmvarianten. Hittade nyligen min egen gamla samling på vinden och blev lite inspirerad. Och inspiration behövs för att komma i mål på utsatt tid med ett projekt av den här omfattningen.
    </p>    
        <h4>Beskrivning av projektet:</h4>
        <strong>Krav 1: Struktur och innehåll.</strong> Jag började med att skapa en struktur för hela webbplatsen med ett utseende och en layout som passar temat och som jag inte skulle behöva ändra på i efterhand. HTML-bakgrunden består av ett färgmönster på svart bakgrund och body-bakgrunden ser ut som en lätt transparent grön plastskiva. Sidan har också fått en väldigt snygg och träffande logga. För att skapa en så enkel och intuitiv inloggning som möjligt är denna hela tiden synlig uppe till höger ovanför headern. Inga extra sidor behöver öppnas. 
   <p>
        Webbplatsen är alltså en nätbutik som säljer vinylskivor. Den innehåller en startsida, en sida där skivorna presenteras, en bloggsida, en Om-sida, gemensamt sidhuvud med logga, titel och slogan. Givetvis finns också menyer med länkar till de olika delarna och en gemensam sidfot. Det går att logga in och innehållet på webbplatsen kan både ändras och återställas. 
    <p>
        <strong>Krav 2: Sida - Musik.</strong> På menysidan <b>Musik</b> hittar besökaren en lista med vinylalbum. De presenteras med artistnamn, albumbild, titel, år, genre och pris. Det går också att klicka sig vidare till en sida med mer information (med beskrivningstext, Wikipedia-länk och Youtube-video). I sidhuvudet finns ett sökfält där det går att söka direkt efter album med wildcards. 
    <p>    
        Det går också att öppna en avancerad sökfunktion, med ytterligare sökfält  för artist, årtal och genre. Genren markeras tydligt med gul färg när den har valts, så att det blir tydligt att den ingår i sökvillkoren (tillsammans med t.ex. artist och årtal). Listan har sortering, paginering och det går att välja hur många album som ska visas per sida. Aktuell sida och antalet sidor indikeras tydligt, för att underlätta navigeringen. Jag har  försökt att göra sidan så intuitiv som möjligt. Det ska gå att förstå utan för mycket instruktionstext. Har t.ex. använt input-attributet "placeholder" för att tipsa om sökmöjligheter.
    <p>
        Efter inloggning kan en administratör ändra, ta bort och lägga till skivor. Genom att använda kryssrutor för genre-valet är det enkelt att välja en eller flera genrer. Vissa fält i formulären är obligatoriska, så att det finns en lägsta nivå på informationen.
    <p>
        <strong>Krav 3: Sida - Nyheter.</strong> Det här är en bloggsida som är indelad i kategorierna "nyheter", "rekrytering", "event" och "medlemmar". Klickar besökaren på en kategori så markeras den tydligt och sidan visar bara inlägg i just den kategorin. I listan visas en kort textsnutt för varje inlägg och det går att antingen klicka på rubriken eller på länken "Läs mer>>" för att se hela inlägget på en egen sida. Missa t.ex. inte efterfesten som aviseras på inlägget "Svenska Mässan".
    <p>
        Jag använder mig av en tabell med två kolumner för att visa inläggen. Om en användare med administratörsbehörighet loggar in visas även länkar för att ändra och göra nya inlägg.
    <p>
        <strong>Krav 4: Första sidan.</strong> Sidan visar albumbilder på de skivor som säljer bäst. Senast sålda skiva (bild plus text) är hårdkodad, men övrig text kan ändras av administratören.
    <p>
        Jag gjorde tre kolumner nedtill på sidan för senaste skivor, blogg och aktuella genrer med hjälp av divar. Innehållet i de tre kolumnerna är dynamiskt. Med hjälp av SQL-frågor hämtas de senast uppdaterade skivorna och blogginläggen. Dessa visas i en länklista. Genrerna hämtas med en funktion som tar reda på vilka genrer som butiken kan erbjuda för tillfället. Den listan kändes inte meningsfull att göra klickbar däremot.  
    <p>
        <strong>Krav 5-6: Extra funktioner (optionell):</strong>
    <p>    
        1. <strong>Användarprofil.</strong> Om ingen är inloggad syns en registreringslänk uppe till höger på sidan. Vanliga användare får behörigheten "medlem" och kan då se det mesta som administratören kan se, men utan att kunna ändra något. Vissa formulärsidor som enbart går ut på att ändra, ta bort och återställa kändes inte meningsfulla att göra synliga för "vanliga användare" eftersom de inte skulle ha något innehåll. Tar man bort sin egen profil loggas man automatiskt ut. Kravet att det ska finnas en <strong>publik</strong> sida som visar användarens profil tolkar jag som att man inte behöver vara administratör för att se profilsidan. Jag har valt att göra så att varje användare bara kan se och ändra sin egen profilsida.
    <p>
        Utöver översiktssidan "Användare" kan inloggade vanliga medlemmar också se sidorna "Webbplats" och "Skivor" under "Administration", men utan att kunna ändra något. Jag använder en extra sessionsvariabel "userid" för att hålla reda på om det är den inloggade användaren som tittar på respektive sida. Exempelvis ändras sidan "edit_user.php" beroende på vem som besöker den. En vanlig medlem når den sidan genom att gå via länken på profilsidan.
    <p>    
        2. <strong>Administration av användare.</strong> Det finns en översiktslista över alla användare. Om användaren har behörigheten "administratör" kan hon eller han ändra, ta bort, lägga till och återställa användare.
    <p>
        Översiktslistan har paginering, sortering och val av antal användare per sida. Formulären för att lägga till och ändra användare har <em>obligatoriska</em> fält och det går att ändra lösenord i ändringsformuläret. Administratören kan tilldela användare behörigheten "Medlem" eller "Administratör" med hjälp av radioknappar.  
   </p>     
        <h4>Allmänt om projektet</h4>
    <p>
        Logga in med admin + admin för att se webbplatsen som administratör. Titta under Administration för att se övriga användare.
    <p>
        <strong>Webbmallen.</strong> Min variant heter Kabyssen och är ju grunden för hela projektet. Det är ett riktigt smart sätt att organisera koden. Den skapar ordning bland filerna och passar bra att använda tillsammans med ett klassbibliotek. 
    <p>    
        <strong>OOP.</strong> Det objektorienterade konceptet känns längre inte främmande och jag  börjar få en känsla för vad som hör hemma i sidkontrollen och vad som bör hamna i klassen. Det finns mycket kvar att lära, men jag känner absolut att jag har en god bas att stå på!
    <p>
        <strong>PHP.</strong> Har lärt mig mycket nytt på PHP-området. Bland annat använder jag de nya säkrare funktionerna för att "hasha" lösenord password_hash() och password_verify(). Fick klart för mig att det inte går att återskapa lösenordet från "hashen". Jag lärde mig att undvika kontrollera hela uttryck med empty() om uttrycket består av både rena strängvariabler och arrayer. Jag har lärt mig att använda substr_replace() för att ersätta data i befintliga variabelsträngar. Och mycket annat...
    <p>
        <strong>MySQL.</strong> Det har ju varit en hel del databasande också och det blir faktiskt roligare ju mer jag håller på med det. Fick lite problem vid återställning av tabeller med främmande nycklar. Det gick inte att göra i vilken ordning som helst pga "restraints". Men det löste sig ganska snabbt, tack vare felmeddelanden som ledde mig in på rätt spår.
    <p>
        <strong>GIT.</strong> Hela kursrepot oophp är som vanligt pushat till github. 
        
    </p>           
        <h4>Allmänt om kursen</h4>
    <p>    
        Det var en tuff inledning. Jag hade ingen susning om objektorienterad programmering sedan tidigare. Därefter har det löpt på bättre och att jag lyckades bli klar med projektet i någorlunda tid, är ett bevis på att handledningen är effektiv och fungerar. Chatten, forumet och guider etc. är en god hjälp.
    <p>
        Mitt betyg är högt: 8 av 10. Och nu väntar nästa utmaning, phpmvc...
    </p>

</article>
EOD;

// Finally, leave it all to the rendering phase of Kabyssen.
include(KABYSSEN_THEME_PATH);
