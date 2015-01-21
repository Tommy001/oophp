<?php

class CVinylMusic {
    
    
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
          

    public function GetMusicButtons() {
        $admin = $this->user->Check_User_Admin();
        $buttons = null;
        if($admin) {
            $buttons = "<tr><td>
                 <form action='add_music.php' method='post'><input type='submit' value='Lägg till skivor i databasen'></form>
            </td><td>
                 <form action='reset_music.php' method='post'><input type='submit' value='Återställ databasen'></form> 
            </td></tr>";
        }
        return $buttons;
    }      
        
           
        public function GetAllRecords() {
        $sql = "SELECT * FROM music ORDER BY artist ASC;";    
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $items = "<tr class='strong'><td>Artist</td><td>Album</td><td>Länkar</td></tr>";
        $admin = $this->user->Check_User_Admin();
        if($admin) {
            foreach($res AS $val) {
                $items .= "<tr><td>" . $val->artist . "</td><td>" . $val->title . "</td><td><a href='delete_vinyl.php?id={$val->id}'>Ta bort</a> I <a href='edit_music.php?id={$val->id}'>Ändra</a> I <a href='info_vinyl.php?id={$val->id}'>Visa</a></td></tr>";
            }           
        } else {
            foreach($res AS $val) {
            $items .= "<tr><td>" . $val->artist . "</td><td>" . $val->title . "</td><td><a href='info_vinyl.php?id={$val->id}'>Visa</a></td></tr>";  
            }            
        }
        return $items;
    }  
    
