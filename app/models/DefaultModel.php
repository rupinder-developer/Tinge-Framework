<?php

class DefaultModel extends Model {
    public function modelTest() {
        $this->db->connect();
        $query = $this->db->update('members', [
            'full_name' => 'Admin' 
        ])->where([
            'id' => 1
        ])->execute();
        echo $query->rowCount();

        echo '<pre>';
        // print_r($query->fetchAll());
    }
}