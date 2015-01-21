<?php
/**
 * Skapar html och sköter paginering för filmdatabasen.
 *
 */
class CVinylHTML extends CVinylSearch {
    
       function __construct() {
        global $kabyssen;
        $this->db = new CDatabase($kabyssen['database']);
   }
 
  // Properties and methods
        private $sqlOrig = '
        SELECT 
        M.*,
        GROUP_CONCAT(G.name) AS genre
        FROM music AS M
        LEFT OUTER JOIN music2genre AS M2G
        ON M.id = M2G.idMusic
        INNER JOIN genre AS G
        ON M2G.idGenre = G.id
        ';
        
        private $groupby  = ' GROUP BY M.id';
        private $params=array();
        private $where = null;
        private $hits = null;

// Get all genres that are active
    public function GetActiveGenres($genre) {
        $sql = '
        SELECT DISTINCT G.name
        FROM genre AS G
        INNER JOIN music2genre AS M2G
        ON G.id = M2G.idGenre
        ';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $genres = null;
        foreach($res as $val) {
            if($val->name == $genre) {
                $genres .= "<div class='pagenum_current noblock'>$val->name</div> ";
            }
            else {
                $genres .= "<div class='noblock'><a href='" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a></div> ";
            }
        }
        return $genres;
    }  
    
    public function GetActiveGenres_Start() {
        $sql = '
        SELECT DISTINCT G.name
        FROM genre AS G
        INNER JOIN music2genre AS M2G
        ON G.id = M2G.idGenre
        ';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $genres = null;
        foreach($res as $val) {
                $genres .= "<div class='noblock'><strong>{$val->name}</strong></div> | ";
            }
        return $genres;
    }     


// Prepare the query based on incoming arguments for music table
    public function PrepareQuery($artist, $title, $genre, $hits, $page, $year1, $year2, $orderby, $order) {
        $this->hits = $hits;
        $this->sqlOrig;
        $this->groupby;
        $limit    = null;
        $sort     = " ORDER BY $orderby $order";


        
// Select by artist
        if($artist) {
            $this->where .= ' AND artist LIKE ?';
            $this->params[] = $artist;
        }         
        
// Select by title
        if($title) {
            $this->where .= ' AND title LIKE ?';
            $this->params[] = $title;
        } 

// Select by year
        if($year1) {
            $this->where .= ' AND year >= ?';
            $this->params[] = $year1;
        } 
        if($year2) {
            $this->where .= ' AND year <= ?';
            $this->params[] = $year2;
        } 

// Select by genre
        if($genre) {
            $this->where .= ' AND G.name = ?';
            $this->params[] = $genre;
        } 
// Pagination
        if($hits && $page) {
            $limit = " LIMIT $hits OFFSET " . (($page - 1) * $hits);
        }

        // Complete the sql statement
        $where = $this->where ? " WHERE 1 {$this->where}" : null;
        $sql = $this->sqlOrig . $where . $this->groupby . $sort . $limit;
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $this->params);

        // Put results into a HTML-table
        $tr = "<tr><th>Rad</th><th>Artist " . $this->orderby('artist') . "</th><th>Bild</th><th>Album " . $this->orderby('title') . "</th><th>Info</th><th>År " . $this->orderby('year') . "</th><th>Genre</th><th>Pris</th></tr>";
        $rad = 0;
        foreach($res AS $key => $val) {
            $rad++;
            $info = $this->Truncate($val->beskrivning, $val->id);
            $tr .= "<tr><td>{$rad}</td><td>{$val->artist}</td><td><a href='info_vinyl.php?id={$val->id}'><img src='img.php?src={$val->image}&amp;width=100' alt='{$val->title}' /></a></td><td>{$val->title}</td><td>{$info}</td><td>{$val->year}</td>
            <td>{$val->genre}</td><td>{$val->pris}</td></tr>";
        }
     return $tr;   
    }    

        public function Truncate($data, $id) {
        // strip tags to avoid breaking any html
        $string = strip_tags($data);

        if (strlen($string) > 50) {

            // truncate string
            $stringCut = substr($string, 0, 50);

            // make sure it ends in a word 
            $string = substr($stringCut, 0, strrpos($stringCut, ' '))."... <a href='info_vinyl.php?id={$id}'>Läs mer &gt;&gt;</a>"; 
        }
        return $string;
    }


// Get max pages for current query, for navigation
    public function GetMaxPages() {
        $sqlOrig = $this->sqlOrig;
        $where = $this->where;
        $groupby = $this->groupby;
        $sql = "
        SELECT
        COUNT(id) AS rows
        FROM 
        (
        $sqlOrig $where $groupby
        ) AS music
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $this->params);
        $rows = $res[0]->rows;
        $max = ceil($rows / $this->hits);  
        return array($max, $rows);
    } 

    public function GetHTML_SearchForm($title, $advanced) {
        $html = "</ul></nav></div><div class='search'><div class='left'><form>
            Sök album: <input placeholder='t.ex. %Purple%' type='search' name='title' value='{$title}'>
        </form></div>";

        if(!$advanced) {
            $html .= "<div class='right'><a href='" . $this->getQueryString(array('advanced' => true)) . "'>Avancerad sökning...</a></div></div>";
        } else {  
            $html .= "<div class='right'><a href='" . $this->getQueryString(array('advanced' => null)) . "'>Enkel sökning...</a></div></div>";
        }    
        return $html;
    }
    
    public function GetHTML_form($artist, $genre, $genres, $hits, $year1, $year2) {
        $html = "<article class='rowspace no-border margin-top'><form><fieldset>
        <input class='pagenum_current' type=hidden name=genre value='{$genre}'/>
        <input type=hidden name=hits value='{$hits}'/>
        <input type=hidden name=page value='1'/>
        <input type=hidden name=advanced value='1'/>
        <p>
            <label><strong>Sök artist</strong> (använd % som wildcard): <input placeholder='t.ex. %p%' type='search' name='artist' value='{$artist}'/></label>
        <p>
            <label><strong>Välj genre:</strong></label> {$genres}
        <p>
            <label><strong>Utgiven mellan åren:</strong> 
            <input placeholder='från' type='text' name='year1' value='{$year1}'/></label>
            - 
            <label><input placeholder='till' type='text' name='year2' value='{$year2}'/></label>
        <p>
            <input type='submit' name='submit' value='Sök'/>
        </p>
        </fieldset></form></article>";
        return $html;
    }
    
    public function GetHTML_table($rows, $hitsPerPage, $tr, $navigatePage) {
        $html = "<div class='dbtable'>
        <div class='left'><form><input type='submit' value='Nollställ sidan'>
        </form></div>
        <div class='rows'>{$rows} träffar. {$hitsPerPage}</div>
        <table>
        {$tr}
        </table>
        <div class='pages'>{$navigatePage}</div>
        </div>";
        return $html;
    }
    
    
 
}
