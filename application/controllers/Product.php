<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    function index(){
        $this->load->view('body/header');
        $this->load->view('produk');
        $this->load->view('body/footer');
    }
}