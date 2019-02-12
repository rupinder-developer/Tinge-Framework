<?php

class DefaultModel extends Model {
    public function modelTest() {
        echo 'Default Model -> modelTest()';
        /*
         * Database Usage
         * > $this->db->connect();
         * > $this->db->scanTables();
         * 
         * Helpers Usage
         * > $obj = $this->helper('helper_name');
         * > $obj->func_name();  
        */
    }
}