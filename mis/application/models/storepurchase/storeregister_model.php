<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Storeregister_model extends CI_Model

{
	var $srtable = 'sp_stock_register';
        var $sritem = 'sp_stock_register_items';
      
        
	
	public function __construct() 
    { 
        parent::__construct(); 
       
    } 
	
	
	
	// Insert in category table
	function insert($data)
	{
		if($this->db->insert($this->srtable,$data))
			//return TRUE;
                        return $this->db->insert_id();
		else
			return FALSE;
	}
        function update_stock_register($data,$id,$ponum)
	{
		$array = array('sr_id' => $id, 'ponum' => $ponum);
                $this->db->where($array); 
                $this->db->update($this->srtable, $data);
	}
        
        function update_stock_register_item($id,$itno,$itco,$qty,$rate,$amt)
        {
                 
            $myquery="update sp_stock_register_items set item_qty=".$qty.",item_rate=".$rate.",item_amt=".$amt." where id=".$id." and itemno=".$itno." and itemcode='".$itco."'";
            
            
               
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
        // update item master table from edit item page------------
        
        function update_item_master($id,$itno,$itco,$qty)
        {
            //$mquery="Select item_qty from sp_stock_register_items where itemno=".$itno." and itemcode='".$itco."' and sno=".$sno;
            $mquery="Select item_qty from sp_stock_register_items where id=".$id;
            $q=$this->db->query($mquery);
            $qty_available=$q->row()->item_qty;
           
         
        $myquery="update sp_item_master set qty=(qty-".$qty_available.")+".$qty." where itemno=".$itno." and itemcode='".$itco."'";
        
       // echo $myquery;
       // die();
        
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
        
        
        //--------------------------------------------------
        
        
        
        function deletefrom_stockReg_items($id,$tponum)
	{
		$this->db->delete($this->sritem, array('sno' => $id,'ponum'=>$tponum));
	}
        
        
        function insert_batch($data)
	{
		if($this->db->insert_batch($this->sritem,$data))
			return TRUE;
		else
			return FALSE;
	}
        function get_store_register_item()
        {
            $query = $this-> db->get_where('sp_stock_register'); 
            
                        if($query->num_rows() > 0)
			{	
                            return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_stock_register_data_byID($id)
        {
          
        $myquery = "SELECT
  `sp_stock_register`.*,
  `sp_supplier`.`s_name`,
  `departments`.`name`
FROM
  `sp_stock_register`
  INNER JOIN `departments` ON `sp_stock_register`.`dept` = `departments`.`id`
  INNER JOIN `sp_supplier` ON `sp_stock_register`.`supplier_nm` =
    `sp_supplier`.`s_id`
     where `sp_stock_register`. `sr_id`= " . $id ;
        
        
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
             return $query->result();
        } else {
            return FALSE;
        }
		
        }
        
        function get_stock_register_items_byID($id)
        {
             /*   $myquery = "
                    SELECT
  `sp_stock_register_items`.*,
  `sp_item`.`itemname`,
  `sp_dept_location`.`location` AS `location1`
FROM
  `sp_stock_register_items`
  INNER JOIN `sp_item` ON `sp_stock_register_items`.`itemno` =
    `sp_item`.`itemno` AND `sp_stock_register_items`.`itemcode` =
    `sp_item`.`itemcode`
  INNER JOIN `sp_dept_location` ON `sp_stock_register_items`.`location` =
    `sp_dept_location`.`dep_loc_id`
                    
     where `sp_stock_register_items`. `sno`= " . $id ;*/
        
        $myquery = "SELECT
  `sp_item`.`itemname`,
  `sp_stock_register_items`.`id`,
  `sp_stock_register_items`.`ponum`,
  `sp_stock_register_items`.`sno`,
  `sp_stock_register_items`.`itemno`,
  `sp_stock_register_items`.`itemcode`,
  `sp_stock_register_items`.`location`,
  `sp_stock_register_items`.`pstatus`,
  `sp_stock_register_items`.`item_qty`,
  `sp_stock_register_items`.`item_amt`,
  `sp_stock_register_items`.`item_rate`
FROM
  `sp_stock_register_items`
  INNER JOIN `sp_item`
    ON `sp_item`.`itemno` = `sp_stock_register_items`.`itemno` AND
    `sp_item`.`itemcode` = `sp_stock_register_items`.`itemcode`
    where `sp_stock_register_items`. `sno`= " . $id ;
        
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
             return $query->result();
        } else {
            return FALSE;
        }
        }
        
        function get_stock_register_items_byID_instock($id)
        {
            $myquery = "
                    SELECT
  `sp_item`.`itemname`,
  `sp_stock_register_items`.`id`,
  `sp_stock_register_items`.`ponum`,
  `sp_stock_register_items`.`sno`,
  `sp_stock_register_items`.`itemno`,
  `sp_stock_register_items`.`itemcode`,
  `sp_stock_register_items`.`location`,
  `sp_stock_register_items`.`pstatus`,
  `sp_stock_register_items`.`item_qty`,
  `sp_stock_register_items`.`item_amt`,
  `sp_stock_register_items`.`item_rate`
FROM
  `sp_stock_register_items`
  INNER JOIN `sp_item`
    ON `sp_item`.`itemno` = `sp_stock_register_items`.`itemno` AND
    `sp_item`.`itemcode` = `sp_stock_register_items`.`itemcode` and
      `sp_stock_register_items`.`location`='instock'
      where `sp_stock_register_items`. `sno`= " . $id ;
            $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
             return $query->result();
        } else {
            return FALSE;
        }
        }
        
        function get_item_tot_quantity_byID($id,$po)
        {
            $it=explode("-", $id);
            $array = array('itemno' => $it[0],'itemcode' => $it[1] ,'sno' => $po);     
            $query=$this->db->select('item_qty')->get_where('sp_stock_register_items',$array);
         
                if ( $query->num_rows() > 0 )
                {
                    $row = $query->row_array();
                    return $row;
                }
                else { return false;}
            
        }
        
        function get_item_issued_quantity_byID($id,$po)
        {
            $it=explode("-", $id);
            $array = array('itemno' => $it[0],'itemcode' => $it[1] ,'ponum' => $po);   
         $query=$this->db->select('sum(qty_issue)-sum(item_rec_qty) as qty_issue')->get_where('sp_item_issue',$array);
         
                if ( $query->num_rows() > 0 )
                {
                    $row = $query->row_array();
                    return $row;
                }
                else { return false;}
            
        }
        
         function getmasteritm($itmemno,$itemcode){
             
             $q=$this->db->get_where('sp_item_master',array('itemno'=>$itmemno,'itemcode'=>$itemcode));
             if($q->num_rows() > 0){
                 return $q->row();
             }
             return false;
         }
        
         function updateitemmaster($data,$con){
             $this->db->update('sp_item_master', $data, $con);
         }
	
	function get_ponum_byID($itmemno,$itemcode)
        {
            $q=$this->db->get_where('sp_stock_register_items',array('itemno'=>$itmemno,'itemcode'=>$itemcode));
             if($q->num_rows() > 0){
                 return $q->result();
             }
             return false;

        }

}

?>