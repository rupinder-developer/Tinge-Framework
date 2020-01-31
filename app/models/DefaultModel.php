<?php

class DefaultModel extends Model {
    public function modelTest() {
        $this->db->connect();
        $query = $this->db->delete('orders')->where([
            'id' => 1
        ])->execute();
        echo $query->rowCount();

        echo '<pre>';
        // print_r($query->fetchAll());
    }
}