<?php

class CVinylBlog extends CVinylContent {
    
    
  /**
   * Members
   */
    protected $db = null;
    protected $user = null;
    protected $filter = null;
    protected $search = null;
       
    public function __construct() {
        parent::__construct();
        $this->user = new CVinylUser();
        $this->filter = new CTextFilter();
        $this->search = new CVinylSearch();
        }
        

    public function GetBlogPost($slug, $kategori) {

        $acronym = $this->user->Check_User();
        $admin = $this->user->Check_User_Admin();
        $res = $this->GetBlog($slug, $kategori);
        $cat = $this->GetActiveCategories($kategori);
        $post = null;
        $post = isset($slug) ? null: <<<EOD
        <h3><strong>Läs vår blogg! Klicka på den kategori som intresserar dig.</strong></h3>
        {$cat}
        <div class='grans'></div>
EOD;
        if($res) {
            foreach($res as $content) {
                $creationdate = $this->GetCreationDate($content->id);
                $title = htmlentities($content->title, null, 'UTF-8');
                $data = $this->filter->doFilter(htmlentities($content->DATA, null, 'UTF-8'), $content->FILTER);
                // om alla inlägg visas i en lista ska texten trunkeras
                // men om bara ett inlägg visas ska den inte trunkeras
                $data = isset($slug) ? $data : $this->Truncate($data, $content);
                $link = ($admin) ? "<a href='edit.php?id={$content->id}&amp;type={$content->TYPE}'>Ändra den här posten</a>" : null;
                $link_alla = isset($slug) ? "<br><a href='blog.php'>Visa alla poster</a>" : null;
                $link_ny = ($admin) ? "<br><a href='create.php'>Gör ett nytt inlägg</a>" : null;                
                $post .= <<<EOD
                <table><tr>
                <td class='blogtable'>                
                <h3 class='textmarg'>
                <a href='blog.php?slug={$content->slug}'>{$title}</a></h3>
                {$data}
                </td>
                <td>
                <p>Skapades {$creationdate[0]->created}</p>
                {$link}
                {$link_alla}
                {$link_ny}
                </td></tr></table>
EOD;
            }    
        }
        return $post;
    }
    
    public function Truncate($data, $content) {
        // strip tags to avoid breaking any html
        $string = strip_tags($data);

        if (strlen($string) > 100) {

            // truncate string
            $stringCut = substr($string, 0, 100);

            // make sure it ends in a word 
            $string = substr($stringCut, 0, strrpos($stringCut, ' '))."... <a href='blog.php?slug={$content->slug}'>Läs mer &gt;&gt;</a>"; 
        }
        return $string;
    }
    
    public function GetActiveCategories($kategori){
        $sql = "
        SELECT DISTINCT K.id, K.kategori
        FROM kategorier AS K
        INNER JOIN cont2cat AS C2C
        ON K.id = C2C.idCat
        INNER JOIN content AS C
        ON C.id = C2C.idCont
        WHERE C.published <= NOW();
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $kat = "<div class='noblock'>Kategorier: <a href='?'>alla</a></div> ";
        foreach($res as $val) {
            if($val->id == $kategori) {
                $kat .= "<div class='pagenum_current noblock'>{$val->kategori}</div> ";
            }
            else {
                $kat .= "<div class='noblock'><a href='" . $this->search->getQueryString(array('kategori' => $val->id)) . "'>{$val->kategori}</a></div> ";
            }
        }
        return $kat;
    }    
}    

