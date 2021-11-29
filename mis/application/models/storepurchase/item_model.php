<?php

class Item_model extends CI_Model
{
	
  	
	

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
 	
	
        function insert($data)
	{
		if($this->db->insert('sp_item',$data))
			//return TRUE;
                        return $this->db->insert_id();
		else
			return FALSE;
	}
        function insert_issue_item($data)
        {
            if($this->db->insert('sp_item_issue',$data))
			return TRUE;
		else
			return FALSE;
        }
        function insert_return_item($data)
        {
            if($this->db->insert('sp_item_return',$data))
			//return TRUE;
                     return $this->db->insert_id();
		else
			return FALSE;
        }
        function insert_item_master($data)
        {
            if($this->db->insert('sp_item_master',$data))
			return TRUE;
		else
			return FALSE;
        }
	
        function insert_item_supp($data)
        {
            if($this->db->insert('sp_item_supplier',$data))
			return TRUE;
		else
			return FALSE;
        }
        
        function insert_dept_location($data)
        {
            if($this->db->insert('sp_dept_location',$data))
			return TRUE;
		else
			return FALSE;
        }
                
        function get_item()
	{
                $query = $this->db->query("select * from sp_item order by itemname");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}
        
        function get_item_issued_list()
	{
              //  $query = $this->db->query("select * from sp_item_issue");
              $query = $this->db->query("select * from sp_item_issue");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}
         function get_item_issued_list_byID($id)
	{
                $query = $this->db->query("select * from sp_item_issue where iss_itm_id=".$id);
            
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;

	}
      
        function check_exists($iname)
        {
            $sql= "select * from sp_item where itemname='".$iname."'";
           
            $query = $this->db->query($sql);
                      
            if($query->num_rows() > 0)
		return true;
	    else
                return false;
        }
        
