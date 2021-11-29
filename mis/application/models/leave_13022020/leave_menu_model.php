<?php

/*
 * Author : Nishant raj
 */
require_once 'leave_constants.php';

class leave_menu_model extends CI_Model {

    function __construct() {
        // Calling the Model parent constructor
        parent::__construct();
    }

    function getMenu() {

        $menu = array();

        $menu['emp'] = array();
        //$menu['emp']['Leave Management']['Station Leave'] = array();
       // $menu['emp']['Leave Management']['Station Leave']['Apply for Station Leave'] = site_url('leave/leave_station');
       // $menu['emp']['Leave Management']['Station Leave']['Station Leave Archive'] = site_url('leave/leave_station/stationLeaveHistory');
        //$menu['emp']['Leave Management']['Station Leave']['Edit/Cancel Station Leave'] = site_url('leave/leave_station/cancelStationLeave');
        $menu['emp']['Leave Management']['Application Form'] = site_url('leave/leave_all_kind_application');
        $menu['emp']['Leave Management']['My Leave Archive'] = site_url('leave/leave_all_kind_application/archive');
        $menu['emp']['Leave Management']['My Current Leave Balance'] = site_url('leave/leave_balance_individual');

		/*$ADMIN_ARRAY for Director only*/		
        $menu['dt'] = array();
        $menu['dt']['Leave Management']['Leave Applications'] = site_url('leave/leave_all_kind_application/approving_authority');
        $menu['dt']['Leave Management']['My History'] = site_url('leave/leave_all_kind_application/history_of_authority');
		$menu['dt']['Leave Management']['Station Leave History'] = site_url('leave/leave_all_kind_application/station_leave_history_for_dt');
		/*$ADMIN_ARRAY for Director only*/
			
			
        //$menu['emp']['Leave Management']['Leave History'] = site_url('leave/leave_history');
        //$menu['emp']['Leave Management']['Cancel Leave'] = site_url('leave/leave_cancel');
//        $menu['hod']['Leave Management']['Station Leave'] = array();
//        $menu['hod']['Leave Management']['Station Leave']['Leave for Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['hod']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded Leave'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['hod']['Leave Management']['Station Leave']['Leave History of Employee'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//        $menu['hos']['Leave Management']['Station Leave'] = array();
//        $menu['hos']['Leave Management']['Station Leave']['Leave for Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['hos']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded Leave'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['hos']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//
        /* $menu['deo']['Leave Management'] =array();
          $menu['deo']['Leave Management']['Restricted/Vacation Date entry']=site_url('leave/leave_admin/vacationRestrictedDateEntry');
          $menu['deo']['Leave Management']['New Balance Entry'] = site_url('leave/leave_admin/newLeaveBalanceEntry');
          $menu['deo']['Leave Management']['Edit/Delete Restricted/Vacation Dates'] = site_url('leave/leave_admin/datesEditDelete');
         */
//        $menu['RG']['Leave Management'] =array();
////        $menu['RG']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['RG']['Leave Management']['Station Leave'] = array();
//        $menu['RG']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['RG']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['RG']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//        $menu['DEAN_ACAD']['Leave Management'] =array();
////        $menu['DEAN_ACAD']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['DEAN_ACAD']['Leave Management']['Station Leave'] = array();
//        $menu['DEAN_ACAD']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['DEAN_ACAD']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['DEAN_ACAD']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//
//        $menu['DT']['Leave Management'] =array();
////        $menu['DT']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['DT']['Leave Management']['Station Leave'] = array();
//        $menu['DT']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['DT']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['DT']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//
//        $menu['DEAN_RnD']['Leave Management'] =array();
//        //$menu['DEAN_RnD']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['DEAN_RnD']['Leave Management']['Station Leave'] = array();
//        $menu['DEAN_RnD']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['DEAN_RnD']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['DEAN_RnD']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//        $menu['DEAN_FnP']['Leave Management'] =array();
//        //$menu['DEAN_FnP']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['DEAN_FnP']['Leave Management']['Station Leave'] = array();
//        $menu['DEAN_FnP']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['DEAN_FnP']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['DEAN_FnP']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
//
//        $menu['admin']['Leave Management'] =array();
//        //$menu['DEAN_FnP']['Leave Management']['View Leave History'] = site_url('leave/leave_deo/leave_administration');
//        $menu['admin']['Leave Management']['Station Leave'] = array();
//        $menu['admin']['Leave Management']['Station Leave']['Approval/Disapproval'] = site_url('leave/leave_station/pendingStationLeaveStatus');
//        $menu['admin']['Leave Management']['Station Leave']['Approved/Rejected/Forwarded'] = site_url('leave/leave_station/adminLeaveHistory');
//        $menu['admin']['Leave Management']['Station Leave']['Leave History'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee');
        $i = 0;
        foreach (Leave_constants::$ADMIN_ARRAY as $row) {

            $menu[$row]['Leave Management'] = array();
            $menu[$row]['Leave Management']['Leave Applications'] = site_url('leave/leave_all_kind_application/approving_authority');
            $menu[$row]['Leave Management']['My History'] = site_url('leave/leave_all_kind_application/history_of_authority');
            /*  $menu[$row]['Leave Management']['Approval/Cancellation/Forward'] = site_url('leave/leave_admin/ApprovalRejectionForward');
              $menu[$row]['Leave Management']['Approved/Cancelled/Forwarded'] = site_url('leave/leave_admin/leaveApprovedRejectedForwarded');
              $menu[$row]['Leave Management']['Leave History'] = site_url('leave/leave_admin/leaveHistoryOfEmployee');
              $menu[$row]['Leave Management']['Leave Balance Entry'] = site_url('leave/leave_admin/newLeaveBalanceEntry');
              $menu[$row]['Leave Management']['Balance Update History'] = site_url('leave/leave_admin/balanceUpdateHistory');
             * 
             */
            //$menu[$row]['Leave Management']['Station Leave'] = array();
            // $menu[$row]['Leave Management']['Station Leave']['New/Pending Leave'] = site_url('leave/leave_station/pendingStationLeaveStatus');  // Disapproval caption changed to Rejection As per rq. 16/9/15
            //$menu[$row]['Leave Management']['Station Leave']['Processed Leave'] = site_url('leave/leave_station/adminLeaveHistory'); // approved/rejected/forwarded caption changed to Processed Leaves List As per rq. 16/9/15
            //$menu[$row]['Leave Management']['Station Leave']['Leave Archive'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee'); // Leave History   caption changed to Leaves Archive As per rq. 16/9/15
            $i++;
        }
        $i = 0;
        foreach (Leave_constants::$OTHERS_UNIQUE_ADMIN as $row) {

            $menu[$row]['Leave Management'] = array();
            //$menu[$row]['Leave Management']['Station Leave'] = array();
            //$menu[$row]['Leave Management']['Station Leave']['New/Pending Leave'] = site_url('leave/leave_station/pendingStationLeaveStatus');  // Disapproval caption changed to Rejection As per rq. 16/9/15
            //$menu[$row]['Leave Management']['Station Leave']['Processed Leave'] = site_url('leave/leave_station/adminLeaveHistory'); // approved/rejected/forwarded caption changed to Processed Leaves List As per rq. 16/9/15
            //$menu[$row]['Leave Management']['Station Leave']['Leave Archive'] = site_url('leave/leave_station/adminLeaveHistoryAllEmployee'); // Leave History   caption changed to Leaves Archive As per rq. 16/9/15
            $menu[$row]['Leave Management']['Nature of Leaves Master'] = site_url('leave/leave_types');
			$menu[$row]['Leave Management']['Pupose of Visit Master'] = site_url('leave/leave_tour_purpose_of_visit_master');
            $menu[$row]['Leave Management']['Entry form of balance sheet for all type of leaves'] = site_url('leave/leave_balance_sheet');
            //$menu[$row]['Leave Management']['Entry of Leave Credit'] = site_url('leave/Leave_credit_sheet');
            $menu[$row]['Leave Management']['Entry form for Dynamic Leave Credit'] = site_url('leave/leave_dynamic_credit_sheet');
            //$menu[$row]['Leave Management']['Edit form for Dynamic Leave Credit'] = site_url('leave/Leave_dynamic_credit_edit_sheet');
            $menu[$row]['Leave Management']['Entry form for Leave Availed'] = site_url('leave/leave_availed_sheet');
			$menu[$row]['Leave Management']['Application Form'] = site_url('leave/leave_all_kind_application/admin_side_application');
			$menu[$row]['Leave Management']['Approved Leave Applications'] = site_url('leave/leave_all_kind_application/admin_side_archive');
			$menu[$row]['Leave Management']['Approved Leave Applications History'] = site_url('leave/leave_all_kind_application/admin_side_archive_history');
			
			

            $i++;
        }
        return $menu;
    }

}
