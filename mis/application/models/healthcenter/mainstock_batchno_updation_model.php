<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Mainstock_batchno_updation_model extends CI_Model {

    var $hc_batchno_updation = 'hc_batchno_updation_log';
    var $hc_stock_updation = 'hc_stock_updation_log';

    public function __construct() {
        parent::__construct();

    }
    // start hc_stock_updation
    function insert_hc_stock_updation($data)
	{
		if($this->db->insert($this->hc_stock_updation,$data))
			return $this->db->insert_id();
		else
			return FALSE;
    }
    function get_hc_stock_updation_ById($id) 
    {
        $query = $this->db->query("SELECT * from hc_stock_updation_log WHERE hc_stock_updation_log.id =" . $id);
        if ($query->num_rows() > 0) { // 
            return $query->row_array();
        } else {
            return false;
        }
    }
    function get_hc_mainstock_details($id) 
    {
        $query = $this->db->query("select hc_medicine.m_name , hc_mainstore_stock.* from hc_medicine inner join hc_mainstore_stock on
        (hc_medicine.m_id = hc_mainstore_stock.m_id) where hc_medicine.activity = 'Active' and hc_medicine.m_id =" . $id);
        if ($query->num_rows() > 0) { // 
            return $query->row_array();
        } else {
            return false;
        }
    }
    function get_hc_stock_updation()
    {
        $query = $this->db->query(" SELECT * FROM hc_stock_updation_log where status = 'pending' and stock_type = 'mainstock' order by id desc");
               if ($query->num_rows() > 0) 
               { // 
                   return $query->result_array();
               } 
               else 
               {
                   return false;
               }
    }
    function update_hc_stock_updation($id,$status,$user_by) 
    {
        $timestamp = date("Y-m-d H:i:s");
        $query = $this->db->query("update hc_stock_updation_log set status = '$status' , approved_or_cancel_by = '".$user_by."' ,
        approved_or_cancel_at = '".$timestamp."' WHERE hc_stock_updation_log.id =" . $id);
        if ($this->db->affected_rows() > 0) { // 
            return true;
        } else {
            return false;
        }
    }
    function update_hc_stock_updation_on_reject($update_array) 
    {
        $timestamp = date("Y-m-d H:i:s");
        $id = $update_array['id'];
        $status = $update_array['status'];
        $approved_or_cancel_by = $update_array['approved_or_cancel_by'];
        $reject_reason = $update_array['reject_reason'];
        $query = $this->db->query("update hc_stock_updation_log set status = '".$status."' , approved_or_cancel_by = '".$approved_or_cancel_by."' ,
        approved_or_cancel_at = '".$timestamp."' , reject_reason = '".$reject_reason."' WHERE hc_stock_updation_log.id =" . $id);
        if ($this->db->affected_rows() > 0) { // 
            return true;
        } else {
            return false;
        }
    }
    function update_hc_mainstore_stock($m_id,$ms_qty)
    {
        $sql = "update hc_mainstore_stock set ms_qty='".$ms_qty."' where m_id='".$m_id."'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) { // 
            return true;
        } else {
            return false;
        }
    }
    // end hc_stock_updation
    function insert_hc_batchno_updation($data)
	{
		if($this->db->insert($this->hc_batchno_updation,$data))
			return $this->db->insert_id();
		else
			return FALSE;
    }
    function update_hc_batchno_updation($id,$status,$user_by) 
    {
        $timestamp = date("Y-m-d H:i:s");
        $query = $this->db->query("update hc_batchno_updation_log set status = '$status' , approved_or_cancel_by = '".$user_by."' ,
        approved_or_cancel_at = '".$timestamp."' WHERE hc_batchno_updation_log.id =" . $id);
        if ($this->db->affected_rows() > 0) { // 
            return true;
        } else {
            return false;
        }
    }
    function get_mainstock_batchno_data($id)
    {
        $query = $this->db->query("SELECT * from hc_medi_expdate 
        WHERE hc_medi_expdate.m_id = '".$id."'");

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
             return false;
        }
        
    }
    function get_mainstock_batchno_search($id,$selbatch)
    {
        $query = $this->db->query("SELECT * from hc_medi_expdate 
        WHERE hc_medi_expdate.m_id = '".$id."' AND hc_medi_expdate.batchno = '".$selbatch."'");

        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        } else 
        {
             return false;
        }
        
    }
    function update_hc_medi_expdate($m_id,$qty,$batchno)
    {
        $sql = "update hc_medi_expdate set qty='".$qty."' where batchno='".$batchno."' and m_id='".$m_id."'";
        $query = $this->db->query($sql);
    }
    function get_hc_batchno_updation_ById($id) 
    {
        $query = $this->db->query("SELECT * from hc_batchno_updation_log WHERE hc_batchno_updation_log.id =" . $id);
        if ($query->num_rows() > 0) { // 
            return $query->row_array();
        } else {
            return false;
        }
    }
    function get_batch_no_request()
    {
        $query = $this->db->query(" SELECT * FROM hc_batchno_updation_log where status = 'pending' and stock_type = 'mainstock' order by id desc");
               if ($query->num_rows() > 0) 
               { // 
                   return $query->result_array();
               } 
               else 
               {
                   return false;
               }
    }
    function getMedicineByid($id) 
    {

        $query = $this->db->query("SELECT m_id, m_name  FROM hc_medicine where activity = 'ACTIVE' and m_id = '$id'");
               if ($query->num_rows() > 0) 
               { // 
                   return $query->row_array();
               } 
               else 
               {
                   return false;
               }
    }
    function get_mainstock_batchno_all_data($id)
    {
        $query = $this->db->query("SELECT hc_medi_expdate.*,hc_mainstore_stock.ms_qty from hc_medi_expdate
        INNER JOIN hc_mainstore_stock ON (hc_medi_expdate.m_id = hc_mainstore_stock.m_id)
        WHERE hc_medi_expdate.m_id = '".$id."' ORDER BY hc_medi_expdate.exp_date ASC");

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
             return false;
        }
        
    }
// end

// physical verification report query
function get_mainstock_phy_ver_report()
{
    $query = $this->db->query("SELECT CONCAT(user_details.first_name, ' ', user_details.middle_name, ' ',user_details.last_name) as name,
    hc_medicine.m_name,hc_stock_updation_log.* from hc_stock_updation_log
         INNER JOIN (hc_medicine, user_details) ON 
          (hc_stock_updation_log.m_id = hc_medicine.m_id AND hc_stock_updation_log.user_id = user_details.id)
              ORDER BY hc_stock_updation_log.updated_at ASC");

    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
         return false;
    }
    
}
function get_name_byId($id)
 {
    $query = $this->db->query("SELECT CONCAT(first_name, ' ', middle_name, ' ',last_name) as name FROM user_details WHERE id = '$id'");

    if ($query->num_rows() > 0) {
        return $query->row_array();
    } else {
         return false;
    }
 }
function get_mainstock_phy_ver_report_bySearch($from,$to,$search_type)
 {
    if($search_type=="all")
     {
        $query = $this->db->query("SELECT CONCAT(user_details.first_name, ' ', user_details.middle_name, ' ',user_details.last_name) as name,
        hc_medicine.m_name,hc_stock_updation_log.* from hc_stock_updation_log
        INNER JOIN (hc_medicine,user_details) ON (hc_stock_updation_log.m_id = hc_medicine.m_id and hc_stock_updation_log.user_id = user_details.id)
         WHERE hc_stock_updation_log.stock_type = 'mainstock' and hc_stock_updation_log.updated_at between DATE('$from') AND DATE('$to')
             ORDER BY hc_stock_updation_log.updated_at ASC");
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
             return false;
        }
     }
     else
     {
        $query = $this->db->query("SELECT CONCAT(user_details.first_name, ' ', user_details.middle_name, ' ',user_details.last_name) as name,hc_medicine.m_name,hc_stock_updation_log.* from hc_stock_updation_log
        INNER JOIN (hc_medicine,user_details) ON (hc_stock_updation_log.m_id = hc_medicine.m_id and hc_stock_updation_log.user_id = user_details.id)
         WHERE hc_stock_updation_log.stock_type = 'mainstock' and hc_stock_updation_log.`status` = '$search_type' AND hc_stock_updation_log.updated_at between DATE('$from') AND DATE('$to')
             ORDER BY hc_stock_updation_log.updated_at ASC");
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
             return false;
        }
     }
 }
 // physical verification report query

 // physical batchno verification report query
 function get_mainstock_batchno_phy_ver_report()
 {
     $query = $this->db->query("SELECT hc_medicine.m_name,hc_batchno_updation_log.* from hc_batchno_updation_log
     INNER JOIN hc_medicine ON (hc_batchno_updation_log.m_id = hc_medicine.m_id)
     hc_batchno_updation_log.stock_type = 'mainstock'
          ORDER BY hc_batchno_updation_log.updated_at ASC");

     if ($query->num_rows() > 0) {
         return $query->result();
     } else {
          return false;
     }
     
 }
 function get_mainstock_batchno_phy_ver_report_bySearch($from,$to,$search_type)
 {
     if($search_type=="all")
     {
        $query = $this->db->query("SELECT hc_medicine.m_name,hc_batchno_updation_log.* from hc_batchno_updation_log
        INNER JOIN hc_medicine ON (hc_batchno_updation_log.m_id = hc_medicine.m_id)
         WHERE hc_batchno_updation_log.stock_type = 'mainstock' and hc_batchno_updation_log.updated_at between DATE('$from') AND DATE('$to')
             ORDER BY hc_batchno_updation_log.updated_at ASC");
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
             return false;
        }
     }
     else
     {
        $query = $this->db->query("SELECT hc_medicine.m_name,hc_batchno_updation_log.* from hc_batchno_updation_log
        INNER JOIN hc_medicine ON (hc_batchno_updation_log.m_id = hc_medicine.m_id)
         WHERE hc_batchno_updation_log.stock_type = 'mainstock' AND hc_batchno_updation_log.`status` = '$search_type' AND hc_batchno_updation_log.updated_at between DATE('$from') AND DATE('$to')
             ORDER BY hc_batchno_updation_log.updated_at ASC");
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
             return false;
        }
     }
 }
  // physical batchno verification report query
        

}