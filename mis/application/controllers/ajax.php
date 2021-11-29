<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		// Will never be used
	}

	public function grade_pay($pay_band = '')
	{
		// fetching grade pay for a particular pay band
		// echo $pay_band;
		// exit;
		$this->load->model('pay_scales_model','',TRUE);
		$data['grade_pays'] = $this->pay_scales_model->get_grade_pay($pay_band);
		// print_r($data['grade_pays']);
		// exit;
		$this->load->view('ajax/grade_pay',$data);
	}

	 public function designation($type = '')
	{
		// fetching designations of a particular type , if type is not given then all the designations are shown
		$this->load->model('designations_model','',TRUE);

		if($type === '')
			$data['designations'] = $this->designations_model->get_designations();
		else if($type === 'ft')
			$data['designations'] = $this->designations_model->get_designations("type in ('ft','others')");
		else if($type === 'nfta' || $type === 'nftn')
			$data['designations'] = $this->designations_model->get_designations("type in ('nft','others')");
		else
			$data['designations'] = FALSE;
		$this->load->view('ajax/designation',$data);
	} 
	
   public function designation2($type = '')
    {  
        $did = $this->input->post('grade_pay');
        
        $type=$did;
    
        // fetching designations of a particular type , if type is not given then all the designations are shown
        $this->load->model('designations_model','',TRUE);

        if($type === '')
            $data['designations'] = $this->designations_model->get_designations();
        else if($type === 'ft')
            $data['designations'] = $this->designations_model->get_designations("type in ('ft')");
        else if($type === 'nfta')
            $data['designations'] = $this->designations_model->get_designations("type in ('nfta')");
       else if($type === 'nftn')
            $data['designations'] = $this->designations_model->get_designations("type in ('nftn')");
        else
            $data['designations'] = "no data found";
        
        echo json_encode($data);
    }

	public function department($type = '')
	{
		// fetching departments of a particular type

		$this->load->model('departments_model','',TRUE);

		if($type === 'ft')
			$data['departments'] = $this->departments_model->get_departments('academic');
		else if($type === 'nftn')
			//$data['departments'] = $this->departments_model->get_departments('nonacademic');
                         $data['departments'] = $this->departments_model->get_departments();  //  change 27.8.15  Include all Dept and Sections for Non Academic staff also
		else if($type === '' || $type === 'nfta')
			$data['departments'] = $this->departments_model->get_departments();
		else
			$data['departments'] = FALSE;

		$this->load->view('ajax/department',$data);
	}

	public function empNameByDept($dept = '')
	{
		$this->load->model('user/user_details_model','',TRUE);
		$data['empNames'] = $this->user_details_model->getEmpNamesByDept($dept);
		$this->load->view('ajax/empNameByDept',$data);
	}
	public function empNameByDeptFT($dept = '')
	{
		$this->load->model('user/user_details_model','',TRUE);
		$data['empNames'] = $this->user_details_model->getEmpNamesByDeptFT($dept);
		//echo $this->db->last_query();die();
		$this->load->view('ajax/empNameByDeptFT',$data);
	}

	public function stuNameByDept($dept = '')
	{
		$this->load->model('user/user_details_model','',TRUE);
		$data['stuNames'] = $this->user_details_model->getStuNamesByDept($dept);
		$this->load->view('ajax/stuNameByDept',$data);
	}
	public function stuNameByDeptAndCourse($dept = '',$course)
	{
		$this->load->model('user/user_details_model','',TRUE);
		$data['stuNames'] = $this->user_details_model->getStuNameByDeptAndCourse($dept,$course);
		$this->load->view('ajax/stuNameByDept',$data);
	}

	public function empDeptByName($id = '')
	{
		$this->load->model('user/user_details_model','',TRUE);
		$data['empDept'] = $this->user_details_model->getDeptByEmpNames($id);
		//echo $empDept;
		$this->load->view('ajax/department_name',$data);
	}
	public function empNameByDesc($id = '')
	{
		$this->load->model('consultant/consultant_help_model','',TRUE);
		$data['designations'] = $this->consultant_help_model->getDesignation($id);
		$this->load->view('ajax/designation',$data);
	}

}


/* End of file ajax.php */
/* Location: Codeigniter/application/controllers/ajax.php */