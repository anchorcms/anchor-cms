<?php
class Post {
  public $id, $slug, $title, $exerpt, $content, $css, $javascript, $date;
  
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
  
  public function all($options = array()) {
    global $db;
    
    $default = array('order_by' => 'id', 'limit' => 20);
    $options = array_merge($default, $options);
    
    $query = $db->prepare('SELECT * FROM posts ORDER BY :order_by LIMIT :limit');
    
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
}