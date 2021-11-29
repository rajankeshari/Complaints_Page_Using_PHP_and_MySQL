<?php

class Auditorium_constants{
	//Types of Status for Booking
	static $PENDING_BOOKING = 1;
	static $ACCEPT_BOOKING = 2;
	static $REJECT_BOOKING = 3;
	static $CANCEL_BOOKING = 4;
	static $WAITING_CANCEL_AT_PENDING = 15;
	static $WAITING_CANCEL_AT_ACCEPT = 25;

	//Request levels
	static $REQUEST_BASE = 0;
	static $REQUEST_HEAD = 1;
	static $REQUEST_INCHARGE = 2;
	static $REQUEST_DIRECTOR = 3;

	//Table Names
	static $STATUS_TABLE = "auditorium_booking";
	static $DETAILS_TABLE = "auditorium_details";

	static $REQUESTS_AUTH_ARRAY = array('dt', 'dt_pa', 'dt_da1', 'dt_da2', 'dt_da3',
										'adean_sw', 'dean_sw_pa', 'dean_sw_da', 'adean_sw_da',
										'hos', 'sw_dr', 'sw_ar', 'sw_da1', 'sw_da2');

	//Keys for encyption
	static $ENCRYPTION_METHOD = 'aes128';
	static $PRIVATE_KEY = '28512f6a041e37ad89c659261a411e7e36f2eb4e';
	static $SALT = '892e322c35326ebb';

	static $APPLIER_AUTH = array('stu', 'emp');
	static $APPROVER_LOW = array('dsw', 'hod', 'hos', 'adean_sw', 'dean_sw_pa', 'dean_sw_da', 'adean_sw_da','sw_dr', 'sw_ar', 'sw_da1', 'sw_da2');
	static $APPROVER_HIGH = array('dt', 'dt_pa', 'dt_da1', 'dt_da2', 'dt_da3','rg', 'rg_pa', 'rg_so', 'rg_da1', 'rg_da2', 'rg_da3');

}

?>