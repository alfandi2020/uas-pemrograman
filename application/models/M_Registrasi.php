<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Registrasi extends CI_Model {
    function get_paket($id){
        return $this->db->query("SELECT * FROM mt_paket where media='$id'")->result();
    }
    function list_client($postData){
        $response = array();
        
        //value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = 'a.aktif';
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        //search
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (a.nama like '%".$searchValue."%' or a.alamat like '%".$searchValue."%' or a.telp like'%".$searchValue."%' or a.kode_pelanggan like'%".$searchValue."%' ) ";
        }
        $id_user = $this->session->userdata('id_user');
        $alamat_get = $this->db->query("SELECT * FROM users where id='$id_user'")->row_array();
        $arr = explode(',',$alamat_get['group']);
        $this->db->select('count(*) as allcount');
        $this->db->from('dt_registrasi as a');
        $this->db->join('mt_paket as b', 'a.speed = b.id_paket','left');
        $this->db->order_by('a.aktif', 'desc');
        // $this->db->where('a.status','Aktif');
        if ($this->session->userdata('sort_status')) {
            $this->db->where('a.status',$this->session->userdata('sort_status'));
        }
        if ($this->session->userdata('sort_group')) {
            $this->db->where('a.group',$this->session->userdata('sort_group'));
        }
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',$arr);
        }
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        $this->db->select('count(*) as allcount');
        $this->db->from('dt_registrasi as a');
        $this->db->join('mt_paket as b', 'a.speed = b.id_paket','left');
        $this->db->order_by('a.aktif', 'desc');
        // $this->db->where('a.status','Aktif');
        if ($this->session->userdata('sort_status')) {
            $this->db->where('a.status',$this->session->userdata('sort_status'));
        }
        if ($this->session->userdata('sort_group')) {
            $this->db->where('a.group',$this->session->userdata('sort_group'));
        }
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',$arr);
        }
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        // if($searchQuery != '')
        $this->db->select('*');
        $this->db->from('dt_registrasi as a');
        $this->db->join('mt_paket as b', 'a.speed = b.id_paket','left');
        $this->db->order_by('a.aktif', 'desc');
        if ($this->session->userdata('sort_status')) {
            $this->db->where('a.status',$this->session->userdata('sort_status'));
        }
        if ($this->session->userdata('sort_group')) {
            $this->db->where('a.group',$this->session->userdata('sort_group'));
        }
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',$arr);
        }
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        //  $records = $this->db->quer  y("SELECT a.id_cetak,a.nama,b.paket,a.tagihan,a.penerima,a.periode,a.tanggal,a.nomor_struk FROM dt_registrasi as a left join mt_paket as b on(a.internet = b.id_wireless) where '$searchQuery' order by '$columnName' asc limit $rowperpage")->result();
        $records = $this->db->get()->result();
        $data = array();
        // $no =1;
        $no = $_POST['start']+1;
        foreach($records as $record ){
            if ($record->status == "Aktif") {
                $status = '<span class="badge badge-glow badge-success">'.$record->status.'</span>';
            }else if($record->status == "Free"){
                $status = '<span class="badge badge-glow badge-primary">'.$record->status.'</span>';
            }else if($record->status == "Off"){
                $status = '<span class="badge badge-glow badge-danger">'.$record->status.'</span>';
            }else{
                $status = '';
            }
            if ($this->session->userdata('role') == 'Koordinator' || $this->session->userdata('role') == 'Sub Koordinator') {
                $disabled = 'disabled';
            }else{
                $disabled = '';
            }
            if ($this->session->userdata('role') == 'Admin') {
                $disabled_admin = 'disabled';
            }else{
                $disabled_admin ='';
            }
            if ($record->status == 'Aktif') {
                $change = '<button type="button" id="' .$record->id . '" class="btn btn-icon btn-icon rounded-circle btn-success mr-1 mb-1 waves-effect waves-light '.$disabled.'" data-toggle="modal" data-target="#modalStatus'.$record->id.'"><i class="feather icon-refresh-ccw"></i></button>
                    <div class="modal fade" id="modalStatus'.$record->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            <form action="change_status" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">NonAktifkan Pelanggan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h6>Data Pelanggan</h6>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <label>Nama</label>
                                            <input class="form-control" disabled value="'.$record->nama.'">
                                        </div>
                                        <div class="col-xl-6">
                                            <label>Group</label>
                                            <input class="form-control" disabled value="'.$record->kode_pelanggan.'">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-xl-6">
                                            <label>Telp</label>
                                            <input class="form-control" disabled value="'.$record->telp.'">
                                        </div>
                                        <div class="col-xl-6">
                                            <label>Alamat</label>
                                            <input class="form-control" disabled value="'.$record->alamat.'">
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-center mt-2">
                                        <div class="col-xl-6">
                                            <label>Tanggal Non Aktif</label>
                                           <input type="hidden" name="id" value="'.$record->id.'" required class="form-control">
                                           <input type="date" name="tgl_nonaktif" required class="form-control">
                                        </div>
                                        <div class="col-xl-6">
                                            <label>Status Pelanggan</label>
                                            <div class="vs-radio-con">
                                                <input type="radio" name="status" checked value="2">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="">Off</span>
                                            </div>
                                            <div class="vs-radio-con">
                                                <input type="radio" name="status" value="1">
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                <span class="">Cuti</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-center mt-2">
                                        <div class="col-xl-12">
                                            <label>Keterangan</label>
                                           <input type="text" class="form-control" name="note" required placeholder="pelanggan cuti / off">
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-center mt-2">
                                        <div class="col-xl-12">
                                            <h6 class="alert alert-danger text-center">
                                            <i class="feather icon-alert-triangle align-middle"></i> Apakah anda yakin ingin Nonaktifkan Pelanggan ?
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Ya</button>
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                ';
            }else{
                $change = '<a href="#" id="' .$record->id . '" class="btn btn-icon btn-icon rounded-circle btn-danger mr-1 mb-1 waves-effect waves-light '.$disabled.' change_status_aktif"><i class="feather icon-refresh-ccw"></i></a>';
            }
            $action = '<button type="button" class="btn btn-icon btn-icon rounded-circle btn-warning mr-1 mb-1 waves-effect waves-light '.$disabled.'" data-toggle="modal" data-target="#modalClient'.$record->id.'"><i class="feather icon-eye"></i></button> 
            <div class="modal fade" id="modalClient'.$record->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">View Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Paket Internet</h6>
                        <div class="row">
                            <div class="col-xl-6">
                                <label>Media</label>
                                <input class="form-control" disabled value="'.$record->media.'">
                            </div>
                            <div class="col-xl-6">
                                <label>Paket Internet</label>
                                <input class="form-control" disabled value="'.$record->mbps.' Mbps - Rp.'.number_format($record->harga,0,'.','.').' - '.$record->paket_internet.'">
                            </div>
                        </div>
                        <hr>
                        <h6>Inventory</h6>
                        <div class="row">
                            <div class="col-xl-6">
                                <label>Router</label>
                                <input class="form-control" disabled value="'.$record->router.'">
                            </div>
                            <div class="col-xl-6">
                                <label>CPE</label>
                                <input class="form-control" disabled value="'.$record->cpe.'">
                            </div>
                        </div>
                        <hr>
                        <h6>Data</h6>
                        <div class="row mt-2">
                            <div class="col-xl-4">
                                <label>Nama</label>
                                <input class="form-control" disabled value="'.$record->nama.'">
                            </div>
                            <div class="col-xl-4">
                                <label>Nomor KTP</label>
                                <input class="form-control" disabled value="'.$record->ktp.'">
                            </div>
                            <div class="col-xl-4">
                                <label>Nomor NPWP</label>
                                <input class="form-control" disabled value="'.$record->npwp.'">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-4">
                                <label>Group</label>
                                <input class="form-control" disabled value="'.$record->group.'">
                            </div>
                            <div class="col-xl-4">
                                <label>Alamat</label>
                                <input class="form-control" disabled value="'.$record->alamat.'">
                            </div>
                            <div class="col-xl-4">
                                <label>Kode Pelanggan</label>
                                <input class="form-control" disabled value="'.$record->kode_pelanggan.'">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-4">
                                <label>Teknisi</label>
                                <input class="form-control" disabled value="'.$record->teknisi.'">
                            </div>
                            <div class="col-xl-4">
                                <label>Kontak Handphone</label>
                                <input class="form-control" disabled value="'.$record->telp.'">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
            <a class="btn btn-icon btn-icon rounded-circle btn-primary mr-1 mb-1 waves-effect waves-light '.$disabled.'" href="update/' . $record->id . '" class="url"><i class="feather icon-edit"></i></a>
            '.$change.'
            <a href="#" id="'.$record->id.'" class="btn btn-icon btn-icon rounded-circle btn-danger mr-1 mb-1 waves-effect waves-light '.$disabled.' '.$disabled_admin.' del_client"><i class="feather icon-trash-2"></i></a>';

            $data[] = array(
            "no"=>$no++,
            "id"=> $record->id,
            "nama"=>$record->nama,
            "kode_pelanggan"=>$record->kode_pelanggan,
            "action" => $action,
            "status"=>$status,
            "email"=>$record->email,
            "alamat"=> $record->alamat,
            "telp"=>$record->telp,
            "group"=>$record->group,
            );
        }

        //response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
    function status_payment($postData){
        $response = array();
        
        //value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = 'a.nama';
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        //search
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (a.nama like '%".$searchValue."%' or a.alamat like '%".$searchValue."%' or a.kode_pelanggan like'%".$searchValue."%' ) ";
        }

        $this->db->select('count(*) as allcount');
        $this->db->from('dt_registrasi as a');
        $this->db->join('mt_paket as b', 'a.speed = b.id_paket','left');
        $this->db->where('a.status','Aktif');
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        // $this->db->like('a.nama',$searchValue);
        // $this->db->or_like('a.alamat',$searchValue);
        // $this->db->or_like('a.kode_pelanggan',$searchValue);
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',explode(',',$this->session->userdata('kode_group')));
        }
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        $this->db->select('count(*) as allcount');
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        // $this->db->like('a.nama',$searchValue);
        // $this->db->or_like('a.alamat',$searchValue);
        // $this->db->or_like('a.kode_pelanggan',$searchValue);
            // $this->db->like('nama',$searchValue);
        $this->db->from('dt_registrasi as a');
        $this->db->join('mt_paket as b', 'a.speed = b.id_paket','left');
        $this->db->where('a.status','Aktif');
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',explode(',',$this->session->userdata('kode_group')));
        }
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('*');
        $this->db->from('dt_registrasi as a');
        $this->db->join('mt_paket as b', 'a.speed = b.id_paket','left');
        $this->db->where('a.status','Aktif');
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',explode(',',$this->session->userdata('kode_group')));
        }
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        // $this->db->like('a.nama',$searchValue);
        // $this->db->or_like('a.alamat',$searchValue);
        // $this->db->or_like('a.kode_pelanggan',$searchValue);
        //  $this->db->order_by('tanggal', 'desc');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        //  $records = $this->db->query("SELECT a.id_cetak,a.nama,b.paket,a.tagihan,a.penerima,a.periode,a.tanggal,a.nomor_struk FROM dt_registrasi as a left join mt_paket as b on(a.internet = b.id_wireless) where '$searchQuery' order by '$columnName' asc limit $rowperpage")->result();
        $records = $this->db->get()->result();
        $data = array();
        // $no =1;
        $no = $_POST['start']+1;
        $bulann= str_replace(' ', '', $this->session->userdata('filterBulan'));
        $tahun= str_replace(' ', '', $this->session->userdata('filterTahun'));
        foreach($records as $record ){
            $cek = $this->db->query("SELECT * FROM dt_cetak where id_registrasi='".$record->kode_pelanggan."' and periode='".$bulann."' and tahun='".$tahun."'")->num_rows();
            if ($cek == true) {
                $status = '<span class="badge badge-glow badge-success">Sudah Bayar</span>';
                $tagihan = '';
            }else{
                $status = '<span class="badge badge-glow badge-danger">Belum Bayar</span>';
                $tagihan =  '<a href="#" id="'.$record->id.'" class="notif-confirm btn btn-icon btn-icon rounded-circle btn-success waves-effect waves-light"><i class="feather icon-send"></i></a> &nbsp;
                <a href="#" id="'.$record->id.'" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-light notif-confirm2"><i class="feather icon-file-text"></i></a> &nbsp;
                <a href="#" id="'.$record->id.'" class="btn btn-icon btn-icon rounded-circle btn-danger waves-effect waves-light notif-confirm3"><i class="feather icon-stop-circle"></i></a>';
            }

            $data[] = array(
            "no"=>$no++,
            "id"=> $record->id,
            "nama"=>$record->nama,
            "alamat"=> $record->alamat,
            "group"=>$record->group,
            "tagihan"=>$tagihan,
            "status"=>$status,
            );
        }

        //response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
    function cetak_struk2($postData){
        $response = array();
        
        //value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = 'a.tanggal_pembayaran';
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        //search
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (a.nama like '%".$searchValue."%' or a.tagihan like '%".$searchValue."%' ) ";
        }

        $bulann = $this->session->userdata('filterBulan');
        $thn = $this->session->userdata('filterTahun');
        $this->db->select('count(*) as allcount');
        $this->db->from('dt_cetak as a');
        $this->db->join('mt_paket as b', 'a.mbps = b.id_paket','left');
        $this->db->where('a.periode', $bulann);
        $this->db->where('a.tahun', $thn);

        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        // $this->db->like('a.nama',$searchValue);
        // $this->db->or_like('a.alamat',$searchValue);
        // $this->db->or_like('a.kode_pelanggan',$searchValue);
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',explode(',',$this->session->userdata('kode_group')));
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        $this->db->select('count(*) as allcount');
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        // $this->db->like('a.nama',$searchValue);
        // $this->db->or_like('a.alamat',$searchValue);
        // $this->db->or_like('a.kode_pelanggan',$searchValue);
            // $this->db->like('nama',$searchValue);
        $this->db->from('dt_cetak as a');
        $this->db->join('mt_paket as b', 'a.mbps = b.id_paket','left');
        $this->db->where('a.periode', $bulann);
        $this->db->where('a.tahun', $thn);
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',explode(',',$this->session->userdata('kode_group')));
        }
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('*');
        $this->db->from('dt_cetak as a');
        $this->db->join('mt_paket as b', 'a.mbps = b.id_paket','left');
        $this->db->where('a.periode', $bulann);
        $this->db->where('a.tahun', $thn);
        if ($this->session->userdata('role') != 'Super Admin' && $this->session->userdata('role') != 'Admin') {
            $this->db->where_in('a.group',explode(',',$this->session->userdata('kode_group')));
        }
        if ($searchValue) {
            $this->db->where($searchQuery);
        }
        // $this->db->like('a.nama',$searchValue);
        // $this->db->or_like('a.alamat',$searchValue);
        // $this->db->or_like('a.kode_pelanggan',$searchValue);
        //  $this->db->order_by('a.tanggal_pembayaran', 'desc');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        //  $records = $this->db->query("SELECT a.id_cetak,a.nama,b.paket,a.tagihan,a.penerima,a.periode,a.tanggal,a.nomor_struk FROM dt_registrasi as a left join mt_paket as b on(a.internet = b.id_wireless) where '$searchQuery' order by '$columnName' asc limit $rowperpage")->result();
        $records = $this->db->get()->result();
        $data = array();
        // $no =1;
        $no = $_POST['start']+1;
        $bulann= $this->session->userdata('filterBulan');
        foreach($records as $record ){
            // $cek = $this->db->query("SELECT * FROM dt_cetak where id_registrasi='$record->kode_pelanggan' and periode='$bulann'")->num_rows();
            // if ($cek == true) {
            //     $status = '<span class="badge badge-glow badge-success">Sudah Bayar</span>';
            //     $tagihan = '';
            // }else{
            //     $status = '<span class="badge badge-glow badge-danger">Belum Bayar</span>';
                $tagihan =  '<a href="#" id="'.$record->id_cetak.'" class="delete-confirm-struk btn btn-icon btn-icon rounded-circle btn-danger waves-effect waves-light"><i class="feather icon-trash"></i></a>';
                // $tagihan =  '<a href="#" id="'.$record->id_cetak.'" class="delete-confirm-struk btn btn-icon btn-icon rounded-circle btn-danger waves-effect waves-light"><i class="feather icon-trash"></i></a> &nbsp;
                // <a href="cetak_struk_pdf" id="'.$record->id_cetak.'" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-light notif-confirm2"><i class="feather icon-file-text"></i></a>';
            // }

            $data[] = array(
            "no"=>$no++,
            "id"=> $record->id_cetak,
            "nama"=>$record->nama,
            "paket"=> $record->mbps. ' Mbps',
            "nominal"=> "Rp.".number_format($record->tagihan,0,'.','.'),
            "penerima"=> $record->penerima,
            "periode"=> $record->periode,
            "tahun"=> $record->tahun,
            "tanggal_bayar"=> $record->tanggal_pembayaran,
            "action"=> $tagihan,
            );
        }

        //response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
    

}