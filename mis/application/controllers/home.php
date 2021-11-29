<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        parent::__construct();
        $this->load->library('pagination');
    }

    var $emaildata = "emaildata";

    var $stu_hostel_info = "stu_hostel_info";
// for user notifications
  function show_notification($send_date,$user_to,$auth_id){
  //  echo $id;
//echo  date('Y-m-d H:i:s', strtotime('-10 day')); exit;
    $this->load->model("user/user_notifications_model", "user_notice", TRUE);
    $st=$this->user_notice->getNotice_data($send_date,$user_to,$auth_id);
  //  echo $this->db->last_query();
//    echo "<pre>";print_r($st);
    if($st->status==0){
    $this->user_notice->update_notice_status($send_date,$user_to,$auth_id);
  }
    redirect($st->notice_path);
  }
//end


    public function index() {



        $this->load->model("information/view_notice_model", "notice", TRUE);
        $rmk = $this->notice->get_user_remarks();
		
		//echo '<pre>'; print_r($this->session->all_userdata());exit;

        //		$this->addCSS("home/home-style.css");
        /* $this->CI->session->userdata('dob') == '0000-00-00' */ //changed by anuj
        // this is used for student admission module edit by daljeet
        if ($rmk[0]->remark == 'N' && uri_string() != 'new_student_admission/student_first_login' && in_array('stu', $this->CI->session->userdata('auth'))) {

            $this->session->set_flashData('flashInfo', 'Please fill this form to continue.');
            redirect('new_student_admission/student_first_login');
        }
        $this->checkPassword();

        //===================OBC==========================
        $scategory = $this->notice->get_category();

        $obc_tbl_status = $this->notice->check_obc_filled();

        //================================================
        /*
          if( $this->CI->session->userdata('dob') == '0000-00-00' && uri_string()!='student_add_data/student_first_login' && in_array('stu',$this->CI->session->userdata('auth')))
          {
          $this->session->set_flashData('flashInfo','Please fill this form to continue.');
          redirect('student_add_data/student_first_login');
          }
         */
        $this->addCSS("home/home-feed-style.css");
//		$this->addCSS("home/home-calendar.css");

        $this->addJS("../core/fullcalendar.min.js");
//		$this->addJS("home/home-feed-script.js");
//		$this->addJS("home/home-calendar.js");
// Stopped due to problem

        /*
          $this->addCSS("birthday/one.css");
          $this->addCSS("birthday/five.css");
          $this->load->model('birthday/birthday_model');
          $employee_count = $this->birthday_model->seperate_count();
          $overall_count = $this->birthday_model->count();
          $student_count = $overall_count-$employee_count;
         */
        $this->drawHeader("Management Information System", "Home");

// fingerprint exist or not
//$this->load->model('fp_auth/fp_model','',TRUE);
//$data['fp_data'] = $this->fp_model->user_fp_data();
// end fingerprint exist or not

// Security ques check Sachin
        $this->load->model('sec_inside/sec_inside_model');
        $display = $this->sec_inside_model->check_if_reset($this->session->userdata('id'));
// Security ques check Sachin upto this line	 and pass the data to line no 72 "display" => $display, after 	"student_count" => $student_count ,
        //related to notice, minutes or circular module
        $this->checkCircularValidity();
        $this->checkNoticeValidity();
        $this->checkMinuteValidity();
        $this->load->model("information/view_circular_model", "circular", TRUE);
        $this->load->model("information/view_minute_model", "minutes", TRUE);

        // added by snrai
        $overall_count = (isset($overall_count) ? $overall_count : 0 );
        $employee_count = (isset($employee_count) ? $employee_count : 0 );
        $student_count = (isset($student_count) ? $student_count : 0 );

        //------------------------Start@Shyam Mishra ---------------------------
        $is_student = $this->session->userdata('auth');
        if ((isset($is_student[0])) && ($is_student[0] == "stu")) {
            $this->load->model("global_models/main_model");
            $req_data = array("domain_name");
            $student_email = $this->main_model->get_records_from_id($this->emaildata, $req_data, "admission_no", $this->session->userdata('id'));
            $is_email = (!empty($student_email) ? 1 : 0); //notExist = 0, Exist = 1  and Unkonwn = 2
            $req_hostel_data = array("hostel_name");
            $is_student_hostel = $this->main_model->get_records_from_id($this->stu_hostel_info, $req_hostel_data, "admn_no", $this->session->userdata('id'));
            $is_hostel = (!empty($is_student_hostel) ? 1 : 1);
        } else {
            $is_email = 2;
            $is_hostel = 2;
        }

        //------------------------End@Shyam Mishra ---------------------------
		  //------------------------Employee Email Checking Start ---------------------------
        $is_student = $this->session->userdata('auth');
		//echo '<pre>';print_r($is_student);echo '</pre>';die();
        if ((isset($is_student[0])) && ($is_student[0] == "emp")) {
		//if ((isset($is_student[0])) && ($is_student[0] == "emp" || $is_student[0] == "prj_emp")) {
			
            $this->load->model("global_models/main_model");
            $req_data = array("domain_name");
            $emp_email = $this->main_model->get_records_from_id_emp('emaildata_emp', $req_data, "emp_id", $this->session->userdata('id'));

            $is_email_emp = (!empty($emp_email) ? 1 : 0); //notExist = 0, Exist = 1  and Unkonwn = 2

        } else {
            $is_email_emp = 2;

        }
		//echo  $is_email_emp;die();
		//Chart logic starts======
	//	 $this->load->model('chartmodel', 'chart');
		//    $data['chart_data'] =$chart_data=$this->chart->get_chart_data();
      //  $data['min_year'] = $min_year=$this->chart->get_min_year();
      //  $data['max_year'] = $max_year=$this->chart->get_max_year();

		// Chart logic ends====

        //------------------------Employee Email Checking End ---------------------------
		
		//------------------------Project Employee Email Checking Start ---------------------------
		
        $is_student = $this->session->userdata('auth');
		        if ((isset($is_student[0])) && ($is_student[0] == "prj_emp")) {
		
			
            $this->load->model("global_models/main_model");
            $req_data = array("domain_name");
            $emp_email = $this->main_model->get_records_from_id('emaildata', $req_data, "admission_no", $this->session->userdata('id'));

            $is_email_prj_emp = (!empty($emp_email) ? 1 : 0); //notExist = 0, Exist = 1  and Unkonwn = 2

        } else {
            $is_email_prj_emp = 2;

        }
	/*if($this->session->userdata('id')=='18MT0440'){
			echo $is_email;die();
		}*/
        //------------------------Project Employee Email Checking End ---------------------------
		
        $this->load->view('home', $data, array("unreadNotice" => $this->notice->get_new_notice_count(),
            "overall_count" => $overall_count,
            "employee_count" => $employee_count,
            "student_count" => $student_count,
            "display" => $display,
            "unreadCircular" => $this->circular->get_new_circular_count(),
            "student_category" => $scategory->category,
            "obc_tbl_status" => $obc_tbl_status,
            "unreadMinute" => $this->minutes->get_new_minute_count(),
            "is_email" => $is_email,
			"is_email_emp" => $is_email_emp,
			"is_email_prj_emp" => $is_email_prj_emp,
            "is_hostel" => $is_hostel,
		//	"chart_data"=>$chart_data,
            "min_year"=>$min_year,
            "max_year"=>$max_year

			));
        $this->drawFooter();
    }

    private function checkCircularValidity() {
        $this->load->model('information/search_edit_circular_model', '', TRUE);
        $this->search_edit_circular_model->remove();
    }

    private function checkNoticeValidity() {
        $this->load->model('information/search_edit_notice_model', '', TRUE);
        $this->search_edit_notice_model->remove();
    }

    private function checkMinuteValidity() {
        $this->load->model('information/search_edit_minute_model', '', TRUE);
        $this->search_edit_minute_model->remove();
    }

    private function checkPassword() {
        $this->load->model('user/users_model', '', TRUE);
        $user = $this->users_model->getUserById($this->session->userdata('id'));
        $id_pass = $this->authorization->strclean($this->session->userdata('id'));
        if ($user && $user->password == $this->authorization->encode_password($id_pass, $user->created_date))
            redirect('change_password');
    }

    //get notice for notification on home page
    function getNotices($date = '') {
        if ($date == '')
            $date = date('Y-m-d');
        $this->load->model("information/view_notice_model", "notice", TRUE);
        //added by snrai
        /* $config = array();
          $config["base_url"] = base_url() . "index.php/home/getNotices";
          $total_row = $this->notice->record_count();
          $config["total_rows"] = $total_row;
          $config["per_page"] = 5;
          $config['use_page_numbers'] = TRUE;
          $config['num_links'] = $total_row;
          $config['cur_tag_open'] = '&nbsp;<a class="current">';
          $config['cur_tag_close'] = '</a>';
          $config['next_link'] = 'Next';
          $config['prev_link'] = 'Previous';

          $this->pagination->initialize($config);
          if($this->uri->segment(3)){
          $page = ($this->uri->segment(3)) ;
          }
          else{
          $page = 1;
          }
          //$data["results"] = $this->pagination_model->fetch_data($config["per_page"], $page);
          $str_links = $this->pagination->create_links();
          $data["links"] = explode('&nbsp;',$str_links ); */
        //end adding bu snrai
        if (strlen($this->uri->segment(3)) >= 6) {
            $click = 0;
        } else {
            $click = $this->uri->segment(3);
        }
        //echo $this->notice->get_new_notice_count();die();
        $this->load->view('ajax/notices', array("notices" => $this->notice->get_notices($date, $click),
            "Qdate" => $date, "noticeCount" => $this->notice->get_new_notice_count()));
    }

    function getCirculars($date = '') {
        if ($date == '')
            $date = date('Y-m-d');
        $this->load->model("information/view_circular_model", "circular", TRUE);
        $this->load->view('ajax/circulars', array("circulars" => $this->circular->get_circulars($date), "Qdate" => $date));
    }

    function getMinute($date = '') {
        if ($date == '')
            $date = date('Y-m-d');
        $this->load->model("information/view_minute_model", "minute", TRUE);
        $this->load->view('ajax/minutes', array("minutes" => $this->minute->get_minutes($date), "Qdate" => $date));
    }

    //added by anuj
    function stu_information() {

        $admn_no = $this->input->post('admn_no');
        $this->load->model('mis_report/home_student_model', '', TRUE);
        $data['stu_details'] = $this->home_student_model->get_stu_details($admn_no);

        echo json_encode($data);
    }

    function insert_stu_subcast() {
        $this->load->model("information/view_notice_model", "notice", TRUE);
        $data['admn_no'] = $admn_no = $this->input->post('admn_no');
        $data1['sub_cast'] = $data['sub_cast'] = $sub_cast = $this->input->post('stu_cast');
        $data1['iss_dist'] = $data['iss_dist'] = $iss_dist = $this->input->post('iss_dist');
        $data1['iss_state'] = $data['iss_state'] = $iss_state = $this->input->post('iss_state');
        $data1['iss_auth'] = $data['iss_auth'] = $iss_auth = $this->input->post('iss_auth');

        $chk = $this->notice->check_obc_filled($data['admn_no']);

        if (!empty($chk)) {
            $p = $this->notice->update_obc_data($data1, $admn_no);
        } else {
            $p = $this->notice->insert_obc_data($data);
        }
        echo $p;
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
