
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reg_Form extends MY_Controller {

    private $subjects = array();
    private $date = array();
    private $img_name1 = '';
    private $img_name = '';

    function __construct() {
        //parent::__construct(array('stu'));
        parent::__construct();
        $this->load->model('reg_exam_rc_form/validation_model', 'vm', true);
        $this->load->model('reg_exam_rc_form/other_basic_model', 'sbm', true);
        $this->load->model('reg_open_specific/reg_open_model', 'sp', true);
        $this->load->model('reg_exam_rc_form/exam_form_new_model');
        $this->load->model('student_sem_form/check_open_close_date_new', '', TRUE);
    }

    //Main Index form//
    function index() {
        //print_r( $this->session->userdata('auth'));die();
        if (!($this->session->userdata('isJRF')) {
            //echo "Hello"; die();
            $f = false;
            $this->load->model('student_sem_form/get_results', '', TRUE);
            $results = $this->get_results->getSemesterDetailsById($this->session->userdata('id'));
            foreach ($results as $r) {
                if ($r->passfail == 'F' || $r->passfail == 'F') {
                    $f = true;
                    break;
                }
            }
            if ($f == false) {
                //   redirect('home','refresh');
            }
        }
//=======================Exam All Starts Anuj====================================================================
         /*   $month = date("m");
                if ($month <= 5)
		{
			$s = "Monsoon";
		}
		else
		{
			$s = "Winter";
		}*/
        //$sess_syear=$this->check_open_close_date_new->get_latest_session_session_year($s);
         //echo $this->db->last_query();
        $sess_syear=$this->sbm->get_latest_open_date_exam();
       if(empty($sess_syear)){
           $sess_syear=$this->sbm->get_latest_open_date_exam_special();
       }
        $data['syear']=$syear=$sess_syear->session_year;
        $data['sess']=$sess=$sess_syear->session;
        $open_for = 'all';
        $etype = $sess_syear->exam_type;
        $normal_id = $this->check_open_close_date_new->get_open_close_date_id($open_for, $etype,$syear,$sess); //get latest row id from table
          //  echo $this->db->last_query();die();

        if(!empty($normal_id))
        {
        $normal = $this->check_open_close_date_new->check_open_close_all_normal($normal_id->id);

        if (!empty($normal)) {
            $per = 1;
            $data['lfine'] = 0;   //lfine means late fine
            $open_id=$normal_id->id;
        } else {

            $normal_lfine = $this->check_open_close_date_new->check_open_close_all_latefine($normal_id->id);

            if (!empty($normal_lfine)) {

                $key_id = $normal_lfine[0]->master_id;
                $late_id = array();
                $i = 0;
                foreach ($normal_lfine as $p) {
                    $late_id[$i] = $p->id;
                    $i++;
                }

                for ($j = 0; $j < count($normal_lfine); $j++) {
                    $ff = $this->check_open_close_date_new->check_open_close_late_fine($late_id[$j]);

                    if (!empty($ff)) {
                        $flag = 1;
                        $data['lfamt']=$lfineamt=$ff->lateamount;
                        break;
                    } else {
                        $flag = 0;
                    }
                }

                if ($flag == '1') {
                    $data['lfine'] = 1;
                    $per = 1;
                    //$chk = $this->check_open_close_date_new->get_row_details($key_id);
                    $open_id=$normal_id->id;
                } else {
                    $per = 0;
                }
            } else {
                $per = 0;
            }
        }
        }
        else{
            $per=0;
        }
//=======================Regular All Ends====================================================================
//=======================Regular Specific Starts====================================================================

        //echo $per;die();

        if ($per == 0) {
            $open_for = 'specific';
            $etype = $sess_syear->exam_type;
            $normal_id = $this->check_open_close_date_new->get_open_close_date_id($open_for,$etype,$syear,$sess); //get latest row id from table

            if(!empty($normal_id))
            {

                $normal = $this->check_open_close_date_new->check_open_close_all_normal($normal_id->id);

                $stu_spe = $this->check_open_close_date_new->get_stu_dept_course_bracnh_sem($this->session->userdata('id'));

                if ($stu_spe->dept_id == $normal[0]->dept) {
                    $dept = $normal[0]->dept;
                } else {
                    $dept = $normal[0]->dept;
                }
                if ($stu_spe->course_id == $normal[0]->course) {
                    $course = $normal[0]->course;
                } else {
                    $course = $normal[0]->course;
                }
                if ($stu_spe->branch_id == $normal[0]->branch) {
                    $branch = $normal[0]->branch;
                } else {
                    $branch = $normal[0]->branch;
                }
                if ($stu_spe->semester == $normal[0]->semester) {
                    $sem = $normal[0]->semester;
                } else {
                    $sem = $normal[0]->semester;
                }

                $nor_spe = $this->check_open_close_date_new->check_specific_normal($normal_id->id, $dept, $course, $branch, $sem);

                if (!empty($nor_spe)) { //checking normal against specific
                    $per = 1;
                    $data['lfine'] = 0;
                    $open_id=$normal_id->id;
                } else {

                    //checking late fine against specific
                    $normal_lfine = $this->check_open_close_date_new->check_open_close_all_latefine($normal_id->id);

                    if (!empty($normal_lfine)) {
                        $key_id = $normal_lfine[0]->master_id;
                        $late_id = array();
                        $i = 0;
                        foreach ($normal_lfine as $p) {
                            $late_id[$i] = $p->id;
                            $i++;
                        }
                        for ($j = 0; $j < count($normal_lfine); $j++) {
                            $ff = $this->check_open_close_date_new->check_open_close_late_fine($late_id[$j]);

                            if (!empty($ff)) {
                                $flag = 1;
                                $data['lfamt']=$lfineamt=$ff->lateamount;
                                break;
                            } else {
                                $flag = 0;
                            }
                        }
                        if ($flag == '1') {
                            $data['lfine'] = 1;
                            $per = 1;
                            //$chk = $this->check_open_close_date_new->get_row_details($key_id);
                            $open_id=$normal_id->id;
                        } else {
                            $per = 0;
                        }
                    } else {
                        $per = 0;
                    }

                }

            }
            else{
                $per=0;
            }

        }

//=======================Regular Specific Ends====================================================================
//=======================Regular Individual Starts====================================================================
        if($per==0){
        $open_for = 'indi_stu';
        $etype = $sess_syear->exam_type;
        $normal_id = $this->check_open_close_date_new->get_open_close_date_id_indi($open_for,$etype,$this->session->userdata('id'));
//echo $this->db->last_query();die();

//get latest row id from table

        if(!empty($normal_id))
        {
    $normal = $this->check_open_close_date_new->check_open_close_individual_normal($normal_id->id,$this->session->userdata('id'));

            if(!empty($normal)){
                $per=1;
                $data['lfine'] = 0;
                $open_id=$normal_id->id;
            }
            else{
                // logic for late fine will be here
                $normal_lfine = $this->check_open_close_date_new->check_open_close_all_latefine($normal_id->id);

                    if (!empty($normal_lfine)) {
                        $key_id = $normal_lfine[0]->master_id;
                        $late_id = array();
                        $i = 0;
                        foreach ($normal_lfine as $p) {
                            $late_id[$i] = $p->id;
                            $i++;
                        }
                        for ($j = 0; $j < count($normal_lfine); $j++) {
                            $ff = $this->check_open_close_date_new->check_open_close_late_fine($late_id[$j]);

                            if (!empty($ff)) {
                                $flag = 1;
                                $data['lfamt']=$lfineamt=$ff->lateamount;
                                break;
                            } else {
                                $flag = 0;
                            }
                        }
                        if ($flag == '1') {
                            $data['lfine'] = 1;
                            $per = 1;
                            //$chk = $this->check_open_close_date_new->get_row_details($key_id);
                            $open_id=$normal_id->id;
                        } else {
                            $per = 0;
                        }
                    } else {
                        $per = 0;
                    }


            }
        }
        else{
            $per=0;
        }
        }

//=======================Regular Individual Ends ====================================================================
        //$per=0;
        //echo $per;die();
        if($per==0){
            //redirect('/student_sem_form/regular_form/daterror', 'refresh');
            //redirect('other_reg_form/reg_form/dateclosed');
            redirect('reg_exam_rc_form/reg_form/dateclosed');
        }
        else{
            $dates=$this->check_open_close_date_new->get_row_details($open_id);
        }

 //===========================================================================================================





        if ($per == '1') {

            // check already filled or not
            if ($this->exam_form_new_model->check_for_current_session($this->session->userdata('id'), $dates->session_year, $dates->session,$dates->exam_type)) {
//echo $this->db->last_query();die();
                redirect('reg_exam_rc_form/reg_form/success', 'refresh');
            }

            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            //  print_r($this->input->post('subjects'));
            $this->form_validation->set_rules('subjects', 'At least 1 Subject Minimum and Maximum 5', 'required|callback_check_default');
            if (!($this->session->userdata('isJRF'))
                $this->form_validation->set_rules('reason', 'Reason', 'required');
            $this->form_validation->set_rules('dateofPayment', 'Date of Payment', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            $this->form_validation->set_rules('transId', 'Transaction id / Reference No.', 'required');
            $this->form_validation->set_rules('slip', 'slip', 'callback_handle_upload');
            if ($data['lfine'] == '1') {
                $this->form_validation->set_rules('slip1', 'slip1', 'callback_handle_upload1');
            }

            if ($this->form_validation->run() == true) {

                $this->subjects = $this->getSelectedSubjectDetails($this->input->post('subjects'));

                $date['dop'] = $this->input->post('dateofPayment');
                $date['amt'] = $this->input->post('amount');
                $date['transId'] = $this->input->post('transId');
                $date['img_name'] = $this->img_name;
                $date['img_name'] = $this->img_name1;
                $date['sem'] = $this->input->post('g');
                $date['extype']=(($dates->exam_type=='exam')?'R':'S');
                $this->date = $date;
                //print_r($date);die();
                $this->confirm($open_id); //id of row
            } else {
                // $data = array();
                if ($this->session->userdata('isJRF')) {
                    //  $this->addJS("subject_mapping/stu_report_file.js");
                    $data['jrfS'] = $this->sbm->getjrfSubject();
                } else {
                    $data['sem'] = $this->sbm->getCourseDurationById($this->session->userdata('course_id'));
                    $data['g'] = $this->input->post('semesterR');
                }

                //  print_r($data);
                //$this->drawHeader('Examination Form for JRF, Repeater & Carryover');
                $this->load->view('template/header');
                $this->load->view('reg_exam_rc_form/stu/stu_reg_form', $data);
                $this->load->view('template/footer');
                ////$this->drawFooter();
            }
        } //if
        else {
            redirect('reg_exam_rc_form/reg_form/dateclosed');
        }
    }

    //function added by Kunal

    function getSelectedSubjectDetails($subject_keys){
      $sub=array();
      foreach ($subject_keys as $subject_key) {
        $sub[$subject_key]=$this->parseSubjectKey($subject_key);
      }
      return $sub;
    }

    function parseSubjectKey($subject_key){
      $sub=array();
      if($subject_key==='HSS_1'||$subject_key==='MS_1'){
        $sub=array(0,"","","JRF","JRF","JRF","",$subject_key);
      }else{
        $sub=explode("#",$subject_key);
      }
      $retsub=array();
      $retsub['sem']=$sub[0];
      $retsub['g1']=$sub[1];
      $retsub['g2']=$sub[2];
      $retsub['course_id']=$sub[3];
      $retsub['branch_id']=$sub[4];
      $retsub['dept_id']=$sub[5];
      $retsub['aggr_id']=$sub[6];
      $retsub['sub_id']=$sub[7];
      return $retsub;
    }

    //

    function getsemester() {
        $s = '';
        $sem = $this->sbm->getCourseDurationById($this->input->post('course_id'));
        for ($i = 1; $i <= $sem; $i++) {
            if ($i == $g) {
                $s.='<option selected="selected" value="' . $i . '">' . $i . ' Semester</option>';
            } else {
                $s.=' <option  value="' . $i . '">' . $i . ' Semester</option>';
            }
        }
        echo $s;
    }

    function getSubject() {

        if ($this->input->post('semesterR')) {
            $data['s'] = array();
            if (($this->input->post('semesterR') == 1) && ($this->session->userdata('course_id') == 'be' || $this->session->userdata('course_id') == 'b.tech' || $this->session->userdata('course_id') == 'dualdegree' || $this->session->userdata('course_id') == 'int.msc.tech' || $this->session->userdata('course_id') == 'int.m.tech' || $this->session->userdata('course_id') == 'int.m.sc')) {
                $data['s'] = $this->sbm->getSubject($this->session->userdata('course_id'), $this->session->userdata('branch_id'), $this->input->post('semesterR'), $this->session->userdata('id'), "", "", $this->input->post('g1'));
            } else if (($this->input->post('semesterR') == 2) && ($this->session->userdata('course_id') == 'be' || $this->session->userdata('course_id') == 'b.tech' || $this->session->userdata('course_id') == 'dualdegree' || $this->session->userdata('course_id') == 'int.msc.tech' || $this->session->userdata('course_id') == 'int.m.tech' || $this->session->userdata('course_id') == 'int.m.sc')) {
                $data['s'] = $this->sbm->getSubject($this->session->userdata('course_id'), $this->session->userdata('branch_id'), $this->input->post('semesterR'), $this->session->userdata('id'), "", "", $this->input->post('g2'));
            } else {
                $data['s'] = $this->sbm->getSubject($this->session->userdata('course_id'), $this->session->userdata('branch_id'), $this->input->post('semesterR'), $this->session->userdata('id'));
            }
            $data['s']['subjects']['resS'] = $this->input->post('semesterR');
            //echo $this->input->post('g1');
            echo json_encode($data['s']['subjects']);
        }
    }

    function getSubject1() {
        $this->load->model('student_sem_form/get_subject', '', TRUE);
        if ($this->input->post('semesterR')) {
            $data['s'] = array();
            if (($this->input->post('semesterR') == 1) && ($this->input->post('course_id') == 'be' || $this->input->post('course_id') == 'b.tech' || $this->input->post('course_id') == 'dualdegree' || $this->input->post('course_id') == 'int.msc.tech' || $this->input->post('course_id') == 'int.m.tech' || $this->input->post('course_id') == 'int.m.sc')) {
                //$data['s'] = $this->sbm->getSubject($this->input->post('course_id'), $this->input->post('branch_id'), $this->input->post('semesterR'), $this->session->userdata('id'), "", "", $this->input->post('g1'));
                $data['s'] = $this->get_subject->getSubjectviaAggrid($this->input->post('semesterR'), $this->input->post('aggr_id'), $this->input->post('dept_id'), $this->input->post('g1'), '');
            } else if (($this->input->post('semesterR') == 2) && ($this->input->post('course_id') == 'be' || $this->input->post('course_id') == 'b.tech' || $this->input->post('course_id') == 'dualdegree' || $this->input->post('course_id') == 'int.msc.tech' || $this->input->post('course_id') == 'int.m.tech' || $this->input->post('course_id') == 'int.m.sc')) {
                //$data['s'] = $this->sbm->getSubject($this->input->post('course_id'), $this->input->post('branch_id'), $this->input->post('semesterR'), $this->session->userdata('id'), "", "", $this->input->post('g2'));
                $data['s'] = $this->get_subject->getSubjectviaAggrid($this->input->post('semesterR'), $this->input->post('aggr_id'), $this->input->post('dept_id'), $this->input->post('g2'), '');
            } else {
                //  $data['s'] =$this->sbm->getSubject($this->input->post('course_id'), $this->input->post('branch_id'), $this->input->post('semesterR'), $this->session->userdata('id'),'',$this->input->post('dept_id'));
                $data['s'] = $this->get_subject->getSubjectviaAggrid($this->input->post('semesterR'), $this->input->post('aggr_id'), $this->input->post('dept_id'), '', 1);
            }
            $data['s']['subjects']['resS'] = $this->input->post('semesterR');
            //echo $this->input->post('g1');
            echo json_encode($data['s']['subjects']);
        }
    }

    //confirm//
    function confirm($id) {
        // print_r($this->date);

        $this->load->model('student_sem_form/get_results', '', TRUE);

        //$date = $this->vm->getOCDate();
        //echo $this->db->last_query();
        $date = $this->exam_form_new_model->get_row_details($id);

        //print_r($date);die();
        //if (empty($date)) {
        //    $date = $wfine = $this->exam_form_new_model->get_date_open_close_status_with_fine();
       // }
        $results = $this->get_results->getSemesterDetailsById($this->session->userdata('id'));
        //$this->drawHeader('Examination Form for Other, Repeator & Carryover Confirmation');
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/confirm', array('subjects' => $this->subjects, 'date' => $date, 'img_name1' => $this->img_name1, 'img_name' => $this->img_name, 'results' => $results));
        $this->load->view('template/footer');
        //$this->drawFooter();
    }

    //form Save
    function other_form_save() {
        date_default_timezone_set('Asia/Calcutta');
        //print_r($this->date);
        if ($this->session->userdata('isJRF')) {
            $c = 'jrf';
            $b = 'jrf';
        } else {
            $c = $this->session->userdata('course_id');
            $b = $this->session->userdata('branch_id');
        }
        if ($this->input->post('check') == 1) {
            $this->load->model('reg_exam_rc_form/form_insert_model', 'fim', true);
            $session = $this->input->post('session');
            $sem_form['admn_no'] = $this->session->userdata('id');
            $sem_form['course_id'] = $c;
            $sem_form['branch_id'] = $b;
            $sem_form['semester'] = $this->input->post('semesterR');
            $sem_form['reason'] = $this->input->post('reason');
            $sem_form['current_semester'] = $this->session->userdata('semester');
            $sem_form['session_year'] = $this->input->post('session_year');
            $sem_form['session'] = $this->input->post('session');
            $sem_form['course_aggr_id'] = 'N/A';
            $sem_form['timestamp'] = date("Y-m-d H:i:s");
            $sem_form['status'] = '0';
            $sem_form['type']= (($this->input->post('extype'))=='exam'?'R':'S');
            //print_r($sem_form);die();
            $lid = $this->fim->insertForm($sem_form);

            if ($this->SaveFee($lid)) {
                if ($this->insertSubject($lid)) {
                    $this->session->unset_userdata('flag');
                    /**
                    $this->load->model('student_sem_form/sbasic_model', '', TRUE);
                    $this->load->library('notification');
                    $hod = $this->sbasic_model->getHodbydept($this->session->userdata('dept_id'));
                    $this->load->model('department_auth/dept_basic_model', 'dbm', TRUE);
                    $fa = $this->dbm->getDAByDCBS($this->session->userdata('course_id'), $this->session->userdata('branch_id'), $this->session->userdata('semester') + 1);
                    if (!empty($fa)) {
                        $this->notification->notify($fa->emp_id, 'fa', 'Other Registration form Status', 'Registration form of Student( ' . $this->session->userdata('id') . ' ) has been submitted for your approval.', 'student_sem_form/regular_check', '');
                    }
                    $this->notification->notify($hod[0]->id, 'hod', 'Other Registration form Status', 'Registration form of Student( ' . $this->session->userdata('id') . ' ) has been submitted for your approval.', 'student_sem_form/regular_check', '');
                    //Close Spec//
                    **/
                    $this->load->model('reg_open_specific/reg_open_model', 'sp', true);
                    $this->sp->updateRegSpec(array('status' => '1'), array('admn_no' => $this->session->userdata('id')));

                    redirect('/reg_exam_rc_form/reg_form/done/' . $lid, 'refresh');
                }
            }
        }
    }

    private function insertSubject($lid) {
        $this->load->model('reg_exam_rc_form/form_insert_model', 'fim', true);
        $i = 1;
        $this->subjects=$this->getSelectedSubjectDetails($this->input->post('subjects'));
        foreach ($this->subjects as $sub) {
            $subj = array();
            $subj['form_id'] = $lid;
            $subj['sub_seq'] = $i;
            $subj['sub_id'] = $sub['sub_id'];
            $subj['sem']=$sub['sem'];
            $subj['g1']=$sub['g1'];
            $subj['g2']=$sub['g2'];
            $subj['course_id']=$sub['course_id'];
            $subj['branch_id']=$sub['branch_id'];
            $subj['dept_id']=$sub['dept_id'];
            $subj['aggr_id']=$sub['aggr_id'];
            if($this->fim->insertSubject($subj)){
              $i++;
            }else{
              echo 'Error';
              return false;
            }
        }
        return true;
    }

    private function SaveFee($lid) {
        $this->load->model('reg_exam_rc_form/form_insert_model', 'fim', true);
        $fee['form_id'] = $lid;
        $fee['admn_no'] = $this->session->userdata('id');
        $fee['fee_date'] = date('Y-m-d', strtotime($this->input->post('dateofPayment')));
        $fee['fee_amt'] = $this->input->post('amount');
        $fee['transaction_id'] = $this->input->post('transId');
        $fee['receipt_path'] = $this->input->post('image_name');
        $fee['late_receipt_path'] = $this->input->post('image_name1');
        // print_r($fee); die();
        $this->fim->insertFeeDetails($fee);
        return true;
    }

    function resubmitfrom($id) {
        $this->session->set_userdata('flag', $id);
        redirect('/reg_exam_rc_form/reg_form', 'refresh');
    }

    function success() {
        $sem = ($this->session->userdata('semester'));
        if ($this->session->userdata('course_id') == 'jrf') {
            $sem = '-1';
        }
        $data['status'] = $this->sbm->formrResponse($this->session->userdata('id'), $sem);

        $data['oldrecord'] = $this->sbm->getApprovedFormByStudent($this->session->userdata('id'));
        //print_r($data);die();
        if (!is_array($data['oldrecord'])) {
            $data['oldrecord'] = 0;
        }
        /**
        if ($this->session->userdata('isJRF')) {
            $this->drawHeader('JRF Registration Form Status');
        } else {
            $this->drawHeader('Other Registration Form Status');
        }
        **/
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/success', $data);
        $this->load->view('template/footer');
        //$this->drawFooter();
    }

    function view($id, $fid, $p = 0) {

        if (isset($id)) {
            $this->load->model('student_sem_form/get_results', '', TRUE);
            $this->load->model('student_sem_form/sbasic_model', '', TRUE);
            $data['student'] = $this->sbm->GetStudent($id, $fid);
            $data['confirm'] = $this->sbm->getSelectedSubject($data['student'][0]->form_id);
            $data['results'] = $this->get_results->getSemesterDetailsById($this->session->userdata('id'));

            if ($this->session->userdata('isJRF')) {
                $data['stu_auth'] = '1';
            } else {
                $data['stu_auth'] = '0';
            }

            $this->load->view('templates/header_assets');
            if ($p == 1) {
                $this->load->helper(array('dompdf', 'file'));
                //$html.=	$this->load->view('templates/header_assets');
                $html = $this->load->view('reg_exam_rc_form/stu/print_form.php', $data, TRUE);
                pdf_create($html, 'Regform_' . $data['student'][0]->admn_no);
            } else {

                $this->load->view('reg_exam_rc_form/stu/view.php', $data);
            }
        }
    }

    //done
    function done($id) {
        //$this->drawHeader('');
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/done', array('fid' => $id));
        $this->load->view('template/footer');
        //$this->drawFooter();
    }

    //date Colosed Error//
    function dateclosed() {
        //$this->drawHeader('Date has been closed OR Not Open');
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/dateC');
        $this->load->view('template/footer');
        //$this->drawFooter();
    }

    //Semester Validation//
    function semesterError() {
        //$this->drawHeader('You Cannt fill the form of this Semester');
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/serror');
        $this->load->view('template/footer');
        //$this->drawFooter();
    }

    function courseError() {
        //$this->drawHeader('You Cannt fill the form of this Semester');
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/cerror');
        $this->load->view('template/footer');
        //$this->drawFooter();
    }

    //Validation Handler//
    function check_default() {
        $items=array();
        $items=$this->input->post('subjects');
        //print_r($items);
        if(count($items)==0){
          $this->form_validation->set_message('check_default','no subject was selected');
          return false;
        }
    		foreach ($items as $item) {
    			if($item==''){
            $this->form_validation->set_message('check_default','NULL subject found');
    				return false;
    			}
    		}
    		return true;
    }

    //Image Upload Validation Handler || Image upload Regular fee//
    function handle_upload() {
        $config['upload_path'] = './assets/images/reg_exam_rc_form/exam_slip/';
        $config['max_size'] = '200';
        $config['allowed_types'] = 'pdf|jpg|png|jpeg';
        $config['file_name'] = $this->session->userdata('id') . "_" . ($this->session->userdata('semester') + 1);
        $this->load->library('upload', $config);

        if (isset($_FILES['slip']) && !empty($_FILES['slip']['name'])) {

            if ($this->upload->do_upload('slip')) {
                // set a $_POST value for 'image' that we can use later
                $upload_data = $this->upload->data();
                $this->img_name = $upload_data['file_name'];
                $_POST['slip'] = $upload_data['file_name'];
                return true;
            } else {
                // possibly do some clean up ... then throw an error
                $this->form_validation->set_message('handle_upload', $this->upload->display_errors());
                return false;
            }
        } else {
            // throw an error because nothing was uploaded
            $this->form_validation->set_message('handle_upload', "You must upload Fee Receipt!");
            return false;
        }
    }

    function handle_upload1() {
        $config['upload_path'] = './assets/images/reg_exam_rc_form/exam_slip/';
        $config['max_size'] = '200';
        $config['allowed_types'] = 'pdf|jpg|png|jpeg';
        $config['file_name'] = $this->session->userdata('id') . "_" . ($this->session->userdata('semester') + 1) . "_lateFee";
        $this->load->library('upload', $config);

        if (isset($_FILES['slip1']) && !empty($_FILES['slip1']['name'])) {

            if ($this->upload->do_upload('slip1')) {
                // set a $_POST value for 'image' that we can use later
                $upload_data = $this->upload->data();
                $this->img_name1 = $upload_data['file_name'];
                $_POST['slip'] = $upload_data['file_name'];
                return true;
            } else {
                // possibly do some clean up ... then throw an error
                $this->form_validation->set_message('handle_upload', $this->upload->display_errors());
                return false;
            }
        } else {
            // throw an error because nothing was uploaded
            $this->form_validation->set_message('handle_upload', "You must upload Fee Receipt!");
            return false;
        }
    }

    function getaggrId() {
        $data = $this->sbm->getaggrIdbyCB($this->input->post('course'), $this->input->post('branch'));

        $e = "<option value=''>Select year</option>";
        if (count($data) > 0) {
            foreach ($data as $d) {
                $e.="<option value='" . $d->aggr_id . "'>" . $d->bc . "</option>";
            }
        }
        echo $e;
    }

    function wrong_student() {
        //$this->drawHeader('Examination Form');
        $this->load->view('template/header');
        $this->load->view('reg_exam_rc_form/stu/serror');
        $this->load->view('template/footer');
        ////$this->drawFooter();
    }

}

?>
