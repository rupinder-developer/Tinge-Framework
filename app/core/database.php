<?php
/**
 * Database.
 * @author    Rupinder Singh <rupinder.developer@gmail.com>
 * @copyright 2020
 */
namespace MySQL;

require_once dirname(__FILE__).'/config.php';

class Database {
    /**
     * Private Variables
     *
     * @var Connection $handler
     * @var array      $bindParams
     * @var array      $where
     * 
     * @var string     $orderBy
     * @var string     $select
     * @var string     $cols
     * @var string     $limit
     */
    private $bindParams;
    private $handler;
    private $orderBy;
    private $select;
    private $limit;
    private $where;
    private $cols;

    function __construct() {
        // Initialization
        $this->bindParams = [];
        $this->orderBy    = '';
        $this->where      = [];
        $this->cols       = '*';
        $this->limit      = '';
    }//end __construct()

    public function __destruct() {
        $this->handler = null;
    }//end __destruct()

    public function connect() {
        try {
            // PDO Connection
            $this->handler = new \PDO('mysql:host='.HOSTNAME.';dbname='.DB_NAME, USERNAME, PASSWORD);
            $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->handler;
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }//end connect()

    public function select($tableName) {
        $this->select = $tableName;
        return $this;
    }//end select()

    public function project($cols) {
        $this->cols = $cols;
        return $this;
    }//end project()

    public function where($conditions, $glue = 'AND') {
        $temp = [];
        foreach($conditions as $key => $value) {
            array_push($temp, "{$key}=:where_{$key}");
            $this->bindParams[":where_{$key}"] = $value;
        }
        array_push($this->where, ' ('.implode(" {$glue} ", $temp).')');
        return $this;
    }//end where()

    public function in($cols, $array) {
        $temp = [];
        foreach($array as $value) {
            $uniqid = uniqid();
            array_push($temp, ":{$uniqid}");
            $this->bindParams[":{$uniqid}"] = $value;
        }
        array_push($this->where, "({$cols} IN (".implode(', ', $temp)."))");
        return $this;
    }//end in()

    public function nin($cols, $array) {
        $temp = [];
        foreach($array as $value) {
            $uniqid = uniqid();
            array_push($temp, ":{$uniqid}");
            $this->bindParams[":{$uniqid}"] = $value;
        }
        array_push($this->where, "({$cols} NOT IN (".implode(', ', $temp)."))");
        return $this;
    }//end nin()

    public function orderBy($cols, $sortBy = '') {
        $this->orderBy = " ORDER BY {$cols} {$sortBy} ";
        return $this;
    }//end orderBy()

    public function limit($limit, $offset = null) {
        $this->limit = ' LIMIT '.$limit.($offset?', '.$offset:'').' ';
        return $this;
    }//end limit()

    public function execute() {
        if (count($this->where) > 0) {
            $where = ' WHERE '.implode(' AND ',$this->where);
        } else {
            $where = '';
        }
        echo 'SELECT '.$this->cols.' FROM '.$this->select.$where.$this->orderBy.$this->limit;
        $query = $this->handler->prepare('SELECT '.$this->cols.' FROM '.$this->select.$where.$this->orderBy.$this->limit);
        $query->execute($this->bindParams);
        return $query;

        // Cleaning up resources
        $this->bindParams = [];
        $this->orderBy    = '';
        $this->where      = [];
        $this->cols       = '*';
        $this->limit      = '';
    }//end execute()

    public function insert($tableName, $values) {
        $col        = [];
        $val        = [];
        $bindParams = [];
        foreach($values as $key => $value) {
            array_push($col, $key);
            array_push($val, ":{$key}");
            $bindParams[":{$key}"] = $value;
        }
        $query = $this->handler->prepare("INSERT INTO {$tableName}(".implode(', ', $col).") VALUES(".implode(', ', $val).")");
        return $query->execute($bindParams);
    }//end insert()

    public function query($sql) {
        return $this->handler->prepare($sql);
    }//end query()

    /**
     * installSQL() Function.
     *
     * Working :
     *
     * 1. Example 1st : $obj->installSQL('path/file_name.sql');
     *    Output      : Install SQL file to your connected database
     *
     * @param  $url
     * @return boolean
     */
    public function installSQL($url) {
        if ($this->connect() === true) {
            $stmt = file_get_contents($url);
            $query = $this->handler->prepare($stmt);
            if ($query->execute() === true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }//end installSQL()

    /**
     * dropTables() Function.
     *
     * @return void
     */
    public function dropTables() {
        if ($this->connect() === true) {
            $sql = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = "BASE TABLE" AND ';
            $sql .= 'TABLE_SCHEMA=:dbName';
            $query = $this->handler->prepare($sql);
            $db = DB_NAME;
            $query->bindParam(':dbName', $db);
            $query->execute();
            $array = $query->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($array as $table) {
                $queryMain = $this->handler->prepare('DROP TABLE ' . $table['TABLE_NAME']);
                $queryMain->execute();
            }
        }
    }//end dropTables()

    /**
     * scanTables() Function.
     * This function return the list of tables present in your database.
     *
     * @return array
     */
    public function scanTables() {
        if ($this->connect() === true) {
            $sql = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = "BASE TABLE" AND ';
            $sql .= 'TABLE_SCHEMA=:dbName';
            $query = $this->handler->prepare($sql);
            $db = DB_NAME;
            $query->bindParam(':dbName', $db);
            $query->execute();
            $array = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $array;
        }
    }//end scanTables()
}//end class
