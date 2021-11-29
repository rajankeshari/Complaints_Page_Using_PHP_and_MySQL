<?php

/**
*
* @copyright no copyright
* This class contains basic db manipulation and querying functions that extend the DB query of the in built functions
*/
class Basic extends CI_Model{
	/**
	*
	* @param none
	* @return	void

	*/
	function __construct(){
		parent::__construct();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The functions adds a feature to allow passing a condition array to get data from any table.
	* @param table name, condition array for getting data satisfying conditions.
	* @return	result array of the query.

	*/
	public function get($table,$cond=[]){
		$query = $this->db->where($cond)->get($table);
		return $query->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function adds a feature to allow passing a condition array to insert data to any table.
	* @param table name, values array for passing the value to be inserted,  condition array for getting data satisfying conditions.(though not required).
	* @return a boolean to determine if the query ran successfully or not.

	*/
	public function insert($table,$values,$cond=[]){
		
		$query = $this->db->where($cond)->insert($table,$values);
		return $query;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function adds a feature to allow passing a condition array to update data of any table.
	* @param table name, values array for passing the value to be updated to,  condition array for selecting data satisfying conditions to update.
	* @return a boolean to determine if the query ran successfully or not.

	*/
	public function update($table,$values,$cond=[]){
		

		$query = $this->db->update($table,$values,$cond);
		return $query;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function adds a feature to allow passing a condition array to delete data from any table.
	* @param table name, condition array for selecting data satisfying conditions to delete.
	* @return a boolean to determine if the query ran successfully or not.

	*/
	public function delete($table,$cond=[]){
		$result = $this->db->where($cond)->delete($table);
		return $result;
	}
	
	//-----------------------------------------------------------------------------------------

}
