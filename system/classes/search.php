<?php

class Search extends Template {

	public $term;

    //  Get the results, based on search parameter
    public function getResults() {
    	global $config;
        $this->db = new Database($config['database']);
        
        $term = $this->db->filter($this->url[1]);
        
        $pages = (array) $this->db->query("select * from pages where `content` like '%$term%' or `title` like '%$term%'", true);
        $posts = (array) $this->db->query("select * from posts where `html` like '%$term%' or `title` like '%$term%'", true);
        
        for($i = 0; $i <= count($posts) - 1; $i++) {
        
        	//  Set the content and slug
        	$posts[$i]->content = $posts[$i]->html;
        	$posts[$i]->slug = 'articles/' . $posts[$i]->slug;
        	
        	//  Remove the link to the HTML to make the array-object-array thing smaller
        	unset($posts[$i]->html);
        }
        
        if(!empty($posts) || !empty($pages)) {
        	return (object) array_merge($pages, $posts);
        }
        
        return false;
    }
    
    /**
     *	Get the current search value
     */
    public function value($what) {
    	//  If you've got notice-level warnings on in PHP, this throws errors
    	if(isset($_POST[$what])) {
    		return html_entity_decode(Database::filter($what));
    	} else {
    		return (isset($this->url[1]) && $this->getSlug() == 'search') ? urldecode($this->url[1]) : '';
    	}
    }
}