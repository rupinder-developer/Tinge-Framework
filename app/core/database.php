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
     * @var string     $select
     * @var array      $where
     * @var string     $cols
     */
    private $bindParams;
    private $handler;
    private $select;
    private $where;
    private $cols;

    function __construct() {
        // Initialization
        $this->bindParams = [];
        $this->where = [];
        $this->cols = '*';
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

    public function cols($cols) {
        $this->cols = $cols;
        return $this;
    }//end cols()

    public function where($conditions, $glue = 'AND') {
        $temp = [];
        foreach($conditions as $key => $value) {
            array_push($temp, "{$key}=:where_{$key}");
            $this->bindParams[":where_{$key}"] = $value;
        }
        array_push($this->where, ' ('.implode(" {$glue} ", $temp).')');
        return $this;
    }//end where()

    public function execute() {
        if (count($this->where) > 0) {
            $where = ' WHERE '.implode(' AND ',$this->where);
        } else {
            $where = '';
        }
        echo 'SELECT '.$this->cols.' FROM '.$this->select.$where;

        // Cleaning up resources
        $this->bindParams = [];
        $this->where = [];
        $this->cols = '*';
    }//end execute()

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
