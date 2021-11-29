
<?php
/*
 * Author : Jay Doshi
 */
class Semester_fee_upload_menu_model extends CI_Model{

    function __construct()
    {
        // Calling the Model parent constructor
        parent::__construct();
    }

    function getMenu(){

        $menu = array();

        $menu['offline_sem_fee_admin'] = array();
        // $menu['acc_hos']['NFR'] = array();
        // $menu['acc_hos']['NFR']['NFR Fee Upload'] = site_url("nfr_fee_upload/upload_fee_details");
        // $menu['acc_hos']['NFR']['Sink NFR Fee'] = site_url("nfr_fee_upload/upload_fee_details/sink_fee_details_nfr");

        $menu['offline_sem_fee_admin']['Semester Fee Payment'] = array();
        $menu['offline_sem_fee_admin']['Semester Fee Payment']['Fee Offline Upload'] = site_url("semester_fee_upload/semester_fee_upload");
        $menu['offline_sem_fee_admin']['Semester Fee Payment']['Sink Offline Fee'] = site_url("semester_fee_upload/semester_fee_upload/sink_fee_details_semester");

        return $menu;


    }

}

?>