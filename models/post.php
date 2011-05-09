<?php
class Post {
  public $id, $slug, $title, $excerpt, $content, $css, $javascript, $date;
  
  public function __construct($info = null) {
    if (is_array($info) === true) {
      foreach ($info as $key => $val) {
        $this->$key = $val;
      }
    }
  }
  
  public function get_id() {
    echo isset($this->id) ? $this->id : '';
  }
  
  public function get_slug() {
    echo isset($this->slug) ? $this->slug : '';
  }
  
  public function get_title() {
    echo isset($this->title) ? $this->title : '';
  }
  
  public function get_excerpt() {
    echo isset($this->excerpt) ? $this->excerpt : '';
  }
  
  public function get_content() {
    echo isset($this->content) ? $this->content : '';
  }
  
  public function get_css() {
    echo isset($this->css) ? $this->css : '';
  }
  
  public function get_javascript() {
    echo isset($this->javascript) ? $this->javascript : '';
  }
  
  public function get_date() {
    echo isset($this->date) ? $this->date : '';
  }
  
  /*
   * Functions for finding posts. Access these with Post::function_name
   */
  
  public function all($options = array(), $select = '*') {
    global $db;
    
    $default = array('limit' => 20);
    $options = array_merge($default, $options);
    
    $query = $db->prepare('SELECT ' . $select . ' FROM posts ORDER BY id DESC LIMIT :limit');
    
    foreach ($options as $key => &$val) {
      $query->bindParam(':' . $key, $val, (is_integer($val) ? PDO::PARAM_INT : PDO::PARAM_STR));
    }
    
    $query->execute();
    if ($query->rowCount() == 0) { return false; }
    
    $posts = array();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $post) {
      $posts[] = new Post($post);
    }
    return $posts;
  }
  
  public function find($id) {
    global $db;
    
    if (is_int($id) === true) {
      $query = $db->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
      $query->execute(array($id));
    } else {
      $query = $db->prepare('SELECT * FROM posts WHERE slug = ? LIMIT 1');
      $query->execute(array($id));
    }
    return ($query->rowCount() == 0) ? false : new Post($query->fetch(PDO::FETCH_ASSOC));
  }
  
  public function update($data) {
    global $db;
    
    if (isset($this->id) === false) { return false; }
    
    foreach ($data as $key => $value) {
      if ($key != 'id' && isset($this->$key) === true) { $this->$key = $value; }
    }
    
    $query = $db->prepare('UPDATE posts SET slug=?, title=?, excerpt=?, content=?, css=?, javascript=?, date=NOW() WHERE id = ?');
    return $query->execute(array($this->slug, $this->title, $this->excerpt, $this->content, $this->css, $this->javascript, $this->id));
  }
  
  public function create($data) {
    global $db;
    $default = array('slug' => '', 'title' => '', 'excerpt' => '', 'content' => '', 'css' => '', 'javascript' => '');
    foreach (array_diff_key($data, $default) as $key => $value) { unset($data[$key]); }
    $data = array_merge($default, $data);
    $query = $db->prepare('INSERT INTO posts (slug, title, excerpt, content, css, javascript, date) VALUES (?, ?, ?, ?, ?, ?, NOW())');
    return $query->execute(array_values($data));
  }
}