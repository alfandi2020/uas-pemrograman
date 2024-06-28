<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('M_Auth');
    }
    public function indonesian_date($timestamp = '', $date_format = 'd F Y', $suffix = '')
    {
        date_default_timezone_set("Asia/Jakarta");
        if ($timestamp == null) {
            return '-';
        }

        if ($timestamp == '1970-01-01' || $timestamp == '0000-00-00' || $timestamp == '-25200') {
            return '-';
        }


        if (trim($timestamp) == '') {
            $timestamp = time();
        } elseif (!ctype_digit($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace("/S/", "", $date_format);
        $pattern = array(
            '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
            '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
            '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
            '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
            '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
            '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
            '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
            '/November/', '/December/',
        );
        $replace = array(
            'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
            'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember',
        );
        $date = date($date_format, $timestamp);
        $date = preg_replace($pattern, $replace, $date);
        $date = "{$date} {$suffix}";
        return $date;
    }
        function index(){
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $data = $this->M_Auth->login($username);
            if ($password == true) {
                if ($username == $data['username']) {
                        if (password_verify($password, $data['password'])) {
                            $tanggal = time();
                            $bulanxx = $this->indonesian_date($tanggal, 'F');
                            $this->session->set_userdata('filterTahun',date('Y'));
                            $this->session->set_userdata('filterBulan',str_replace(' ', '', $bulanxx));
                            $datax = [
                                'id_user' => $data['id'],
                                'nama' => $data['nama'],
                                'username' => $data['username'],
                                'role' => $data['role'],
                                'kode_group' => $data['group']
                            ];
                            $this->session->set_userdata($datax);
                            $this->session->set_flashdata("msg", "<div class='alert alert-success'>Login Berhasil</div>");
                            redirect('dashboard');
                        } else {
                            $this->session->set_flashdata("msg", "<div class='alert alert-danger'>Password salah !</div>");
                            redirect('auth');
                        }
                } else {
                    $this->session->set_flashdata("msg", "<div class='alert alert-danger'>User tidak ada</div>");
                    redirect('auth');
                }
            }
            $this->load->view('Sign_in');
        }
        function logout(){
            $array_items = array('id_user', 'username');
            $this->session->unset_userdata($array_items);
            redirect('auth');
        }
    }
    

?>