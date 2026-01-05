<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importuser extends CI_Controller {

    public function index(){
        $this->load->view("headv2",array("titel"=>"Impor User Masal"));
        $this->load->view("import.php");
        $this->load->view("footv2");
    }

	// IMPORT PRODUK
	public function import(){
		if(isset($_POST)){
			$config['upload_path'] = './cdn/import/';
			$config['allowed_types'] = 'xls|xlsx|csv';
			$config['file_name'] = "import_".date("YmdHis");

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('file')){
				$error = $this->upload->display_errors();
				echo json_encode(array("success"=>false,"msg"=>$error,"token"=> $this->security->get_csrf_hash()));
			}else{
				$uploadData = $this->upload->data();
				$inputFileName = FCPATH."cdn/import/".$uploadData["file_name"];

				if(!file_exists($inputFileName)){
					echo json_encode(array("success"=>false,"msg"=>"file tidak ditemukan"));
					exit;
				}

				//$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//if(mime_content_type($inputFileName) !== null && in_array(mime_content_type($inputFileName), $file_mimes)) {
					$extension = pathinfo($inputFileName, PATHINFO_EXTENSION);
				
					if('csv' == $extension) {
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					} else {
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					}
				
					$spreadsheet = $reader->load($inputFileName);
					
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					if(count($sheetData) > 1){
						/*
						print_r($sheetData);
						*/
						for($i=1; $i<count($sheetData); $i++){
							if($sheetData[$i]['0'] != null){
								//$string = $this->clean($sheetData[$i]['1']);
								$sheetData[$i]['0'] = $sheetData[$i]['0'] != null ? $sheetData[$i]['0'] : "";
								$sheetData[$i]['1'] = $sheetData[$i]['1'] != null ? $sheetData[$i]['1'] : "";
								$data = array(
									"tgl"	=> date("Y-m-d H:i:s"),
									"tglbuat"	=> date("Y-m-d H:i:s"),
									"tglupdate"	=> date("Y-m-d H:i:s"),
									"username"	=> $sheetData[$i]['0'],
									"nohp"	    => $sheetData[$i]['1'],
                                    "password"  => $this->func->encode("masukaja"),
                                    "level"     => 1,
                                    "status"    => 1
								);
								$this->db->insert("userdata",$data);
								$id = $this->db->insert_id();

                                $this->db->insert("saldo",["usrid"=>$id,"saldo"=>0,"apdet"=>date("Y-m-d H:i:s")]);
                                $this->db->insert("profil",["usrid"=>$id,"nohp"=>$sheetData[$i]['1'],"foto"=>"user.png"]);

                                echo "Sukses impor... &nbsp; ".$sheetData[$i]['0']." -> ".$sheetData[$i]['1']."<br/>";
							}
						}
						//echo json_encode(array("success"=>true,"msg"=>"berhasil mengimpor produk","token"=> $this->security->get_csrf_hash()));
					}else{
						echo json_encode(array("success"=>false,"msg"=>"file tidak sesuai atau kosong"));
					}
				//}else{
				//	echo json_encode(array("success"=>false,"msg"=>"file tidak sesuai: ".mime_content_type($inputFileName." | ".$inputFileName)));
				//}
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}

}