<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * Controller salary_edit
 * @created on : Saturday, 18-Feb-2017 08:19:58
 * @author SHOBH NATH RAI <raishobhnath@outlook.ocm>
 * Copyright 2017
 *
 *
 */


class salary_search extends MY_Controller
{

	public function __construct() 
	{
		parent::__construct(['acc_hos','acc_da2','acc_sal']);         
		$this->load->model('finance_account/salary_edit_model');
		$this->load->model('finance_account/global_variable');
        //$this->load->model('finance_account/finance_electric_model');
	}
	

	/**
	* List all data acc_pay_details_temp
	*
	*/
	public function index() 
	{
        ///// JS  for print
        $this->addJS("exam_control/standard/jquery.dataTables.buttons.min.js");        
        $this->addJS("exam_control/standard/buttons.flash.min.js");                
        $this->addJS("exam_control/standard/jszip.min.js");
        $this->addJS("exam_control/standard/pdfmake.min.js");
        $this->addJS("exam_control/standard/vfs_fonts.js");
        $this->addJS("exam_control/standard/buttons.html5.min.js");
        $this->addJS("exam_control/standard/buttons.print.min.js");
        $this->addJS("exam_control/standard/buttons.colVis.min.js");

         
        
        
        // $this->addCSS("exam_control/standard/jquery.dataTables.min.css");
        $this->addCSS("exam_control/standard/buttons.dataTables.min.css");
            ////// End JS

        $this->addJS("finance_account/print_tbl.js");


		$config = array(
			'base_url'          => site_url('finance_account/salary_edit/index/'),
			'total_rows'        => $this->salary_edit_model->count_all(),
			'per_page'          => 1000,
			'uri_segment'       => 4,
			'num_links'         => 9,
			'use_page_numbers'  => FALSE
			
		);
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		
		$this->pagination->initialize($config);
		$data['total']          = $config['total_rows'];
		$data['pagination']     = $this->pagination->create_links();
		$data['number']         = (int)$this->uri->segment(4) +1;
		

		$this->drawHeader('Table');
		$this->load->view('finance_account/salary_search_view',$data);
		$this->drawFooter();
		
		   
	}

	function search(){
		$arr=array('mon'=>$this->input->post('selmonth'),
			'yr'=>substr($this->input->post('selyear'), 2),
			);
		if(strcmp($this->input->post('txtEmpNo'), "")!=0)
		{
			$arr['empno']=$this->input->post('txtEmpNo');
		}
		//var_dump($arr);die();
		$data['month']=$this->input->post('selmonth');
		$data['year']=$this->input->post('selyear');
		$this->addJS("exam_control/standard/jquery.dataTables.buttons.min.js");        
        $this->addJS("exam_control/standard/buttons.flash.min.js");                
        $this->addJS("exam_control/standard/jszip.min.js");
        $this->addJS("exam_control/standard/pdfmake.min.js");
        $this->addJS("exam_control/standard/vfs_fonts.js");
        $this->addJS("exam_control/standard/buttons.html5.min.js");
        $this->addJS("exam_control/standard/buttons.print.min.js");
        $this->addJS("exam_control/standard/buttons.colVis.min.js");

         
        
        
        // $this->addCSS("exam_control/standard/jquery.dataTables.min.css");
        $this->addCSS("exam_control/standard/buttons.dataTables.min.css");
            ////// End JS

        $this->addJS("finance_account/print_tbl.js");


		$config = array(
			'base_url'          => site_url('finance_account/salary_edit/index/'),
			'total_rows'        => $this->salary_edit_model->count_all(),
			'per_page'          => 1000,
			'uri_segment'       => 4,
			'num_links'         => 9,
			'use_page_numbers'  => FALSE
			
		);
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		
		$this->pagination->initialize($config);
		$data['total']          = $config['total_rows'];
		$data['pagination']     = $this->pagination->create_links();
		$data['number']         = (int)$this->uri->segment(4) +1;
		//echo $mon.$yr.$empno;
		$data['salary_edit_model'] = $this->salary_edit_model->get_all_to_show(1000, $this->uri->segment(4),$arr);

		$this->load->model('user_model', '', TRUE);
		$this->load->model('finance_account/payble_fields_model','PFM');
        $this->load->model('finance_account/deduction_fields_model','DFM');

        $data['deduction_fields'] = $this->DFM->get_all(1000, '');
        $data['deduction_search'] = $this->DFM->arrayManage($data['deduction_fields']);

        $data['payble_fields'] = $this->PFM->get_all(1000, '');
        $data['payble_search'] = $this->DFM->arrayManage( $data['payble_fields'] );
        $data['DA_PER'] = $this->global_variable->GetVariable(['variable_name'=>'DA'],'DESC');
        $data['TDA_PER'] = $this->global_variable->GetVariable(['variable_name'=>'TDA'],'DESC');
        $this->drawHeader('Table');
		$this->load->view('finance_account/salary_search_view',$data);
		$this->drawFooter();
	}
}