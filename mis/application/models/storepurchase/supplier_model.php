<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Supplier_model extends CI_Model

{
	var $table = 'sp_supplier';
	
	public function __construct() 
    { 
        parent::__construct(); 
       
    } 
	
	
	
	
	function insert($data)
	{
		if($this->db->insert($this->table,$data))
			return TRUE;
		else
			return FALSE;
	}
        function update_supplier($id,$data){
     $this->db->where('s_id', $id);
     $this->db->update('sp_supplier', $data);  
     
    }
	
	function getAll_Suppliers()
	{
			
			$query = $this->db->query("SELECT * FROM sp_supplier order by s_name");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
        
        function get_Suppliers_byID($id)
	{
			
			$query = $this->db->query("SELECT * FROM sp_supplier where s_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
	function get_states()
	{
			
			
			$this->db->from('indian_states');
			$this->db->order_by('state_name');
			$query = $this->db->get();
			$query_result = $query->result();
			return $query_result;

	
			
	}
	public function check_supp_exist($id)
            {
             $this->db->where('s_name',$id);
             $query=$this->db->get('sp_supplier');
            if($query->num_rows()>0)
             {
              return true;
             }
             else
             {
              return false;
             }
            }
            
            public function get_supplier()
            {
                  $query = $this-> db-> get('sp_supplier'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
            }
            
             function get_supplier_name($id)
        {
            $query = $this->db->query("SELECT * FROM sp_supplier where s_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_supp_status()
	{
			
			
			$this->db->from('sp_supplier_status');
			$this->db->order_by('supp_status');
			$query = $this->db->get();
			$query_result = $query->result();
			return $query_result;

	
			
	}
        function getSupplier_status_byID($id)
        {
            $query = $this->db->query("SELECT * FROM sp_supplier_status where supp_status_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->row();
			}
			else
			{
				return FALSE;
			}
        }
        
        // Function to get department id of logged in person
        
            function get_deptId($id)
            {

                            $query = $this->db->query("select dept_id from user_details where id='".$id."'");
                            if($query->num_rows() > 0)
                            {	

                            return $query->row();
                            }
                            else
                            {
                                    return FALSE;
                            }
            }
            
            
            function get_supplier_items($id)
        {
          /*  $query = $this->db->query("SELECT
  `sp_item`.`itemname`,
  `sp_item_supplier`.`item_no`,
  `sp_item_supplier`.`item_code`,
  `sp_item_supplier`.`supp_id`
FROM
  `sp_item`
  INNER JOIN `sp_item_supplier` ON `sp_item_supplier`.`item_no` =
    `sp_item`.`itemno` AND `sp_item_supplier`.`item_code` = `sp_item`.`itemcode`
      where supp_id=".$id."
ORDER BY
  `sp_item`.`itemname`
");*/
$query = $this->db->query("select d.itemname,d.itemno as item_no,d.itemcode as item_code,a.s_id as supp_id from sp_supplier a 
inner join sp_category_supplier b on b.supp_id=a.s_id
inner join sp_category c on c.cat_id=b.cat_id
inner join sp_item d on d.category=c.cat_id
where a.s_id=".$id." order by d.itemname");


			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_supplier_by_itemcode_m($item_id){
            $query = $this->db->query("select c.s_id,c.s_name from sp_item a 
inner join sp_item_supplier b on b.item_no=a.itemno inner join sp_supplier c on c.s_id=b.supp_id
where a.itemno=".$item_id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_report_all($data){
            $where="";
            //echo $data['selsupplier'];die();
            //echo '<pre>'; print_r($data);echo '</pre>';die();
            $sql = "select a.cat_name,b.sub_cat_name,c.itemname,c.itemtype,c.itemcode,e.s_name from sp_category a
inner join sp_sub_category b on b.cat_id=a.cat_id
inner join sp_item c on c.subcategory=b.sub_cat_id and c.category=a.cat_id
inner join sp_item_supplier d on d.item_no=c.itemno
inner join sp_supplier e on e.s_id=d.supp_id where 1=1";
            
            if($data['selsupplier']!='none'){
                $sql.=" and e.s_id=?";
                $where.=$data['selsupplier'];
            }
            
            $sql.=" order by a.cat_name,b.sub_cat_name,c.itemname,c.itemtype,c.itemcode,e.s_name";
        $query = $this->db->query($sql,array($where));


        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return false;
        }
            
            
        }

}

?>