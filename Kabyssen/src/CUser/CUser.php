<?php
/**
 * Användarhantering, inloggning, utloggning.
 *
 */
class CUser {
 
    /**
     * Constructor
     *
     */
    public function __construct() {
        global $kabyssen;
        $this->db = new CDatabase($kabyssen['database']);
        }
    
    
    public function Check_User() {
        $acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
        return $acronym;
    }
    
    public function Check_Login($params) {
        $sql = "SELECT acronym, name FROM op_k4_USER WHERE acronym = ? AND password = md5(concat(?, salt))";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);  
        if(isset($res[0])) {
            $_SESSION['user'] = $res[0];
        }
    }

        /**
     * Indikera inloggningsstatus på den översta menyraden 'above header'
       genom att hänga på en sträng efter kmom-menyerna inom samma element
     *
     */
    public function User_Status($acronym, $above_header, $login) {
        if($acronym) {
            $above_header = substr_replace($above_header, "<div class='right'>Du är inloggad som: $acronym ({$_SESSION['user']->name})</div></nav>", -6);
            } else if($login) {
            $above_header = substr_replace($above_header, "<div class='right'>Du har inte loggat in</div></nav>", -6);
            } else {
            $above_header = substr_replace($above_header, "<div class='right'>Du har loggat ut</div></nav>", -6);
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

    
}    
