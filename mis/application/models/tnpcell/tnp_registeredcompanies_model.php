<?
	class Tnp_registeredcompanies_model extends CI_Model {
		function __construct(){
			parent::__construct();
		}

		public function get_unvalidated_companies_list(){
			$this->db->where('validated', 0);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get('company_registration');
			return $query->result();
		}

		public function get_validated_companies_list(){
			$this->db->where('validated', 1);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get('company_registration');
			return $query->result();
		}

		public function get_registeredForm_details($company_id){
			$this->db->where('id', $company_id);
			$query = $this->db->get('company_registration')->row();
			return $query;
		}

		public function validate_company($company_id){
			$this->db->where('id', $company_id);
			$this->db->update('company_registration', array('validated' => 1));
		}

		public function unvalidate_company($username){
			$this->db->where('username', $username);
			$this->db->update('company_registration', array('validated' => 0));
		}

		public function declineRecruiter($company_id){
			$this->db->where('id', $company_id);
			$this->db->update('company_registration', array('validated' => -1));
		}

		public function get_company_id($user_id){
			$this->db->where('id', $user_id);
			$query = $this->db->get('recruiter_details')->row();
			return $query;
		}

		public function addUsers($data){
			$this->db->insert('users', $data);
		}

		public function addUserDetails($data){
			$this->db->insert('user_details', $data);
		}

		public function addJNFuserDetails($data){
			$this->db->insert('jnf_user_details', $data);
		}

		public function addJNFuser($data){
			$this->db->insert('jnf_users', $data);
		}

		public function linkCompanyRecruiter($data){
			$this->db->insert('recruiter_details', $data);
		}

		public function addJNFextraDetails($company_id){
			$this->db->insert('jnf_company_details', $company_id);
			$this->db->insert('jnf_logistics', $company_id);
			$this->db->insert('jnf_salary', $company_id);
			$this->db->insert('jnf_selectioncutoff', $company_id);
			$this->db->insert('jnf_selectionprocess', $company_id);
			
			$this->db->insert('inf_company_details', $company_id);
			$this->db->insert('inf_logistics', $company_id);
			$this->db->insert('inf_salary', $company_id);
			$this->db->insert('inf_selectioncutoff', $company_id);
			$this->db->insert('inf_selectionprocess', $company_id);
		}

		public function addTNPcompany($data){
			$this->db->insert('tnp_company_jnf_inf', $data);
		}

		public function deleteRecruiter($user_id, $company_id){
			$this->db->where('id', $user_id);
			$this->db->delete(array('user_details', 'recruiter_details'));
			$this->db->delete('users', array('id' => $user_id));

			$this->db->where('company_id', $company_id);
			$this->db->delete(array('jnf_user_details', 'jnf_company_details', 'jnf_logistics', 'jnf_salary', 'jnf_selectioncutoff', 'jnf_selectionprocess'));
			$this->db->delete('jnf_users', array('company_id' => $company_id));

		}
	}
?>
