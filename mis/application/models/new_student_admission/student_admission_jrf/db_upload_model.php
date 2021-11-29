<?
class Db_upload_model extends CI_Model
{
	var $table = 'stu_temp_db_jrf'; // table name in the database in which data of newly admitted students are stored.

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function insert($data)
	{
		$table_fields='reg_id';
		
		foreach($data['fields'] as $field)
		{
			if($field!='reg_id')
			{
				$field=", ".$field."";
				$table_fields=$table_fields.$field;
			}
		}
		
		
		$table_row="\"".$data['values']['0']['reg_id']."\"";
			
			foreach($data['fields'] as $field)
			{
				if($field!='reg_id')
				{
					if($field=='contact_no' || $field=='iit_jee_rank' || $field=='gate_score' || $field=='cat_score' || $field=='iit_jee_cat_rank')
					{
						$table_row=$table_row.",\"".$data['values']['0'][$field]."\"";
					}
					else
					    $table_row=$table_row.",\"".$data['values']['0'][$field]."\"";
				}
			}
		
		$table_data="(".$table_row.")";
		
		for($i=1;	$i<sizeof($data['values']);	$i++)
		{
			$table_row="\"".$data['values'][$i]['reg_id']."\"";
			
			foreach($data['fields'] as $field)
			{
				if($field!='reg_id')
				{
					if($field=='contact_no' || $field=='iit_jee_rank' || $field=='gate_score' || $field=='cat_score' || $field=='iit_jee_cat_rank')
					{
						$table_row=$table_row.",".$data['values'][$i][$field];
					}
					else
					$table_row=$table_row.",\"".$data['values'][$i][$field]."\"";
				}
			}
			
			$table_data=$table_data.", (".$table_row.")";
		}
		
		$query='INSERT INTO '.$this->table.' ('.$table_fields.') VALUES '.$table_data;
		
		$this->db->query($query);
		
			
	}
	
	function delete_by_reg_id($reg_id)
	{
		if($reg_id!='')
		{
			$query=$this->db->delete($this->table, array('reg_id' => $reg_id));
		}
	}
	
	
	
	function get_admission_no($q)
	{
		$this->db->select('admission_no','availability');
		$this->db->like('admission_no', $q);
		$this->db->not_like('availability','NA');
		$this->db->limit(10);
		$query = $this->db->get('stu_temp_adm_no_jrf');
		if($query->num_rows() > 0)
		{
			 foreach ($query->result_array() as $row)
			 {
				$row_set[] = htmlentities(stripslashes($row['admission_no'])); //build an array
			 }
			 echo json_encode($row_set); //format the array into json data
		}
  	}
	
	function check($Adm1)
	{
	    $query1 = $this->db->get_where('stu_temp_adm_no_jrf', array('admission_no' => $Adm1));
	   $row1=$query1->row();
       
          if($row1->availability=='NA')
		  {
		     return false;
		  } 
		  else
		  return true;
	}
	
	function allocate($Adm)
	{   
		$arr=array('availability'=>'NA');
		$this->db->where('admission_no',$Adm);
		$this->db->update('stu_temp_adm_no_jrf',$arr);
	}
	
	function getuser($reg_id2)
	{
	   $query2 = $this->db->get_where('stu_temp_db_jrf', array('reg_id' => $reg_id2));
	   $row2=$query2->row_array();
	   
	   if($row2==NULL)
	   return NULL;
	   
	   $reg_id=$row2['reg_id'];
	   $dep_id=strtolower(substr($reg_id,3,3));
	   
	   $query=$this->db->get_where('dept_code_jrf',array('id'=> $dep_id));
	   $row=$query->row_array();
	   
	   return array_merge($row2,$row);
	   
	}
	
	function give_admission_no($first_pat,$first_adm,$last_adm,$adm_mode)
	{
	    $start=intval($first_adm);
		$end=intval($last_adm);
		$j=0;
		
		$data=array(array('admission_no'=>'','availability'=>''));
		
		for($i=$start;$i<=$end;$i++)
		{  
		    if($i<10)
			{
			  $data[$j]['admission_no']=$first_pat.'00000'.$i;
			}
			else if($i>=10&&$i<=99)
			{
			  $data[$j]['admission_no']=$first_pat.'0000'.$i;
			}
			else if($i>=100&&$i<=999)
			{
			  $data[$j]['admission_no']=$first_pat.'000'.$i;
			}
			else if($i>=1000&&$i<=9999)
			{
			   $data[$j]['admission_no']=$first_pat.'00'.$i;
			}
		   
			$data[$j]['availability']='A';
			$j++;
			
		}
		
		
		$table_name='stu_temp_adm_no_jrf';
		
		$this->db->insert_batch($table_name, $data);
	}
	
	
	function feedback($Adm_no,$course_id,$branch_id)
	{
	    $data=array('admn_no'=>$Adm_no,'course_id'=>$course_id,'branch_id'=>$branch_id);
	    $this->db->insert('fb_student_feedback',$data);
	}
	
}
?>