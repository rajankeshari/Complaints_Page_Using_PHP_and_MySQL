<?
class Db_upload_model extends CI_Model
{
	var $table = 'stu_temp_db_mtech'; // table name in the database in which data of newly admitted students are stored.

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
		
		
	
		//$this->db->insert_batch($this->table,$data['values']);
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
		$query = $this->db->get('stu_temp_adm_no_mtech');
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
	    $query1 = $this->db->get_where('stu_temp_adm_no_mtech', array('admission_no' => $Adm1));
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
		$this->db->update('stu_temp_adm_no_mtech',$arr);
	}
	
	function getuser($reg_id2)
	{
	   $query2 = $this->db->get_where('stu_temp_db_mtech', array('reg_id' => $reg_id2));
	   $row2=$query2->row_array();
	   
	  return $row2;
	   
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
		
		
		$table_name='stu_temp_adm_no_mtech';
		
		$this->db->insert_batch($table_name, $data);
	}
	
	
	function feedback($Adm_no,$course_id,$branch_id)
	{
	    $data=array('admn_no'=>$Adm_no,'course_id'=>$course_id,'branch_id'=>$branch_id);
	    $this->db->insert('fb_student_feedback',$data);
	}
	
	function get_latest_aggr_id($q)
	{
	    $this->db->select('aggr_id');
		$this->db->like('aggr_id', $q);
		$this->db->order_by("aggr_id", "desc"); 
		$query = $this->db->get('course_structure');
		if($query->num_rows() > 0)
		{
		 return $query->result()[0]->aggr_id;
		}
	}
	
	function get_branch_by_course_id()
	{
	     $this->db->where('course_id','m.tech');
		 $query=$this->db->get('course_branch');
		 $res=$query->result();
		 
		 
		 $result=array();
		 
		 $i=0;
		 foreach($res as $r)
		 {
		     $this->db->where('id',$r->branch_id);
			 $query1=$this->db->get('cs_branches');
			 $result[$i]=$query1->row();
			 
			 $i++;
		 }
		 return $result;
	}
	
	function get_dept_by_branch_id($branch_id)
	{
	    for($i=0;$i<strlen($branch_id);$i++)
		{
		    if($branch_id[$i]==' ')
			{
			  $branch_id[$i]='+';
			}
		}
		
	    $this->db->where('branch_id',$branch_id);
		$query=$this->db->get('course_branch');
		$row=$query->row_array();
		
		$this->db->where('course_branch_id',$row['course_branch_id']);
		$query1=$this->db->get('dept_course');
		$row1=$query1->row_array();
		
		$this->db->where('id',$row1['dept_id']);
		$query2=$this->db->get('departments');
		$row3=$query2->row_array();
		
		return $row3;
	}
	
}
?>