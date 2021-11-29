

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



#################################### GROUP_MODEL CLASS START #############################################



//-------------------------------------------------------------------------------------
/**
  * @author    MIS sport team (Prem Sagar)
  * @copyright no copyright
  * It cover all the manipulation for Group activity.
  * It provide functions for creating, deleting and generating report for group. 
  */

class Group_model extends CI_Model
{
	/**
	 *
	 * Includes Class Constructor
	 * Loads the basic model
	 * @param none
	 * @return	void
	 *
	 */

	function __construct()
	{
		parent::__construct();
		$this->load->library('notification',true);
		$this->load->model('spo_section/basic');
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It check whether given member(user_id) is valid.
	 * @param   array 		$member    holds member user id
	 * @return	bool        valid(true) or invalid(false)
	 *
	 */

	public function check_valid_user_id($member=array())
	{	
		// if $member is array
		if(is_array($member))
		{   
			// count no of member	
			$no=count($member);

			// converting member array to comma separated array
			$member=implode("','",$member);

			// query to find total valid member
			$query="SELECT count(id) AS total FROM users WHERE id IN ('".$member."')";

			// executing query
			$result=$this->db->query($query);

			// if query executed successfully
			if($result)
			{    
				// if initial count of member equals to total valid member
				if($result->result_array()[0]['total']==$no)
				{	
					// return member array is valid
					return true;
				}
			}

		}

		// return member array is not valid
		return false;
	}

	//------------------------------------------------------------------------------------------------

	/**
	 * It insert  the new group with status 0 into group table after validation of group information.
	 * It validate any group for same category should not exist for any member in member list.
	 * It fetched inserted group id and do batch insert of all member in members table.
	 * It then send request notification to all member except caption.
	 * It also work for a group having single member. In that case group status changed to 1.
	 * @param   array     $member 	holds member admission no 
	 * @param   string    $cat 		holds category code of group
	 * @return	assoc array         holds message and status of group creation
	 *
	 */
	

	public function insert_group($member=array(),$cat="")
	{	
		// if $member is array and $cat is not empty
		if(is_array($member)&&!empty($cat))
		{	
			// making copy of member user id 
			$o_member=$member;

			// counting total member in group
			$no=count($member);

			// converting member array to comma separated array
			$member=implode("','",$member);

			// query to find list of group  assigned to member of group with same category
			$query="SELECT group_id,adm_no  FROM spo_group_member WHERE cat_code = '$cat' AND status IN (0,1) AND  adm_no IN ('".$member."')";

			// executing query
			$result=$this->db->query($query);

			// store info of such group
			$result=$result->result_array();

			// if such group exist
			if(count($result)>0)
			{	
				// return such group
				$data=array(
					"data"=>$result,
					"cat" =>$cat,
					"status"=>false
				);
				return $data;
			}

			// if such group doesn't exist
			else
			{
				// caption admission no
                $c_adm=end($o_member);

                // group details to be inserted
				$group_data=array(
					"code"=>"G-".$cat,
					"captain_adm_no" =>$c_adm,
					"description" =>"",
					"no_of_mem" =>$no,
					"cat_code" =>$cat,
					"create_date" =>date("Y/m/d") 
				);

				// inserting group data to spo_group_current table
				$result=$this->db->insert('spo_group_current',$group_data);

				// if query executed successfully
				if($result)
				{
					// fetch inserted group id with the help of caption admission no and category code
					$g_id=$this->db->select('id')
								   ->where('captain_adm_no',$c_adm)
								   ->where('cat_code',$cat)
								   ->where('status',0)
									->get('spo_group_current');
				    $g_id=$g_id->result_array()[0]['id'];					
					
					// holds data for batch insert of member details into spo_group_membr table
					$member_data=array();

					// holds group id and category code of group
					$m_array=array("group_id"=>$g_id,"cat_code"=>$cat);

					// adding caption to member_data array with group info.
					$member_data[]=array_merge($m_array,array("adm_no"=>$c_adm,"status"=>1));
					
					// removing caption admission no from $o_member array
				    array_pop($o_member);

				    // for each member user_id in $o_member add member details and group info to member_data array
				    if(isset($o_member))
				    {
				    	foreach ($o_member as $mem_adm)
				    	{
							$member_data[]=array_merge($m_array,array("adm_no"=>$mem_adm,"status"=>0));
						}

						// batch insert
						$result=$this->db->insert_batch('spo_group_member',$member_data);
						
						// if query executed successfully
						if($result)
						{    
							// send notification to each member in $o_member array
							foreach ($o_member as $key=>$value)
							{
								
								$this->notification->notify($value,'stu','Sports','A new group request is available','spo_section/group/group_request_list','spo_section/group/group_request_list');
							}

							// message of creation of group
							 $msg="Group Has been created with group code G-".$cat."-".$g_id." and id ".$g_id;

							 // if size of $o_member is zero ie. group contains single member
							 if(count($o_member)===0)
							 {
							 	// update group status to 1
							 	$this->basic->update('spo_group_current',['status'=>1],['id'=>$g_id]);
							 }
							
							// return message group id and status in form of assoc array  
							return array('message'=>$msg,'g_id'=>$g_id,"status"=>true);
						}
				    }
				}
			}
			
		}

		// return message for wrong credential and status false
		 return array('message'=>"wrong details entered",'status'=>false);
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch all the group request available to user.
	 * @param   integer 	$user_id 	holds user id
	 * @return	2-d assoc array 		holds the all details of all group request
	 *
	 */
	
	public function get_grouprequest_by_userid($user_id="")
	{	

		// if $user_id is not empty
		if(!empty($user_id))
		{   
			// fetch details of group request for given user id
			$result =$this->db->select('*')
					 ->where('adm_no',$user_id)
					 ->where('status',0)
					 ->order_by('update_timestamp')
					 ->get('spo_group_member');


			// return 2-d assoc array of all the fetched group request
		    return $result->result_array();			 
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch the details of a group by group id
	 * @param   integer 	$group_id 	holds group id
	 * @return	assoc array 			holds the all details group
	 *
	 */
	
	public function get_group_by_id($group_id="")
	{	
		// if $group_id is not empty
		if(!empty($group_id))
		{	
			// fetch the group details of group having id $group id.
			$result=$this->db->select('*')
							  ->where('id',$group_id)
							  ->get('spo_group_current');

			// return details of group in form of assoc array.				  
			return($result->result_array()[0]);				  
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch all the member of group by given group id
	 * @param   integer 	$group_id 	holds group id
	 * @return	2-d assoc array 		holds members information 
	 *
	 */
	
	public function get_member_by_group_id($group_id='')
	{	
		// if $group_id is not empty
		if(!empty($group_id))
		{   
			// fetch members detail for given group id  from spo_group_member table.
			$result=$this->db->select(array('status','adm_no'))
			                 ->where('group_id',$group_id)
			                 ->get('spo_group_member');

			 // return 2-d assoc array containing status and id of each member of group.                
			 return ($result->result_array());                
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch all the member_id of group by given group id
	 * @param   integer 	$group_id 	holds group id
	 * @return	2-d assoc array 		holds members id
	 *
	 */
	
	public function get_memberid_by_group_id($group_id='')
	{	
		// if $group_id is not empty
		if(!empty($group_id))
		{	
			// fetch member id for given group id from spo_group_member table.
			$result=$this->db->select(array('adm_no'))
			                 ->where('group_id',$group_id)
			                 ->get('spo_group_member');

			 // return 2-d assoc array containing id of each member of group.
			 return ($result->result_array());                
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * This function increment the no of active member of that group when a request is accepted.
	 * In case if no of active member equals to required member it update group status to 1.
	 * @param   integer 	$group_id 	holds group id 
	 * @return	void
	 *
	 */
	

	public function update_group_incr($g_id)
	{	

		// if $g_id is not empty
		if(!empty($g_id))
		{	
			// Increase no of active member in that group
			$result =$this->db->set('mem_accept', 'mem_accept+1', FALSE)
				   	 ->where('id', $g_id)
					 ->update('spo_group_current');

			// if query executed successfully		 
			if($result)
			{	
				// fetch active member and required member for that group
				$active=$this->db->select(array('mem_accept','no_of_mem'))
						 ->where('id',$g_id)
						 ->get('spo_group_current')->result_array()[0];

				// total active member
				$active_mem=$active['mem_accept'];

				// total required member
				$req_mem=$active['no_of_mem'];
				
				// if active member equals required member
				if($active_mem==$req_mem)
				{	
					// update group status to 1
					$result =$this->db->set('status', 1, FALSE)
				   	 ->where('id', $g_id)
					 ->update('spo_group_current');

					 // fetch members of that group to send notification
					 $members=$this->db->select('adm_no,cat_code')
					                  ->where('group_id',$g_id)
					                  ->get('spo_group_member');
					 $members=$members->result_array();

					 // sending notification to each member of group about the activation of group.                 
					 foreach ($members as $member ) 
					 {
					      $user_id=$member['adm_no'];

					      $group_code="G-".$member['cat_code']."-".$g_id;

					     $this->notification->notify($user_id,'stu','Sports','Group code '. $group_code.' is now active','spo_section/group/view_group','spo_section/group/view_group');            	
					 }                 
				}

			}

			// return status of operation (completed successfully)
			return array('status'=>true);		 
		}

		// return status of operation (failed)
		return array('status'=>false);
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch the all group belongs to a user 
	 * @param   string 		$user_id 	holds user id
	 * @return	2-d assoc array         holds the group info.
	 *
	 */
	
	function get_group_by_member_id($user_id="")
	{	

		// if $user_id is not empty
		if(!empty($user_id))
		{	
			// fetch all the group for given user id from spo_group member having status 1,3,and 4.
			$result=$this->db->select("group_id")
					 ->where([
								'adm_no'	=>	$user_id,
							])
					 ->where_in('status',array(1,3,4))
					 ->order_by('update_timestamp','DESC')
					 ->get('spo_group_member');
				// print_r($this->db->last_query());

			// return 2-d assoc array containing the info of fetched group for given user id.
			return($result->result_array());		 
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch details for each group id given in $g_id_arr list.
	 * @param   array 	$g_id_arr 		holds list group id
	 * @return	2-d assoc array         holds the group info.
	 *
	 */
	
	public function get_group_by_g_id_arr($g_id_arr=[])
	{	
		// if $g_id_arr is array and not empty
		if(is_array($g_id_arr)&&!empty($g_id_arr))
		{	
			// fetch the all group  whose id in $_g_id_arr
			$query=$this->db->where_in('id',$g_id_arr)
							->order_by('update_timestamp','DESC')
							->get('spo_group_current');

			// return 2-d assoc array containing the info of fetched group for given Group id list.						
		   return($query->result_array());					
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch group for given category code and user id
	 * @param   string 		$cat_code    holds the category code
	 * @param   string 		$user_id     holds the user id
	 * @return	array                    holds the group id
	 *
	 */
	
	public function get_groupid_by_cat_user($cat_code="",$user_id="")
	{	
		// if $cat_code and $user_id is not empty
		if(!empty($cat_code)&&!empty($user_id))
		{	
			// fetch group id of group with given category code and user id.
			$query=$this->db->select('group_id')
							->where(array("cat_code"=>$cat_code,"adm_no"=>$user_id))
							->get('spo_group_member');

			// return the array containing group id for given category code and user id. 
		    return($query->result_array()[0]);					
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch the all category code available to a given user
	 * category code is available if user is part of that group
	 * @param   string 		$user_id     holds the user id  
	 * @return	2-d assoc array          holds the category code
	 *
	 */
	
	public function get_catid_by_userid($user_id='')
	{	

		// if $user_id is not empty
		if(!empty($user_id))
		{	
			// fetch all category code available for a given user
			$query=$this->db->select('cat_code')->where(array("adm_no"=>$user_id))->get("spo_group_member");

			// return 2-d assoc array containing the all fetched category code for a given user.
			return $query->result_array();
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch all the active group whose id is in given group id list 
	 * @param   array    $g_id_arr       holds the group id 
	 * @return	2-d assoc array 		 holds the active groups
	 *
	 */
	
	public function get_active_group_by_g_id_arr($g_id_arr=[])
	{	

		// if $g_id_arr is array and not empty
		if(is_array($g_id_arr)&&!empty($g_id_arr))
		{	
			// fetch active group whose id in a given list
			$query=$this->db->where_in('id',$g_id_arr)
							->where(array("status"=>1))
							->get('spo_group_current');

		   // return 2-d assoc array containing the details of fetched active group from a given list					
		   return($query->result_array());					
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch the dues of given member who is a part of a given group
	 * @param   array     $member 	 holds member user id
	 * @param   integer   $group_id  holds the group id
	 * @return	2-d assoc array      holds member id and corresponding dues in that group
	 *
	 */
	
	public function get_dues_by_member_arr($member=array(),$group_id='')
	{	
		// if $member is array and $member, $group_id is not empty
		if(is_array($member)&&!empty($member)&&!empty($group_id))
		{   
			// fetch dues of all member in a given group 
			$result=$this->db->select('adm_no ,sum(quantity_issued) as dues')
							->where_in('adm_no',$member)
							->where(array('status'=>3,'group_id'=>$group_id))
							->group_by('adm_no')
							->get('spo_issue');	

			// return 2-d assoc array containing member dues for a given group						
			return ($result->result_array());
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch total no of group belongs to a user in a given category
	 * @param   string 		$cat_code       holds the category code
	 * @param   string 		$user_id        holds the user id
	 * @return	integer                     holds total no of group
	 *
	 */
	
	public function get_total_group_cat_user_id($cat_code='',$user_id='')
	{    
		// if $cat_code and $user_id is not empty
		if(!empty($cat_code)&&!empty($user_id))
		{ 	

			// fetch the all group belongs to a given user
			$result=$this->db->select('group_id')
							 ->where(array('cat_code'=>$cat_code,'adm_no'=>$user_id))
							 ->where_in('status',array(0,1,3,4))
							 ->get('spo_group_member');

			// return total no of group for a given user				 
			return count($result->result_array()); 				 
		}
	}


//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch the rejected group of a id
	 * @param   string 		$user_id        holds the user id
	 * @return	array                     holds total no of group
	 *
	 */
	
	public function get_rejected_group($user_id=''){    
		return $this->db->select('SGC.id,SGC.code,SC.name,SGC.status,SGC.description')
				->where([
				'status'			=>	2,
				'captain_adm_no'	=>	$user_id,
		])
				->join('spo_category as SC','SC.code = SGC.cat_code')
				->get('spo_group_current as SGC')->result_array();
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch all the category available to user for creating a group
	 * A category will be available if user is not a part of group in given category
	 * A category will be also available in case user is part of group but group has been dissolved
	 * @param   string 		$user_id        holds the user id
	 * @return	2-d assoc array             holds the info of all such category
	 *
	 */
	
	public function get_cat_to_create_group($user_id='')
	{	
		// if $user_id is not empty
		if(!empty($user_id))
		{	
			// query to select all category in which user is not a part of group of that category.
			$query="SELECT * from spo_category where code not in (SELECT cat_code from spo_group_member where adm_no=? and status in (0,1,4) ) ";

			// executing query
			$result=$this->db->query($query,array($user_id));

			// return the 2-d assoc array containing all such category for a user.
			return($result->result_array());
		}
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It validate an item with a given group id
	 * An item is valid if category code of both item as well as group is same.
	 * @param   integer    $item_id 		holds the item id
	 * @param   integer    $group_id 		holds the group id
	 * @return	bool 						valid(true) and Invalid(false)
	 *
	 */
	
	public function validate_group_by_item_id($item_id='',$group_id='')
	{   
		// if $item_id and $group_id is not empty
		if(!empty($item_id)&&!empty($group_id))
		{	
			// query to select group with given group id and whose category code equals the category code of given item.
			$query="SELECT G.id from spo_group_current as G where G.id =? and G.cat_code in (select cat_code from spo_items as I where I.id=?) ";

			// executing query
			$result=$this->db->query($query,array($group_id,$item_id));

			// if such single group not exist  
			if(count($result->result_array())!=1)
				return false;
			else
				return true;
		}
	}

	//-----------------------------------------------------------------------------------------------------------
}


#################################### GROUP_MODEL CLASS END HERE #############################################


