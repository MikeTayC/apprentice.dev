<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/16/15
 * Time: 10:21 PM
 *
 * Abstract model class extended by all models used by framework
 *
 * Has required functions and database connection  extends the core model object
 * which has necessary and required magic functions for storing data
 **/

abstract class Core_Model_Abstract extends Core_Model_Object
{
    /** @var Core_Model_Database|null reference to database singleton */
	protected $_db;

    /** @var  refernce to the table being accessed, set in child constructor */
	protected $_table;

    /** @var  reference to module, set in constructor */
    protected $_module;

    /** @var  reference to class, set in constructor */
    protected $_className;

	/** gets an instance of the database, and sets module and classname references to be worked with **/
	public function __construct()
    {
        $this->_db = Core_Model_Database::getInstance();
        $this->_setModuleAndClassName();
    }

    /**
     * Loads a specific row information and sets the data on the current model object
     * Before and after functions as a dispatcher, will load different accessory info
     * depending on the type of model being called(lesson|user|tag)
     *
     * @param $id : id of lesson/user/tag to be loaded
     * @return $this
     **/
    public function load($id)
    {
        $this->_beforeLoad();
        $this->_data = $this->get(array('id', '=', $id));
        $this->_afterLoad();
        return $this;
    }

    /**
     * Will load all rows information in a given table, each as a separate model object
     *
     * @return array of model objects
     **/
    public function loadAll()
    {
        $arrayOfModels = array();
        $data = $this->getAll();
        if($data) {
            foreach($data as $dataValue) {
                $arrayOfModels[] = Bootstrap::getModel($this->_module . '/' . $this->_className)->load($dataValue['id']);
            }
        }
        return $arrayOfModels;
    }

    /**
     * loads model objects based on specific given search queries
     *
     * @param $fields query paramteres
     * @return array of model objects
     **/
    public function loadAllBasedOnFields($fields)
    {
        $arrayOfModels = array();
        $data = $this->getAllBasedOnGivenFields($fields);
        if(isset($data)) {
            foreach($data as $dataValue) {
                $arrayOfModels[] = Bootstrap::getModel($this->_module . '/' . $this->_className)->load($dataValue['id']);
            }
        }
        return $arrayOfModels;
    }


    /**
     * Loads a specific tag/user/lesson, based on name
     *
     * @param $name to look for
     * @return $this
     **/
    public function loadByName($name)
    {
        $this->get(array('name', '=', $name));

        return $this;
    }

    /**
     * A check to see if a name exists already in the table
     * Useful for validation
     *
     * @param $name
     * @return bool
     **/
    public function checkByName($name)
    {
        if($count = $this->getCount(array('name', '=', $name))){
            return true;
        }
        return false;
    }

    /**
     * Saves the current state of the current model object,
     * Will create a new row if the data is not yet set or will update
     * if id does not exist.
     *
     * Before save and after save there a dispatch functions which will add or
     * delete accessory inforamtion when applicable
     *
     * @return $this
     * @throws Exception
     **/
    public function save()
    {
        $this->_beforeSave();

        /** $fields all current information on the current model object */
        $fields = $this->getData();

        //check if the data is to create or update by checkign if there is an id to be loaded
        if(isset($fields['id'])) {
            //then update
            $this->_beforeUpdate();
            $this->update($fields);
        }
        else {
            $this->create($fields);
        }
        $this->_afterSave();
        return $this;
    }

    /**
     * Same functionallity as save(), no dispatches, no loading after saving
     *
     * @return $this
     * @throws Exception
     **/
    public function saveNoLoad()
    {
        $fields = $this->getData();

        //check if the data is to create or update by checkign if there is an id to be loaded
        if(isset($fields['id'])) {
            //then update
            $this->update($fields);
        }
        else {
            $this->create($fields);
        }
        return $this;
    }

    /**
     * Deletes the current model object from database,
     * Dispatches will delete information related to the current object
     *
     * @return $this
     **/
    public function delete()
    {
        $this->_beforeDelete();
        $this->_db->delete($this->_table, array('id', '=', $this->getId()));

        $this->_afterDelete();

        return $this;
    }

    /**
     * Will delete any and all information based on a the given parameters
     *
     * @param array $fields
     **/
    public function deleteAll($fields = array())
    {
        $this->_db->delete($this->_table, $fields);
    }

    /**
     * Creates a new row in based on given fields and table
     *
     * @param array $fields
     * @throws Exception
     **/
	public function create($fields = array()) {
		if(!$this->_db->insert($this->_table, $fields)) {
			throw new Exception('Problem!');
		}
	}

    /**
     * Returns results of a query based on given parameters as an array
     *
     * @param array $fields
     * @return null
     **/
	public function getAllBasedOnGivenFields($fields =array())
	{
		if($this->_data = $this->_db->get($this->_table, $fields)->results()) {
			return $this->_data;
		}
		return null;
	}

