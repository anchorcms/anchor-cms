<?php
class Post {
  public function listAll($options = array()) {
    global $db;
    
    $default = array('order_by' => 'id', 'limit' => 20);
    $options = array_merge($default, $options);
    
    $query = $db->prepare('SELECT * FROM posts ORDER BY :order_by LIMIT :limit');
    
    foreach ($options as $key => &$val) {
      $query->bindParam(':' . $key, $val, (is_integer($val) ? PDO::PARAM_INT : PDO::PARAM_STR));
    }
    
    $query->execute();
    return ($query->rowCount() > 0) ? $query->fetchAll(PDO::FETCH_ASSOC) : array();
  }
  
  public function find($id) {
    global $db;
    
    $query = $db->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
    $query->execute(array($id));
    return ($query->rowCount() > 0) ? $query->fetch(PDO::FETCH_ASSOC) : false;
  }
}