<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$set = $this->func->getSetting("semua");
		$production = (strpos($set->midtrans_snap,"sandbox") == true) ? false : true;
		\Midtrans\Config::$serverKey = 'SB-Mid-server-oaJ2jLL2vKLvVO_lZgR8XVkS';
		\Midtrans\Config::$isProduction = false;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;

		/*if($this->func->maintenis() == TRUE) {
			include(APPPATH.'views/maintenis.php');

			die();
		}*/
	}

	function tesopo(){
		$lisensi = FCPATH."lisensi.json";
		if(file_exists($lisensi)){
			$lisensi = json_decode(file_get_contents($lisensi), true);
			if(isset($lisensi["key"])){
				//$result = json_decode(file_get_contents('https://member.jadiorder.com/lisensi/validate/'.$lisensi["key"]), true);
				$result = "{status:404}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, 'https://member.jadiorder.com/lisensi/validate/'.$lisensi["key"]);
				curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_REFERER, $_SERVER['SERVER_NAME']);
				$result = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($result,true);
				//print_r($result);
				
				if($result['status'] != 200) {
					$html = header('Refresh: 0; URL=https://member.jadiorder.com/lisensi/nonaktif');
					$search = '<%returnmessage%>';
					$replace = $result['message'];
					$html = str_replace($search, $replace, $html);
					die( $html );
				}
			}else{
				$html = header('Refresh: 0; URL=https://member.jadiorder.com/lisensi/nonaktif');
				$search = '<%returnmessage%>';
				$replace = $result['message'];
				$html = str_replace($search, $replace, $html);
				die( $html );
			}
		}else{
			$html = header('Refresh: 0; URL=https://member.jadiorder.com/lisensi/nonaktif');
			$search = '<%returnmessage%>';
			$replace = $result['message'];
			$html = str_replace($search, $replace, $html);
			die( $html );
		}

		// OBFUSCATED
		//$xrgqltou_3e3e2fb0=FCPATH.base64_decode('bGlzZW5zaS5qc29u');if(file_exists($xrgqltou_3e3e2fb0)){$xrgqltou_3e3e2fb0=json_decode(file_get_contents($xrgqltou_3e3e2fb0),true);if(isset($xrgqltou_3e3e2fb0[base64_decode('a2V5')])){$xjwuzsrn_136ac113=base64_decode('e3N0YXR1czo0MDR9');$vwkmutzk_4c60c3f1=curl_init();curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_HTTPHEADER,[base64_decode('Q29udGVudC1UeXBlOiBhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQ=')]);curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_CUSTOMREQUEST,base64_decode('R0VU'));curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_RETURNTRANSFER,true);curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_URL,base64_decode('aHR0cHM6Ly9tZW1iZXIuamFkaW9yZGVyLmNvbS9saXNlbnNpL3ZhbGlkYXRlLw==').$xrgqltou_3e3e2fb0[base64_decode('a2V5')]);curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_TIMEOUT_MS,10000);curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_SSL_VERIFYHOST,0);curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($vwkmutzk_4c60c3f1,CURLOPT_REFERER,$_SERVER[base64_decode('U0VSVkVSX05BTUU=')]);$xjwuzsrn_136ac113=curl_exec($vwkmutzk_4c60c3f1);curl_close($vwkmutzk_4c60c3f1);$xjwuzsrn_136ac113=json_decode($xjwuzsrn_136ac113,true);if($xjwuzsrn_136ac113[base64_decode('c3RhdHVz')]!=200){$bwpxkwfx_1879f8e5=header(base64_decode('UmVmcmVzaDogMDsgVVJMPWh0dHBzOi8vbWVtYmVyLmphZGlvcmRlci5jb20vbGlzZW5zaS9ub25ha3RpZg=='));$torehxwz_b4f0dba7=base64_decode('PCVyZXR1cm5tZXNzYWdlJT4=');$wgbyrrpf_c9d2445=$xjwuzsrn_136ac113[base64_decode('bWVzc2FnZQ==')];$bwpxkwfx_1879f8e5=str_replace($torehxwz_b4f0dba7,$wgbyrrpf_c9d2445,$bwpxkwfx_1879f8e5);die($bwpxkwfx_1879f8e5);}}else{$bwpxkwfx_1879f8e5=header(base64_decode('UmVmcmVzaDogMDsgVVJMPWh0dHBzOi8vbWVtYmVyLmphZGlvcmRlci5jb20vbGlzZW5zaS9ub25ha3RpZg=='));$torehxwz_b4f0dba7=base64_decode('PCVyZXR1cm5tZXNzYWdlJT4=');$wgbyrrpf_c9d2445=$xjwuzsrn_136ac113[base64_decode('bWVzc2FnZQ==')];$bwpxkwfx_1879f8e5=str_replace($torehxwz_b4f0dba7,$wgbyrrpf_c9d2445,$bwpxkwfx_1879f8e5);die($bwpxkwfx_1879f8e5);}}else{$bwpxkwfx_1879f8e5=header(base64_decode('UmVmcmVzaDogMDsgVVJMPWh0dHBzOi8vbWVtYmVyLmphZGlvcmRlci5jb20vbGlzZW5zaS9ub25ha3RpZg=='));$torehxwz_b4f0dba7=base64_decode('PCVyZXR1cm5tZXNzYWdlJT4=');$wgbyrrpf_c9d2445=$xjwuzsrn_136ac113[base64_decode('bWVzc2FnZQ==')];$bwpxkwfx_1879f8e5=str_replace($torehxwz_b4f0dba7,$wgbyrrpf_c9d2445,$bwpxkwfx_1879f8e5);die($bwpxkwfx_1879f8e5);}

	}


	public function index(){
		redirect();
	}

	// MIDTRANS
	function pay(){
		$bayar = $this->func->getBayar($_GET["order_id"],"semua","invoice");
		$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");
		$alamat = $this->func->getAlamat($trx->alamat,"semua");
		$usr = ($bayar->usrid > 0) ? $this->func->getUser($bayar->usrid,"semua") : $this->func->getUserTemp($bayar->usrid_temp,"semua");
		$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->func->formUang($bayar->diskon)."</b><br/>" : "";
		$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->func->formUang($bayar->diskon)."*\n" : "";
		$toko = $this->func->getSetting("semua");
		$status = json_decode($_POST["response"]);
		$update = date("Y-m-d H:i:s",strtotime("+1 day", strtotime(date("Y-m-d H:i:s"))));
		$pdfurl = "";
		$nohp = ($bayar->usrid > 0) ? $this->func->getProfil($usr->id,"nohp","usrid") : $usr->nohp;

		if($status->payment_type == "cstore"){
			$tipe = "Convenience Store";
			$store = (isset($status->store)) ? $status->store : "Indomaret/Alfamart/Alfamidi";
			$kode = $status->payment_code;
			$pdfurl = (isset($status->pdf_url)) ? $status->pdf_url : "";
			$cara = (isset($status->pdf_url)) ? "Petunjuk Pembayaran: ".$status->pdf_url : "";
		}elseif($status->payment_type == "bank_transfer"){
			$tipe = "Virtual Account";
			$pdfurl = $status->pdf_url;
			if(isset($status->va_numbers)){
				$store = $status->va_numbers[0]->bank;
				$kode = $status->va_numbers[0]->va_number;
				$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
			}elseif(isset($status->permata_va_number)){
				$store = "Bank Permata";
				$kode = $status->permata_va_number;
				$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
			}else{
				$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
				$kode = $status->payment_code;
				$store = "Bank";
			}
		}elseif($status->payment_type == "credit_card"){
			$tipe = "Kartu Kredit";
			$store = $status->bank;
			$kode = $status->masked_card;
			$cara = "";
		}elseif($status->payment_type == "echannel"){
			$tipe = "E-Channel";
			$store = "Bank";
			$kode = $status->biller_code." - ".$status->bill_key;
			$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
			$pdfurl = $status->pdf_url;
		}elseif($status->payment_type == "gopay"){
			$tipe = "E-Channel";
			$store = "Gopay";
			$kode = "";
			$cara = "";
		}else{
			$tipe = "";
			$store = "";
			$kode = "";
			$cara = "";
		}	
		
		if(isset($_GET["status"]) AND $_GET["status"] == "success"){
				
			$this->db->where("id",$bayar->id);
			$this->db->update("pembayaran",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s"),"midtrans_id"=>$_GET["transaction_id"],"midtrans_pdf"=>$pdfurl]);
			
			$stat = ($bayar->digital == 1) ? 3 : 1;
			$this->db->where("idbayar",$bayar->id);
			$this->db->update("transaksi",["status"=>$stat,"tglupdate"=>date("Y-m-d H:i:s")]);

			if($stat == 3){
				$this->db->where("idtransaksi",$trx->id);
				$db = $this->db->get("afiliasi");
				foreach($db->result() as $r){
					$saldo = $this->func->getSaldo($r->usrid,"semua");
					$saldototal = $saldo->saldo + $r->jumlah;
					$tgl = date("Y-m-d H:i:s");
					$data = [
						"usrid"	=> $r->usrid,
						"trxid"	=> "TOPUP_".$r->usrid.date("YmdHis"),
						"jenis"	=> 2,
						"status"=> 1,
						"selesai"	=> $tgl,
						"tgl"	=> $tgl,
						"total"	=> $r->jumlah,
						"metode"=> 1,
						"keterangan"=> "Pencairan komisi dari transaksi #".$trx->orderid
					];
					$this->db->insert("saldotarik",$data);
					$topup = $this->db->insert_id();
	
					$data2 = [
						"tgl"	=> $tgl,
						"usrid"	=> $r->usrid,
						"jenis"	=> 1,
						"jumlah"=> $r->jumlah,
						"darike"=> 1,
						"sambung"	=> $topup,
						"saldoawal"	=> $saldo->saldo,
						"saldoakhir"=> $saldototal
					];
					$this->db->insert("saldohistory",$data2);
	
					$this->db->where("id",$saldo->id);
					$this->db->update("saldo",["apdet"=>$tgl,"saldo"=>$saldototal]);
					
					$this->db->where("id",$r->id);
					$this->db->update("afiliasi",["status"=>2,"cair"=>date("Y-m-d H:i:s"),"saldotarik"=>$topup]);
				}
			}else{
				$this->db->where("idtransaksi",$trx->id);
				$this->db->update("afiliasi",["status"=>1]);
			}
			
			if($bayar->usrid > 0){
				$pesan = "
					Halo <b>".$usr->nama."</b><br/>".
					"Terimakasih, pembayaran untuk pesananmu sudah kami terima.<br/>".
					"Mohon ditunggu, admin kami akan segera memproses pesananmu<br/>".
					"<b>Detail Pesanan</b><br/>".
					"No Invoice: <b>#".$bayar->invoice."</b><br/>".
					"Total Pesanan: <b>Rp ".$this->func->formUang($bayar->total)."</b><br/>".
					"Ongkos Kirim: <b>Rp ".$this->func->formUang($trx->ongkir)."</b><br/>".$diskon.
					"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
					"Detail Pengiriman <br/>".
					"Penerima: <b>".$alamat->nama."</b> <br/>".
					"No HP: <b>".$alamat->nohp."</b> <br/>".
					"Alamat: <b>".$alamat->alamat."</b>".
					"<br/> <br/>".
					"Cek Status pesananmu langsung di menu:<br/>".
					"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
				";
				$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
			}
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Terimakasih, pembayaran untuk pesananmu sudah kami terima. \n".
				"Mohon ditunggu, admin kami akan segera memproses pesananmu \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskonwa.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."* \n".
				"No HP: *".$alamat->nohp."* \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Cek Status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->func->sendWA($nohp,$pesan);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/ipaymunotif");
				$this->load->view("footv2");
			}
		}elseif(isset($_GET["status"]) AND $_GET["status"] == "pending"){
			$this->db->where("id",$bayar->id);
			$this->db->update("pembayaran",["midtrans_id"=>$_GET["transaction_id"],"tglupdate"=>date("Y-m-d H:i:s"),"kadaluarsa"=>$update,"midtrans_pdf"=>$pdfurl]);

			if($bayar->usrid > 0){
				$pesan = "
					Halo <b>".$usr->nama."</b><br/>".
					"Terimakasih, sudah membeli produk kami.<br/>".
					"Segera lakukan pembayaran agar pesananmu segera diproses<br/> <br/>".
					"<b>Detail Pembayaran</b><br/>".
					"Metode Pembayaran: <b>".strtoupper($tipe)."</b><br/> <br/>".
					"Merchant: <b>".strtoupper($store)."</b><br/> <br/>".
					"Kode/Virtual Account: <b>".$kode."</b><br/> <br/>".
					$cara.
					"<br/>".
					"Harap lakukan pembayaran ke Nomor Rekening/Virtual Account dengan <b>NOMINAL YANG SESUAI</b>, batas maksimal waktu pembayaran: ".
					$this->func->ubahTgl("d M Y H:i",$update).
					"<br/> <br/>".
					"<b>Detail Pesanan</b><br/>".
					"No Invoice: <b>#".$bayar->invoice."</b><br/>".
					"Total Pesanan: <b>Rp ".$this->func->formUang($bayar->total)."</b><br/>".
					"Ongkos Kirim: <b>Rp ".$this->func->formUang($trx->ongkir)."</b><br/>".$diskon.
					"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
					"Detail Pengiriman <br/>".
					"Penerima: <b>".$alamat->nama."</b> <br/>".
					"No HP: <b>".$alamat->nohp."</b> <br/>".
					"Alamat: <b>".$alamat->alamat."</b>".
					"<br/> <br/>".
					"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
					"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
				";
				$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
			}
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Terimakasih, sudah membeli produk kami. \n".
				"Segera lakukan pembayaran agar pesananmu segera diproses \n \n".
				"*Detail Pembayaran* \n".
				"Metode Pembayaran: *".strtoupper($tipe)."* \n".
				"Merchant: *".strtoupper($store)."* \n ".
				"Kode/Virtual Account: *".$kode."* \n ".
				$cara."\n".
				"Harap lakukan pembayaran ke Nomor Rekening/Virtual Account dengan *NOMINAL YANG SESUAI*, batas maksimal waktu pembayaran: ".
				$this->func->ubahTgl("d M Y H:i",$update).
				" \n \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->func->sendWA($nohp,$pesan);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/ipaymunotif");
				$this->load->view("footv2");
			}
			//print_r($status);
		}
	}
	function token(){
		if(isset($_POST["invoice"])){
			//echo $_POST["invoice"];
			$bayar = $this->func->getBayar($_POST["invoice"],"semua","invoice");
			$usrid = ($bayar->usrid > 0) ? $this->func->getUser($bayar->usrid,"semua") : $this->func->getUserTemp($bayar->usrid_temp,"semua");
			//$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$email = (isset($usrid->username) AND $usrid->username != "") ? $usrid->username : $this->func->globalset("email");
			$params = array(
				'transaction_details' => array(
					'order_id' => $_POST["invoice"],
					'gross_amount' => $bayar->transfer,
				),
				'customer_details' => array(
					'first_name' => $usrid->nama,
					'last_name' => "",
					'email' => $email,
					'phone' => $usrid->nohp,
				),
			);
			$token = \Midtrans\Snap::getSnapToken($params);
			echo json_encode(["midtranstoken"=>$token,"token"=>$this->security->get_csrf_hash()]);
		}else{
			show_error("Invoice tidak ditemukan",404);
		}
	}
	function tokentopup(){
		if(isset($_POST["id"])){
			$bayar = $this->func->getSaldoTarik($_POST["id"],"semua","invoice");
			$usrid = ($bayar->usrid > 0) ? $this->func->getUser($bayar->usrid,"semua") : $this->func->getUserTemp($bayar->usrid_temp,"semua");
			//$usrid = $this->func->getUser($bayar->usrid,"semua");
			//$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$email = (isset($usrid->username) AND $usrid->username != "") ? $usrid->username : $this->func->globalset("email");
			$params = array(
				'transaction_details' => array(
					'order_id' => $_POST["invoice"],
					'gross_amount' => $bayar->total,
				),
				'customer_details' => array(
					'first_name' => $usrid->nama,
					'last_name' => "",
					'email' => $email,
					'phone' => $usrid->nohp,
				),
			);
			$token = \Midtrans\Snap::getSnapToken($params);
			echo $token;
		}else{
			show_error("Invoice tidak ditemukan",404);
		}
	}
	function cek($orderId){
		$status = \Midtrans\Transaction::status($orderId);
		var_dump($status);
	}
	function mobile($id){
		if(isset($_GET["revoke"])){
			$byr = $this->func->getBayar($id,"semua");
			$this->db->where("id",$id);
			$this->db->update("pembayaran",["invoice"=>$byr->invoice.date("Hi"),"midtrans_id"=>""]);
		}
		$push["data"] = $this->func->getBayar($id,"semua");
		//print_r($push["data"]);

		$this->load->view("headv2");
		$this->load->view("main/midtransup",$push);
		$this->load->view("footv2");
	}
	function webhook(){
		$notif = new \Midtrans\Notification();

		$transaction = $notif->transaction_status;
		$transaction_id = $notif->transaction_id;
		$type = $notif->payment_type;
		$fraud = $notif->fraud_status;
		$order_id = $notif->order_id;
		$bayarid = $this->func->getBayar($notif->order_id,"id","invoice");

		if ($transaction == 'capture') {
			if ($type == 'credit_card'){
				if($fraud == 'challenge'){
					echo "Transaction order_id: " . $order_id ." is challenged by FDS";
				}else{
					$this->suksesTrxMidtrans($bayarid,$transaction_id);
					echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
				}
			}
		}
		else if ($transaction == 'settlement'){
			$this->suksesTrxMidtrans($bayarid,$transaction_id);
			echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
		}
		else if($transaction == 'pending'){
			echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
		}
		else if ($transaction == 'deny') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
		}
		else if ($transaction == 'expire') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
		}
		else if ($transaction == 'cancel') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
		}
	}
	function suksesTrxMidtrans($bayarid,$transaction_id){
		$this->db->where("id",$bayarid);
		$this->db->update("pembayaran",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s"),"midtrans_id"=>$transaction_id]);
			
		$byr = $this->func->getBayar($bayarid,"semua");
		$stat = ($byr->digital == 1) ? 3 : 1;
		$this->db->where("idbayar",$bayarid);
		$this->db->update("transaksi",["status"=>$stat,"tglupdate"=>date("Y-m-d H:i:s")]);
		
		$this->midtransSukses($bayarid,$stat);
	}
	function midtransSukses($bayar,$stat){
		$bayar = $this->func->getBayar($bayar,"semua");
		$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");
		$alamat = $this->func->getAlamat($trx->alamat,"semua");
		$usr = ($bayar->usrid > 0) ? $this->func->getUser($bayar->usrid,"semua") : $this->func->getUserTemp($bayar->usrid_temp,"semua");
		$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->func->formUang($bayar->diskon)."</b><br/>" : "";
		$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->func->formUang($bayar->diskon)."*\n" : "";
		$toko = $this->func->getSetting("semua");
		$nohp = ($bayar->usrid > 0) ? $this->func->getProfil($usr->id,"nohp","usrid") : $usr->nohp;

		if($stat == 3){
			$this->db->where("idtransaksi",$trx->id);
			$db = $this->db->get("afiliasi");
			foreach($db->result() as $r){
				$saldo = $this->func->getSaldo($r->usrid,"semua");
				$saldototal = $saldo->saldo + $r->jumlah;
				$tgl = date("Y-m-d H:i:s");
				$data = [
					"usrid"	=> $r->usrid,
					"trxid"	=> "TOPUP_".$r->usrid.date("YmdHis"),
					"jenis"	=> 2,
					"status"=> 1,
					"selesai"	=> $tgl,
					"tgl"	=> $tgl,
					"total"	=> $r->jumlah,
					"metode"=> 1,
					"keterangan"=> "Pencairan komisi dari transaksi #".$trx->orderid
				];
				$this->db->insert("saldotarik",$data);
				$topup = $this->db->insert_id();

				$data2 = [
					"tgl"	=> $tgl,
					"usrid"	=> $r->usrid,
					"jenis"	=> 1,
					"jumlah"=> $r->jumlah,
					"darike"=> 1,
					"sambung"	=> $topup,
					"saldoawal"	=> $saldo->saldo,
					"saldoakhir"=> $saldototal
				];
				$this->db->insert("saldohistory",$data2);

				$this->db->where("id",$saldo->id);
				$this->db->update("saldo",["apdet"=>$tgl,"saldo"=>$saldototal]);
				
				$this->db->where("id",$r->id);
				$this->db->update("afiliasi",["status"=>2,"cair"=>date("Y-m-d H:i:s"),"saldotarik"=>$topup]);
			}
		}else{
			$this->db->where("idtransaksi",$trx->id);
			$this->db->update("afiliasi",["status"=>1]);
		}

		//$this->db->where("id",$bayar->id);
		//$this->db->update("pembayaran",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s")]);
			
		//$this->db->where("idbayar",$bayar->id);
		//$this->db->update("transaksi",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s")]);
		
		if($bayar->usrid > 0){
			$pesan = "
				Halo <b>".$usr->nama."</b><br/>".
				"Terimakasih, pembayaran untuk pesananmu sudah kami terima.<br/>".
				"Mohon ditunggu, admin kami akan segera memproses pesananmu<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice: <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan: <b>Rp ".$this->func->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim: <b>Rp ".$this->func->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
				"Detail Pengiriman <br/>".
				"Penerima: <b>".$alamat->nama."</b> <br/>".
				"No HP: <b>".$alamat->nohp."</b> <br/>".
				"Alamat: <b>".$alamat->alamat."</b>".
				"<br/> <br/>".
				"Cek Status pesananmu langsung di menu:<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
		}
		$pesan = "
			Halo *".$usr->nama."* \n".
			"Terimakasih, pembayaran untuk pesananmu sudah kami terima. \n".
			"Mohon ditunggu, admin kami akan segera memproses pesananmu \n".
			"*Detail Pesanan* \n".
			"No Invoice: *#".$bayar->invoice."* \n".
			"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
			"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskonwa.
			"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
			"Detail Pengiriman  \n".
			"Penerima: *".$alamat->nama."* \n".
			"No HP: *".$alamat->nohp."* \n".
			"Alamat: *".$alamat->alamat."*".
			" \n  \n".
			"Cek Status pesananmu langsung di menu: \n".
			"*PESANANKU*
		";
		$this->func->sendWA($nohp,$pesan);

		if($bayar->usrid > 0){
			// SEND NOTIFICATION MOBILE
			$this->func->notifMobile("Pembayaran lunas #".$bayar->invoice,"Terimakasih, pembayaran untuk pesananmu sudah kami terima. Mohon ditunggu, admin kami akan segera memproses pesananmu.","",$usr->id);
		}
	}
	public function cektransaksi(){
		if(isset($_GET["bayar"])){
			$bayar = $this->func->getBayar($_GET["bayar"], "semua");
	
			if(!$bayar || empty($bayar->midtrans_id)){
				echo "ID Transaksi Pembayaran tidak valid";
				return;
			}
	
			$orderId = $bayar->midtrans_id;
	
			try {
				$status = \Midtrans\Transaction::status($orderId);
	
				// Inisialisasi variabel default
				$tipe = $store = $kode = $cara = "";
	
				switch($status->payment_type){
					case "cstore":
						$tipe = "Convenience Store";
						$cara = ($bayar->midtrans_pdf != "") ? $bayar->midtrans_pdf : "";
						$kode = $status->payment_code ?? "-";
						$store = $status->store ?? "-";
						break;
	
					case "bank_transfer":
						$tipe = "Virtual Account";
						if(isset($status->va_numbers[0])){
							$store = $status->va_numbers[0]->bank;
							$kode = $status->va_numbers[0]->va_number;
						}elseif(isset($status->permata_va_number)){
							$store = "Bank Permata";
							$kode = $status->permata_va_number;
						}else{
							$store = "Bank";
							$kode = $status->payment_code ?? "-";
						}
						if(!empty($bayar->midtrans_pdf)){
							$cara = "
								<div class='row m-b-10'>
									<div class='col-4'>Petunjuk Pembayaran</div>
									<div class='col-8 text-uppercase font-weight-bold'>: 
									<a href='".$bayar->midtrans_pdf."' target='_blank'>
									<b><i>Lihat Petunjuk &raquo;</i></b></a></div>
								</div>";
						}
						break;
	
					case "credit_card":
						$tipe = "Kartu Kredit";
						$store = $status->bank ?? "-";
						$kode = $status->masked_card ?? "-";
						break;
	
					case "echannel":
						$tipe = "E-Channel";
						$store = "Multi Payment";
						$kode = $status->biller_code." - ".$status->bill_key;
						if(!empty($bayar->midtrans_pdf)){
							$cara = "
								<div class='row m-b-10'>
									<div class='col-4'>Petunjuk Pembayaran</div>
									<div class='col-8 text-uppercase font-weight-bold'>: 
									<a href='".$bayar->midtrans_pdf."' target='_blank'>
									<b><i>Lihat Petunjuk &raquo;</i></b></a></div>
								</div>";
						}
						break;
	
					case "gopay":
						$tipe = "E-Channel";
						$store = "Gopay";
						break;
				}
	
				$sukses = array("success","settlement","capture");
	
				if(in_array($status->transaction_status, $sukses)){
					echo "
						<div class='row m-b-10'><div class='col-4'>Status</div>
						<div class='col-8 text-uppercase text-success font-weight-bold'>: BERHASIL</div></div>
						<div class='row m-b-10'><div class='col-4'>Metode Pembayaran</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$tipe."</div></div>
						<div class='row m-b-10'><div class='col-4'>Merchant</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$store."</div></div>
						<div class='row m-b-10'><div class='col-4'>Kode Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$kode."</div></div>
						<div class='row m-b-10'><div class='col-4'>Jumlah Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: Rp. ".$this->func->formUang($status->gross_amount)."</div></div>
					";
				}else{
					echo "
						<div class='row m-b-10'><div class='col-4'>Status</div>
						<div class='col-8 text-uppercase text-danger font-weight-bold'>: ".$status->transaction_status."</div></div>
						<div class='row m-b-10'><div class='col-4'>Metode Pembayaran</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$tipe."</div></div>
						<div class='row m-b-10'><div class='col-4'>Merchant</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$store."</div></div>
						<div class='row m-b-10'><div class='col-4'>Kode Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$kode."</div></div>
						<div class='row m-b-10'><div class='col-4'>Jumlah Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: Rp. ".$this->func->formUang($status->gross_amount)."</div></div>
						".$cara;
				}
			} catch (Exception $e) {
				echo "<div class='alert alert-danger'>Gagal mengambil status transaksi: " . $e->getMessage() . "</div>";
			}
		}else{
			echo "ID Transaksi Pembayaran tidak valid";
		}
	}
	

}