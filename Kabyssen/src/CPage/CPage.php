<?php

class CPage extends CContent {
    
    
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
        

    public function Page($url) {
        $page = $this->GetPage($url);
        $user = $this->user->Check_User();
        $content['title'] = htmlentities($page->title, null, 'UTF-8');
        $content['data'] = $this->filter->doFilter(htmlentities($page->DATA, null, 'UTF-8'), $page->FILTER);
        $content['editlink'] = isset($user) ? "<a href='edit.php?id=$page->id'>Ändra den här sidan</a>" : null;
        return $content;
    }
    
}    
