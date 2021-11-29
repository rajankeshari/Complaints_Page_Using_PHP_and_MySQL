<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Supplier_medicine_mapping_model extends CI_Model

{
	var $table_supp = 'hc_supplier';
	var $table_manu = 'hc_manufacturer';
	var $table_supp_manu='hc_supp_manu';
        var $table_mtype = 'hc_med_type';
         var $table_mgroup = 'hc_med_group';
        
        
	
	
	public function __construct() 
    { 
        parent::__construct(); 
       
    } 
	
		function get_supplier_list()
		{
			$this->db->from($this->table_supp);
			$this->db->order_by('s_name');
			$result = $this->db->get();
			//$return = array();
			if($result->num_rows() > 0) {
				return $result->result();
			/*foreach($result->result_array() as $row) {
			$return[$row['s_id']] = $row['s_name'];
			}*/
			}else{
				//return $return;
				return false;
			}

					

		}
		function get_supplier_list1()
		{
			$this->db->from($this->table_supp);
			$this->db->order_by('s_name');
			$query = $this->db->get();
			$query_result = $query->result();
			return $query_result;
					
			

		}
		
		function get_manu_list()
		{
			$this->db->from($this->table_manu);
			$this->db->order_by('manu_name');
			$query = $this->db->get();
			$query_result = $query->result();
			return $query_result;

		}
                //-----------------------Calling Medicine Type-------------------------------
                function get_mtype_list()
		{
			$this->db->from($this->table_mtype);
			$this->db->order_by('mtype');
			$query = $this->db->get();
			$query_result = $query->result();
			return $query_result;

		}
                
                //-----------------------Calling Medicine Group Name-------------------------
                function get_mgroup_list()
		{
			$this->db->from($this->table_mgroup);
			$this->db->order_by('mgroupc');
			$query = $this->db->get();
			$query_result = $query->result();
			return $query_result;

		}
                //----------------------------------------------------------------------------
                
		function get_manu_list1()
		{
			$this->db->from($this->table_manu);
			$this->db->order_by('manu_name');
			$result = $this->db->get();
			//$query_result = $query->result();
			$return = array();
			if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
			$return[$row['manu_id']] = $row['manu_name'];
			}
			}

					return $return;

		}
		
		function insert($data)
		{
			if($this->db->insert($this->table_supp_manu,$data))
				return $this->db->insert_id();
			else
				return FALSE;
		}
                function getSuppById($id,$t=""){
			$this->db->select('id');
			$this->db->from($this->table_supp_manu); 
            $this->db->where('id',$id);
		$q= $this->db->get();
		if($t)
		return	$q->result_array();
		else
		return	$q->result();
	}

                
                
                function get_manu_list_bySupp($id,$t="")
                {
                    $query = $this->db->query("
                    SELECT   `hc_supplier`.`s_name`,  `hc_manufacturer`.`manu_name`
                    FROM `hc_supp_manu`  INNER JOIN `hc_supplier` ON (`hc_supp_manu`.`s_id` = `hc_supplier`.`s_id`)
                    INNER JOIN `hc_manufacturer` ON (`hc_supp_manu`.`manu_id` = `hc_manufacturer`.`manu_id`)
                    WHERE  `hc_supplier`.`s_id` ='".$id."'");
                    if($t==1)
                    return	$query->result_array();
                    else
                    return	$query->result();
                }
                
                function get_both_byid($id,$t="")
                {
                    $query = $this->db->query("
                    SELECT   `hc_supplier`.`s_name`,  `hc_manufacturer`.`manu_name` FROM
                    `hc_supp_manu`   INNER JOIN `hc_manufacturer` ON (`hc_supp_manu`.`manu_id` = `hc_manufacturer`.`manu_id`)
                      INNER JOIN `hc_supplier` ON (`hc_supp_manu`.`s_id` = `hc_supplier`.`s_id`)
                    WHERE   `hc_supp_manu`.`id` ='".$id."'");                                       
                    
                    
                    if($t==1)
                    return $query->result_array();
                    else
                    return $query->result();
                }
                
               function  validate_supp_manu($sid,$manuid)
               {
                   $this->db->select("*");
                   $this->db->from($this->table_supp_manu);
                   $this->db->where('s_id',$sid);
                   $this->db->where('manu_id',$manuid);
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