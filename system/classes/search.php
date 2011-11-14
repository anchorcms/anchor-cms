<?php

class Search extends Template {

    //  Get the results, based on search parameter
    public function getResults() {
        $this->db = new Database($this->config['database']);
        return $this->db->query("select * from pages where `content` like '%" . $this->url[1] . "%'");
    }
}