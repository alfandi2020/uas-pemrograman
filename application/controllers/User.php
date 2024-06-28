<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	// private $param;

	public function __construct() {
        parent::__construct();
		$this->load->model(array('M_Registrasi','M_User'));
		if ($this->session->userdata('id_user') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
	}
	public function index()
	{
		$data = [
			'user' => $this->db->get('users')->result(),
			'title' => 'List User'
		];
		$this->load->view('temp/header',$data);
		$this->load->view('body/user/view',$data);
		$this->load->view('temp/footer');
	}
	public function create()
	{
		$data = [
			'title' => 'Buat User'
		];
		$this->load->view('temp/header',$data);
		$this->load->view('body/user/create');
		$this->load->view('temp/footer');
	}
	function submit_user(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$password_conf = $this->input->post('password_conf');
		$nama = $this->input->post('nama');
		$role = $this->input->post('role');
		$this->db->where('username',$username);
        $cek = $this->db->get('users')->num_rows();
		if ($username == true && $nama == true  && $password == true && $role == true) {
			if ($password == $password_conf) {
				if ($cek != true) {
					$data = [
						"nama" => $nama,
						"username" => $username,
						"password" => password_hash($password,PASSWORD_DEFAULT),
						"role" => $role
					];
					$this->db->insert('users',$data);
					$msg = [
						"response" => "success",
						"message" => "Data user $username berhasil ditambahakan"
					];
					echo json_encode($msg);
				
				}else{
					$msg = [
						"response" => "double",
						"message" => "Data user $username sudah ada"
					];
					echo json_encode($msg);
				}
			}else{
				$msg = [
					"response" => "error",
					"message" => "Password harus sama"
				];
				echo json_encode($msg);
			}
		}else{
			$msg = [
				"response" => "error",
				"message" => "Form tidak boleh kosong"
			];
			echo json_encode($msg);
			
		}
	}
	function get_user(){
		$id = $this->input->post('id');
		$this->db->where('id',$id);
		$data['user'] = $this->db->get('users')->row_array();
		$this->load->view('body/user/modal_update',$data);
	}
	function update(){
		if ($this->input->post('password') === $this->input->post('password_konf')) {
			$id = $this->input->post('id');
			$username = $this->input->post('username');
			$this->M_User->update_user($id);
			echo 'Data user '.$username.' berhasil diupdate';
		}else{
			echo "Password harus sama";
		}
	}

	function delete($id){
		$this->db->delete('users', array('id' => $id)); 
		redirect('user');
	}
	function paket(){
		$id = $this->input->post('id');
		$data = $this->M_Registrasi->get_paket($id);
		echo json_encode($data);
	}
	public function filter(){
        if($this->uri->segment(3)){
            $filter = $this->uri->segment(3);
            $this->session->set_userdata('menu-footer', $filter);
            redirect('home/'.$this->uri->segment(3));
        }
    }
}
