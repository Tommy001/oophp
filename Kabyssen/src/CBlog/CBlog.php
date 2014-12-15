<?php

class CBlog extends CContent {
    
    
  /**
   * Members
   */
    protected $db = null;
    protected $user = null;
    protected $filter = null;
       
    public function __construct() {
        parent::__construct();
        $this->user = new CUser();
        $this->filter = new CTextFilter();
        }
        

    public function GetBlogPost($slug) {
        $acronym = $this->user->Check_User();
        $res = $this->GetBlog($slug);
        $post = null; 
        if($res) {
            foreach($res as $content) {
                $creationdate = $this->GetCreationDate($content->id);
                $title = htmlentities($content->title, null, 'UTF-8');
                $data = $this->filter->doFilter(htmlentities($content->DATA, null, 'UTF-8'), $content->FILTER);
                $link = isset($acronym) ? "<a href='edit.php?id={$content->id}'>Ändra den här posten</a>" : null;
                $link_alla = isset($slug) ? "<br><a href='blog.php'>Visa alla poster</a>" : null;
                $post .= <<<EOD
<h1><a href='blog.php?slug={$content->slug}'>{$title}</a></h1>
<article class='me'>{$data}</article>
<p>Skapades {$creationdate[0]->created}</p>
{$link}
{$link_alla}
EOD;
            }    
        }
        return $post;
    }
}    

