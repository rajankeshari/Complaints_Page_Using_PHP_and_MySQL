<?php

class Pay_scales_model extends CI_Model
{

	var $table = 'pay_scales';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_pay_codeBYpaybandANDgradepay($payband,$gradepay)
	{
		/*$this->db->distinct()->select('pay_code')->where(array('pay_band'=>$payband,'grade_pay'=>$gradepay));*/
		
			$query = $this->db->query("SELECT * FROM pay_scales WHERE pay_band='".$payband."' AND grade_pay='".$gradepay."'");
			if($query->num_rows()==1)
			return $query->row();
		else
			return FALSE;
	}

	function get_pay_bands()
	{
		$this->db->distinct()->select('pay_band, pay_band_description');
		$query = $this->db->get($this->table);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return FALSE;
	}

	function get_grade_pay($pay_band = '')
	{
		if($pay_band !== '')
		{
			$this->db->select('pay_code, grade_pay')->where("pay_band='".$pay_band."'",'',FALSE);
			$query = $this->db->get($this->table);
			return $query->result();
		}
		else
			return 'Error: No Pay Band selected';
	}

	function getAll_grade_pay()
	{
		
			$this->db->select('pay_code, grade_pay');
			$query = $this->db->get($this->table);
			return $query->result();
		
		
	}
/*	function insert_entry()
	{
		$this->id   = $this->input->post('emp_id');
	        $this->curr_step = 1;

	        $this->db->insert('emp_current_entry', $this);
	}

    function update_entry()
    {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }
*/
}

/* End of file pay_scales_model.php */
/* Location: mis/application/models/pay_scales_model.php */