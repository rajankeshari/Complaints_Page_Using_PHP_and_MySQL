<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stu_edit_notice extends MY_Controller {

    public function __construct() {
        parent::__construct(array('stu'));
    }

    public function index() {


        $this->drawHeader("Student Edit Module");
        $this->load->view('student/edit/stu_edit_notice_view');
        $this->drawFooter();
    }

}
?>

