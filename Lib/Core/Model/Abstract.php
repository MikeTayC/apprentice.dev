<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/16/15
 * Time: 10:21 PM
 */

abstract class Core_Model_Abstract extends Core_Model_Object
{
	protected $_db;
	protected $_table;

	/*
	 * gits an instance of the database, and sets the table to be worked with
	 */
	public function __construct()
    {
        $this->_db = Core_Model_Database::getInstance();
        $this->_module = Core_Model_Request::getInstance()->getModule();
    }

    public function load($id)
    {
        $this->_beforeLoad();
        $this->_data = $this->get(array('id', '=', $id));
        $this->_afterLoad();
        return $this;
    }

    public function loadAll()
    {
        $arrayOfModels = array();
        $data = $this->getAll();
        if($data) {
            foreach($data as $dataValue) {
                $arrayOfModels[] = Bootstrap::getModel($this->_module . '/' . $this->_table)->load($dataValue['id']);
            }
        }
        return $arrayOfModels;
    }

    public function loadAllBasedOnFields($fields)
    {
        $arrayOfModels = array();
        $data = $this->getAllBasedOnGivenFields($fields);
        if(isset($data)) {
            foreach($data as $dataValue) {
                $arrayOfModels[] = Bootstrap::getModel($this->_module . '/' . $this->_table)->load($dataValue['id']);
            }
        }
        return $arrayOfModels;
    }


    public function loadByName($name)
    {
        $this->get(array('name', '=', $name));

        return $this;
    }


    public function save()
    {
        $this->_beforeSave();

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

    public function delete()
    {
        $this->_beforeDelete();
//        $modelShortName = somestringmanipulation(get_class($this)); // = incubate_lesson , incubate_user
//        Bootstrap::dispatchEvent($modelShortName . '_delete_before', $eventObject);
        $this->_db->delete($this->_table, array('id', '=', $this->getId()));

        $this->_afterDelete();

        return $this;
    }

    public function deleteAll($fields = array())
    {
        $this->_db->delete($this->_table, $fields);
    }


	/*
	 * returns first result found from database search
	 */
	public function create($fields = array()) {
		if(!$this->_db->insert($this->_table, $fields)) {
			throw new Exception('Problem!');
		}
	}

	/*
	 * retrieves all informationi from a given query
	 */
	public function getAllBasedOnGivenFields($fields =array())
	{
		if($this->_data = $this->_db->get($this->_table, $fields)->results()) {
			return $this->_data;
		}
		return null;
	}

    public function get($fields = array())
    {
        if($this->_data = $this->_db->get($this->_table, $fields)->first()) {
            return $this->_data;
        }
        return null;
    }

	public function getAll()
	{
		if($this->_data = $this->_db->getAll($this->_table)->results()) {
			return $this->_data;
		}
		return null;
	}

	/*
	 * returns all name values in a table
	 */
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

	/*
	 * json encodes any value
	 */
	public function jsonEncode($value)
	{
		$jsonValue = json_encode($value);
		return $jsonValue;
	}
	/*
	 * retrieves information from given table, from a given query with multple search fields
	 */
	public function getMultiArguments($fields = array(), $fields2 = array())
	{
		if($this->_data = $this->_db->getMultiArgument($this->_table, $fields, $fields2)->first()){
			return $this->_data;
		}
	}

	public function getCount($fields = array())
	{
		$data = $this->_db->get($this->_table, $fields)->count();
		return $data;
	}

	public function getTotalCount()
	{
		$data = $this->_db->getAll($this->_table)->count();
		return $data;
	}

	public function deleteMultiArguments($where, $where2)
	{
		$data = $this->_db->deleteMultiArgument($this->_table, $where, $where2);
		return $data;
	}

	public function update($fields)
	{
		$this->_db->update($this->_table, $this->getId(), $fields);
	}

    public function check($id)
    {
        if($id) {
            return $this->getCount(array('id', '=', $id));
        }
        return null;
    }

    protected function _beforeLoad(){
        Bootstrap::dispatchEvent("{$this->_table}_load_before", $this);

    }

    protected function _afterLoad(){

        Bootstrap::dispatchEvent("{$this->_table}_load_after", $this);
    }


    protected function _beforeSave(){
        Bootstrap::dispatchEvent("{$this->_table}_save_before", $this);
    }

    protected function _afterSave(){
        Bootstrap::dispatchEvent("{$this->_table}_save_after", $this);
    }

    protected function _beforeDelete(){
        Bootstrap::dispatchEvent("{$this->_table}_delete_before", $this);
    }

    protected function _afterDelete(){
        Bootstrap::dispatchEvent("{$this->_table}_delete_after", $this);

    }

    protected function _beforeCreate()
    {
        Bootstrap::dispatchEvent("{$this->_table}_create_before", $this);
    }

    protected  function _beforeUpdate()
    {
        Bootstrap::dispatchEvent("{$this->_table}_update_before", $this);
    }

    public function unsetData($key)
    {
        unset($this->_data[$key]);
    }

}