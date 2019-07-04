<?php
/**
 * Database.
 * @author    Rupinder Singh <rupinder.developer@gmail.com>
 * @copyright 2018
 */
namespace MySQL;

require_once dirname(__FILE__).'/config.php';

class Database
{
    /**
     * Private Variables
     *
     * @var Connection $handler
     */
    private $handler;

    /**
     * This functions help to build the connection with Database.
     *
     * @return mixed
     */
    public function connect()
    {
        try {
            $this->handler = new \PDO('mysql:host='.HOSTNAME.';dbname='.DB_NAME, USERNAME, PASSWORD);
            $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }//end connect()

    /**
     * Clean up resources here __destruct()
     *
     * @return null
     */
    public function __destruct()
    {
        $this->handler = null;
    }//end __destruct()

    /**
     * select() Function.
     *
     * @param string $table
     * @param array  $condition
     * @param string $glue
     *
     * @return array
     */
    public function select($select, $condition = [], $glue = 'AND')
    {
        if (is_array($select)) {
            $sql = 'SELECT '.$select[0].' FROM '.$select[1];
        } else {
            $sql = 'SELECT * FROM '.$select;
        }

        if (empty($condition)) {
            $query = $this->handler->prepare($sql);
        } else {
            foreach ($condition as $key => $value) {
                $pieces[] = $this->binary.' '.$key.'=:'.$key;
            }
            $where = ' WHERE '.implode(' '.trim($glue).' ', $pieces);
            $query = $this->handler->prepare($sql.$where);
            foreach ($condition as $key => &$value) {
                $query->bindParam(':'.$key, $value);
            }
        }

        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }//end select()

    /**
     * join() Function.
     *
     * @param mixed  $select
     * @param mixed  $join
     * @param string $on
     * @param array  $condition
     * @param string $glue
     *
     * @return array
     */
    public function join($select, $join, $on, $condition = [], $glue = 'AND')
    {
        if (is_array($select)) {
            $select = 'SELECT '.$select[0].' FROM '.$select[1];
        } else {
            $select = 'SELECT * FROM '.$select;
        }

        if (is_array($join)) {
            $join = ' '.$join[1].' JOIN '.$join[0].' ON '.$on;
        } else {
            $join = ' JOIN '.$join.' ON '.$on;
        }

        if (empty($condition)) {
            $where = '';
        } else {
            foreach ($condition as $key => $value) {
                $pieces[] = $this->binary.' '.$key.'=:'.str_replace('.', '_', $key);
            }
            $where = ' WHERE '.implode(' '.trim($glue).' ', $pieces);
        }
        $query = $this->handler->prepare($select.$join.$where);
        foreach ($condition as $key => &$value) {
            $key = str_replace('.', '_', $key);
            $query->bindParam(':'.$key, $value);
        }

        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }//end join()

    /**
     * insert() Function.
     *
     * @param string $table
     * @param array  $values
     *
     * @return boolean
     */
    public function insert($table, $values)
    {
        foreach ($values as $key => $value) {
            $col[] = $key;
            $val[] = ':'.$key;
        }
        $cols  = implode(',', $col);
        $vals  = implode(', ', $val);
        $sql   = 'INSERT INTO '.$table.'('.$cols.') VALUES('.$vals.')';
        $query = $this->handler->prepare($sql);
        foreach ($values as $key => &$value) {
            $query->bindParam(':'.$key, $value);
        }
        $result = $query->execute();
        return $result;
    }//end insert()

    /**
     * update() Function.
     *
     * @param string $table
     * @param array  $values
     * @param array  $condition
     * @param string $glue
     *
     * @return boolean
     */
    public function update($table, $values, $condition = [], $glue = 'AND')
    {
        foreach ($values as $key => $value) {
            $pieces[] = $key.'=:'.$key;
        }
        if (empty($condition) !== true) {
            foreach ($condition as $key => $value) {
                $wherePieces[] = $this->binary.' '.$key.'=:where'.$key;
            }
            $where = ' WHERE '.implode(' '.trim($glue).' ', $wherePieces);
        } else {
            $where = '';
        }
        $update = implode(', ', $pieces);
        $sql    = 'UPDATE '.$table.' SET '.$update.$where;
        $query  = $this->handler->prepare($sql);
        foreach ($values as $key => &$value) {
            $query->bindParam(':'.$key, $value);
        }
        if (empty($condition) !== true) {
            foreach ($condition as $key => &$value) {
                $query->bindParam(':where'.$key, $value);
            }
        }
        $result = $query->execute();
        return $result;
    }//end update()

    /**
     * delete() Function.
     *
     * @param string $table
     * @param array  $condition
     * @param string $glue
     *
     * @return boolean
     */
    public function delete($table, $condition, $glue = 'AND')
    {
        foreach ($condition as $key => $value) {
            $pieces[] = $this->binary.' '.$key.'=:'.$key;
        }
        $where = ' WHERE '.implode(' '.trim($glue).' ', $pieces);
        $sql   = 'DELETE FROM '.$table.$where;
        $query = $this->handler->prepare($sql);
        foreach ($condition as $key => &$value) {
            $query->bindParam(':'.$key, $value);
        }
        $result = $query->execute();
        return $result;
    }//end delete()

    /**
     * query() Function.
     *
     * @param string $sql
     * @param array  $bind
     *
     * @return Preapared Statement
     */
    public function query($sql, $bind = [])
    {
        $result = $this->handler->prepare($sql);
        if (empty($bind) !== true) {
            foreach ($bind as $key => &$value) {
                $result->bindParam($key, $value);
            }
        }
        return $result;
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
    public function installSQL($url)
    {
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
    public function dropTables()
    {
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
    public function scanTables()
    {
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
