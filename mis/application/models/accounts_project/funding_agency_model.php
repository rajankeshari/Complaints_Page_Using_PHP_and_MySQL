
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Funding_agency_model extends CI_model{

		function __construct()
		{
			// Call the Model constructor
			parent::__construct(array('ft','project_so', 'project_admin'));
		}

		// Get all funding agencies
		function getAgency()
		{
			$this->db->order_by("name","asc");
			$this->db->select('*');
			$this->db->from('funding_agencies');
			$query = $this->db->get();
			return $query->result_array();
		}

		// Get funding agency details by id
		function getAgencyById($id)
		{
			$query = $this->db->get_where('funding_agencies', array('id' => $id));
			return $query->row_array();
		}


		//update funding agency
		function update_funding_agency($table, $data)
		{
			$id = $data['id'];
			$this->db->where('id', $id);
			$this->db->update('funding_agencies', $data);
		}


	}



	/* End of file funding_agency_model.php */
	/* Location: ./application/models/accounts_project/funding_agency_model.php */
	