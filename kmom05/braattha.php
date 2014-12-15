<?php


// Rensa bort skadlig kod före lagring i databasen.
$url    = isset($_POST['url'])   ? strip_tags($_POST['url']) : null;
$type   = isset($_POST['type'])  ? strip_tags($_POST['type']) : array();
 
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$data   = isset($_POST['data'])  ? $_POST['data'] : array();

// Inkommande variabler matchar tänkt värdemängd.
is_numeric($id) or die('Check: Id must be numeric.');


/*När variablera visas upp i webbsidan så måste de saneras. Det sker med funktionen htmlentities(). Jag väljer det säkra för det osäkra och sanerar alla variabler. Det känns helt enkelt tryggare så.
Sanera data från databasen innan presentation i webbsidan.*/

$sql = 'SELECT * FROM Content WHERE id = ?';
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($id));
$c   = $res[0];
 
$url    = htmlentities($c->url, null, 'UTF-8');
$type   = htmlentities($c->type, null, 'UTF-8');
$title  = htmlentities($c->title, null, 'UTF-8');
$data   = htmlentities($c->data, null, 'UTF-8');

/* En klar fördel med all denna sanering är att när vi använder PHP PDO och prepared statement så kan vi skicka in precis vad som helst till databasen. Vi slipper att fundera på SQL injections. Det sparar tid och gör hanteringen enkel.

PHP PDO och prepared statements hanterar okänd data säkert. */

$sql = '
  UPDATE Content SET
    title   = ?,
    slug    = ?,
    url     = ?,
    data    = ?,
    type    = ?,
    filter  = ?,
    published = ?,
    updated = NOW()
  WHERE 
    id = ?
';
$params = array($title, $slug, $url, $data, $type, $filter, $published, $id);
$db->ExecuteQuery($sql, $params);

/* När du läser upp innehållet från databasen, lägger det i formuläret och åter sparar det, så är det lätt att ditt ursprungliga NULL görs om till en tom sträng. Detta leder i sin tur till att du får problem med databastabellens restriktioner om att url skall vara UNIQUE. Ett enkelt sätt att gå runt problemet är att kontrollera om url är tom och då sätta den till NULL istället.

Sätt tomt värde på url till NULL. */

$url = empty($url) ? null : $url;
$params = array($title, $slug, $url, $data, $type, $filter, $published, $id);
$res = $db->ExecuteQuery($sql, $params);

/* Just felhantering mot databasen kräver lite hantering. Vad händer om uppdateringen går fel? När du gör en uppdatering om INSERT, UPDATE, eller DELETE så returnerar PDOStatement::execute() true eller false, beroende på om det gick bra eller ej. Sådant kan man behöva kontrollera och det är bäst att du har sådant stöd i din CDatabase-klass.

Kontrollera om frågan gick bra, annars visa fel. */

$res = $db->ExecuteQuery($sql, $params);
if($res) {
  $output = 'Informationen sparades.';
}
else {
  $output = 'Informationen sparades EJ.<br><pre>' . print_r($db->ErrorInfo(), 1) . '</pre>';
}

// page
// Get content
$sql = "
SELECT *
FROM Content
WHERE
  type = 'page' AND
  url = ? AND
  published <= NOW();
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($url));

// Sedan måste innehållet saniteras och filtreras.

// Sanitera innehållet innan det visas.

// Sanitize content before using it.
$title  = htmlentities($c->title, null, 'UTF-8');
$data   = doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);

// Därefter är det bara att visa upp innehållet tillsammans med HTML-elementen.

// Lägg ut innehållet tillsammans med HTML-element.

// Prepare content and store it all in variables in the Anax container.
$anax['title'] = $title;
$editLink = $acronym ? "<a href='edit.php?id={$c->id}'>Uppdatera sidan</a>" : null;
$anax['main'] = <<<EOD
<article>
<header>
<h1>{$title}</h1>
</header>
 
{$data}
 
<footer>
{$editLink}
</footer
</article>
