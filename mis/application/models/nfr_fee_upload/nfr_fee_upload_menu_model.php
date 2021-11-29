


<?php
/*
 * Author : Jay Doshi
 */
class Nfr_fee_upload_menu_model extends CI_Model{

    function __construct()
    {
        // Calling the Model parent constructor
        parent::__construct();
    }

    function getMenu(){

        $menu = array();

        $menu['acc_hos'] = array();
        $menu['acc_hos']['NFR'] = array();
        $menu['acc_hos']['NFR']['NFR Fee Upload'] = site_url("nfr_fee_upload/upload_fee_details");
        $menu['acc_hos']['NFR']['Sink NFR Fee'] = site_url("nfr_fee_upload/upload_fee_details/sink_fee_details_nfr");

        $menu['acc_hos']['Semester Fee Payment'] = array();
        $menu['acc_hos']['Semester Fee Payment']['Fee Offline Upload'] = site_url("semester_fee_upload/semester_fee_upload");
        $menu['acc_hos']['Semester Fee Payment']['Sink Offline Fee'] = site_url("semester_fee_upload/semester_fee_upload/sink_fee_details_semester");

        $menu['nfr_fee_payment'] = array();
        //$menu['nfr_fee_payment']['NFR'] = array();
        $menu['nfr_fee_payment']['NFR Fee Upload'] = site_url("nfr_fee_upload/upload_fee_details");
        $menu['nfr_fee_payment']['Sink NFR Fee'] = site_url("nfr_fee_upload/upload_fee_details/sink_fee_details_nfr");

        return $menu;


    }

}

?>