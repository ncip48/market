<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {
	function index(){
		$data['title'] = title();
		$data['iden'] = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
		$data['kategori'] = $this->model_app->view('rb_kategori_produk');
		$this->template->load('phpmu-one/template','phpmu-one/main',$data);
		//redirect(base_url());
	}
}
