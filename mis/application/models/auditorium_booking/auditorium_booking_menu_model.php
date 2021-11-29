<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditorium_booking_menu_model extends CI_Model{
	function __construct(){
		parent::__construct(array());
	}
	function getMenu()
	{
		$menu = array();
		$menu['emp'] = array();
		$menu['emp']['Auditorium Booking'] = array();
		$menu['emp']['Auditorium Booking']['Book Auditorium'] = site_url('auditorium_booking/applier_end/init');
		$menu['emp']['Auditorium Booking']['Current Bookings'] = site_url('auditorium_booking/auditorium_request/currentStandingRequests');

		$menu['admin'] = array();
		$menu['admin']['Auditorium Booking'] = array();
		$menu['admin']['Auditorium Booking']['List of Auditoriums'] = site_url('auditorium_booking/auditorium_admin/auditoriumList');

		$menu['deo'] = array();
		$menu['deo']['Auditorium Booking'] = array();
		$menu['deo']['Auditorium Booking']['List of Auditoriums'] = site_url('auditorium_booking/auditorium_admin/auditoriumList');

		$menu['stu'] = array();
		$menu['stu']['Auditorium Booking']['Book Auditorium'] = site_url('auditorium_booking/applier_end/init');
		$menu['stu']['Auditorium Booking']['Booking History'] = site_url('auditorium_booking/applier_end/history');

		return $menu;
	}
}
?>