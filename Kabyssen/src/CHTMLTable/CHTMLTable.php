<?php
/**
 * Skapar html och sköter paginering för filmdatabasen.
 *
 */
class CHTMLTable extends CMovieSearch {
    
       function __construct($db) {
               $this->db = $db;
   }
 
  // Properties and methods
        private $sqlOrig = '
        SELECT 
        M.*,
        GROUP_CONCAT(G.name) AS genre
        FROM op_k4_Movie AS M
        LEFT OUTER JOIN op_k4_Movie2Genre AS M2G
        ON M.id = M2G.idMovie
        INNER JOIN op_k4_Genre AS G
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
        FROM op_k4_Genre AS G
        INNER JOIN op_k4_Movie2Genre AS M2G
        ON G.id = M2G.idGenre
        ';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $genres = null;
        foreach($res as $val) {
            if($val->name == $genre) {
                $genres .= "$val->name ";
            }
            else {
                $genres .= "<a href='" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
            }
        }
        return $genres;
    }    


// Prepare the query based on incoming arguments
    public function PrepareQuery($title, $genre, $hits, $page, $year1, $year2, $orderby, $order) {
        $this->hits = $hits;
        $this->sqlOrig;
        $this->groupby;
        $limit    = null;
        $sort     = " ORDER BY $orderby $order";


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
        $tr = "<tr><th>Rad</th><th>Id " . $this->orderby('id') . "</th><th>Bild</th><th>Titel " . $this->orderby('title') . "</th><th>År " . $this->orderby('year') . "</th><th>Genre</th></tr>";
        $rad = 0;
        foreach($res AS $key => $val) {
            $rad++;
            $tr .= "<tr><td>{$rad}</td><td>{$val->id}</td><td><img width='80' height='40' src='{$val->image}' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->YEAR}</td><td>{$val->genre}</td></tr>";
        }
     return $tr;   
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
        ) AS op_k4_movie
        ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $this->params);
        $rows = $res[0]->rows;
        $max = ceil($rows / $this->hits);  
        return array($max, $rows);
    } 
    
    public function GetHTML_form($title, $genre, $genres, $hits, $year1, $year2) {
        $html = "<article class='me'><form><fieldset>
        <legend>Sök</legend>
        <input type=hidden name=genre value='{$genre}'/>
        <input type=hidden name=hits value='{$hits}'/>
        <input type=hidden name=page value='1'/>
        <p>
            <label>Titel (använd % som ett eller flera valfria tecken): <input type='search' class='skugga' name='title' value='{$title}'/></label>
        <p>
            <label>Välj genre:</label> {$genres}</p>
        <p>
            <label>Skapad mellan åren: 
            <input type='text' class='skugga' name='year1' value='{$year1}'/></label>
            - 
            <label><input type='text' class='skugga' name='year2' value='{$year2}'/></label>
        <p>
            <input type='submit' name='submit' value='Sök'/></p>
        <p>
            <a href='?'>Visa alla</a>
        </p>
        </fieldset></form></article>";
        return $html;
    }
    
    public function GetHTML_table($rows, $hitsPerPage, $tr, $navigatePage) {
        $html = "<div class='dbtable'>
        <div class='rows'>{$rows} träffar. {$hitsPerPage}</div>
        <table>
        {$tr}
        </table>
        <div class='pages'>{$navigatePage}</div>
        </div>";
        return $html;
    }
}
