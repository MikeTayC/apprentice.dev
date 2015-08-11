<?php

class Core_Model_Database
{
    private $_databaseConfig;
    private $_host;
    private $_user;
    private $_pass;
    private $_name;
    private $_type;

    private $_dbHandler;
    private $_error = false;
    private $_results;
    private $_query;
    private $_count = 0;


    private static $_instance = null;


    private function __construct()
    {
        $this->setDatabaseConfig();
        //set DSN
        $dsn =  $this->_type . ':host=' . $this->_host . ';dbname=' . $this->_name;

        //set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
        );

        try {
            /*
             * PDO has three params upon construction
             * specify driver, username, password
             * specify driver: mysql,needs host and database name
             * host = server you are running uysually 127.0.0.1
             *
             */
            $this->_dbHandler = new PDO($dsn, $this->_user, $this->_pass, $options);
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if(!isset(self::$_instance)) {
            self::$_instance = new Core_Model_Database();
        }
        return self::$_instance;
    }

    public function setDatabaseConfig()
    {
        $this->_databaseConfig = Core_Model_Config_Json::getModulesDatabaseConfig();

        $this->_type = $this->_databaseConfig['type'];
        $this->_host = $this->_databaseConfig['host'];
        $this->_name = $this->_databaseConfig['name'];
        $this->_user = $this->_databaseConfig['user'];
        $this->_pass = $this->_databaseConfig['pass'];
    }

    public function query($sql, $params = array())
    {
        $this->_error = false;
        if($this->_query = $this->_dbHandler->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param){
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->_query->execute()){
                if($this->_query->columnCount()){
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count = $this->_query->rowCount();
                }
            }
            else {
                $this->_error = true;
            }
        }
        return $this;
    }

    /*
     * abstraction, wrapper function to make it easier and readible to manipulate db data
     * $action: select/insert..
     * $table = db table
     * $where = where clause
     * count($where) === 3, need a field/operator/value
     */
    public function action($action, $table, $where = array())
    {
        if(count($where) === 3) {
            $operators = array('=','<','>','<=','>=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)){
                /*
                 * TODO TRY WHERE 1=1, TO GET ALL POST WHEN THERE ARE NO CHECKS
                 */
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        return false;
    }

    /*
     * EXAMPLE USE:
     * $user = Core_Model_Database::getInstance()->get('users', array('username', '=', 'mctaystee'));
     */
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function getAll($table)
    {
        return $this->action('SELECT * ', $table, array('1','=','1'));
    }

    public function delete($table, $where)
    {
        return $this->action('DELETE ', $table, $where);
    }

    /*
     * EXAMPLE USE:
     * $user = Core_Model_Database::getInstance()->insert('users', array(
     *      'username' => 'mctaystee'
     *      'password' => 'password'
     * ));
     */
    public function insert($table, $fields = array())
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach ($fields as $field) {
                $values .= '?';
                if ($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }
            $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES({$values})";

            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    /*
     * EXAMPLE USE:
     * $user = Core_Model_Database::getInstance()->udpate('users', 3, array(
     *      'username' => 'mctay'
     *      'password' => 'newpassword'
     * ));
     */
    public function update($table, $id, $fields = array())
    {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function first()
    {
        if($this->_results) {
            return $this->_results[0];
        }
        return null;
    }

    /*
     * example use:
     * foreach($dbInstance->results() as $instance){
     *  echo $instance->fieldInInstance,'<br>';
     * }
     */
    public function results()
    {
        return $this->_results;
    }

    /*
     * returns row count from statement
     */
    public function count()
    {
        return $this->_count;
    }
    /*
     * for use:
     * if($dbInstance->error())
     * {
     *      echo 'error';
     * }
     */
    public function error()
    {
        return $this->_error;
    }

}