        public function GetLatestRecords() {
        $sql = "SELECT * FROM music
        WHERE updated <= NOW()
        ORDER BY updated DESC
        LIMIT 3;";    
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $items = null;
            foreach($res AS $val) {
                $items .= "<li>" . $val->artist . " | " . "<a href='info_vinyl.php?id={$val->id}'>" . $val->title . "</a></li>\n";
            }
        return $items;
    }    
                   
    
    public function GetInfo($id) {
        $sql = "SELECT * FROM music WHERE id = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $id);
        if($res[0]) {
            $lp = $res[0];
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $lp;
    }     

        public function GetGenres($id) {
        $sql = "SELECT idGenre FROM music2genre WHERE idMusic IN 
        (SELECT idMusic FROM music2genre
        WHERE idMusic = ?);
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $id);
        $genres = null;
        if($res) {
            foreach($res as $val) {
            $genres[] = $val->idGenre;
            }    
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $genres;
    }

    public function GetGenreTable() {
        $sql = "SELECT * FROM genre WHERE 1";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $genres = array(null); // lägger in null på key 0...
        if($res) {
            foreach($res as $val) {
            $genres[] = $val->name; // ... så att genrerna börjar på key 1
            }    
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $genres;
    }
    
    
  
    public function DeleteVinyl($params=array()) {
        $sql = "
        DELETE music, music2genre
        FROM music
        INNER JOIN music2genre 
            ON music.id = music2genre.idMusic 
        WHERE music.id = ?;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Skivan raderades";
        } else {
            $message = "Skivan kunde inte raderas.<br>"; //. print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    


    public function GetAddMusicForm($genreArray, $admin, $save, $title, $artist, $pris, $year, $image, $wikipedia, $youtube, $beskrivning) {
        $genreTable = $this->GetGenreTable();
        $message1 = null;
        $message2 = null; // for debug purpose
        if($admin) { 
            if($save) {
                $genreflag = is_array($genreArray);
                $message1 = "Album, genre, artist och pris måste anges. Komplettera med det som saknas";
                if(!empty($genreflag && $title && $artist && $pris)) {
                    $params = array($title, $artist, $pris, $year, $image, $wikipedia, $youtube, $beskrivning);
                    $message1 = $this->InsertMusic($params);
                    $message2 = $this->InsertGenre($genreArray);
                }
            }    

            $genreCheckBoxes = null;
            $i=1;
            while($i<count($genreTable)) {
                $br = $i==6 || $i==11 ? '<br>' : null;
                $genreCheckBoxes .=  "<input type='checkbox' name='genres[]' value=$i />" . "{$genreTable[$i]}{$br} ";
                $i++;
            }   
            // uppdateringsformuläret
            $html = <<<EOD
            <h2>Lägg till skivor i databasen</h2>
            <div class='me two_col'>     
            <div class='bg'><form method=post><fieldset>     
            <p>Album*</p>
            <input class='textfield' type=text name='title' value=''><br>
            <p>Artist*</p>
            <input class='textfield' type=text name='artist' value=''><br>
            <p>Genre*</p>
            {$genreCheckBoxes}
            <p>Beskrivning</p>
            <textarea class='textarea' name='beskrivning'></textarea><br>
            </div>
            <div class='bg'>    
            <p>Pris*</p>
            <input class='textfield' type=text name='pris' value=''><br>
            <p>År</p>
            <input class='textfield' type=text name='year' value=''><br>
            <p>Bild</p>
            <input class='textfield' type=text name='image' value=''><br>
            <p>Wikipedia</p>
            <input class='textfield' type=text name='wikipedia' value=''><br>
            <p>Youtube</p>
            <input class='textfield' type=text name='youtube' value=''><br><br>    
            <input type=submit name=save value='Lägg till'><br>
            <p>Obligatoriska fält är märkta med *</p><br>
            <h4>{$message1}</h4>
            </fieldset></form></div></div>
EOD;
        } else {
            $html = <<<EOD
            <h2>Lägga till skivor</h2>
            <article class='me bg'>
            Du måste vara inloggad för att lägga till skivor.
            </article>
EOD;
        }
        return $html;
    }
    
    private function checked($value, $genres) {
        $checked = in_array($value, $genres) ? 'checked' : null;
        return $checked;
    }
    
    public function EditMusicForm($id, $genreArray, $admin, $save, $title, $artist, $pris, $year, $image, $wikipedia, $youtube, $beskrivning) {
        $message1 = null;
        $message2 = null; // for debug purpose
        if($admin) { 
            if($save) {
                $genreflag = is_array($genreArray);                
                $message1 = "Album, genre, artist och pris måste anges. Komplettera med det som saknas";
                if(!empty($genreflag && $title && $artist && $pris)) {
                    $params = array($title, $artist, $pris, $year, $image, $wikipedia, $youtube, $beskrivning);
                    $message1 = $this->UpdateMusic($params, $id);
                    $message2 = $this->UpdateMusic2Genre($genreArray, $id);            
                }
            }    
            $lp = $this->GetInfo(array($id));
            $genres = $this->GetGenres(array($id));
            $genreTable = $this->GetGenreTable();   

            $genreCheckBoxes = null;
            $i=1;

            while($i<count($genreTable)) {
                $br = $i==6 || $i==11 ? '<br>' : null;
                $genreCheckBoxes .=  "<input type='checkbox' name='genres[]' value=$i " . $checked = $this->checked($i, $genres) . "/>" . "{$genreTable[$i]}{$br} ";
                $i++;
            }

            $html = <<<EOD
            <h2>Ändra skivdata</h2>
            <div class='me two_col'>            
            <div class='bg'><form method=post><fieldset>
            <p>Album*</p>
            <input class='textfield' type=text name='title' value='{$lp->title}'><br>
            <p>Artist*</p>
            <input class='textfield' type=text name='artist' value='{$lp->artist}'><br>
            <p>Genre*</p>
            {$genreCheckBoxes}
            <p>Beskrivning</p>
            <textarea class='textarea' name='beskrivning'>{$lp->beskrivning}</textarea><br>
            </div>
            <div class='bg'>
            <p>Pris*</p>
            <input class='textfield' type=text name='pris' value='{$lp->pris}'><br>
            <p>År</p>
            <input class='textfield' type=text name='year' value='{$lp->year}'><br>
            <p>Bild</p>
            <input class='textfield' type=text name='image' value='{$lp->image}'><br>
            <p>Wikipedia</p>
            <input class='textfield' type=text name='wikipedia' value='{$lp->wikipedia}'><br>
            <p>Youtube</p>
            <input class='textfield' type=text name='youtube' value='{$lp->youtube}'><br><br>    
            <input type=submit name=save value='Uppdatera'><br>
            <p>Obligatoriska fält är märkta med *</p><br>
            <h4>{$message1}</h4>
            </fieldset></form></div></article>
EOD;
        } else {
            $html = <<<EOD
            <h2>Ändra skivdata</h2>            
            <article class='me bg'>    
            <p>Du måste vara inloggad som administratör för att göra ändringar.</p>
            </article>
EOD;
        }
        return $html;
    }
   
    
    public function ResetMusicDatabase() {
        $message['dropmusic2genre_table'] = $this->DropMusic2GenreTable();
        $message['createmusic_table'] = $this->CreateMusicTable();
        $message['insertmusic_content'] = $this->InsertMusicContent();
        $message['createm2gtable'] = $this->CreateMusic2GenreTable();
        $message['insertm2gcontent'] = $this->InsertMusic2GenreContent();        
        
        return $message;
    }    
    
    public function CreateMusicTable($params=array()) {    
        $sql = "DROP TABLE IF EXISTS `music`;
        CREATE TABLE `music` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(100) NOT NULL,
        `artist` varchar(100) DEFAULT NULL,
        `tracks` int(11) DEFAULT NULL,
        `year` int(11) NOT NULL DEFAULT '1900',
        `beskrivning` text,
        `image` varchar(100) DEFAULT NULL,
        `pris` decimal(6,2) DEFAULT NULL,
        `youtube` varchar(100) DEFAULT NULL,
        `wikipedia` varchar(100) DEFAULT NULL,
        `lyssna` varchar(100) DEFAULT NULL,
        `updated` datetime DEFAULT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Skivtabellen skapades.";
        } else {
            $message = "Skivtabellen kunde inte skapas."; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function InsertMusicContent($params=array()) {
        $sql = "INSERT INTO `music` (`id`, `title`, `artist`, `tracks`, `year`, `beskrivning`, `image`, `pris`, `youtube`, `wikipedia`, `lyssna`, `updated`, `created`) VALUES
        (1, 'Wish You Were Here', 'Pink Floyd', 0, 1975, 'Wish You Were Here (förkortat WYWH) är ett musikalbum av Pink Floyd, utgivet den 15 september 1975. Albumet kretsar kring alienation, nedbrytning av mänskliga relationer och hur människor behandlas som ting av t ex företag och stater; även förlusten av ungdom och oskuldsfull glädje är ett genomgående tema. Vissa texter tematiserar musikbranschen och dess avsaknad av förståelse för musikernas villkor (t ex den ironiska Have A Cigar) samt saknaden efter den tidigare bandmedlemmen Syd Barrett, som albumet också är tillägnat, men musiken syftar också på brutalitet, vansinne och främlingskap i andra sammanhang som var aktuella vid denna tid, t ex militarisering och byråkratisk paranoia. Roger Waters skulle komma att fullfölja denna kritik mot samtiden både på de tre följande albumen med gruppen (där han var huvudansvarig för texter och upplägg) innan den splittrades, och som soloartist med t ex Amused To Death.', 'music/wishyouwerehere.jpg', 199.00, 'kmHWBo46iow', 'http://sv.wikipedia.org/wiki/Wish_You_Were_Here', '', '2015-01-13 22:01:17', '2014-12-24 14:00:03'),
        (2, 'The Dark Side of the Moon', 'Pink Floyd', NULL, 1973, 'The Dark Side of the Moon (DSOTM) är ett konceptalbum från 1973 av det brittiska rockbandet Pink Floyd. Albumet tillhör bandets mest kända och uppskattade verk och är ett av de mest berömda inom genren progressiv rock. Det har sålts i 45 miljoner exemplar, vilket gör det till världens tredje mest sålda musikalbum. Det är det album som har legat längst på topplistan Billboard 200: totalt 758 veckor (drygt 14 ½ år) mellan 1973 och 1988.\r\n\r\nAlbumet gavs ut den 1 mars 1973 i USA och den 24 mars i Storbritannien. Bandet hade redan året innan turnerat med materialet under arbetsnamnet Eclipse. Albumets omslag, som designades av Hipgnosis och George Hardie, är svart med ett stort prisma i vilket en ljusstråle bryts upp till ett spektrum.', 'music/darkside.jpg', 179.00, 'XiimzQ0KqBA', 'http://sv.wikipedia.org/wiki/The_Dark_Side_of_the_Moon', NULL, '2015-01-17 22:34:43', '2014-12-24 14:00:04'),
        (3, 'The Swing of Delight', 'Carlos Santana', 0, 1980, 'The Swing of Delight is a 1980 double album by Carlos Santana. It was the last of three solo albums (the others being Illuminations in 1974 and Oneness in 1979) to be released under his temporary Sanskrit name Devadip Carlos Santana, given to him by Sri Chinmoy.', 'music/swingofdelight.jpg', 99.00, 'beD58ordH08', 'http://en.wikipedia.org/wiki/The_Swing_of_Delight', '', '2015-01-13 21:44:24', '2014-12-24 14:00:05'),
        (4, 'Dirty Work', 'The Rolling Stones', NULL, 1986, 'Dirty Work är ett musikalbum av The Rolling Stones släppt 1986. Albumet är allmänt sett som ett av gruppens svagaste album från 1980-talet, i stor utsträckning till följd av de dåvarande spänningarna mellan Keith Richards och Mick Jagger. Ett undantag bland kritikerna var Robert Christgau som höll skivan som en favorit och gav den A i betyg. Han uppskattade producenten Steve Lillywhites avskalade rockarrangemang och låttexternas ärliga desperation. Gruppen bytte i samband med detta album distributör av sitt skivbolag Rolling Stones Records från Atlantic Records till CBS Records.', 'music/dirtywork.jpg', 89.00, 'yv2mc_UkZf0', 'http://sv.wikipedia.org/wiki/Dirty_Work', NULL, '2015-01-14 13:02:39', '2014-12-24 14:00:09'),
        (5, 'Purple Rain', 'Prince', NULL, 1984, 'Purple Rain är ett musikalbum av Prince och hans band The Revolution från 1984 och utgör soundtracket till filmen Purple Rain.\r\n\r\nAlbumet blev Princes stora genombrott och låg etta på Billboards albumlista 24 veckor i rad. Purple Rain vann en Oscar för bästa soundtrack och två Grammys för bästa soundtrack och bästa rockframträdande av duo eller grupp (Prince and the Revolution). Albumet genererade flera hitlåtar, däribland \"When Doves Cry\" och \"Let''s Go Crazy\" som båda toppade Billboards singellista. Rockballaden \"Purple Rain\" låg tvåa på listan och är en av Princes mest kända låtar. Albumet har sålt i 20 miljoner exemplar världen över och är Princes bäst säljande album.', 'music/purplerain.jpg', 179.00, 'F8BMm6Jn6oU', 'http://sv.wikipedia.org/wiki/Purple_Rain_%28musikalbum%29', NULL, '2015-01-18 11:39:16', '2014-12-24 14:00:14'),
        (6, 'Rain Dogs', 'Tom Waits', 0, 1985, 'Rain Dogs är ett album av Tom Waits, utgivet 1985. Skivomslagets foto är taget av den svenske fotografen Anders Petersen ur boken Cafe Lehmitz.', 'music/raindogs.jpg', 289.00, 'qTlkVTwMLFs', 'http://sv.wikipedia.org/wiki/Rain_Dogs', '', '2015-01-13 22:20:17', '2014-12-24 14:00:24'),
        (18, 'Living My Life', 'Grace Jones', NULL, 1982, 'Living My Life är ett musikalbum av Grace Jones lanserat i november 1982 på Island Records. Skivan spelades in på Bahamas. Under inspelningarna spelades även en låt med titeln \"Living My Life\" in, men den kom inte med på skivan utan släpptes senare som singel. Efter den här skivan kom Jones att ta en paus från musikkarriären några år för att bland annat satsa på skådespeleri.', 'music/livingmylife.jpg', 129.00, 'tLVHHptCmxc?list=RDtLVHHptCmxc', 'http://sv.wikipedia.org/wiki/Living_My_Life', NULL, '2015-01-18 11:34:22', '2014-12-24 14:00:34'),
        (30, 'Blå himlen blues', 'Imperiet', NULL, 1985, 'Blå himlen blues är ett album av Imperiet, släppt 15 mars 1985 på Mistlur.\r\n\r\nFör albumet fick bandet även Rockbjörnen i kategorin \"Årets svenska skiva\".', 'music/blahimlen.jpg', 99.00, '1Wsh80G_L6A', 'http://sv.wikipedia.org/wiki/Bl%C3%A5_himlen_blues', NULL, '2015-01-14 12:29:09', '2014-12-24 14:00:43'),
        (34, 'Sabbath Bloody Sabbath', 'Black Sabbath', NULL, 1973, 'Sabbath Bloody Sabbath är det brittiska heavy metal-bandet Black Sabbaths femte studioalbum, släppt 1 december 1973 i Storbritannien och i januari 1974 i USA. Albumet var startskottet till ett mer experimentellt Black Sabbath. På detta album hade man även rekryterat Rick Wakeman från Yes på keyboard.\r\nDet skräckinjagande skivomslaget målades av konstnären Drew Struzan. På omslagets framsida syns en man på en säng, troligtvis drabbad av syner eller en mardröm, omgiven av demoner och en dödskalle med numret 666 under. Baksidan visar istället framsidans totala motsats med mannen i sängen omgiven av sörjande närstående. Skivan släpptes i ett utvikskonvolut och på insidan fanns en otydlig transparent bild på bandmedlemmarna i ett sovrum.', 'music/Black_Sabbath_SbS.jpg', 248.00, 'BqS0NME_PZM', 'http://sv.wikipedia.org/wiki/Sabbath_Bloody_Sabbath', NULL, '2015-01-14 15:55:46', '2015-01-07 19:41:10'),
        (35, 'Solitude Standing', 'Suzanne Vega', NULL, 1987, 'Solitude Standing is the second album by singer-songwriter Suzanne Vega. Released in 1987, it is the most popular and critically acclaimed of her career. As can be seen by the CD insert, many of the songs had been written prior to 1987 (see track listing for dates).', 'music/solitude.jpg', 129.00, 'C5V4LNHHFyA', 'http://en.wikipedia.org/wiki/Solitude_Standing', NULL, '2015-01-14 12:13:31', '2015-01-14 11:13:31'),
        (36, 'Diamond Life', 'Sade', NULL, 1984, 'Diamond Life is the debut studio album by the English band Sade. It was released in the United Kingdom on 16 July 1984 by Epic Records and in the United States on 27 February 1985 by Portrait Records. Released in the wake of Sade''s two hit singles \"Your Love Is King\" and \"When Am I Going to Make a Living\", the album peaked at number two on the UK Albums Chart and spent over six months in the top ten. Diamond Life also topped the charts in Austria, France, Germany, Netherlands and Switzerland, while reaching number five in the US and number seven in Canada.\r\n\r\nThe album won the Brit Award for Best British Album in 1985.', 'music/diamondlife.png', 199.00, 'OOxiKv2gC1M', 'http://en.wikipedia.org/wiki/Diamond_Life', NULL, '2015-01-14 12:17:08', '2015-01-14 11:17:08'),
        (37, '90125', 'Yes', NULL, 1983, '90125 är det elfte studioalbumet av den brittiska progressiva rockgruppen Yes utgivet 14 november 1983. Albumet producerades av Trevor Horn som även sjöng på bandets föregående album, Drama. Sångaren Jon Anderson och keyboardisten Tony Kaye var nu tillbaka i bandet men gitarristen Steve Howe var här ersatt av Trevor Rabin.\r\n\r\nSingeln Owner of a Lonely Heart blev bandets enda listetta på Billboard Hot 100. Albumet gav Yes ett kommersiellt genombrott och gjorde gruppen mer mainstream och därmed kända för en större publik. Albumet är namngivet efter sitt katalognummer hos Atco Records.', 'music/90125.jpg', 139.00, 'HlPGecLF00g', 'http://sv.wikipedia.org/wiki/90125', NULL, '2015-01-14 12:22:26', '2015-01-14 11:22:26');
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Skivtabellens innehåll har infogats.";
        } else {
            $message = "Skivtabellens innehåll kunde inte infogas."; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    

    public function DropMusic2GenreTable($params=array()) {    
        $sql = "DROP TABLE IF EXISTS `music2genre`;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Kopplingstabellen togs bort.";
        } else {
            $message = "Kopplingstabellen kunde inte tas bort."; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function CreateMusic2GenreTable($params=array()) {    
        $sql = "CREATE TABLE `music2genre` (
        `idMusic` int(11) NOT NULL,
        `idGenre` int(11) NOT NULL,
        PRIMARY KEY (`idMusic`,`idGenre`),
        KEY `idGenre` (`idGenre`),
        CONSTRAINT `music2genre_ibfk_1` FOREIGN KEY (`idMusic`) REFERENCES `music` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
        CONSTRAINT `music2genre_ibfk_2` FOREIGN KEY (`idGenre`) REFERENCES `genre` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Kopplingstabellen skapades.";
        } else {
            $message = "Kopplingstabellen kunde inte skapas."; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    public function InsertMusic2GenreContent($params=array()) {
        $sql = "INSERT INTO `music2genre` VALUES (4,1),(6,1),(3,2),(5,4),(34,5),(5,6),(6,6),(18,6),(34,8),(1,9),(2,9),
        (37,9),(30,11),(18,12),(3,13),(4,13),(6,13),(30,13),(35,13),(37,13),
        (4,14),(5,15),(18,15),(35,15),(36,15),(37,16);
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Värdena har återställts i kopplingstabellen.";
        } else {
            $message = "Värdena kunde inte återställas i kopplingstabellen."; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }     
    

    public function InsertMusic($params=array()) {
        $sql = "INSERT INTO music
        (title, artist, pris, year, image, wikipedia, youtube, beskrivning, updated, created) 
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Skivan lades till.";
        } else {
            $message = "Skivan kunde inte läggas till.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
    
    public function InsertGenre($genreArray) {
        $sql = "INSERT INTO music2genre
        (idMusic, idGenre) 
        VALUES
        (?, ?);";
        $idMusic = $this->db->LastInsertId();
        foreach($genreArray as $val) {
            $params = array($idMusic, $val);
            $res = $this->db->ExecuteQuery($sql, $params);
        }    
        if($res) {
            $message = "Genren lades till.";
        } else {
            $message = "Genren kunde inte läggas till.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
    
    public function UpdateMusic($params=array(), $id) {
        $sql = "UPDATE music SET title = ?, artist = ?, pris = ?, year = ?, image = ?, wikipedia = ?, youtube = ?, beskrivning = ?, updated = NOW()
        WHERE id = $id;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Skivan uppdaterades.";
        } else {
            $message = "Skivan kunde inte uppdateras.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
    
    // kopplingstabellen går inte att uppdatera på normalt sätt
    // alla rader med aktuellt id raderas och sedan läggs nya rader till
    public function UpdateMusic2Genre($genreArray, $id) {
        $sql = "DELETE FROM music2genre WHERE idMusic = ?; 
        ";
        $res = $this->db->ExecuteQuery($sql, array($id));   
        if($res) {
           $message1 = "Id raderades i kopplingstabellen.";
        } else {
           $message1 = "Id kunde inte raderas i kopplingstabellen.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        $sql = "INSERT INTO music2genre
        (idMusic, idGenre) 
        VALUES
        (?, ?);";
        foreach($genreArray as $val) {
            $params = array($id, $val);
            $res = $this->db->ExecuteQuery($sql, $params);
        }    
        if($res) {
            $message = "Valen lades till i kopplingstabellen.";
        } else {
            $message = "Valen kunde inte läggas till i kopplingstabellen.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    

    
}    
