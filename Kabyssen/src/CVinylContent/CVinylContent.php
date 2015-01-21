<?php

class CVinylContent {
    
    
  /**
   * Members
   */
    protected $db = null;
    protected $user = null;
       
    public function __construct() {
        global $kabyssen;
        $this->db = new CDatabase($kabyssen['database']);
        $this->user = new CVinylUser();
        }          
         // hämta rader från tabell för visning
    public function ViewContent($params=array()) {
        $sql = "SELECT * FROM content WHERE id = ?;";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
        if($res[0]) {
            $resultat = $res[0];
        } else {
            die('How embarassing. We didn\'t manage to bring you anything.');
        }
        $data['title'] = htmlentities($resultat->title, null, 'UTF-8');
        $data['slug'] = htmlentities($resultat->slug, null, 'UTF-8');
        $data['url'] = htmlentities($resultat->url, null, 'UTF-8');
        $data['data'] = htmlentities($resultat->DATA, null, 'UTF-8');
        $data['type'] = htmlentities($resultat->TYPE, null, 'UTF-8');
        $data['filter'] = htmlentities($resultat->FILTER, null, 'UTF-8');
        $data['pub'] = htmlentities($resultat->published, null, 'UTF-8');
        return $data;
    }
        

    // uppdatera rad i tabellen          
    public function UpdateContent($params=array()) {
        $sql = "UPDATE content SET title = ?, slug = ?, url = ?, DATA = ?, TYPE = ?, FILTER = ?, published = ?, updated = NOW() WHERE id = ?;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Uppdateringen lyckades";
        } else {
            $message = "Uppdateringen misslyckades.<br>"; 
            //<pre>" . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
        
    /**
    *  Create a link to the content, based on its type.
    *
    * @param object $content to link to.
    * @return string with url to display content.
    */
    public function getUrlToContent($content) {
        switch($content->TYPE) {
            case 'page': return "page.php?url={$content->url}"; break;
            case 'post': return "blog.php?slug={$content->slug}"; break;
            default: return null; break;
        }
    }
    
    public function GetItAll() {
        $sql = "SELECT *, (published <= NOW()) AS available
        FROM content;";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $items = "<tr class='strong'><td>Typ</td><td>Publicerad</td><td>Titel</td><td>Länkar</td></tr>";
        $admin = $this->user->Check_User_Admin();
        if($admin) {
            foreach($res AS $key => $val) {
                $items .= "<tr><td>{$val->TYPE}</td><td> " . (!$val->available ? 'inte ' : null) . "publicerad</td><td> " . htmlentities($val->title, null, 'UTF-8') . "</td><td><a href='delete.php?id={$val->id}'>Ta bort</a> I <a href='edit.php?id={$val->id}&amp;type={$val->TYPE}'>Ändra</a> I <a href='" . $this->getUrlToContent($val) . "'>Visa</a></td></tr>";
            } 
            return $items;
        } else {
            foreach($res AS $key => $val) {
                $items .= "<tr><td>{$val->TYPE} </td><td>" . (!$val->available ? 'inte ' : null) . "publicerad</td><td> " . htmlentities($val->title, null, 'UTF-8') . " </td><td><a href='" . $this->getUrlToContent($val) . "'>Visa</a></td></tr>";
                }
            }
            return $items; 
        }
        
    public function GetButtons() {
        $admin = $this->user->Check_User_Admin();
        if($admin) {
            $buttons = "<tr><td>
                 <form action='create.php' method='post'><input type='submit' value='Gör ett nytt inlägg i bloggen'></form>
            </td><td>
                 <form action='reset.php' method='post'><input type='submit' value='Återställ databasen'></form>      
            </td></tr>";
        } else {
            $buttons = "<tr><td>
                <form action='blog.php' method='post'>
                <input type='submit' value='Visa alla bloggposter'>
                </form></td><tr>";
        }
        return $buttons;
    }          
        
        
    public function GetLatestPosts($params=array()) {
        $sql = "SELECT * FROM content
        WHERE updated <= NOW() AND published <= NOW() AND TYPE = 'post'
        ORDER BY updated DESC
        LIMIT 3;
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
        $items = null;
        foreach($res AS $key => $val) {
                $items .= "<li><a href='blog.php?slug={$val->slug}'>" . htmlentities($val->title, null, 'UTF-8') . "</a></li>";
            } 
            return $items; 
        }        
     
       
    public function GetBlog($slug, $kategori) {
        $slugSQL = $slug ? 'slug = ?' : '1';
        if(isset($kategori)) {
            $sql = "SELECT * FROM content 
            INNER JOIN cont2cat
            ON content.id = cont2cat.idCont
            WHERE idCat = ? AND published <= NOW() 
            ORDER BY updated DESC;
            "; 
            $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($kategori));
        } else {
            $sql = "SELECT * FROM content WHERE TYPE = 'post' AND $slugSQL AND published <= NOW() ORDER BY updated DESC;
            "; 
            $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($slug));
        }
        
