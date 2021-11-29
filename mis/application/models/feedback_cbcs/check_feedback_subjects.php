<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Check_feedback_subjects extends MY_Controller {

    public function __construct() {

        parent::__construct(array('stu'));

        $this->load->model('feedback/check_feedback_subjects_model', '', TRUE);
    }

    public function index() {
        $admn_no=$this->session->userdata('id');
        $data['details_core'] = $this->check_feedback_subjects_model->get_details_core($admn_no);
        $data['details_elec'] = $this->check_feedback_subjects_model->get_details_elective($admn_no);
        $data['details_hon'] = $this->check_feedback_subjects_model->get_details_honour($admn_no);
        $data['details_min'] = $this->check_feedback_subjects_model->get_details_minor($admn_no);

        $this->drawheader('Check Feedback Subjects');
        $this->load->view('feedback/student/check_feedback_subjects_view', $data);
        $this->drawfooter();
    }
    }

?>
