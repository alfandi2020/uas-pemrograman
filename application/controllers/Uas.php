<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uas extends CI_Controller {
	// private $param;

	public function __construct() {
        parent::__construct();
        // $this->load->library('Pdf');
        // $this->load->model('M_admin');
        $this->load->helper(array('form', 'url'));
        // $this->load->library(array('form_validation','Routerosapi'));
        //cek jika user blm login maka redirect ke halaman login
        // if ($this->session->userdata('username', 'nama')  != true) {
        //     $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Maaf anda belum login !</div>');
        //     redirect('login');
        // }
	}
	public function index()
	{
        $data = [
            "title" => 'dashboard',
            'row' => $this->db->get('mahasiswa')->result()
        ];
		// $this->load->view('body/header',$data);
		$this->load->view('uas',$data);
		// $this->load->view('body/footer');
	}
    function submit()
    {
        $nama = $this->input->post('nama');
        $npm = $this->input->post('npm');
        $gender = $this->input->post('gender');
        $alamat = $this->input->post('alamat');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $data = [
            "nama" => $nama,
            "npm" => $npm,
            "gender" => $gender,
            "alamat" => $alamat,
            "tgl_lahir" => $tgl_lahir
        ];
        if ($this->uri->segment(3) == 'update') {
            $this->db->where('id',$this->uri->segment(4));
            $this->db->update('mahasiswa',$data);
            redirect('uas');
        }else{
            $this->db->insert('mahasiswa',$data);
            redirect('uas');
        }
    }
    function delete()
    {
        $this->db->where('id',$this->uri->segment(3));
        $this->db->delete('mahasiswa');
        redirect('uas');
    }
    function get()
    {
        $data = $this->db->get_where('mahasiswa',['id' => $this->input->post('id')])->row_array();
        echo json_encode($data);
    }
}
