<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Afiliasi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
                $this->load->view('headv2',array("titel"=>"Akun Saya"));
                $this->load->view('admin/afiliasi');
                $this->load->view('footv2');
			}else{
				$this->load->view("headv2");
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	
	public function load(){
        $column = array(0=>"no",1=>"orderid",2=>"tgl",3=>"usrid",4=>"jumlah",5=>"status");
        $page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
        $cari = (isset($_POST["search"]["value"]) AND $_POST["search"]["value"] != "") ? $_POST["search"]["value"] : "";
        $orderby = (isset($_POST["order"][0]["column"]) AND $_POST["order"][0]["column"] != "") ? $column[$_POST["order"][0]["column"]] : "status";
        $dir = (isset($_POST["order"][0]["dir"]) AND $_POST["order"][0]["dir"] != "") ? $_POST["order"][0]["dir"] : "ASC";
        
        $this->db->select("id");
        $this->db->where("usrid",$_SESSION["usrid"]);
        $rows = $this->db->get("afiliasi");
        $rows = $rows->num_rows();
        
        $this->db->where("usrid",$_SESSION["usrid"]);
        $this->db->order_by($orderby,$dir);
        $this->db->limit($_POST['length'], $_POST['start']);
        $db = $this->db->get("afiliasi");
            
        $data = array();
        $no = $_POST["start"];
        foreach($db->result() as $r){
            $trx = $this->func->getTransaksi($r->idtransaksi,"semua");
            $status = ($r->status == 1) ? "<b class='text-warning'>Menunggu Pencairan</b><br/>".$this->func->ubahTgl("d M Y",$r->cair) : "<span class='text-danger'>Belum Bayar</span>";
            $status = ($r->status == 2) ? "<span class='text-success'>Pencairan Selesai</span><br/>".$this->func->ubahTgl("d M Y",$r->cair) : $status;
            $status = ($r->status == 3) ? "<span class='text-danger'>Dibatalkan</span>" : $status;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = "#".$trx->orderid;
            $row[] = $this->func->ubahTgl("d M Y",$trx->tgl);
            $row[] = $this->func->getProfil($trx->usrid,"nama","usrid");
            $row[] = "Rp. ".$this->func->formUang($r->jumlah);
            $row[] = $status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $rows,
            "recordsFiltered" => $rows,
            "data" => $data,
            $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
        );
        //output dalam format JSON
        echo json_encode($output);
	}
}