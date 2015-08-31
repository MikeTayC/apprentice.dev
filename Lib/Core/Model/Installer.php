<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 8/29/15
 * Time: 9:40 PM
 *
 *
 **/
class Core_Model_Installer
{
	protected $_db;

	public function __construct()
	{
		$this->_db = Core_Model_Database::getInstance();
	}
	public function runInstallScript()
	{
		foreach(array('user', 'lesson', 'tag', 'completedCoursesMap', 'userTagMap', 'tagMap') as $table) {
			$createMethod = '_createTable' . ucfirst($table);
			$this->$createMethod();
			$this->_insertData($table);
		}
	}

	protected function _insertData($table)
	{
		switch($table){
			case 'user':
				foreach(Bootstrap::getAdminInitConfig() as $admin){
					Bootstrap::getModel('user/model')->create(array(
						'email' => $admin,
						'role' => 'admin'
					));
				}
				break;
			case 'tag':
				foreach(array('Application Engineer', 'Quality Assurance Analyst', 'Front End Developer') as $tag){
					Bootstrap::getModel('tag/model')->create(array('name' => $tag));
				}
				break;
			default:
				break;
		}
	}

	protected function _createTableUser()
	{
		$this->_db->_dbHandler->query('CREATE TABLE IF NOT EXISTS user (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			google_id VARCHAR(60), name VARCHAR(50),
			email VARCHAR(60),
			joined TIMESTAMP,
			role VARCHAR(20),
			groups VARCHAR(60))
			');
	}

	protected function _createTableLesson()
	{
		$this->_db->_dbHandler->query('CREATE TABLE IF NOT EXISTS lesson (
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(60),
			description VARCHAR(255),
			duration INT)
			');
	}

	protected  function _createTableTag()
	{
		$this->_db->_dbHandler->query('CREATE TABLE IF NOT EXISTS tag (
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				name VARCHAR(60))
		');
	}

	protected function _createTableUserTagMap()
	{
		$this->_db->_dbHandler->query('CREATE TABLE IF NOT EXISTS UserTagMap(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			tag_id INT,
			user_id INT)
		');
	}

	protected function _createTableTagMap()
	{
		$this->_db->_dbHandler->query('CREATE TABLE IF NOT EXISTS TagMap(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			tag_id INT,
			lesson_id INT);
		');
	}

	protected function _createTableCompletedCoursesMap()
	{
		$this->_db->_dbHandler->query('CREATE TABLE IF NOT EXISTS CompletedCoursesMap(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			user_id INT,
			lesson_id INT,
			date TIMESTAMP)
		');
	}

}