    /**
     * Loads the first result information into the curent model objects
     * by setting it to $this->_data
     *
     * @param array $fields
     * @return null
     **/
    public function get($fields = array())
    {
        if($this->_data = $this->_db->get($this->_table, $fields)->first()) {
            return $this->_data;
        }
        return null;
    }

    /**
     * Returns all information from a table by setting it equal to
     * _data
     *
     * @return null
     **/
	public function getAll()
	{
		if($this->_data = $this->_db->getAll($this->_table)->results()) {
			return $this->_data;
		}
		return null;
	}

	/**
	 * Returns all name values in a table
	 **/
	public function getAllNamesAsArray()
	{
		if($allData = $this->getAll()) {
			foreach($allData as $data) {
				$nameArray[] = $data['name'];
			}
			return $nameArray;
		}
		return null;
	}

    /**
     * Json encodes any value
     * @param $value to be encoded
     * @return string encoded value
     **/
    public function jsonEncode($value)
	{
		$jsonValue = json_encode($value);
		return $jsonValue;
	}

    /**
     * Retrieves information from given table, from a given query with multple search fields
     *
     * @param array $fields first params to query with
     * @param array $fields2 second params to query with
     * @return null
     */
	public function getMultiArguments($fields = array(), $fields2 = array())
	{
		if($this->_data = $this->_db->getMultiArgument($this->_table, $fields, $fields2)->first()){
			return $this->_data;
		}
	}

    /**
     * Gets a count of how many rows are in a table, based on a given search query
     *
     * @param array $fields
     * @return int
     **/
	public function getCount($fields = array())
	{
		$data = $this->_db->get($this->_table, $fields)->count();
		return $data;
	}

    /**
     * Gets a total count of rows in a table
     *
     * @return int
     **/
	public function getTotalCount()
	{
		$data = $this->_db->getAll($this->_table)->count();
		return $data;
	}

    /**
     * Deletes a row of table, based on two paramters
     *
     * @param $where
     * @param $where2
     * @return $this|bool|Core_Model_Database
     **/
	public function deleteMultiArguments($where, $where2)
	{
		$data = $this->_db->deleteMultiArgument($this->_table, $where, $where2);
		return $data;
	}

    /**
     * Updates a single row in a table, based on the id of the current
     * model objects
     *
     * @param $fields array : column to update
     **/
	public function update($fields)
	{
		$this->_db->update($this->_table, $this->getId(), $fields);
	}

    /**
     * Checks to see if the row exists, by returning a count,
     * if no count exists, null will be returned, also if there is no id
     * in as param
     *
     * @param $id
     * @return int|null
     **/
    public function check($id)
    {
        if($id) {
            return $this->getCount(array('id', '=', $id));
        }
        return null;
    }

    /** Defeault dispatch function, different depending current model object */
    protected function _beforeLoad(){
        Bootstrap::dispatchEvent("{$this->_table}_load_before", $this);

    }

    /** Defeault dispatch function, different depending current model object */
    protected function _afterLoad(){

        Bootstrap::dispatchEvent("{$this->_table}_load_after", $this);
    }

    /** Defeault dispatch function, different depending current model object */
    protected function _beforeSave(){
        Bootstrap::dispatchEvent("{$this->_table}_save_before", $this);
    }

    /** Defeault dispatch function, different depending current model object */
    protected function _afterSave(){
        Bootstrap::dispatchEvent("{$this->_table}_save_after", $this);
    }

    /** Defeault dispatch function, different depending current model object */
    protected function _beforeDelete(){
        Bootstrap::dispatchEvent("{$this->_table}_delete_before", $this);
    }

    /** Defeault dispatch function, different depending current model object */
    protected function _afterDelete(){
        Bootstrap::dispatchEvent("{$this->_table}_delete_after", $this);

    }

    /** Defeault dispatch function, different depending current model object */
    protected function _beforeCreate()
    {
        Bootstrap::dispatchEvent("{$this->_table}_create_before", $this);
    }

    /** Defeault dispatch function, different depending current model object */
    protected  function _beforeUpdate()
    {
        Bootstrap::dispatchEvent("{$this->_table}_update_before", $this);
    }

    /** unsets a specific value on the curent model object **/
    public function unsetData($key)
    {
        unset($this->_data[$key]);
    }

    /**
     * sets the current module, and class name on the object
     **/
    protected function _setModuleAndClassName()
    {
        $className = get_class($this);
        $classExplode = explode('_', $className);
        $module = strtolower($classExplode[0]);
        $this->_module = $module;
        $this->_className = strtolower($classExplode[2]);
    }
}