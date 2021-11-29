<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Physical_verification_model extends CI_Model {

   // var $hc_mainstock_phy_ver_upload = 'hc_mainstock_phy_ver_upload';
      var $hc_physical_verification_upload = 'hc_physical_verification_upload';

    public function __construct() {
        parent::__construct();
    }

    function insert_hc_mainstock_phy_ver_upload($data)
	{
        if($this->db->insert($this->hc_physical_verification_upload,$data))
            return true;
			//return $this->db->insert_id();
		else
			return FALSE;
    }
    function fin_year()
    {
        $query = $this->db->query("SELECT curr_fin_year FROM hc_budget ORDER BY budget_id DESC");
        if ($query->num_rows() > 0) { // 
            return $query->result_array();
        } else {
            return false;
        }
    }
    function get_hc_mainstock_phy_ver_upload()
    {
        $query = $this->db->query("SELECT CONCAT(user_details.first_name, ' ', user_details.middle_name, ' ',user_details.last_name) as name,
        hc_physical_verification_upload.* FROM hc_physical_verification_upload INNER JOIN user_details
        ON (hc_physical_verification_upload.uploaded_by = user_details.id)
        ORDER BY hc_physical_verification_upload.id DESC");
        if ($query->num_rows() > 0) { // 
            return $query->result_array();
        } else {
            return false;
        }
    }



}