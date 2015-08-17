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
	protected $_data;
	protected $_table;

	/*
	 * gits an instance of the database, and sets the table to be worked with
	 */
	public function __construct()
	{
		$this->_db = Core_Model_Database::getInstance();
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
				$nameArray[] = $data->name;
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

	public function update($fieldToCheck, $fieldCheck, $fields)
	{
		$this->_db->update($this->_table, $fieldToCheck, $fieldCheck, $fields);
	}


}