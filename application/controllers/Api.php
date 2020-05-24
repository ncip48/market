<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Api extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data kontak
    function produk_get() {
        $page = $_GET['page'];
        $search = $_GET['search'];
        $id_kategori = $_GET['id_kategori'];
        $data = [];
        if($search != ''){
            //if($this->db->query("SELECT id_produk,nama_produk,gambar,harga_konsumen,diskon FROM rb_produk WHERE nama_produk LIKE '%$search%' ORDER BY id_produk DESC LIMIT $page")->num_rows=='1'){
                $query = $this->db->query("SELECT * FROM rb_produk WHERE nama_produk LIKE '%$search%' ORDER BY id_produk DESC LIMIT $page");
                if($query->num_rows() > 0){
                    $produk = $query->result();
                    $data = array(
                        'result' => '1',
                        'data' => $produk
                    );
                }else{
                    $produk = 'Maaf Produk Tidak Ditemukan';
                    $data = array(
                        'result' => '0',
                        'message' => $produk
                    );
                }
                //$prod = $produk->result();
            //}else{
                //$hasil = '0';
            //}
        }else{
        //$record = $this->model_app->view_ordering_limit('rb_produk','id_produk','DESC','0',$page)->result_array();
            if($id_kategori != ''){
                $query = $this->db->query("SELECT * FROM rb_produk WHERE id_kategori_produk = $id_kategori ORDER BY id_produk DESC LIMIT 6");
                if($query->num_rows() > 0 ){
                    $produk = $query;
                    $data = array(
                        'result' => '1',
                        'data' => $produk->result()
                    );
                }else{
                    $produk = 'Maaf Produk Tidak Ditemukan di Kategori ini';
                    $data = array(
                        'result' => '0',
                        'message' => $produk
                    );
                }
            }else{
                $produk = $this->db->query("SELECT * FROM rb_produk ORDER BY id_produk DESC LIMIT $page");
                $data = array(
                    'result' => '1',
                    'data' => $produk->result()
                );
            }
            //$prod = $produk->result();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    function kategori_get(){
        $id = $_GET['id'];
		$page = $_GET['page'];
		if($id == ''){
            $produk = $this->model_app->view_ordering('rb_kategori_produk', 'nama_kategori', 'ASC');
            $data = array(
                'result' => '1',
                'data' => $produk
            );
			$this->response($data, REST_Controller::HTTP_OK);
		}else{
			$produk = $this->db->query("SELECT * FROM rb_produk a JOIN rb_kategori_produk b ON a.id_kategori_produk=b.id_kategori_produk WHERE b.id_kategori_produk = $id ORDER BY a.id_produk DESC LIMIT $page");
            //$print = $this->model_app->view_where_ordering('rb_kategori_produk', array('id_kategori_produk'=>$id), 'nama_kategori', 'ASC');
            if($produk->num_rows() > 0){
                $data = array(
                    'result' => '1',
                    'data' => $produk->result()
                );
                $this->response($data, REST_Controller::HTTP_OK);
            }else{
                $produk = 'Maaf Produk Tidak Ditemukan di Kategori ini';
                $data = array(
                    'result' => '0',
                    'message' => $produk
                );
                $this->response($data, REST_Controller::HTTP_OK);
            }
		}
    }

    function login_post(){
        $json = file_get_contents('php://input');
        $obj = json_decode($json, TRUE);
        $username = strip_tags($obj['username']);
        $email = strip_tags($obj['email']);
        $password = hash("sha512", md5(strip_tags($obj['password'])));
        //$username = strip_tags($this->input->post('username'));
		//$password = hash("sha512", md5(strip_tags($this->input->post('password'))));
        $query = $this->db->query("SELECT * FROM rb_konsumen where username='".$username."' AND password='".$password."'");
        $check = $query->num_rows();

        if($check > 0){
            $data = [];
            $data = array(
                'result' => 1,
                'message' => "Login Berhasil",
                'data' => $query->result()
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [];
            $data = array(
                'result' => 0,
                'message' => 'Username/Password Salah'
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }
        
    }

    function cekuser_post(){
        $json = file_get_contents('php://input');
        $obj = json_decode($json, TRUE);
        $id_konsumen = strip_tags($obj['id_konsumen']);
        //$username = strip_tags($this->input->post('username'));
		//$password = hash("sha512", md5(strip_tags($this->input->post('password'))));
        $query = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$id_konsumen."'");
        $check = $query->num_rows();

        if($check > 0){
            $data = [];
            $data = array(
                'result' => 1,
                'message' => "Login Berhasil",
                'data' => $query->result()
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }else{
            $data = [];
            $data = array(
                'result' => 0,
                'message' => 'Username/Password Salah'
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }
    }

    function cart_get(){
        $id_konsumen = $_GET['id_konsumen'];
        $query = $this->db->query("SELECT a.*, b.nama_produk,b.gambar,b.diskon FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk WHERE a.id_konsumen=$id_konsumen");
        //$query = $this->model_app->view_where('rb_penjualan_temp',array('id_konsumen'=>$id_konsumen));
        $cek = $query->num_rows();
        $result = $query->result();
        $this->response($result, REST_Controller::HTTP_OK);
    }


    

    //Masukan function selanjutnya disini
}
?>