<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {

	public function index(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view('admin/head',["menu"=>24]);
		$this->load->view('admin/gudang');
		$this->load->view('admin/foot');
	}
	
	// KURIR
	public function setkurir($id=null){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view('admin/gudangkurir',["id"=>$id]);
	}
	function aktifkankurir($id=null){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["push"])){
			$toko = $this->func->getGudang($id,"kurir");
			$kurir = explode("|",$toko);
			$kurir[] = $this->security->xss_clean($_POST["push"]);
			$push = implode("|",$kurir);
			
			$this->db->where("id",$id);
			$this->db->update("gudang",array("kurir"=>$push,"tgl"=>date("Y-m-d H:i:s")));

			echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate kurir","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	function nonaktifkankurir($id=null){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["push"])){
			$toko = $this->func->getGudang($id,"kurir");
			$kurir = explode("|",$toko);
			for($i=0; $i<count($kurir); $i++){
				if($kurir[$i] == $_POST["push"]){
					unset($kurir[$i]);
				}
			}
			array_values($kurir);
			$push = implode("|",$kurir);
				
			$this->db->where("id",$id);
			$this->db->update("gudang",array("kurir"=>$push,"tgl"=>date("Y-m-d H:i:s")));

			echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate kuri","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	
	/* MULTIGUDANG */
	public function data(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view("admin/gudanglist","",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}elseif(isset($_POST["formid"])){
			//$_POST["formid"] = intval(["formid"]);
			$this->db->where("id",intval($_POST["formid"]));
			$db = $this->db->get("gudang");
			$data = [];
			foreach($db->result() as $r){
				$data = [
					"id"	=> $_POST["formid"],
					"nama"	=> $r->nama,
					"idkab"	=> $r->idkab,
					"alamat"	=> $r->alamat,
					"kontak"	=> $r->kontak,
					"kontak_nohp"	=> $r->kontak_nohp,
					"keterangan"	=> $r->keterangan,
					"token"	=> $this->security->get_csrf_hash()
				];
			}
			echo json_encode($data);
		}else{
			redirect("ngadimin");
		}
	}
	public function tambah(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$_POST["tgl"] = date("Y-m-d H:i:s");
			
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("gudang",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("gudang",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapus(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("gudang",$_POST["id"]);
			$pro = $this->db->get("produk");

			if($pro->num_rows() == 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->delete("gudang");
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"msg"=>"masih ada produk di gudang ini, silahkan produk dihapus terlebih dahulu atau ganti data gudang pada produk tersebut","token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false,"msg"=>"","token"=> $this->security->get_csrf_hash()]);
		}
	}

}