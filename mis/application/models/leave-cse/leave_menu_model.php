<?php
/*
 * Author : Nishant raj
 */
class Leave_menu_model extends CI_Model{

    function __construct()
    {
        // Calling the Model parent constructor
        parent::__construct();
    }

    function getMenu(){

        $menu = array();

        $menu['emp'] = array();
        $menu['emp']['Leave Management'] = array();
        //$menu['emp']['Leave Management']['Apply For Leave'] = site_url('leave/leave_application');
        $menu['emp']['Leave Management']['Station Leave'] = array();
        $menu['emp']['Leave Management']['Station Leave']['Apply for Station Leave'] = site_url('leave/leave_station');
        $menu['emp']['Leave Management']['Station Leave']['Station Leave History'] = site_url('leave/leave_station/stationLeaveHistory');
        $menu['emp']['Leave Management']['Station Leave']['Cancel Station Leave'] = site_url('leave/leave_station/cancelStationLeave');
        //$menu['emp']['Leave Management']['Leave History'] = site_url('leave/leave_history');
        //$menu['emp']['Leave Management']['Cancel Leave'] = site_url('leave/leave_cancel');

        $menu['hod']['Leave Management']['Station Leave'] = array();
        $menu['hod']['Leave Management']['Station Leave']['Leave for Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['hod']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded Leave'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['hod']['Leave Management']['Station Leave']['Leave History of Employee'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');

        $menu['hos']['Leave Management']['Station Leave'] = array();
        $menu['hos']['Leave Management']['Station Leave']['Leave for Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['hos']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded Leave'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['hos']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');


        $menu['deo']['Leave Management'] =array();
        $menu['deo']['Leave Management']['Leave Entry by Employee ID']=site_url('leave/leave_deo');
        $menu['deo']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['deo']['Leave Management']['Station Leave']['Leave Approved/Rejected/Forwarded by You'] = site_url('leave/leave_station/adminLeaveHistory');


        $menu['RG']['Leave Management'] =array();
//        $menu['RG']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
        $menu['RG']['Leave Management']['Station Leave'] = array();
        $menu['RG']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['RG']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['RG']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');

        $menu['DEAN_ACAD']['Leave Management'] =array();
//        $menu['DEAN_ACAD']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
        $menu['DEAN_ACAD']['Leave Management']['Station Leave'] = array();
        $menu['DEAN_ACAD']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['DEAN_ACAD']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['DEAN_ACAD']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');


        $menu['DT']['Leave Management'] =array();
//        $menu['DT']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
        $menu['DT']['Leave Management']['Station Leave'] = array();
        $menu['DT']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['DT']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['DT']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');


        $menu['DEAN_RnD']['Leave Management'] =array();
        //$menu['DEAN_RnD']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
        $menu['DEAN_RnD']['Leave Management']['Station Leave'] = array();
        $menu['DEAN_RnD']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['DEAN_RnD']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['DEAN_RnD']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');

        $menu['DEAN_FnP']['Leave Management'] =array();
        //$menu['DEAN_FnP']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
        $menu['DEAN_FnP']['Leave Management']['Station Leave'] = array();
        $menu['DEAN_FnP']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['DEAN_FnP']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['DEAN_FnP']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');

        $menu['admin']['Leave Management'] =array();
        //$menu['DEAN_FnP']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
        $menu['admin']['Leave Management']['Station Leave'] = array();
        $menu['admin']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
        $menu['admin']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
        $menu['admin']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');

        return $menu;
    }
}

