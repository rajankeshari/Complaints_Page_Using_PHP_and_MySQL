

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



#################################### SPORT_MODEL CLASS START #############################################



//-------------------------------------------------------------------------------------
/**
  *
  * @author    MIS sport team (Prem Sagar)
  * @copyright no copyright
  * It cover insertion, deletion and update of table.
  *
  */

class Sport_model extends CI_Model
{
	/**
	 *
	 * Includes Class Constructor
	 * @param none
	 * @return	void
	 *
	 */

	function __construct()
	{
		parent::__construct();
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * it fetch all the record from table mentioned in parameter.
	 * @param   string 		$table 		table name
	 * @return	2-d assoc array 	holds the all record of table
	 *
	 */

	public function get_all_data($table='')
	{	
		// if table name is not empty
		if(!empty($table))
		{	
			// fetching all record from table
			$query=$this->db->get($table);
			
			if($query)
			{	
				// if fetched return 2-d assoc array
				return $query->result_array();
			}      
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * This function insert single record into table mentioned in parameter.
	 * Here keys in data array should exactly match with table schema.
	 * @param   string 				$table 		table name	
	 * @param   assoc array 		$data 		data to be inserted
	 * @return	assoc array 		contain status of insertion and corresponding message
	 *
	 */

	public function insert($table="",$data=array())
	{	

		// if table name is not empty and data is not empty
		if(!empty($table)&&!empty($data))
		{	
			// insert into table 
			$query=$this->db->insert($table,$data);

			// if query executed successfully
			if($query)
			{   

				$msg="Data has been Successfully inserted";

				// return assoc array containing message and status.
				return array("message"=>$msg,"status"=>true);
			}
			else
			{
				$msg="Data has not been  inserted into ";

				// return assoc array containing message and status.
				return array("message"=>$msg,"status"=>false);
			}
		}

		// return assoc array containing message and status.
		return array("message"=>"parameter is not set properly","status"=>false);	
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * This function performs update operation on single record in a table
	 * Here keys in data array should exactly match with table schema.
	 * @param   string 				$table 		table name	
	 * @param   assoc array 		$data 		data to be updated
	 * @param   assoc array 		$primary	primary key of table to identify unique record.
	 * @return	assoc array 		contain status of update operation and corresponding message.
	 *
	 */


	public function update($table="",$data=array(),$primary=array())
	{	
		// if table name is not empty and data array and primary array is not empty
		if(!empty($table)&&!empty($data)&&!empty($primary))
		{	
			// perform update operation on record identified with primary array
			$query=$this->db->update($table,$data,$primary);
           
			// if query is executed successfully            
			if($query)
			{   
				// return assoc array containing message and status.
				$msg="Data has been Successfully updated";
				return array("message"=>$msg,"status"=>true);
			}
			else
			{	
				// return assoc array containing message and status.
				$msg="Data has not been  updated into";
				return array("message"=>$msg,"status"=>false);
			}
		}

		// return assoc array containing message and status.
		return array("message"=>"parameter is not set properly","status"=>false);
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It delete single record from table 
	 * Here keys in primary array should exactly match with primary key of table.
	 * @param   string 				$table 		table name	
	 * @param   assoc array 		$primary	primary key of table to identify unique record.
	 * @return	assoc array 		contain status of delete operation and corresponding message
	 *
	 */

	public function delete($table="",$primary=array())
	{
		// if table name is not empty and  primary array is not empty	
		if(!empty($table)&&!empty($primary))
		{	
			// delete one record from table based on primary key in primary array
			$query=$this->db->delete($table,$primary);

			// if query executed successfully
			if($query)
			{   
				// return assoc array containing message and status.
				$msg="Record has been Successfully deleted from ".$table." table";
				return array("message"=>$msg,"status"=>true);
			}
			else
			{	
				// return assoc array containing message and status.
				$msg="Record has not been  deleted from ".$table." table";
				return array("message"=>$msg,"status"=>false);
			}
		}

		// return assoc array containing message and status.
		return array("message"=>"parameter is not set properly","status"=>false);	
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * it fetch all the record from table based on condition given  in parameter.
	 * @param   string 				$table 		table name
	 * @param   assoc array 		$cond		holds the condition for where
	 * @return	2-d assoc array 	holds the fetched record from  table
	 *
	 */

	public function get_all_data_cond($table='',$cond=array())
	{	
		// if table is not empty
		if(!empty($table))
		{	
			// if $cond is array and not empty
			if(is_array($cond)&&!empty($cond))
			{	
				// fetch record from database based on condition
				$query=$this->db->where($cond)->get($table);
				
				// if query executed successfully
				if($query)
				{	
					// return assoc array containing fetched data and status.
					return array('status'=>true,'data'=>$query->result_array());
				}  
			}   
		}
		else
		{	
			// return assoc array containing status.
			return array('status'=>false);
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * it fetch all the record from table based on condition given  in parameter.
	 * @param   string 				$table 		table name
	 * @param   assoc array 		$cond		holds the condition for where
	 * @param   array 				$condIn		holds the condition for wherein
	 * @return	2-d assoc array 	holds the fetched record from  table
	 *
	 */

	public function get_all_data_cond_wherein($table='',$cond=[],$condIn=[])
	{	
		// set the condition for where in query
		$this->db->where($cond);

		// set the condition for where_in in query
		foreach ($condIn as $key =>$value)
		{
			$this->db->where_in($key,$value);
		}

		// fetching records from resulting query
		$query = $this->db->get($table);

		//return assoc array containing fetched record from table based on given condition. 
		return $query->result_array();
	}

	//-----------------------------------------------------------------------------------------------------
}


#################################### SPORT_MODEL CLASS END HERE #############################################