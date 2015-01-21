<?php
/**
 * Användarhantering, inloggning, utloggning.
 *
 */
class CVinylUser {
    
    // medlemsvariabler
    private $search;
    private $groupby  = ' GROUP BY user.id';
    
 
    /**
     * Constructor
     *
     */
    public function __construct() {
        global $kabyssen;
        $this->db = new CDatabase($kabyssen['database']);
        $this->search = new CVinylSearch();
        }
    
    public function NewUserForm($admin, $save, $password, $username, $fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $username) {
        $message = null;
        if($admin) {
            if($save) {
                if(!empty($password && $username && $fornamn && $efternamn)) {
                    $pass = isset($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
                    $params = array($fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $username, $pass);
                    $message = $this->InsertUser($params);
                } else {
                    $message = "<h4>Du måste minst fylla i förnamn, efternamn, användarnamn och lösenord. Komplettera med det som saknas.</h4>";
                }
            }  

            $html = <<<EOD
            <h2>Lägg till användare</h2>
            <div class='me two_col'>    
            <div class='bg'><form method=post><fieldset>
            <p>Förnamn*</p>
            <input class='textfield' type=text name='fornamn' value=''><br>
            <p>Efternamn*</p>
            <input class='textfield' type=text name='efternamn' value=''><br>
            <p>Adress</p>
            <input class='textfield' type=text name='adress' value=''><br>
            <p>Postnummer</p>
            <input class='textfield' type=text name='postnr' value=''><br>  
            <p>Ort</p>
            <input class='textfield' type=text name='ort' value=''><br>
            <p>E-post</p>
            <input class='textfield' type=text name='epost' value=''><br>    
            </div>
            <div class='bg'>    
            <p>Rättighet</p>
            <input type='radio' name='rattighet' value='medlem' checked>Medlem
            <input type='radio' name='rattighet' value='admin'>Administratör<br> 
            <p>Användarnamn*</p>
            <input class='textfield' type=text name='acronym' value=''><br>
            <p>Lösenord*</p>
            <input class='textfield' type=password name='password' value=''><br><br>
            <input type=submit name=save value='Spara'><br>
            <p>Obligatoriska fält är märkta med *</p>
            {$message}
            </fieldset></form></div></div>
EOD;
        } else {
            $html = <<<EOD
            <h2>Lägg till användare</h2>
            <article class='me bg'>
            Du måste vara inloggad som administratör för att lägga till användare.
            </article>
EOD;
        }
        return $html;
    }

    public function Check_User() {
        $acronym = isset($_SESSION['vinyl_user']) ? $_SESSION['vinyl_user'] : null;
        return $acronym;
    }
    
    public function Check_User_Admin() {
        $admin = false;
        if(isset($_SESSION['vinyl_user'])) {
            $admin = $_SESSION['rattighet'] == 'admin' ? true : false;
        }    
        return $admin;
    }
    
    public function GetMemberLink($acronym, $header) {
        if($acronym) {
            $header = "<div class='memberlink'><a href='user_profile.php'>Min profilsida</a></div>" . $header;
        } else {
            $header = "<div class='memberlink'><a href='create_profile.php'>Registrera dig som medlem</a></div>" . $header;
        }
        return $header;
    }
    
    public function Check_Login($params) {
        $acronym = $params[0];
        $sql = "SELECT * FROM user WHERE acronym = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($acronym));  
        if(isset($res[0])) {
            $login = password_verify($params[1], $res[0]->password);
            if($login) {
                $_SESSION['vinyl_user'] = $res[0]->acronym . " (" . $res[0]->fornamn . " " . $res[0]->efternamn . ")";
                $_SESSION['rattighet'] = $res[0]->rattighet;
                $_SESSION['userid'] = $res[0]->id;
            }
        }
    }

        /**
     * Indikera inloggningsstatus på den översta menyraden 'above header'
       genom att hänga på en sträng efter kmom-menyerna inom samma element
     *
     */
    public function User_Status($acronym, $above_header, $login) {
        if($acronym) {
            $above_header = substr_replace($above_header, "<div class='right button'><form method='post' action='' name='logout'>Du är inloggad som {$_SESSION['vinyl_user']}
                <input type='submit' name='logout' value='Logga ut'></form></div></nav>", -6);
            } else if($login) {
            $above_header = substr_replace($above_header, "<div class='right button'>
                <form method='post' action='movie_login.php' name='login'>
                <input placeholder='Användarnamn' type='text' name='acronym'>
                <input placeholder='Lösenord' type='password' name='password'>
                <input type='submit' name='submit' value='Logga in'></form></div></nav>", -6);
            } else {
            $above_header = substr_replace($above_header, "<div class='right button'><form method='post' action='movie_login.php' name='login'>Du har loggat ut
                <input type='text' name='acronym'>
                <input type='password' name='password'>
                <input type='submit' name='submit' value='Logga in'></form></div></nav>", -6);
            }
    
        return $above_header; 
    }    

        
    public function Get_Login_Form() {
        $html = "<article class='me'><form method='post' name='login'><fieldset>
        <legend>Inloggning</legend>
        <p><em>Du kan logga in med doe/doe eller admin/admin.</em></p>
        <label>Användarnamn</label><br>
        <input type='text' name='acronym'><br><br>
        <label>Lösenord</label><br>
        <input type='password' name='password'><br><br>
        <input type='submit' name='submit' value='Logga in'>
        <p><a href='movie_logout.php'>Till utloggningen</a></p>
        </fieldset></form></article>";
        return $html;
    }
    
    public function Get_Logout_Form() {
        $html = "<article class='me'><form method='post' name='login'><fieldset>
        <legend>Utloggning</legend>
        <p><input type='submit' name='logout' value='Logga ut'></p>
        <p><a href='movie_login.php'>Till inloggningen</a></p>
        </fieldset></form></article>";
        return $html;
    }    
    
    public function GetUser($id) {
        $sql = "SELECT * FROM user WHERE id = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $id);
        if($res[0]) {
            $user = $res[0];
        } else {
            die('How embarassing. I didn\'t manage to fetch anything.');
        }
        return $user;
    }

    public function GetButtons() {
        $admin = $this->Check_User_Admin();
        if($admin) {
            $buttons = "<tr><td>
                 <form action='create_user.php' method='post'><input type='submit' value='Skapa en ny användare'></form>
            </td><td>
                 <form action='reset_user.php' method='post'><input type='submit' value='Återställ användardatabasen'></form>
            </td></tr>";
        } else {
            $buttons = null;
        }
        return $buttons;
    }
    
    public function GetAllUsers($hits, $page, $orderby, $order) {
        $sql = "SELECT * FROM user";
        $limit    = null;
        $sort     = " ORDER BY $orderby $order";
        $groupby = $this->groupby;
        
// Pagination
        if($hits && $page) {
            $limit = " LIMIT $hits OFFSET " . (($page - 1) * $hits);
        }        
        
       // Complete the sql statement
        $sql = $sql . $groupby . $sort . $limit;
    
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $items = "<tr class='strong'><td>Användare" . $this->search->orderby('acronym') . "</td><td>Namn" . $this->search->orderby('fornamn') . "</td><td>Behörighet" . $this->search->orderby('rattighet') . "</td><td>Länkar</td></tr>";
        $admin = $this->Check_User_Admin();
        if($admin) {
            foreach($res AS $key => $val) {
                $items .= "<tr><td>{$val->acronym}</td><td> " . htmlentities($val->fornamn, null, 'UTF-8') . " " . htmlentities($val->efternamn, null, 'UTF-8') . "</td><td>{$val->rattighet}</td><td><a href='delete_user.php?id={$val->id}'>Ta bort</a> I <a href='edit_user.php?id={$val->id}'>Ändra</a> I <a href='user_profile.php?id={$val->id}'>Visa</a></td></tr>";
            } 
            return $items;
        } else {
            foreach($res AS $key => $val) {
                $items .= "<tr><td>{$val->acronym}</td><td> " . htmlentities($val->fornamn, null, 'UTF-8') . " " . htmlentities($val->efternamn, null, 'UTF-8') . "</td><td>{$val->rattighet}</td><td><p>Du har inte behörighet att se länkarna</p></td></tr>";
                }
            }
            return $items; 
        }    
        
// Get max pages for current query, for navigation
    public function GetMaxPages($hits) {
        $sqlOrig = "SELECT * FROM user";
        $where = null;  
        $groupby  = $this->groupby;
        $params=array();
        $sql = "
        SELECT
        COUNT(id) AS rows
        FROM 
        (
        $sqlOrig $where $groupby
        ) AS user
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
        $rows = $res[0]->rows; // 13 för tillfället
        $max = ceil($rows / $hits);  
        return array($max, $rows);
    }         

    public function UpdateUserAndPassword($params=array()) {
        $sql = "UPDATE user SET fornamn = ?, efternamn = ?, adress = ?, postnr = ?, ort = ?, epost = ?, rattighet = ?, uppdaterad = NOW(), acronym = ?, password = ? WHERE id = ?;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Uppdateringen lyckades";
        } else {
            $message = "Uppdateringen misslyckades.<br>";
            // <pre>. print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
    
    public function UpdateUser($params=array()) {
        $sql = "UPDATE user SET fornamn = ?, efternamn = ?, adress = ?, postnr = ?, ort = ?, epost = ?, rattighet = ?, uppdaterad = NOW(), acronym = ? WHERE id = ?;
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "Uppdateringen lyckades";
        } else {
            $message = "Uppdateringen misslyckades.<br>";
            // <pre>. print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

        public function InsertUser($params=array()) {
        $sql = "INSERT INTO user (fornamn,efternamn,adress,postnr,ort,epost,rattighet,acronym,password) VALUES (?,?,?,?,?,?,?,?,?)
        ";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $message = "<h4>Användaren har lagts till.</h4>";
        } else {
            $message = "<h4>Användaren kunde inte läggas till. Prova med ett annat användarnamn.</h4><br>";
            // <pre>. print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }
    
    public function GetUserProfile($id, $acronym) {

        $admin = $this->Check_User_Admin();
        $res = $this->GetUser(array($id));
        if(isset($_SESSION['userid'])) {
            $currentuser = $res->id == $_SESSION['userid'] ? true : false;
        }
        $userprofile = null;
        $userprofile = <<<EOD
        <h3><strong>Användarprofil</strong></h3>
EOD;
        if(isset($res) && $acronym) {
            $link_edit = $admin || $currentuser ? "<a href='edit_user.php?id={$res->id}'>Ändra</a>" : null;
            $link_alla = $admin ? "<br><a href='admin_user.php'>Visa alla</a>" : null;
            $link_ny = $admin ? "<br><a href='create_user.php'>Lägg till</a>" : null;  
            $link_tabort = $admin || $currentuser ? "<br><a href='delete_user.php?id={$res->id}'>Ta bort</a>" : null;  
            $userprofile .= <<<EOD
            <table><tr>
            <td class='blogtable'>                
            <h3 class='textmarg'>
            {$res->fornamn} {$res->efternamn}</h3>
            <p>{$res->adress}</p>
            <p>{$res->postnr} {$res->ort}</p>
            <p>{$res->epost}</p>
            <p><strong>Behörighet: {$res->rattighet}</strong></p>
            </td>
            <td>
            Blev medlem: {$res->medlemsdatum}
            Senast ändrad: {$res->uppdaterad}            
            {$link_edit}
            {$link_alla}
            {$link_ny}
            {$link_tabort}
            </td></tr></table>
EOD;
                
        } else {
        $userprofile = <<<EOD
        <h3><strong>Användarprofil</strong></h3>
        <article class='me bg'>
        <p>Du måste vara inloggad för att se användarprofilen.</p>
        </article>
EOD;
        }
        return $userprofile;
    }    
    
    public function DeleteUser($delete, $id) {
        $sql = "
        DELETE FROM user
        WHERE id = ?;
        ";
 
        if($delete) {
            $res = $this->db->ExecuteQuery($sql, array($id));
            if($res) {
                $message = "Användaren raderades";
                $currentuser = false;
                if(isset($_SESSION['userid'])) {
                    $currentuser = $id == $_SESSION['userid'] ? true : false;
                }
                if($currentuser) {
                    unset($_SESSION['vinyl_user']);
                    unset($_SESSION['userid']);
                    header('Location: ' . $_SERVER['HTTP_REFERER']);    
                }
                    
            } else {
                $message = "Användaren kunde inte raderas.<br>"; //. print_r($this->db->errorInfo(), 1) . "</pre>";
            }    
            $html = <<<EOD
            <h2>Ta bort en användare</h2>            
            <article class='me'><form method=post><fieldset>
            <h3>$message</h3><br>
            </article>";
EOD;
        } else {  
            $user = $this->GetUser(array($id));
            
            $html = <<<EOD
            <h2>Ta bort användare</h2>            
            <article class='me'><form method=post><fieldset>
            <input type=hidden name=id value='{$id}'>
            <p>OBS! Användaren <strong>"{$user->fornamn} {$user->efternamn}"</strong> kommer att raderas definitivt.</p><br>
            <input type=submit name='delete' value='Ta bort'>   
            </fieldset></form></article>;
EOD;
        }

        return $html;
    }

    public function ResetUserDatabase() {
        $message['createuserdatabase'] = $this->CreateUserDatabase();
        $message['insertuserdatabase'] = $this->InsertUserContent();
        return $message;
    }    

    // används för att markera kryssrutor i olika formulär
    private function checked($value1, $value2) {
        $checked = in_array($value1, $value2) ? 'checked' : null;
        return $checked;
    }
    
    public function GetEditUserForm($id, $acronym, $admin, $username, $fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $password, $save) {
        $message = null;
        $currentuser = false;
        if(isset($_SESSION['userid'])) {
                $currentuser = $id == $_SESSION['userid'] ? true : false;
        }
        if($admin || $currentuser) {
            if($save) {
                // om ett nytt lösenord anges
                if(!empty($password && $username && $fornamn && $efternamn)) {
                    $pass = isset($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
                    $params = array($fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $username, $pass, $id);
                    $message = $this->UpdateUserAndPassword($params);
                } else if(!empty($username && $fornamn && $efternamn)) {
                    // om inget nytt lösenord anges
                    $params = array($fornamn, $efternamn, $adress, $postnr, $ort, $epost, $rattighet, $username, $id);
                    $message = $this->UpdateUser($params);
                } else {
                    // om obligatoriska fält saknas
                    $message = "<h4>Förnamn, efternamn och användarnamn måste vara med. Komplettera med det som saknas.</h4>";
                }
            }

            $res = $this->GetUser(array($id));
            
            if($admin) {
                $rattighet = "<p>Rättighet</p>
                <input type='radio' name='rattighet' value='medlem' " . $checked = $this->checked('medlem', array($res->rattighet)) . ">Medlem
                <input type='radio' name='rattighet' value='admin' " . $checked = $this->checked('admin', array($res->rattighet)) . ">Administratör<br>";
            }

            $html = "
            <h2>Ändra användare</h2>
            <div class='me two_col'>    
            <div class='bg'><form method=post><fieldset>
            <input type=hidden name=id value='{$id}'>
            <p>Förnamn*</p>
            <input class='textfield' type=text name='fornamn' value='{$res->fornamn}'><br>
            <p>Efternamn*</p>
            <input class='textfield' type=text name='efternamn' value='{$res->efternamn}'><br>
            <p>Adress</p>
            <input class='textfield' type=text name='adress' value='{$res->adress}'><br>
            <p>Postnummer</p>
            <input class='textfield' type=text name='postnr' value='{$res->postnr}'><br>  
            <p>Ort</p>  
            <input class='textfield' type=text name='ort' value='{$res->ort}'><br>
            <p>E-post</p>
            <input class='textfield' type=text name='epost' value='{$res->epost}'><br>    
            </div>
            <div class='bg'>    
            {$rattighet}
            <p>Användarnamn*</p>
            <input class='textfield' type=text name='acronym' value='{$res->acronym}'><br>
            <p>Nytt lösenord</p>
            <input class='textfield' type=text name='password' value=''><br><br>
            <input type=submit name=save value='Spara'><br>
            <p>Obligatoriska fält är märkta med *</p><br>
            <h4>{$message}</h4>
            </fieldset></form></div></div>";
        } else {
            $html = "
            <h2>Ändra användare</h2>
            <article class='me bg'>
            Du måste vara inloggad som administratör för att göra ändringar.
            </article>";
        } 
        return $html;
    }
       


    
    private function CreateUserDatabase() {
    $sql = "DROP TABLE IF EXISTS user;
    CREATE TABLE `user` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `acronym` varchar(20) NOT NULL,
    `fornamn` varchar(50) NOT NULL,
    `efternamn` varchar(50) NOT NULL,
    `adress` varchar(50) DEFAULT NULL,
    `postnr` varchar(10) DEFAULT NULL,
    `ort` varchar(50) DEFAULT NULL,
    `epost` varchar(50) DEFAULT NULL,
    `rattighet` varchar(10) NOT NULL,
    `uppdaterad` datetime NOT NULL,
    `password` varchar(255) DEFAULT NULL,
    `medlemsdatum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `acronym` (`acronym`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;
    ";
        $res = $this->db->ExecuteQuery($sql);
        if($res) {
            $message = "Tabellen skapades.";
        } else {
            $message = "Tabellen kunde inte skapas.<br>"; // . print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }

    private function InsertUserContent() {
        $sql = "INSERT INTO `user` (`id`, `acronym`, `fornamn`, `efternamn`, `adress`, `postnr`, `ort`, `epost`, `rattighet`, `uppdaterad`, `password`, `medlemsdatum`) VALUES
        (1, 'joppe', 'Johan', 'Persson', 'Göteborgsvägen 40', '444 44', 'Göteborg', 'joppe@vinylrecords.com', 'admin', '2015-01-16 08:32:36', '$2y$10\$GcHzygSaBqdDWJzkoSA4TeSNSu/I3P.IMJJ2jRZRHJmz4.Mp7xcYi', '2015-01-15 20:16:42'),
        (2, 'tompa', 'Tommy', 'Johansson', 'Trolldalsvägen 40', '423 43', 'Torslanda', 'tommy@franskaord.se', 'medlem', '2015-01-15 22:06:57', '$2y$10\$FH22CTZuCYyr38A2yXhd8OHhD7n2IPYLTUALhdrITJmoLJ1a1Rmh6', '2015-01-15 20:16:42'),
        (4, 'emma', 'Emma', 'Nilsson', '', '', '', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$WbpKlVQ7DnCO550mEBCBLu2gVk/ydathtoAcnHT1ks8y5S570JpE.', '2015-01-16 12:25:52'),
        (5, 'admin', 'Maria', 'Kullersten', 'Kullerstensgatan 1', '123 45', 'Kulleberga', 'mariakullersten@telia.com', 'admin', '2015-01-17 14:40:02', '$2y$10\$TNbPfdkNKlVAh4WQkDM4w.Nc76uEur.b9X6pJ4vNLZVh7WIyffyv.', '2015-01-16 17:06:59'),
        (6, 'nalle', 'Nalle', 'Palleson', 'Skogsbrynet', '123 45', 'Skogen', 'mulle@skogen.nu', 'medlem', '0000-00-00 00:00:00', '$2y$10\$kRJkKX6cUG9BdsgSB1k3ce/sjx1VcZ5wn1S.0uxQfiqzAESwUJhWC', '2015-01-16 17:08:29'),
        (8, 'david', 'David', 'Davidsson', '', '', '', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$KZaUEOD7eMOCU3zQrSUEZe0xaVOmX7ENsWsftcqlFZDhkQcCm3pt.', '2015-01-16 17:15:33'),
        (9, 'doe', 'Doris', 'Doredsson', '', '', '', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$aB9ZttL33Xf2z9RogU1pVOn7ktFewGHKOVdKU3oazLwPmgKnQ2q26', '2015-01-16 17:16:14'),
        (10, 'anita', 'Anita', 'Transistor', '', '', '', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$praLvCwP8VHCohItBsfE1.W6ScyAcKMfvw0lhE77SlxiNiB7yJW5G', '2015-01-16 17:16:43'),
        (11, 'magus', 'Magnus', 'Magnusson', '', '', '', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$eKNfpf278ctIlmjEcIj3YufMwHquEX5EfsOdDm8vyUu5fbwPy1bwq', '2015-01-16 17:17:17'),
        (12, 'harald', 'Harald', 'Norge', 'Oslovägen', '234234', 'Oslo', 'norway@oslo.org', 'medlem', '0000-00-00 00:00:00', '$2y$10\$eTQJmaiHxk9CcXS1kCfXfOcxPENeISZtclPOw5.82PQ5kaqMlA1ne', '2015-01-16 17:19:43'),
        (13, 'danne', 'Daniel', 'Johansson', 'Malmövägen', '234 56', 'Malmö', 'danne123@coldmail.com', 'medlem', '0000-00-00 00:00:00', '$2y$10$.d4Op2qhv3CCtYcr4z0xFu8ZuDRSDcxte/Gz3kv9.GgOTfEMnKXm.', '2015-01-16 17:20:56'),
        (14, 'kulan', 'Herkules', 'Jonsson', 'Bagarevägen 3', '789 45', 'Umeå', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$STKfyQ/IsCOb3QoVebcKQ.uvESCSVfSECEO2risnLi0XesYZeTqYq', '2015-01-16 22:12:13'),
        (15, 'fabiola', 'Fabiola', 'Lagotto', 'Trolldalsvägen 40', '423 43', 'Torslanda', '', 'medlem', '0000-00-00 00:00:00', '$2y$10\$Gslxb0LJCTBd3PvGQofWnO3MJIaErCPMZYsnmEOnjlkKAL5KgJSae', '2015-01-17 16:06:10'),
        (16, 'kalle', 'Kalle', 'Kula', 'Kulan 3', '456 78', 'Norrköping', '', 'medlem', '0000-00-00 00:00:00', '$2y$10$5omrI3cwC8J.AL.ERcQDa.bgiF0UFv1sP4jh6.qZlflVl/3bheEyS', '2015-01-17 16:12:19');
        ";
        $res = $this->db->ExecuteQuery($sql);
        if($res) {
            $message = "Exempelinnehåll har infogats.<br>";
        } else {
            $message = "Exempelinnehållet kunde inte infogas.<br>"; // print_r($this->db->errorInfo(), 1) . "</pre>";
        }
        return $message;
    }    
    
}    
