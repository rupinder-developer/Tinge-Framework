<?php

class Request {
    
    public function has($data, $method, $condition = NULL) {
        if (is_array($data)) {
            if($condition == 'OR') {
                // OR Condition
                $flag = false;
                foreach($data as $item) {
                    if (isset($method[$item]) && !empty($method[$item])) {
                        $flag = true;
                        break;
                    } else {
                        continue;
                    }
                }
                return $flag;
            } else { 
                // AND Condition
                $flag = true;
                foreach($data as $item) {
                    if (isset($method[$item]) && !empty($method[$item])) {
                        continue;
                    } else {
                        $flag = false;
                        break;
                    }
                }
                return $flag;
            }
        } else {
            if (isset($method[$data]) && !empty($method[$data])) {
                return true;
            } else {
                return false;
            }
        } 
    }
}