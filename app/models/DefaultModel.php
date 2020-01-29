<?php

class DefaultModel extends Model {
    public function modelTest() {
        $this->db->connect();
        $query = $this->db->select('members')->where([
            'id' => 2
        ], 'or')->execute();
        echo '<pre>';
        print_r($query->fetchAll());
    }
}