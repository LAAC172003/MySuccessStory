<?php
//https://codeshack.io/super-fast-php-mysql-database-class/
namespace MySuccessStory\Api\Model;

use mysqli;

include "identifiers.php";

//class who contains the sql connection and its methods
class SqlConnection
{

	protected $connection;
	protected $query;
	protected $show_errors = true;
	protected $query_closed = true;
	public $query_count = 0;
	//connection to the database
	public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = '', $charset = 'utf8')
	{
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
			$this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($charset);
	}
	//function query the database
	public function query($query)
	{
		if (!$this->query_closed) {
			$this->query->close();
		}
		if ($this->query = $this->connection->prepare($query)) {
			if (func_num_args() > 1) {
				$args = array_slice(func_get_args(), 1);
				$types = '';
				$args_ref = array();
				foreach ($args as $key => &$arg) {
					if (is_array($args[$key])) {
						foreach ($args[$key] as $j => &$a) {
							$types .= $this->_gettype($args[$key][$j]);
							$args_ref[] = &$a;
						}
					} else {
						$types .= $this->_gettype($args[$key]);
						$args_ref[] = &$arg;
					}
				}
				array_unshift($args_ref, $types);
				call_user_func_array(array($this->query, 'bind_param'), $args_ref);
			}
			$this->query->execute();
			if ($this->query->errno) {
				$this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
			}
			$this->query_closed = false;
			$this->query_count++;
		} else {
			$this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
		}
		return $this;
	}

	//function fetch all the data 
	public function fetchAll($callback = null)
	{
		$params = array();
		$row = array();
		$meta = $this->query->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}
		call_user_func_array(array($this->query, 'bind_result'), $params);
		$result = array();
		while ($this->query->fetch()) {
			$r = array();
			foreach ($row as $key => $val) {
				$r[$key] = $val;
			}
			if ($callback != null && is_callable($callback)) {
				$value = call_user_func($callback, $r);
				if ($value == 'break') break;
			} else {
				$result[] = $r;
			}
		}
		$this->query->close();
		$this->query_closed = true;
		return $result;
	}
	//fetch an array of data
	public function fetchArray()
	{
		$params = array();
		$row = array();
		$meta = $this->query->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}
		call_user_func_array(array($this->query, 'bind_result'), $params);
		$result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
		$this->query->close();
		$this->query_closed = true;
		return $result;
	}
	//close the connetion to the database
	public function close()
	{
		return $this->connection->close();
	}
	//function get the number of rows
	public function numRows()
	{
		$this->query->store_result();
		return $this->query->num_rows;
	}
	//return the affected rows
	public function affectedRows()
	{
		return $this->query->affected_rows;
	}
	//return the last id inserted in the database
	public function lastInsertID()
	{
		return $this->connection->insert_id;
	}
	//return an error 
	public function error($error)
	{
		if ($this->show_errors) {
			exit($error);
		}
	}
	//return the type
	private function _gettype($var)
	{
		if (is_string($var)) return 's';
		if (is_float($var)) return 'd';
		if (is_int($var)) return 'i';
		return 'b';
	}
}
