<?php
//  My database class. Uses MySQLi.

class Database {
    protected $_db,
              $_query,
              $_query_count = 0,
              $_info;

    public $_statement;

    //  Connect to the database.
    public function __construct($info = '') {
        
        //  host, username, password, database name
        if($info) {
            return $this->setup($info);
        }
        
        return;
    }
    
    //  The _real_ setup file. Used for overwriting shi'.
    public function setup($info) {
        $this->_info = $info;
        $this->_db = new mysqli($info['host'], $info['username'], $info['password'], $info['name']);
        
        return $this;
    }
    
    /**
     *      fetch(what, from, where, limit, order by);
     *
     *      what: a string listing what to retrieve. (optional)
     *      from: string the table name.
     *      where: key/value array saying what to fetch
     *      limit: a string/number limiting the amount. (0,30; 1; 10, etc.)
     *      order: a string saying what to order by
     *      or: a boolean object saying whether to use "or" or "and"
     *
     *      returns a mysqli object.
     */
    public function fetch($what = '', $from, $where = '', $limit = '', $order = '', $or = false) {

        $what = ((!empty($what)) ? $what : '*');
        $from = ((strpos($from, '`') == false) ? '`' . $from . '`': $from);
        $limit = ((!empty($limit)) ? ' limit ' . $limit : ''); 
        $order = ((!empty($order)) ? ' order by ' . $order : ''); 
        
        //  Build the filter
        $cond = '';
        if($where != '') {
        $cond .= ' where ';
            $i = 1;
            $total = count($where);
            foreach($where as $key => $value) {
                $cond .= '`' . $key . '` = ' . "'" . $value . "'";
                if($i != $total && $i < $total) $cond .= ' ' . ($or == true ? 'or' : 'and') . ' ';
                $i++;
            }
        }
        $cond .= $limit . $order;
    
        $this->_statement = "select " . $what . " from " . $from . $cond;
        $this->_query = $this->_db->query($this->_statement);
        
        //  Loop through the query
        $row = array();

        if($this->_query != false) {
    		while($f = $this->_query->fetch_object()) {
    			$row[] = $f;
            }
            //  Get a row count
            $this->_rows = count($row);
        }
        
        //  And return it
		return $row;
    }
    
    
    /**
     *      insert(where, what);
     *
     *      where: a string, saying what table to insert the data in.
     *      what: an array with the information to insert.
     *
     *      returns true or false.
     */
    public function insert($where, $what) {
        //insert into `links` values(null, 'test', 'test', 'http://google.com', null);
        
        $values = '';
        $i = 1;
        $total = count($what);
        foreach($what as $key => $value) {
            
            //  Check if the value is empty
            if((is_string($value)) && ($value == '')) {
                $value = 'null';
            } else {
                $value = "'" . $value . "'";
            }
            
            $values .= $value;
            
            //  And add a comma
            if($i < $total) $values .= ', ';
            
            $i++;
        }
        
        $this->_statement = 'insert into `' . $where . '` values(' . $values . ')';
        
        //  MUST be the same length as the table it's inserting into at the moment.
        return $this->query($this->_statement);
    }


    /**
     *      remove(where, id);
     *
     *      where: the table name.
     *      id: the id/column name of the table.
     *      col: optional (if you're not using an ID).
     *
     *      returns a mysqli object.
     */
    
    public function remove($where, $id, $col = '') {
        
        $col = (is_array($id) ? $id[0] : ($col != '' ? $col : 'id'));
        $id = (is_array($id) ? $id[1] : $id);
        
        $this->_statement = 'delete from `' . $where . '` where `' . $col . '` = ' . $id;

        return $this->query($this->_statement);
    }

    /**
     *      clear(table);
     *
     *      where: the table name.
     *
     *      Empties the contents of a table. Returns a mysqli object.
     */
    
    public function clear($table) {
        return $this->query('truncate table `' . $table . '`');
    }
    
    /**
     *      query($statement, $return_a_fetched_object [true/false])
     *
     *      aliases the mysqli query object, and adds a counter.
     */
    public function query($statement, $object = false) {
        //  Add the count up
        $this->_query_count++;
        $this->_statement = $statement;
            
        //  And query, but only if we've got a connection.
        $query = $this->_db->query($this->_statement);
        
        if($object === true && !empty($query)) {
        	$row = array();
        	while($a = $query->fetch_object()) {
        		$row[] = $a;
        	}
        	
        	return $row;
        }
        
        return $query;
    }


    /**
     *      unfilter($what)
     *
     *      decode the text from the database; opposite of filter().
     */
        
    public function unfilter($what) {
        return html_entity_decode(stripslashes($what));
    }


    /**
     *      get_count()
     *
     *      gets the total number of queries (returns a number).
     */
        
    public function get_count() {
        return $this->_query_count;
    }

    /**
     *      filter($what, $type = [$_GET or $_POST])
     *
     *      filter a string for safe database insertion.
     */
        
    public function filter($what, $type = 'get') {
        if(isset($this->_db)) {
            return htmlentities($this->_db->real_escape_string($what));
        } else {
            $type = ($type == 'get' ? INPUT_GET : INPUT_POST);
            return htmlentities(filter_input($type, $what, FILTER_SANITIZE_STRING));
        }
    }

    
    /**
     *      Get the number of rows affected
     */
    public function rows($statement = '') {
        //  return $this->_rows;
        return $this->_query->num_rows;
    }
}