<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Fy_model extends CI_Model

{
	var $table_fy = 'hc_budget';
	
	public function __construct() 
    { 
        parent::__construct(); 
       
    } 
	
	function check_fyear_availablity($fyear)
	{
			
			$query = $this->db->query("SELECT * FROM hc_budget where curr_fin_year='".$fyear."'");
			
			if($query->num_rows() > 0)
			{	
				
			return TRUE;
			}
                        else
                        {
                            return FALSE;
                        }
	}
	
	
	
	function insert($data)
	{
		if($this->db->insert($this->table_fy,$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
        function check_finyear_status()
	{
		$myquery="SELECT * FROM hc_budget where status='active'";
                $query = $this->db->query($myquery);
                
                if($query->num_rows()>0)
                {
			return	$query->row()->budget_id;
                }
		else
                {
			return FALSE;
                }
	}
        function update_bug_status($id)
        {
            $myquery="update hc_budget set status='inactive' where budget_id=".$id;
            $query = $this->db->query($myquery);
            return true;
        }
        
        
        function insert_bud_log($res)
	{
	    date_default_timezone_set('Asia/Calcutta');
            $timestamp = date('Y-m-d H:i:s');  
            $data= array_merge($res[0],array('usernm'=>$this->session->userdata('id')),array('created_on'=>$timestamp));	
               if($this->db->insert('hc_budget_log',$data))
			return TRUE;
		else
			return FALSE;
	}
	
	function fyear_getAll()
	{
			
			//$query = $this->db->query("SELECT * FROM hc_budget where curr_fin_year=(SELECT YEAR(NOW()))");
            
                        $query = $this->db->query("SELECT * FROM hc_budget where status='active'");
			
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
	function fyear_getAll_view()
	{
			
			$query = $this->db->query("SELECT * FROM hc_budget order by curr_fin_year desc");
			
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
	
	function getFYById($id,$t=""){
			$this->db->select('*');
			$this->db->from($this->table_fy); 
            $this->db->where('budget_id',$id);
		$q= $this->db->get();
		if($t)
		return	$q->result_array();
		else
		return	$q->result();
	}
        function getFYById_log($id)
        {
	       
            $this->db->select('*');
            $this->db->from($this->table_fy); 
            $this->db->where('budget_id',$id);
		$q= $this->db->get();
               
		if($q->num_rows()>0)
		return	$q->result_array();
		else
		return	FALSE;
	}
        
        
	
	function delete_budget($id)
	{
		$this->db->where('budget_id', $id);
		$this->db->delete('hc_budget');
		
	}
	function show_budget_id($data){
        $this->db->select('*');
        $this->db->from('hc_budget');
        $this->db->where('budget_id', $data);
        $query = $this->db->get();
        $result = $query->result();
        return $result;  
    }
	function update_budget($id,$data){
     $this->db->where('budget_id', $id);
     $this->db->update('hc_budget', $data);  
    }
	
	function get_Sum_group($dt1)
	{
		//echo $dt1;die();
            $dt= explode('-', $dt1);
            //print_r($dt);die();
            $stime=$dt[0]."-04-01";
            $etime=$dt[1]."-03-31";
            $query = $this->db->query("SELECT 
  sum(`hc_medi_receive`.`amount`) AS `gtotal`,
  `hc_manufacturer`.`m_group`
FROM
  `hc_medicine`
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
  INNER JOIN `hc_medi_receive` ON (`hc_medicine`.`m_id` = `hc_medi_receive`.`m_id`)
WHERE
  `hc_medi_receive`.`supp_date` BETWEEN CAST('".$stime."' AS DATE) AND CAST('".$etime."' AS DATE)
GROUP BY
  `hc_manufacturer`.`m_group`

");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
	function get_rowFrom_mRec($dt1)
	{
            $dt= explode('-', $dt1);
            $stime=$dt[0]."-04-01";
            $etime=$dt[1]."-03-31";
            
            $query = $this->db->query("SELECT 
  sum(`hc_medi_receive`.`amount`) AS `gtotal`,
  `hc_manufacturer`.`m_group`
FROM
  `hc_medicine`
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
  INNER JOIN `hc_medi_receive` ON (`hc_medicine`.`m_id` = `hc_medi_receive`.`m_id`)
WHERE
  `hc_medi_receive`.`supp_date` BETWEEN CAST('".$stime."' AS DATE) AND CAST('".$etime."' AS DATE)
GROUP BY
  `hc_manufacturer`.`m_group`

");
		
		if($query->num_rows() > 0)
			{	
				
			return $query->num_rows(); 
			}
			else
			{
				return FALSE;
			}
		
	}
        
        
        
        //----------------get data from main financiyal table-------------
        
        function show_budget_CFY($id)
	{
			
			//$query = $this->db->query("SELECT * FROM hc_budget where curr_fin_year=(SELECT YEAR(NOW()))");
            
                        $query = $this->db->query("SELECT * FROM hc_budget where budget_id=".$id);
			
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
        
        //-----------------------------------------------------------------
        
        //-----------------get financial year from budget log file----------
        
        function show_budget_LOGFY($id)
	{
			
			//$query = $this->db->query("SELECT * FROM hc_budget where curr_fin_year=(SELECT YEAR(NOW()))");
            
                        $query = $this->db->query("SELECT * FROM hc_budget_log where budget_id=".$id);
			
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
        
        //-------------------------------------------------------------------
 


}

?>