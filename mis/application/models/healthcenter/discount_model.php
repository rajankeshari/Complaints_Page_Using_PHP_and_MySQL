<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Discount_model extends CI_Model

{
	
	var $mdis_tbl= 'hc_rate_dis_manu';
	var $sdis_tbl= 'hc_rate_dis_supp';
	
	
	public function __construct() 
    { 
        parent::__construct(); 
       
    } 
	
	function manu_dis_insert($data)
	{
		if($this->db->insert($this->mdis_tbl,$data))
			return TRUE;
		else
			return FALSE;
	}
	
	function supp_dis_insert($data)
	{
		if($this->db->insert($this->sdis_tbl,$data))
			return TRUE;
		else
			return FALSE;
	}
	
	
	
	function get_manu_discount()
	{
		$this->db->select("*");
		$this->db->from('hc_medicine med');
		$this->db->join('hc_manufacturer manuf','med.manu_id=manuf.manu_id');
		$this->db->join('hc_rate_dis_manu rd','rd.m_id=med.m_id');
		$query=$this->db->get();
		if($query->num_rows()!=0)
		{
			return $query->result();
		}
		else{
			return false;
		}
	}
	function get_supp_discount()
	{
		
		$query = $this->db->query("
		SELECT 
  `hc_supplier`.`s_name`,
  `hc_medicine`.`m_name`,
  `hc_rate_dis_supp`.`dis`,
  `hc_rate_dis_supp`.`sdvfrom`,
  `hc_rate_dis_supp`.`sdvto`,
  `hc_manufacturer`.`manu_name`,
  `hc_supplier`.`s_id`,
  `hc_medicine`.`m_id`,
  `hc_manufacturer`.`manu_id`
FROM
  `hc_rate_dis_supp`
  INNER JOIN `hc_supplier` ON (`hc_rate_dis_supp`.`s_id` = `hc_supplier`.`s_id`)
  INNER JOIN `hc_medicine` ON (`hc_rate_dis_supp`.`m_id` = `hc_medicine`.`m_id`)
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)



		
		");
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
		
		
	}
	
	function get_comparision()
	{
		
		$query = $this->db->query("

SELECT 
  `hc_medicine`.`m_name`,
  `hc_manufacturer`.`manu_name`,
  `hc_supplier`.`s_name`,
  `hc_rate_dis_manu`.`dis` AS dism,
  `hc_rate_dis_supp`.`dis` AS diss
FROM
  `hc_medicine`
  INNER JOIN `hc_manufacturer` ON (`hc_medicine`.`manu_id` = `hc_manufacturer`.`manu_id`)
  INNER JOIN `hc_rate_dis_manu` ON (`hc_manufacturer`.`manu_id` = `hc_rate_dis_manu`.`manu_id`)
  AND (`hc_medicine`.`m_id` = `hc_rate_dis_manu`.`m_id`)
  INNER JOIN `hc_rate_dis_supp` ON (`hc_medicine`.`m_id` = `hc_rate_dis_supp`.`m_id`)
  INNER JOIN `hc_supplier` ON (`hc_supplier`.`s_id` = `hc_rate_dis_supp`.`s_id`)
		
		
		");
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
		
		
	}
	
	function get_discount($m_id,$manu_id)
	{
		$this->db->select("*");
		$this->db->from('hc_rate_dis_manu');
		$this->db->where('m_id',$m_id);
		$this->db->where('manu_id',$manu_id);
		$query=$this->db->get();
		
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
		
	}
	
	
	function get_discount_supp($m_id,$s_id)
	{
		$this->db->select("*");
		$this->db->from('hc_rate_dis_supp');
		$this->db->where('m_id',$m_id);
		$this->db->where('s_id',$s_id);
		$query=$this->db->get();
		
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
		
	}
        
        function show_manurc_id($m_id,$manu_id)
        {
        $this->db->select('*');
        $this->db->from('hc_rate_dis_manu');
        $this->db->where('manu_id', $manu_id);
        $this->db->where('m_id', $m_id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;  
      }
      //--------------------------------------------------------------------------------
       function show_supprc_id($s_id,$m_id,$manu_id)
        {
        $this->db->select('*');
        $this->db->from('hc_rate_dis_supp');
         $this->db->where('s_id', $s_id);
        $this->db->where('manu_id', $manu_id);
        $this->db->where('m_id', $m_id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;  
      }
      //------------------------------------------------------------------------------------
      
      function update_manuRc($m_id,$manu_id,$data){
       $this->db->where('m_id', $m_id);
       $this->db->where('manu_id', $manu_id);
       $this->db->update('hc_rate_dis_manu', $data);  
      }
      
      function update_SuppRc($s_id,$m_id,$manu_id,$data){
       $this->db->where('s_id', $s_id);
       $this->db->where('m_id', $m_id);
       $this->db->where('manu_id', $manu_id);
       $this->db->update('hc_rate_dis_supp', $data);  
      }
      
      function getManu_RcById_log($m_id,$manu_id)
        {
	       
            $this->db->select('*');
            $this->db->from('hc_rate_dis_manu'); 
            $this->db->where('m_id',$m_id);
            $this->db->where('manu_id',$manu_id);
            $q= $this->db->get();
            if($q->num_rows()>0)
		return	$q->result_array();
		else
		return	FALSE;
	}
        //---------------------------Suppler--------------------
            function getSupp_RcById_log($s_id,$m_id,$manu_id)
        {
	       
            $this->db->select('*');
            $this->db->from('hc_rate_dis_supp'); 
             $this->db->where('s_id',$s_id);
            $this->db->where('m_id',$m_id);
            $this->db->where('manu_id',$manu_id);
            
            $q= $this->db->get();
            if($q->num_rows()>0)
		return	$q->result_array();
		else
		return	FALSE;
	}
        
        //---------------------------------------------------
        function insert_ManuRc_log($res)
	{
	    $timestamp = date('Y-m-d H:i:s');  
            $data= array_merge($res[0],array('usernm'=>$this->session->userdata('id')),array('created_on'=>$timestamp));	
               if($this->db->insert('hc_rate_dis_manu_log',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function insert_SuppRc_log($res)
	{
	    $timestamp = date('Y-m-d H:i:s');  
            $data= array_merge($res[0],array('usernm'=>$this->session->userdata('id')),array('created_on'=>$timestamp));	
               if($this->db->insert('hc_rate_dis_supp_log',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function  validate($mid,$manuid)
               {
                   $this->db->select("*");
                   $this->db->from('hc_rate_dis_manu');
                   $this->db->where('m_id',$mid);
                   $this->db->where('manu_id',$manuid);
                   $query=$this->db->get();
                   if($query->num_rows()>0){
                       return TRUE;
                   }
                       
                   else {
                       return FALSE;
                   }
               }
          
               function  validate_supp($sid,$manuid,$mid)
               {
                   $this->db->select("*");
                   $this->db->from('hc_rate_dis_supp');
                   $this->db->where('s_id',$sid);
                   $this->db->where('manu_id',$manuid);
                    $this->db->where('m_id',$mid);
                   $query=$this->db->get();
                   if($query->num_rows()>0){
                       return TRUE;
                   }
                       
                   else {
                       return FALSE;
                   }
               }
	
	


}

?>