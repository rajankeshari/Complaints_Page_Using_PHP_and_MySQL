<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() {
		if($this->session->userdata('isLoggedIn'))
			redirect('home');
		else
			$this->showlogin();
	}

	//access private so that it can't be called from outside
	private function showlogin($error_code = 0) {
		$data['error_code'] = $error_code;
    	$this->load->view('templates/header_assets');
    	$this->load->view('login',$data);
	}

	function login_user() {
		
    	$user = $this->input->post('username');
     	$pass = $this->input->post('password');
		
		

		$this->load->model('user/users_model','',TRUE);
		//Ensure values exist for user and pass, and validate the user's credentials
		if( $user && $pass && $this->users_model->validate_user($user,$pass)) {
			$this->load->model('user/user_login_attempts_model','',TRUE);
			$this->user_login_attempts_model->insert(array("id" => $this->session->userdata('id'), "time" => date('Y-m-d H:i:s')));
                       //changes
                        $maxID= $this->user_login_attempts_model->get_log_in_maxID($this->session->userdata('id'));
                 
                        $re= $this->user_login_attempts_model->get_logg(array('log_id'=>$maxID));
                               if(is_array($re)){
                                if($re[0]->logged_out_time == ''){
                          $this->user_login_attempts_model->update_log(
                                  array('logged_out_time'=>date('Y-m-d H:i:s'),'logout_ip'=>$this->input->ip_address()),
                                  array('log_id'=>$maxID)
                                  );
                                }
                               }
                                $data['user_id'] = $this->session->userdata('id');
                                $data['logged_in_time']=date('Y-m-d H:i:s');
                                $data['login_ip']=$this->input->ip_address();
                                $this->user_login_attempts_model->insert_log($data);
                        
			redirect('home');
		}
		else $this->showlogin(1);
	}

	function login_user_app() {
        
       // $user = $this->input->post('1183');
        //$pass = $this->input->post('p');
    	$user ='16';
        $pass = 'exam123';
	//$this->load->helper('cookie');
        $this->load->model('user/users_model','',TRUE);
        //Ensure values exist for user and pass, and validate the user's credentials
        if( $user && $pass && $this->users_model->validate_user($user,$pass)) {
            $this->load->model('user/user_login_attempts_model','',TRUE);
            $this->user_login_attempts_model->insert(array("id" => $this->session->userdata('id'), "time" => date('Y-m-d H:i:s')));
                       //changes
                        $maxID= $this->user_login_attempts_model->get_log_in_maxID($this->session->userdata('id'));
                 
                        $re= $this->user_login_attempts_model->get_logg(array('log_id'=>$maxID));
                               if(is_array($re)){
                                if($re[0]->logged_out_time == ''){
                          $this->user_login_attempts_model->update_log(
                                  array('logged_out_time'=>date('Y-m-d H:i:s'),'logout_ip'=>$this->input->ip_address()),
                                  array('log_id'=>$maxID)
                                  );
                                }
                               }
                                $data['user_id'] = $this->session->userdata('id');
                                $data['logged_in_time']=date('Y-m-d H:i:s');
                                $data['login_ip']=$this->input->ip_address();
                                $this->user_login_attempts_model->insert_log($data);
                        
            $data['status']='true';
            $data['id']=$this->session->userdata('id');
        //    set_cookie('cookie_user',$this->session->userdata('id'),'3600'); 
            /*$cookie = array(
                    'name'   => cookie_user,
                    'value'  =>$this->session->userdata('id'),
                    'expire' =>  86500,
                    'secure' => false
                );
            $this->input->set_cookie($cookie); 
            $data['cookie_user']=get_cookie('cookie_user');*/
            echo json_encode($data);
        }
        else{
             $data['status']='false';
             echo json_encode($data);
        } 
    }
	function logout($error_code = 0) {
        $this->load->model('user/user_login_attempts_model','',TRUE);
          $maxID= $this->user_login_attempts_model->get_log_in_maxID($this->session->userdata('id'));
          $this->user_login_attempts_model->update_log(
                                  array('logged_out_time'=>date('Y-m-d H:i:s'),'logout_ip'=>$this->input->ip_address()),
                                  array('log_id'=>$maxID)
                                  );
        $this->session->sess_destroy();
        $this->showlogin($error_code);
	}
}

/* End of file login.php */
/* Location: mis/application/controllers/login.php */
