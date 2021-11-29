<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Category_model extends CI_Model

{
	var $table = 'sp_category';
        var $sub_cat_table = 'sp_sub_category';
        var $sub_cat_type_table='sp_sub_category_type';
        
	
	public function __construct() 
    { 
        parent::__construct(); 
       
    } 
	
	
	
	// Insert in category table
	function insert($data)
	{
		if($this->db->insert($this->table,$data))
			return TRUE;
		else
			return FALSE;
	}
        //Insert in sub cateogry table
        function insert_sub_category($data)
	{
		if($this->db->insert($this->sub_cat_table,$data))
			return TRUE;
		else
			return FALSE;
	}
        // Insert in sub category type table
        
        
         function insert_sub_category_type($data)
	{
		if($this->db->insert($this->sub_cat_type_table,$data))
			return TRUE;
		else
			return FALSE;
	}
//------------------------------------------------------------------------------------------------------------------------------        
        // fetching data from category table
        function get_category()
        {
            $query = $this->db->query("SELECT * FROM sp_category order by cat_name asc");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        // fetch category name by category id
        function get_category_name($id)
        {
                        $query = $this->db->query("SELECT * FROM sp_category where cat_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        // fetching sub category data
        function get_sub_category()
        {
            $query = $this->db->query("SELECT * FROM sp_sub_category");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        // fetching sub category data by category ID
        function get_sub_category_list($id)
        {
                    $query = $this->db->query("SELECT * FROM sp_sub_category where cat_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        // fetching sub category type data
        
        function get_sub_category_type()
        {
            $query = $this->db->query("SELECT * FROM sp_sub_category_type");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        // getting sub category name by sub category id
        function get_sub_category_name($id)
        {
                        $query = $this->db->query("SELECT * FROM sp_sub_category where sub_cat_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_sub_category_type_list($id)
        {
                    $query = $this->db->query("SELECT * FROM sp_sub_category_type where sub_cat_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function check_category($id)
        {
                        $query = $this->db->query("SELECT * FROM sp_category where cat_name='".$id."'");
			if($query->num_rows() > 0)
			{	
				
                                return TRUE;
			}
			else
			{
				return FALSE;
			}
            
        }
        function check_sub_category($id)
        {
                        $query = $this->db->query("SELECT * FROM sp_sub_category where sub_cat_name='".$id."'");
			if($query->num_rows() > 0)
			{	
				
                                return TRUE;
			}
			else
			{
				return FALSE;
			}
            
        }
        function update_category_name($cat_id,$cat_name)
        {
                $myquery="update sp_category set cat_name='".$cat_name."' where cat_id=".$cat_id;
                $query = $this->db->query($myquery);
                if($this->db->affected_rows() >=0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
                
        }
        function update_sub_category($sub_cat_id,$cat_id,$sub_cat_name)
        {
                               
              $myquery=" update sp_sub_category set cat_id=".$cat_id.", sub_cat_name='".$sub_cat_name."' where sub_cat_id=".$sub_cat_id;
              
              $query = $this->db->query($myquery);
                if($this->db->affected_rows() >=0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
                
        }
        function get_category_supplier(){
            $sql="select a.*,b.cat_name,c.s_name from sp_category_supplier a 
inner join sp_category b on b.cat_id=a.cat_id
inner join sp_supplier c on c.s_id=a.supp_id";

        
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
        }
        
        function insert_category_supp($data)
	{
		if($this->db->insert('sp_category_supplier',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function get_catogroy_supplier_list_byID($id){
            $sql="select a.*,b.cat_name,c.s_name from sp_category_supplier a 
inner join sp_category b on b.cat_id=a.cat_id
inner join sp_supplier c on c.s_id=a.supp_id where a.cat_sup_id=?";

        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
        }
        
         function update_category_supplier($id,$data)
        {
                 
            $this->db->where('cat_sup_id', $id);
            $this->db->update('sp_category_supplier', $data);
              
                if($this->db->affected_rows() >0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
                
        }
        
        function get_items_by_subcategory_m($id)
        {
                    $query = $this->db->query("select * from sp_item where subcategory=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
	
	

}

?>