        function get_item_name($id)
        {
            $query = $this->db->query("SELECT * FROM sp_item where itemno=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_cat_name_byItemID($id)
        {
            $query = $this->db->query("select cat_name from sp_category where cat_id=(select category from sp_item where itemno=".$id.")");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        
        function get_subcat_name_byItemID($id)
        {
            $query = $this->db->query("select sub_cat_name from sp_sub_category where sub_cat_id=(select subcategory from sp_item where itemno=".$id.")");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        function get_item_supplier_list()
        {
            $query = $this->db->query("SELECT * FROM sp_item_supplier");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        function get_item_supplier_list_byID($id)
        {
            $query = $this->db->query("SELECT * FROM sp_item_supplier where it_sup_id=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->row();
			}
			else
			{
				return FALSE;
			}
        }
        
        
        
        function get_dept_location()
        {
            $query = $this->db->query("SELECT * FROM sp_dept_location");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_dept_location_by_id($id)
        {
            $query = $this->db->query("SELECT * FROM sp_dept_location where dept_id='".$id."'");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        function get_dept_location_by_id_forLocation($id)
        {
            $query = $this->db->query("SELECT * FROM sp_dept_location where dep_loc_id='".$id."'");
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_Item_byID($id)
        {
                    $query = $this->db->query("SELECT * FROM sp_item where itemno=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
        }
        
        
        function update_item($id,$data)
        {
                 
            $this->db->where('itemno', $id);
            $this->db->update('sp_item', $data);
              
                if($this->db->affected_rows() >=0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
                
        }
        
        function update_issue_return($id,$rq)
        {
           
          
            $mquery="Select item_rec_qty from sp_item_issue where iss_itm_id=".$id;
            $q=$this->db->query($mquery);
            $qty_available=$q->row()->item_rec_qty;
            $s=$qty_available+$rq;
          
        $myquery="update sp_item_issue set item_rec_qty=".$s." where iss_itm_id=".$id;
        
        $query = $this->db->query($myquery);
            
            if($this->db->affected_rows() >=0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
            
            
//            $qty= $this->db->get_where('sp_item_issue',array('iss_itm_id'=>$id));
//
//          $this->db->last_query();
//            die();
//            $array=array('item_rec_qty'=>$rq);    
//            $this->db->where('iss_itm_id', $id);
//            $this->db->update('sp_item_issue', $array);
//              
//                if($this->db->affected_rows() >=0)
//                { 
//                    return true; 
//                }
//                else
//                {
//                    return false;
//                }
                
        }
        
         function update_item_supplier($id,$data)
        {
                 
            $this->db->where('it_sup_id', $id);
            $this->db->update('sp_item_supplier', $data);
              
                if($this->db->affected_rows() >=0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
                
        }
         function update_dept_location($id,$data)
        {
                 
            $this->db->where('dep_loc_id', $id);
            $this->db->update('sp_dept_location', $data);
              
                if($this->db->affected_rows() >=0)
                { 
                    return true; 
                }
                else
                {
                    return false;
                }
                
        }
        
        function get_qty_by_itemcode($id)
        {
            //$this->db->get_where('sp_item_master',array('itemno'=>substr($id,0,1),'itemcode'=>substr($id,1)))
        }
        
        function update_issued_item_quantity($q,$id)
        {
                $myquery="update sp_item_issue set qty_issue='".$q."' where iss_itm_id=".$id;
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
        
        function get_item_list()
        {
                 $myquery="SELECT
  `sp_item`.`itemname`,
  `sp_item_master`.`qty`
FROM
  `sp_item`
  INNER JOIN `sp_item_master` ON `sp_item`.`itemno` = `sp_item_master`.`itemno`
    AND `sp_item`.`itemcode` = `sp_item_master`.`itemcode`;";
                 
                $query = $this->db->query($myquery);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
        }
        
        function get_report($cat='',$scat='',$item='',$loc='',$dept='',$status='',$po='')
        {
          
            $sql=" SELECT
  `A`.*,
  `B`.*,
  `sp_category`.`cat_name`,
  `sp_sub_category`.`sub_cat_name`
FROM
  (SELECT `sp_stock_register_items`.`sno`,
    `sp_stock_register_items`.`ponum`,
    `sp_stock_register_items`.`itemno`,
    `sp_stock_register_items`.`itemcode`,
    `sp_stock_register_items`.`location`,
    `sp_stock_register_items`.`pstatus`,
    Sum(`sp_stock_register_items`.`item_qty`) AS `tot_sum`,
    `sp_item`.`itemname`,
    `sp_item`.`category`,
    `sp_item`.`subcategory`
  FROM `sp_stock_register_items`
    INNER JOIN `sp_item` ON `sp_stock_register_items`.`itemcode` =
      `sp_item`.`itemcode`
  GROUP BY `sp_stock_register_items`.`itemcode`) `A`
  INNER JOIN `sp_category` ON `A`.`category` = `sp_category`.`cat_id`
  INNER JOIN `sp_sub_category`
    ON `A`.`subcategory` = `sp_sub_category`.`sub_cat_id`
  LEFT JOIN (SELECT (Sum(`sp_item_issue`.`qty_issue`) -
    Sum(`sp_item_issue`.`item_rec_qty`)) AS `tot_issue`,
    `sp_item_issue`.`itemcode`,`sp_item_issue`.`deptno`
  FROM `sp_item_issue`
  GROUP BY `sp_item_issue`.`itemcode`) `B` ON `B`.`itemcode` = `A`.`itemcode`
  having 1=1
";
            
            
          //$sql.=  " group by itemno ";      
            if($cat!="none")
            {
                $sql.= " and category=".$cat;
                
            }
            if($scat!="none")
            {
                $sql.= " and subcategory=".$scat;
                
            }
             if($item!="none")
            {
                $sql.= " and itemno=".$item;
                
            }
            if($dept!="none")
            {
                $sql.= " and deptno='".$dept."'";
                
            }
           if($loc!="none")
            {
                $sql.= " and location1='".$loc."'";
                
            }
                              
            $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }
       
       /* function get_report_return_item()
        {
            $sql="SELECT
  `sp_item_issue`.*,
  `sp_item`.`itemname`,
  `sp_stock_register`.`ponum` AS `ponum1`
FROM
  `sp_item`
  INNER JOIN `sp_item_issue` ON `sp_item`.`itemno` = `sp_item_issue`.`itemno`
    AND `sp_item`.`itemcode` = `sp_item_issue`.`itemcode`
  INNER JOIN `sp_stock_register` ON `sp_item_issue`.`ponum` =
    `sp_stock_register`.`sr_id`";
            
                
            $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }*/
        
         function get_report_return_item_empid($item='',$eid='',$dept='',$emp='')
        {
            $sql="SELECT `sp_item_issue`.*, `sp_item`.`itemname`, `sp_stock_register`.`ponum` AS `ponum1`
FROM `sp_item` INNER JOIN `sp_item_issue` ON `sp_item`.`itemno` = `sp_item_issue`.`itemno`
    AND `sp_item`.`itemcode` = `sp_item_issue`.`itemcode` INNER JOIN `sp_stock_register` ON `sp_item_issue`.`ponum` =
    `sp_stock_register`.`sr_id` where 1=1 ";
             
            
            if($emp!="none")
            {
             $sql.= " and sp_item_issue.empid= '".$emp."'";
            }
            
            if($dept!="none")
            {
                $sql.= " and sp_item_issue.deptno= '".$dept."'";
            }
            
            if($item!="none")
            {
                $id=  explode("-",$item);
             $sql.= " and sp_item_issue.itemno = ".$id[0]." and sp_item_issue.itemcode= '".$id[1]."'";
            }
            if($eid)
            {
             $sql.= " and sp_item_issue.empid='".$eid."'";
            }
                
            $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }
        
        
        function get_total_issued_item($hpo,$hitemno)
        {
   $query = $this->db->query("select sum(qty_issue) as s_qty from sp_item_issue where itemno=".$hitemno." and ponum=".$hpo);
			if($query->num_rows() > 0)
			{	
				
			return $query->row();
			}
			else
			{
				return FALSE;
			}
        }
        
        function get_total_issued_item_byItemno($id)
        {
                    $query = $this->db->query("select sum(qty_issue) as s_qty from sp_item_issue where itemno=".$id);
			if($query->num_rows() > 0)
			{	
				
			return $query->row();
			}
			else
			{
				return FALSE;
			}
        }
        
        
        function issued_qty_sum_byEMPID($po,$ino,$ic,$eid)
        {
            $array = array('ponum' => $po,'itemno' => $ino,'itemcode' => $ic ,'empid' => $eid);     
            $query=$this->db->select_sum('qty_issue')->get_where('sp_item_issue',$array);
                if ( $query->num_rows() > 0 )
                {
                    return $query->row();
                     
                }
                else { return false;}
        }
        
        function return_qty_sum_byEMPID($po,$ino,$ic,$eid)
        {
            $array = array('ponum' => $po,'itemno' => $ino,'itemcode' => $ic ,'empcode' => $eid);     
            $query=$this->db->select_sum('received_qty')->get_where('sp_item_return',$array);
                if ( $query->num_rows() > 0 )
                {
                    return $query->row();
                     
                }
                else { return false;}
        }
        
        
        
        function get_report_from_stock_register($id)
        {
            $array = array('itemno' => $id);     
            $query=$this->db->select_sum('item_qty')->get_where('sp_stock_register_items',$array);
                if ( $query->num_rows() > 0 )
                {
                    return $query->row();
                     
                }
                else { return false;}
        }
        function get_report_from_item_issue($id)
        {
            $array = array('itemno' => $id);     
            $query=$this->db->select('itemno,sum(qty_issue)-sum(item_rec_qty) as iss_qty')->get_where('sp_item_issue',$array);
                if ( $query->num_rows() > 0 )
                {
                    return $query->row();
                     
                }
                else { return false;}
        }
        
        function getitemissueById($id,$po){
           $query= $this->db->get_where('sp_item_issue',array('itemno'=>$id,'ponum'=>$po));
            if ( $query->num_rows() > 0 )
                {
                    return $query->result();
                     
                }
                else { return false;}
        }
        
         function getlocationById($id){
           $query= $this->db->get_where('sp_dept_location',array('dep_loc_id'=>$id));
            if ( $query->num_rows() > 0 )
                {
                    return $query->row();
                     
                }
                else { return false;}
        }
        
        function get_item_return_date($po,$ino,$ico,$id)
        {
            
            $query= $this->db->get_where('sp_item_return',array('ponum'=>$po,'itemno'=>$ino,'itemcode'=>$ico,'empcode'=>$id));
            if ( $query->num_rows() > 0 )
                {
                    return $query->result();
                     
                }
                else { return false;}
        }
        
        // fetching itemno based on category ID
        function get_itemno_by_categoryID($id)
        {
            
            $query= $this->db->get_where('sp_item',array('category'=>$id));
            if ( $query->num_rows() > 0 )
                {
                    return $query->result();
                     
                }
                else { return false;}
            
        }
        
        // fetching itemno based on sub category ID
        function get_itemno_by_sub_categoryID($id)
        {
            
            $query= $this->db->get_where('sp_item',array('subcategory'=>$id));
            if ( $query->num_rows() > 0 )
                {
                    return $query->row();
                     
                }
                else { return false;}
            
        }
        
        function get_item_details($id)
        {
            
                            $sql="SELECT
  `sp_item_issue`.`ponum`,
  `sp_item_issue`.`itemno`,
  `sp_item_issue`.`itemcode`,
  `sp_item_issue`.`tot_qty`,
  `sp_item_issue`.`issued_qty`,
  `sp_item_issue`.`deptno`,
  `sp_item_issue`.`issued_to`,
  `sp_item_issue`.`location`,
  `sp_item_issue`.`empid`,
  `sp_item_issue`.`qty_issue`,
  `sp_item_issue`.`issued_date`,
  `sp_item_issue`.`userid`,
  `sp_item_issue`.`item_rec_qty`,
  `sp_item_return`.`received_qty`,
  `sp_item_return`.`received_date`,
  `sp_item`.`itemname`,
  `sp_stock_register`.`ponum` AS `ponum1`
FROM
  `sp_item_issue`
  LEFT JOIN `sp_item_return` ON `sp_item_issue`.`ponum` =
    `sp_item_return`.`ponum` AND `sp_item_issue`.`itemno` =
    `sp_item_return`.`itemno` AND `sp_item_issue`.`itemcode` =
    `sp_item_return`.`itemcode` AND `sp_item_issue`.`empid` =
    `sp_item_return`.`empcode`
  INNER JOIN `sp_item` ON `sp_item_issue`.`itemno` = `sp_item`.`itemno` AND
    `sp_item_issue`.`itemcode` = `sp_item`.`itemcode`
  INNER JOIN `sp_stock_register` ON `sp_item_issue`.`ponum` =
    `sp_stock_register`.`sr_id`
     where sp_item_issue.itemno=".$id;

            
                
            $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
        }
        
        
        
}
