<?php
/**
 * A dice with images as graphical representation.
 *
 */
class CDiceGameBoard extends CDice {
 
  // Properties and methods extending or overriding base class

    const FACES = 6;  
    /**
     * Constructor
     *
     */
    public function __construct() {
         parent::__construct(self::FACES);
    }
      /**
   * Hämta spelplanen.
   *
   */
    public function GetGameBoard() {
        $html = "<div id='dice-container'>";
        $html .= "<div class='dicegame'><a href='?roll'><ul class='dice'>";
        foreach($this->rolls as $val) {
            $html .= "<li class='dice-$val'></li>";
        }
        $html .= "</ul></a></div>";
        $html .= "<div class='diceround'>
        <h2>".$this->total."<br>poäng</h2>
        </div>
        <a href='?save'><div class='dicesave'>
        <h1><br>".$this->allround."</h1>
        </div></a>
        <div class='diceroll'>
        <h2>".$this->roll."<br>kast</h2>
        </div>
        <a href='?init'><div class='diceinit'>
        </div></a>        
        </div>";
        return $html;
    }

  
 
   
}
