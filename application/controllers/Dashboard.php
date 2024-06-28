<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	// private $param;

	public function __construct() {
        parent::__construct();
		if ($this->session->userdata('id_user') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
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
	public function index()
	{
		$tanggal = time();
        $bulan = $this->indonesian_date($tanggal, 'F');
		$tahun = $this->session->userdata('filterTahun');
		$group_sess = explode(',',$this->session->userdata('kode_group'));
		$condition_group = $this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin';
		//total aktif
		$this->db->select('COUNT(id) as pelanggan');
		$this->db->where('status','Aktif');
		if ($condition_group) {
			$this->db->where_in('group',$group_sess);
		}
		$total_client = $this->db->get('dt_registrasi')->row_array();
		//total pelanggan free
		$this->db->select('COUNT(id) as pelanggan');
		$this->db->where('status','Free');
		if ($condition_group) {
			$this->db->where_in('group',$group_sess);
		}
		$free = $this->db->get('dt_registrasi')->row_array();
		//total pelanggan off
		$this->db->select('COUNT(id) as pelanggan');
		$this->db->where('status','Off');
		if ($condition_group) {
			$this->db->where_in('group',$group_sess);
		}
		$off = $this->db->get('dt_registrasi')->row_array();
		//total pelanggan total
		$this->db->select('COUNT(id) as pelanggan');
		// $this->db->where('status','Off');
		if ($condition_group) {
			$this->db->where_in('group',$group_sess);
		}
		$total = $this->db->get('dt_registrasi')->row_array();
		// $total = $this->db->query("SELECT COUNT(id) as pelanggan from dt_registrasi")->row_array();
		// $this->db->select('*');
		
		if ($condition_group) {
			$this->db->where_in('a.group',$group_sess);
		}
		$this->db->where('b.periode',$bulan);
		$this->db->where('b.tahun',$tahun);
		$this->db->join('dt_registrasi as a','a.kode_pelanggan = b.id_registrasi');
		$payment = $this->db->get('dt_cetak as b')->result();

		$arraydata = implode(',',$group_sess);

		if ($condition_group) {
			$this->db->where_in('a.group',$group_sess);
		}
		$this->db->from('dt_registrasi as a');
		$this->db->join('mt_paket as b','a.speed=b.id_paket');
		$belum_bayar = $this->db->get()->result();
		// $belum_bayar = $this->db->query("SELECT * FROM dt_registrasi as a left join mt_paket as b on(a.speed=b.id_paket)")->result();
		$data = [
			"title" => "Dashboard",
			'total' => $total_client,
			'free' => $free,
			'tidak_aktif' => $off,
			'semua' => $total,
			'payment' => $payment,
			'belum_bayar' => $belum_bayar
		];
		$this->load->view('temp/header',$data);
		$this->load->view('body/dashboard');
		$this->load->view('temp/footer');
	}
    public function jam()
    {
        date_default_timezone_set('Asia/Jakarta'); //Menyesuaikan waktu dengan tempat kita tinggal
        echo date('H:i:s'); //Menampilkan Jam Sekarang
    }
	public function menu()
	{
		$this->load->view('temp/sidebar');
	}
	function profile(){
		$this->load->view('body/profile');
	}
	public function filter(){
        if($this->uri->segment(3)){
            $filter = $this->uri->segment(3);
            $this->session->set_userdata('menu-footer', $filter);
            redirect('home/'.$this->uri->segment(3));
        }
    }
	
}
