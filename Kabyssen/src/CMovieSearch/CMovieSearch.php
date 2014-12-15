<?php
/**
 * Sök- och sorteringsfunktioner för filmdatabasen.
 *
 */
class CMovieSearch {
 
  // Properties and methods


    /**
     * Constructor
     *
     */
    public function __construct($db) {
        $this->db = $db;
    }


    /**
    * Function to create links for sorting
    *
    * @param string $column the name of the database column to sort by
    * @return string with links to order by column.
    */
    protected function orderby($column) {
        return "<span class='orderby'>
        <a href='?orderby={$column}&amp;order=asc'>&darr;</a>
        <a href='?orderby={$column}&amp;order=desc'>&uarr;</a></span>";
    }
 
    /**
    * Use the current querystring as base, modify it according to $options and return the modified query string.
    *
    * @param array $options to set/change.
    * @param string $prepend this to the resulting query string
    * @return string with an updated query string.
    */
    protected function getQueryString($options, $prepend='?') {
        // parse query string into array
        $query = array();
        parse_str($_SERVER['QUERY_STRING'], $query);
 
        // Modify the existing query string with new options
        $query = array_merge($query, $options);
 
        // Return the modified querystring
        return $prepend . http_build_query($query);
    }

    /**
    * Create links for hits per page.
    *
    * @param array $hits a list of hits-options to display.
    * @return string as a link to this page.
    */
    public function getHitsPerPage($hits) {
        $nav = "Träffar per sida: ";
        foreach($hits AS $val) {
            $nav .= "<a href='" . $this->getQueryString(array('hits' => $val)) . "'>$val</a>";
        }  
        return $nav;
    }
 
    /**
    * Create navigation among pages.
    *
    * @param integer $hits per page.
    * @param integer $page current page.
    * @param integer $max number of pages. 
    * @param integer $min is the first page number, usually 0 or 1. 
    * @return string as a link to this page.
    */
    function getPageNavigation($hits, $page, $max, $min=1) {
        $nav  = "<a href='" . $this->getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> ";
        $nav .= "<a href='" . $this->getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> ";
 
        for($i=$min; $i<=$max; $i++) {
            $nav .= "<a href='" . $this->getQueryString(array('page' => $i)) . "'>$i</a> ";
        }
 
        $nav .= "<a href='" . $this->getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> ";
        $nav .= "<a href='" . $this->getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> ";
        return $nav;
    }
}    
