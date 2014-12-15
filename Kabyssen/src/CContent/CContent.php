<?php

class CContent {
    
    
  /**
   * Members
   */
    protected $db = null;
    protected $user = null;
       
    public function __construct() {
        global $kabyssen;
        $this->db = new CDatabase($kabyssen['database']);
        $this->user = new CUser();
        }          
         // hämta rader från tabell för visning
    public function ViewContent($params=array()) {
        $sql = "SELECT * FROM opk5Content WHERE id = ?;";
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
        $sql = "UPDATE opk5Content SET title = ?, slug = ?, url = ?, DATA = ?, TYPE = ?, FILTER = ?, published = ?, updated = NOW() WHERE id = ?;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Uppdateringen lyckades";
        } else {
            $message = "Uppdateringen misslyckades.<br><pre>" . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
        
    /**
    * opk5 Create a link to the content, based on its type.
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
        FROM opk5Content;";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $items = null;
        $user = $this->user->Check_User();
        if($user) {
            foreach($res AS $key => $val) {
                $items .= "<li>{$val->TYPE} (" . (!$val->available ? 'inte ' : null) . "publicerad): " . htmlentities($val->title, null, 'UTF-8') . " (<a href='delete.php?id={$val->id}'>Ta bort</a> I <a href='edit.php?id={$val->id}'>Ändra</a> I <a href='" . $this->getUrlToContent($val) . "'>Visa</a>)</li>\n";
            } 
            return $items;
        } else {
            foreach($res AS $key => $val) {
                $items .= "<li>{$val->TYPE} (" . (!$val->available ? 'inte ' : null) . "publicerad): " . htmlentities($val->title, null, 'UTF-8') . " (<a href='" . $this->getUrlToContent($val) . "'>Visa</a>)</li>\n";
                }
            }
            return $items; 
        }    
       
    public function GetBlog($slug) {
        $slugSQL = $slug ? 'slug = ?' : '1';
        $sql = "SELECT * FROM opk5Content WHERE TYPE = 'post' AND $slugSQL AND published <= NOW() ORDER BY updated DESC;"; 
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($slug));
        return $res;
    }
    
    public function GetCreationDate($id) {
        $sql = "SELECT created FROM opk5Content WHERE id  = ?;"; 
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));
        return $res;
    }    
    
    public function GetPage($url) {
        $sql = "SELECT * FROM opk5Content WHERE TYPE = 'page' AND url = ? AND published <= NOW()";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $url);
        if($res[0]) {
            $page = $res[0];
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $page;
    }    

    
   public function InsertNewContent($title, $type, $data) {

        $slug = strip_tags($title);
        $slug = $this->slugify($title);
        $url = $type == 'page' ? $slug : null;

        $sql = "INSERT INTO opk5Content(slug, url, type, data, title, published, created) VALUES(?, ?, ?, ?, ?, NOW(), NOW())";

        $params = array($slug, $url, $type, $data, $title);
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Tabellraden infogades";
        } else {
            $message = "Tabellraden kunde inte infogas.<br>"; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    } 
    
    /**
    * opk5 Create a slug of a string, to be used as url.
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
        $sql = "DELETE FROM opk5Content WHERE id = ?;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Tabellraden raderades";
        } else {
            $message = "Tabellraden kunde inte raderas.<br>"; //. print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function ResetDatabase() {
        $message['createtable'] = $this->CreateTable();
        $message['createcontent'] = $this->CreateSampleContent();
        return $message;
    }
    
    public function CreateSampleContent($params=array()) {
        $sql = "INSERT INTO opk5Content (slug, url, TYPE, title, DATA, FILTER, published, created) VALUES
  ('hem', 'hem', 'page', 'Hem', \"Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter 'nl2br' som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.\", 'bbcode,nl2br', NOW(), NOW()),
  ('om', 'om', 'page', 'Om', \"Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.\", 'markdown', NOW(), NOW()),
  ('blogpost-1', NULL, 'post', 'Välkommen till min blogg!', \"Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.\", 'link,nl2br', NOW(), NOW()),
  ('blogpost-2', NULL, 'post', 'Nu har sommaren kommit', \"Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.\", 'nl2br', NOW(), NOW()),
  ('blogpost-3', NULL, 'post', 'Nu har hösten kommit', \"Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost\", 'nl2br', NOW(), NOW())
;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Exempelinnehåll har infogats.<br>";
        } else {
            $message = "Exempelinnehållet kunde inte infogas.<br>"; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function CreateTable($params=array()) {    
        $sql = "DROP TABLE IF EXISTS opk5Content;
        CREATE TABLE opk5Content
        (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
        slug CHAR(80) UNIQUE,
        url CHAR(80) UNIQUE,
        TYPE CHAR(80),
        title VARCHAR(80),
        DATA TEXT,
        FILTER CHAR(80),
        published DATETIME,
        created DATETIME,
        updated DATETIME,
        deleted DATETIME) ENGINE INNODB CHARACTER SET utf8;";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Tabellen skapades.";
        } else {
            $message = "Tabellen kunde inte skapas.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    


 
          
    
}    