        return $res;
    }
    
    public function GetCreationDate($id) {
        $sql = "SELECT created FROM content WHERE id  = ?;"; 
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));
        return $res;
    }    
    
    public function GetPage($url) {
        $sql = "SELECT * FROM content WHERE TYPE = 'page' AND url = ? AND published <= NOW()";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $url);
            if(isset($res[0])) {
                $page = $res[0];
            } else {
                $page = null;
            }
            return $page;
        }    
       

    
    public function GetFilter($id) {
        $sql = "SELECT * FROM content WHERE id = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $id);
        $filter = null;
        if($res[0]) {
            $filter = $res[0]->FILTER;
            $filters = preg_replace('/\s/', '', explode(',', $filter));
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $filters;
    }    

    public function GetType($id) {
        $sql = "SELECT * FROM content WHERE id = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $id);
        $type = null;
        if($res[0]) {
            $type[] = $res[0]->TYPE;
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $type;
    }
    
   public function InsertNewContent($title, $type, $data, $filter) {

        $slug = strip_tags($title);
        $slug = $this->slugify($title);
        $url = $type == 'page' ? $slug : null;

        $sql = "INSERT INTO content(slug, url, type, data, title, filter, published, created) VALUES(?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $params = array($slug, $url, $type, $data, $title, $filter);
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Tabellraden infogades";
        } else {
            $message = "Tabellraden kunde inte infogas.<br>"; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    } 
    
    /**
    *  Create a slug of a string, to be used as url.
    *
    * @param string $str the string to format as slug.
    * @returns str the formatted slug. 
    */
    function slugify($str) {
        $str = mb_strtolower(trim($str));
        $str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = trim(preg_replace('/-+/', '-', $str), '-');
        return $str;
    }
    
    
    public function DeleteContent($params=array()) {
        $sql = "DELETE FROM content WHERE id = ?;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Tabellraden raderades. ";
        } else {
            $message = "Tabellraden kunde inte raderas i content. "; //. print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        $sql = "DELETE FROM cont2cat WHERE idCont = ?;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message1 = "Tabellraden raderades i kopplingstabellen.";
        } else {
            $message1 = "Tabellraden raderades inte i kopplingstabellen.<br>"; //. print_r($this->db->errorInfo(), 1) . "</pre>";
            // $message1 is for debug purposes
        }        
        return $message;
    }
        

    public function ResetDatabase() {
        $message['createtable'] = $this->CreateTable();
        $message['insertcontent'] = $this->InsertSampleContent();
        $message['createcont2cat'] = $this->CreateCont2Cat();
        $message['insertcont2cat'] = $this->InsertCont2Cat();        
        return $message;
    }
    
    public function InsertSampleContent($params=array()) {
        $sql = "INSERT INTO `content` (`id`, `slug`, `url`, `TYPE`, `title`, `DATA`, `FILTER`, `published`, `created`, `updated`, `deleted`) VALUES
        (1, 'start', 'start', 'page', 'Välkommen till Vinyl Records!', '[big]Våra storsäljare är...[/big] Black Sabbath, Imperiet, Tom Waits, Pink Floyd... \r\noch senast sålda skiva är [b]Sade-albumet Diamond Life[/b]\r\n\r\n', 'bbcode,nl2br,typo,', '2014-12-28 19:51:43', '2014-12-28 19:51:43', '2015-01-14 17:28:30', NULL),
        (2, 'om', 'om', 'page', 'Om oss', '[big]Vinyl Records startade som ett källarprojekt av ett antal entusiaster.[/big]\r\n\r\nVi inser förstås att slaget delvis redan är förlorat, men anser att våra kära gamla vinylskivor förtjänar ett bättre öde än att hamna på tippen.\r\n\r\nVi lanserar den här nätbutiken som en kulturgärning och driver den utan vinstsyfte. Eller okej, skulle det bli en liten vinst så blir vi förstås glada. Men det är inget som vi förväntar oss.\r\n\r\n[big]Vi håller till i Göteborg[/big] och har ännu ingen fysisk vinylbutik, men om det här försöket med skivor på internet blir lyckat, kanske vi gör ett försök.\r\n\r\nVi tänker att du som besöker oss är likasinnad och du är välkommen att [b]registrera dig som medlem här på hemsidan. [/b]', 'bbcode,nl2br,typo,', '2014-12-28 19:51:43', '2014-12-28 19:51:43', '2015-01-14 16:57:03', NULL),
        (7, 'rolling-stones', NULL, 'post', 'Sällsynta Rolling Stones-skivor', 'Albumet **Dirty Work** från 1986 finns att köpa. Om du saknar den här skivan i din Rolling Stones-samling ska du inte vänta för länge. Vi har bara **ETT** exemplar kvar!', 'markdown,', '2014-12-29 18:24:00', '2014-12-29 18:24:00', '2015-01-14 22:04:01', NULL),
        (8, 'carlos-santana', NULL, 'post', 'Ny LP med Carlos Santana', 'Nu äntligen har vi fått in Albumet **\"The swing of delight\"** på lagret och den går att beställa här. Först till kvarn!\r\n\r\nVill du veta mer om Carlos Santana så hittar du hans bibliografi på wikipedia. \r\n\r\nKolla in videon från YouTube under rubriken **Musik** (klicka på albumbilden).', 'markdown,', '2014-12-29 18:24:56', '2014-12-29 18:24:56', '2015-01-14 13:58:25', NULL),
        (9, 'pink-floyd', NULL, 'post', 'Album med Pink Floyd på hyllan', '**Wish You Were Here** har kommit in och finns att köpa. Leta upp den under rubriken **Musik** och klicka på albumbilden.\r\n\r\n**Beställ nu så att du inte går miste om den här juvelen.**', 'markdown,nl2br,', '2014-12-29 18:29:13', '2014-12-29 18:29:13', '2015-01-14 14:18:59', NULL),
        (10, 'prince', NULL, 'post', 'Mer med Prince', 'Vi har fått in TVÅ exemplar av albumet \"Purple Rain\". Kolla in den under rubriken **\"Musik\"**. ', 'markdown,nl2br,', '2014-12-29 18:43:14', '2014-12-29 18:43:14', '2015-01-14 12:56:03', NULL),
        (11, 'nyheter', 'nyheter', 'page', 'Senaste skivorna', 'Nu har vi fått in en del nytt på lagret:', '', '2015-01-07 12:42:00', '2015-01-07 12:42:00', '2015-01-10 10:35:06', NULL),
        (12, 'blogg', 'blogg', 'page', 'Bloggen', 'De senaste inläggen i vår **blogg** är:', 'markdown,typo,', '2015-01-07 12:56:24', '2015-01-07 12:56:24', '2015-01-12 19:59:36', NULL),
        (13, 'genrer', 'genrer', 'page', 'Genrer', 'För tillfället har vi skivor i följande genrer:', '', '2015-01-07 12:57:25', '2015-01-07 12:57:25', NULL, NULL),
        (23, 'svenska-m-ssan', NULL, 'post', 'Svenska Mässan', 'Du kommer väl och besöker oss i monter 03:23 på Svenska Mässan den 23 februari?\r\nDet kommer att bjudas på såväl allsångsrefränger som stadiga luftgitarriff.\r\nDen som under mässan klär sig i den bästa retrostilen har chansen att vinna väldigt fina priser. \r\nDen inofficiella efterfesten kommer att äga rum hemma hos Joppe, vår webbprogrammerare.\r\n\r\n**Lite hålltider för efterfesten:**\r\n22.00 – Vinylparty\r\n24.00 – Nattamat\r\n01.30 – Hemgång eller... vi får väl se vem som tröttnar först.', 'markdown,link,nl2br,typo,', '2015-01-10 20:35:35', '2015-01-10 20:35:35', '2015-01-16 15:00:50', NULL),
        (24, 'vi-rekryterar', NULL, 'post', 'Vi rekryterar', 'Nu söker vi en ekonomiansvarig som kan ta hand fakturering och säljsupport. Du ska sköta offertuppföljning, göra vissa inköp och mycket annat. Vi är mycket flexibla och kan anpassa arbetsuppgifterna så det passar både dig och oss.', 'markdown,link,nl2br,typo,', '2015-01-13 17:39:05', '2015-01-11 17:39:05', '2015-01-14 13:24:59', NULL),
        (26, 'erbjudande-f-r-medlemmar', NULL, 'post', 'Erbjudande för medlemmar', 'Nu har vi ett kanonerbjudande! Men bara för medlemmar...', 'bbcode,link,nl2br,typo,', '2015-01-15 22:44:27', '2015-01-11 22:44:27', '2015-01-14 08:40:13', NULL),
        (29, 'skivor-med-soul-drottningen-sade', NULL, 'post', 'Skivor med soul-drottningen Sade', 'I våra samlingar har vi ett fåtal exemplar kvar av albumet [b]Diamond Life[/b] med Sade. \r\nLäs mer under rubriken [b]Musik[/b] och varför inte lyssna på ett spår från Youtube.\r\nGå till rubriken [b]Musik[/b] och klicka på albumbilden du hittar i listan.\r\n\r\n[b]Först till kvarn![/b]', 'bbcode,link,nl2br,typo,', '2015-01-14 13:15:36', '2015-01-14 13:15:36', '2015-01-16 11:54:06', NULL),
        (30, 'medlemsservice', NULL, 'post', 'Medlemsservice', 'Skriv till oss om du tycker att det är något som saknas! Har du som medlem något förslag till förändring av hemsidan eller annat, så tveka inte utan hör av dig!', 'bbcode,link,nl2br,typo,', '2015-01-14 13:17:57', '2015-01-14 13:17:57', NULL, NULL),
        (31, 'signerade-skivor', NULL, 'post', 'Signerade skivor', 'Vi har samlat alla signerade skivor och kommer att ta med oss dessa till mässan i Göteborg. Först till kvarn kan köpa signerade album med Prince, Black Sabbath, Sade, Suzanne Vega och många fler... Boka din biljett snarast!', 'bbcode,link,nl2br,typo,', '2015-01-14 13:21:59', '2015-01-14 13:21:59', NULL, NULL),
        (32, 'administrat-r', NULL, 'post', 'Administratör', 'Vi behöver en ny administratör till vår hemsida. Dina huvudsakliga uppgifter kommer att bestå i att uppdatera hemsidan och lägga in nyinkomna skivor i databasen. Självklart kommer du även att stå för en del webbutveckling och förbättringar av hemsidan.\r\n\r\nDu kommer att jobba tillsammans med Joppe, vår webbprogrammerare, som kommer att sätta dig in i arbetsuppgifterna.', 'bbcode,link,nl2br,typo,', '2015-01-14 13:23:31', '2015-01-14 13:23:31', '2015-01-14 14:21:15', NULL);
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Exempelinnehåll har infogats.<br>";
        } else {
            $message = "Exempelinnehållet kunde inte infogas.<br>"; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function CreateTable($params=array()) {    
        $sql = "DROP TABLE IF EXISTS content;
        CREATE TABLE `content` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `slug` char(80) DEFAULT NULL,
        `url` char(80) DEFAULT NULL,
        `TYPE` char(80) DEFAULT NULL,
        `title` varchar(80) DEFAULT NULL,
        `DATA` text,
        `FILTER` char(80) DEFAULT NULL,
        `published` datetime DEFAULT NULL,
        `created` datetime DEFAULT NULL,
        `updated` datetime DEFAULT NULL,
        `deleted` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`),
        UNIQUE KEY `url` (`url`)
        ) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
    ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Tabellen skapades.";
        } else {
            $message = "Tabellen kunde inte skapas.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    } 
    
    // raderar och återskapar kopplingstabellen för webbinnehåll och blogg
    public function CreateCont2Cat($params=array()) {    
        $sql = "DROP TABLE IF EXISTS `cont2cat`;
        CREATE TABLE `cont2cat` (
        `idCont` int(5) DEFAULT NULL,
        `idCAT` int(5) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Kopplingstabellen skapades.";
        } else {
            $message = "Kopplingstabellen kunde inte skapas.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    
    
    // infogar standardinnehåll vid återställning av tabellen för blogg
    // och webbinnehåll
    public function InsertCont2Cat($params=array()) {
        $sql = "INSERT INTO `cont2cat` VALUES (7,1),(8,1),(9,1),(10,1),(23,3),(24,2),(26,4),(29,1),(30,4),(31,3),(32,2);
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Värdena har återställts i kopplingstabellen.<br>";
        } else {
            $message = "Värdena kunde inte återställas i kopplingstabellen.<br>"; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function GetContentEditForm($id, $type, $admin, $save, $title, $slug, $kategori, $url, $data, $filter, $pub, $save) {
        $message1 = null;
        $message2 = null;
        // kolla först om anv är inloggad
        if($admin) {
                $url = empty($url) ? null : $url; // om url inte är satt ska den vara null
                if($save) {
                    $params = array($title, $slug, $url, $data, $type, $filter, $pub, $id);
                    $message1 = $this->UpdateContent($params);
                    $message2 = $this->UpdateCont2Cat($kategori, $id);
                }    

                $res = $this->ViewContent(array($id));
    
                $get_filters = $this->GetFilter(array($id));
                $get_type = $this->GetType(array($id));
                $kategorier = null;
                if($type == 'post'){
                    $get_category = $this->GetCategory(array($id));  
                    $kategorier = "<p>Kategori</p>    
                    <input type='radio' name='kategori' value='1' " . $checked = $this->checked('1', $get_category) . ">Nyheter
    
                    <input type='radio' name='kategori' value='2' " . $checked = $this->checked('2', $get_category) . ">Rekrytering

                    <input type='radio' name='kategori' value='3' " . $checked = $this->checked('3', $get_category) . ">Event

                    <input type='radio' name='kategori' value='4' " . $checked = $this->checked('4', $get_category) . ">Medlemmar";
                }
    
                switch($type) {
                case "post":
                    $typ = 'blogginlägg';
                    break;
                case "page":
                    $typ = 'text på webbsida';
                    break;
                default:
                    $typ = null;
                    break;
                }
   
    // uppdateringsformuläret
                $html = "
                <h2>Ändra {$typ}</h2>
                <div class='me two_col'>    
                <div class='bg'><form method=post><fieldset>
                <input type=hidden name=id value='{$id}'>
                <p>Titel</p>
                <input class='textfield' type=text name='title' value='{$res['title']}'><br>
                <p>Slug</p>
                <input class='textfield' type=text name='slug' value='{$res['slug']}'><br>
                <p>URL</p>
                <input class='textfield' type=text name='url' value='{$res['url']}'><br>
                <p>Text</p>
                <textarea class='textarea' name='data'>{$res['data']}</textarea><br>
                </div>
                <div class='bg'>    
                {$kategorier}    
                <p>Textfilter</p>
                <input type='radio' name='filter' value='markdown,' " . $checked = $this->checked('markdown', $get_filters) . ">Markdown
   
                <input type='radio' name='filter' value='bbcode,' " . $checked = $this->checked('bbcode', $get_filters) . ">BBcode<br> 

                <input type='checkbox' name='link' value='link,' " . $checked = $this->checked('link', $get_filters) . ">Länkar
 
                <input type='checkbox' name='nl2br' value='nl2br,' " . $checked = $this->checked('nl2br', $get_filters) . ">Radbrytningar
 
                <input type='checkbox' name='typo' value='typo,' " . $checked = $this->checked('typo', $get_filters) . ">Smartypants Typographer<br>
                <p>Publiceringsdatum</p>
                <input class='textfield' type=text name='published' value='{$res['pub']}'><br><br>
                <input type=submit name=save value='Spara'><br><br>
                <h4>{$message1}</h4>
                <h4>{$message2}</h4>
                </fieldset></form></div></div>";
        } else {
            $html = <<<EOD
            <article class='me bg'>
            Du måste vara inloggad för att göra ändringar.
            </article>
EOD;
        }
        return $html;
    }
    
    public function GetDeleteContentForm($id, $admin, $delete) {
        // kolla först om anv är inloggad
        if($admin) { 
            if($delete) {
                $message = $this->DeleteContent(array($id));
                $html = <<<EOD
                <h2>Ta bort en sida eller en post</h2>                
                <article class='me bg'><form method=post><fieldset>
                <h4>$message</h4>
                </article>
EOD;
            } else {  
                $content = $this->ViewContent(array($id));
                $html = <<<EOD
                <h2>Ta bort en sida eller en post</h2>
                <article class='me bg'><form method=post><fieldset>                
                <input type=hidden name=id value='{$id}'>
                <p>OBS! Posten eller sidan <strong>"{$content['title']}"</strong> kommer att raderas definitivt.</p><br>
                <input type=submit name='delete' value='Ta bort'>
                </fieldset></form></article>
EOD;
            }
        } else {
            $html = <<<EOD
        <h2>Ta bort en sida eller en post</h2>
        <article class='me bg'>
        Du måste vara inloggad som administratör för att kunna ta bort innehåll.</article>
EOD;
        } 
        return $html;
    }
    
    
    private function checked($value, $genres) {
        $checked = in_array($value, $genres) ? 'checked' : null;
        return $checked;
    }
               

    
    public function InsertCategory($category=array()) {
        $sql = "INSERT INTO cont2cat
        (idCont, idCat) 
        VALUES
        (?, ?);";
        $idCont = $this->db->LastInsertId();
        foreach($category as $val) {
            $params = array($idCont, $val);
            $res = $this->db->ExecuteQuery($sql, $params);
        }    
        if($res) {
            $message = "Kategorin lades till.";
        } else {
            $message = "Kategorin kunde inte läggas till.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    } 
    

    public function GetCategory($id=array()) {
        $sql = "SELECT idCat FROM cont2cat WHERE idCont = ?;
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $id);
        $cat = null;
        if($res) {
            $cat[] = $res[0]->idCat;
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $cat;
    }
    
    // förberett för att kunna ha fler än 1 kategori
    public function UpdateCont2Cat($kategori, $id) {
        $sql = "UPDATE cont2cat SET
        idCAT = ? WHERE idCont = ?;
        ";
        $params = array($kategori, $id);
        $res = $this->db->ExecuteQuery($sql, $params);   
        if($res) {
            $message = "Valen uppdaterades i kopplingstabellen.";
        } else {
            $message = "Valen kunde inte uppdateras i kopplingstabellen.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    
    
}    
