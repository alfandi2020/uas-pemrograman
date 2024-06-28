<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {
	// private $param;

	public function __construct() {
        parent::__construct();
		if ($this->session->userdata('id_user') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
		$this->load->model(array('M_Registrasi'));

	}
	public function index()
	{
    
        $harga = $this->remove_special($this->input->post('harga'));
        $media = $this->input->post('media');
        $kecepatan = $this->input->post('kecepatan');
        $paket = $this->input->post('paket');
        // $deskripsi = $this->input->post('deskripsi');
        if (count($this->input->post()) > 3) {
            $insert = [
                // "deskripsi" => $deskripsi,
                "mbps" => $kecepatan,
                "harga" => $harga,
                "media" => $media,
                "paket_internet" => $paket
            ];
            $this->db->insert('mt_paket',$insert);
            redirect('paket','<div class="alert alert-primary mb-2" role="alert">Tambah Paket berhasil</div>');
        }
		$data = [
			'title' => 'Paket Internet',
            'paket' => $this->db->get('mt_paket')->result()
		];
		$this->load->view('temp/header',$data);
		$this->load->view('body/pelanggan/paket');
		$this->load->view('temp/footer');
	}
	function delete($id){
		$this->db->delete('mt_paket', array('id_paket' => $id)); 
		redirect('paket');
	}
	public function filter(){
        if($this->uri->segment(3)){
            $filter = $this->uri->segment(3);
            $this->session->set_userdata('menu-footer', $filter);
            redirect('home/'.$this->uri->segment(3));
        }
    }
    function remove_special($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }
}
