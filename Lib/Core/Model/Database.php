<?php
/**
 * Class Core_Model_Database
 *
 * Main database class, handles connection and queries to a database
 **/
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
    private $_tableFields;


    /** @var null will hold singleton instance of database */
    private static $_instance = null;


    /**
     * private custructor sets database configuration for connection
     **/
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
             * host = server you are running usually 127.0.0.1
             */
            $this->_dbHandler = new PDO($dsn, $this->_user, $this->_pass, $options);
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * Singleton
     * @return Core_Model_Database|null
     **/
    public static function getInstance()
    {
        if(!isset(self::$_instance)) {
            self::$_instance = new Core_Model_Database();
        }
        return self::$_instance;
    }

    /**
     * Sets database configuration
     * Called in constructor
     **/
    public function setDatabaseConfig()
    {
        $this->_databaseConfig = Core_Model_Config_Json::getModulesDatabaseConfig();

        $this->_type = $this->_databaseConfig['type'];
        $this->_host = $this->_databaseConfig['host'];
        $this->_name = $this->_databaseConfig['name'];
        $this->_user = $this->_databaseConfig['user'];
        $this->_pass = $this->_databaseConfig['pass'];
    }

    /**
     * Function queries the databse
     *
     * @param $sql query statement
     * @param array $params, parameters to be binded to query
     * @return $this
     **/
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
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_ASSOC);
                    $this->_count = $this->_query->rowCount();
                }
            }
            else {
                $this->_error = true;
            }
        }
        return $this;
    }

    /**
     * abstraction, wrapper function to make it easier and readible to manipulate db data
     * @param $action: select/insert..
     * @param $table: db table
     * @param array $where : where clause
     *  count($where) === 3, need a field/operator/value
     * @return $this|bool
     **/
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

    /**
     * abstraction, wrapper function to make it easier and readible to manipulate db data
     * @param $action: select/insert..
     * @param $table: db table
     * @param array $where : where clause
     * @param array $where2 : second where clause to query by
     *  count($where) === 3, need a field/operator/value
     * @return $this|bool
     **/
    public function multiAction($action, $table, $where = array(), $where2 = array())
    {
        if(count($where) === 3 && count($where2) === 3) {
            $operators = array('=','<','>','<=','>=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            $field2 = $where2[0];
            $operator2 = $where2[1];
            $value2 = $where2[2];

            if(in_array($operator, $operators)){
                /*
                 * TODO TRY WHERE 1=1, TO GET ALL POST WHEN THERE ARE NO CHECKS
                 */
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? AND {$field2} {$operator2} ?";
                if(!$this->query($sql, array($value, $value2))->error()){
                    return $this;
                }
            }
        }
        return false;
    }


    /**
     * Returns a select query
     *
     * <code>
     * $user = Core_Model_Database::getInstance()->get('users', array('username', '=', 'mctaystee'));
     * </code>
     * @param $table
     * @param $where
     * @return $this|bool|Core_Model_Database
     */
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    /**
     * Returns all from a table
     *
     * @param $table
     * @return $this|bool|Core_Model_Database
     */
    public function getAll($table)
    {
        return $this->action('SELECT * ', $table, array('1','=','1'));
    }

    /**
     * Returns all from a table, agains two search parameters
     * @param $table
     * @param $where
     * @param $where2
     * @return $this|bool|Core_Model_Database
     **/
    public function getMultiArgument($table, $where, $where2)
    {
        return $this->multiAction('SELECT *', $table, $where, $where2);
    }

    /**
     * Delets a row in a table
     *
     * @param $table
     * @param $where
     * @return $this|bool|Core_Model_Database
     **/
    public function delete($table, $where)
    {
        return $this->action('DELETE ', $table, $where);
    }

    /**
     * Deletes a row based on two parameters
     *
     * @param $table
     * @param $where
     * @param $where2
     * @return $this|bool|Core_Model_Database
     **/
    public function deleteMultiArgument($table, $where, $where2)
    {
        return $this->multiAction('DELETE ', $table, $where, $where2);
    }

    /**
     *
     * Insertsa  new row into a table
     * <code>
     * $user = Core_Model_Database::getInstance()->insert('users', array(
     *      'username' => 'mctaystee'
     *      'password' => 'password'
     * ));
     * </code>
     * @param $table
     * @param array $fields
     * @return bool
     */
    public function insert($table, $fields = array())
    {
        $this->setTableFields($table);
        if (count($fields)) {
            $keys = array_keys($fields);
            foreach($keys as $key) {
                if($this->checkColumnExists($key)) {
                    continue;
                }
                else {
                    unset($keys[$key]);
                    unset($fields[$key]);
                }
            }
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


    /**
     * Updates a particular row in a table
     * <code>
     * $user = Core_Model_Database::getInstance()->udpate('users', 3, 'user_id', array(
     *      'username' => 'mctay'
     *      'password' => 'newpassword'
     * ));
     * </code>
     *
     * @param $table
     * @param $fieldToCheck
     * @param array $fields
     * @return bool
     **/
    public function update($table, $fieldToCheck, $fields = array())
    {
        $this->setTableFields($table);
        $set = '';
        $x = 1;
        if (count($fields)) {
            $keys = array_keys($fields);
            foreach ($keys as $key) {
                if ($this->checkColumnExists($key)) {
                    continue;
                } else {
                    unset($fields[$key]);
                }
            }
        }
        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$fieldToCheck}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    /**
     * Returns the first result of a query
     *
     * @return null
     **/
    public function first()
    {
        if($this->_results) {
            return $this->_results[0];
        }
        return null;
    }

    /**
     *
     * Returns all results of a query
     *
     * <code>
     * foreach($dbInstance->results() as $instance){
     *   echo $instance->fieldInInstance,'<br>';
     * }
     * </code>
     * @return mixed
     **/
    public function results()
    {
        return $this->_results;
    }


    /**
     * Returns a count of a query
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Returns if any errors are set
     * <code>
     * if($dbInstance->error())
     * {
     *      echo 'error';
     * }
     * </code>
     * @return bool
     **/
    public function error()
    {
        return $this->_error;
    }

    /**
     * returns the curernt tables fields,
     * Used for verifiying a column exists
     *
     * @param $table
     **/
    public function setTableFields($table)
    {
        $q = $this->_dbHandler->prepare("DESCRIBE " . $table);
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        $this->_tableFields = $table_fields;
    }

    /**
     * Verifies a column exists
     *
     * @param $fieldKey
     * @return bool
     **/
    public function checkColumnExists($fieldKey)
    {

        if(in_array($fieldKey, $this->_tableFields)) {
            return true;
        }
        return false;
    }

    public function validationCheck($table, $name)
    {
        $result = $this->get($table, array('name','=', $name))->count();
        return $result;
    }
}