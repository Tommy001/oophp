<?php

class CTextFIlter {
    
       function __construct() {

   }    
   
    /**
    * Medlemsvariabler
    *        
    **/   


    
    /** Call each filter.
    *
    * @param string $text the text to filter.
    * @param string $filter as comma separated list of filter.
    * @return string the formatted text.
    **/
    public function doFilter($text, $filter) {
        if($filter) {
    // Define all valid filters with their callback function.
            $valid = array(
                'bbcode'   => 'bbcode2html',
                'link'     => 'make_clickable',
                'markdown' => 'markdown',
                'nl2br'    => 'nl2br',
                'typo'    => 'smartyPantsTypographer',
                );
 
  // Make an array of the comma separated string $filter
            $filter = rtrim($filter, ','); // ta bort kommatecken på slutet
            $filters = preg_replace('/\s/', '', explode(',', $filter));
 
  // For each filter, call its function with the $text as parameter.
            foreach($filters as $func) {
                if(isset($valid[$func])) {
                    $text = self::$valid[$func]($text);
                } else {
                    throw new Exception("The filter '$filter' is not a valid filter string.");
                }
            }    
        }
        return $text;
    }

    /**
    * Helper, BBCode formatting converting to HTML.
    *
    * @param string text The text to be converted.
    * @returns string the formatted text.
    * p tag added combined with CSS to add indentation
    */
    public function bbcode2html($text) {
        $search = array(
            '/\[b\](.*?)\[\/b\]/is',
            '/\[big\](.*?)\[\/big\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[img\](https?.*?)\[\/img\]/is',
            '/\[url\](https?.*?)\[\/url\]/is',
            '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
        );   
        $replace = array(
            '<strong>$1</strong>',
            '<strong style=\'font-size:1.3em\'>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<img src="$1" />',
            '<a href="$1">$1</a>',
            '<a href="$1">$2</a>'
        );     
        return preg_replace($search, $replace, $text);
    }

    /**
    * Make clickable links from URLs in text.
    *
    * @param string $text the text that should be formatted.
    * @return string with formatted anchors.
    */
    public function make_clickable($text) {
        return preg_replace_callback(
            '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            create_function(
                '$matches',
                'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
            ),
            $text
            );
    }

    /**
     * Make \n to <br>
     * 
     * @param string $text to be formatted.
     * @return string as the formatted html-text.
     */
    public function nl2br($text) {
        return nl2br($text);
    }
         

    /**
     * Format text according to Markdown syntax.
     *
     * @param string $text the text that should be formatted.
     *
     * @return string as the formatted html-text.
     *
     * @link http://dbwebb.se/coachen/skriv-for-webben-med-markdown-och-formattera-till-html-med-php
     */
     function markdown($text) {
         require_once(__DIR__ . '/php-markdown/Michelf/Markdown.inc.php');
         require_once(__DIR__ . '/php-markdown/Michelf/MarkdownExtra.inc.php');
    
         return \Michelf\MarkdownExtra::defaultTransform($text);
     }
     
     /**
     * Format text according to PHP SmartyPants Typographer.
     *
     * @param string $text the text that should be formatted.
     * @return string as the formatted html-text.
     */
     function smartyPantsTypographer($text) {
         require_once(__DIR__ . '/php-smartypants-typographer/smartypants.php');
         return SmartyPants($text);
}
}    
