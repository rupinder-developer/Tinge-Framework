<?php

class DefaultModel extends Model {
    public function modelTest() {
        $this->db->connect();
        $query = $this->db->select('member')->execute();


        echo '<pre>';
        print_r($query->fetchAll());
    }
}