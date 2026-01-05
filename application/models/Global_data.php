<?php
if(!defined('BASEPATH')) exit('Hacking Attempt : Keluar dari sistem !! ');

class Global_data extends CI_Model{
    public function __construct(){
        parent::__construct();
    }

	function demo(){
		return false;
	}

	function clean($string) {
		//$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
	}
	function cleanURL($string) {
		$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}
	function getSetting($data){
		if($data != "semua"){
		$this->db->where("field",$data);
		}
		$res = $this->db->get("setting");
		$result = null;
		if($data == "semua"){
			$result = array(null);
			foreach($res->result() as $re){
				$result[$re->field] = $re->value;
			}
			$result = (object)$result;
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->value;
			}
		}
		return $result;
	}
	function globalset($data){
		if($data != "semua"){
		$this->db->where("field",$data);
		}
		$res = $this->db->get("setting");
		$result = null;
		if($data == "semua"){
			$result = array(null);
			foreach($res->result() as $re){
				$result[$re->field] = $re->value;
			}
			$result = (object)$result;
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->value;
			}
		}
		return $result;
	}
	function userlevel($level=1){
		$array = array(
			1 => "Normal Member",
			2 => "Reseller",
			3 => "Agen",
			4 => "Agen Premium",
			5 => "Distributor",
		);
		return $array[$level];
	}

	function maintenis(){
		//return true;
		return false;
	}

	// SEND FCM NOTIFICATION
	function notifMobile($judul,$isi,$page="",$to=null,$img=null){
		$result = json_encode([$to]);
		$set = $this->globalset("semua");
		$this->db->where("usrid",$to);
		$this->db->where("status",1);
		$db = $this->db->get("token");
		$touser = [];
		foreach($db->result() as $r){
			if($r->apptoken != ""){
				$touser[] = $r->apptoken;
			}
		}
		if($db->num_rows() == 0){ $touser = ["/topics/all"]; }

		if($to == null OR ($to != null AND $db->num_rows() > 0)){
			for($i=0; $i<count($touser); $i++){
				$to = $to != null ? $touser[$i] : "/topics/all";
				$data = array(
					'title'=>$judul,
					'body'=>$isi,
					"click_action"=>"FCM_PLUGIN_ACTIVITY"
				);
				if($img != null){
					$data['image'] = $img;
				}
				$fields = array(
					'to'=>$to,
					'notification'=>$data,
					"priority" => "high",
				);
				$headers = array(
					'Authorization: key='.$set->fcm_serverkey,
					'Content-Type: application/json'
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				curl_close( $ch );

				//print_r($result);
			}
		}
		return $result;
	}

	function tema($pilih=null){
		$warna = [
			1 => [
				[
					"light"=>"linear-gradient( 109.5deg,  rgba(92,121,255,1) 11.2%, rgb(48, 213, 238) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(54, 77, 182) 11.2%, rgb(58, 196, 218) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgba(167, 255, 255, 0.67) 0.1%, rgba(239,249,251,0.63) 90.1% )",
					"foot"=>"rgba(181,239,249,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(181,239,249,1) 0%, rgb(242, 243, 248) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(130, 149, 232) 11.2%, rgb(151, 103, 199) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgba(102, 126, 234,1) 11.2%, rgb(118, 75, 162) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(130, 149, 232,0.2) 0.1%, rgba(151, 103, 199,0.2) 90.1% )",
					"foot"=>"rgba(226, 196, 255,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(226, 196, 255,1) 0%, rgb(242, 243, 248) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(128, 208, 199) 11.2%, rgb(19, 84, 122) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgba(86, 179, 168,1) 11.2%, rgb(7, 62, 94) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(128, 208, 199,0.2) 0.1%, rgba(19, 84, 122,0.2) 90.1% )",
					"foot"=>"rgba(192, 250, 243,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(192, 250, 243,1) 0%, rgb(242, 243, 248) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(255, 117, 140) 11.2%, rgb(255, 126, 179) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgba(252, 66, 97,1) 11.2%, rgb(255, 51, 135) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(255, 117, 140,0.2) 0.1%, rgba(255, 126, 179,0.2) 90.1% )",
					"foot"=>"rgba(255, 199, 208,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(255, 199, 208,1) 0%, rgb(242, 243, 248) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(255, 204, 18) 11.2%, rgb(254, 122, 21) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgba(245, 192, 0,1) 11.2%, rgb(201, 87, 0) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(255, 204, 18,0.2) 0.1%, rgba(254, 122, 21,0.2) 90.1% )",
					"foot"=>"rgba(255, 194, 148,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(255, 194, 148,1) 0%, rgb(242, 243, 248) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(102,203,149) 11.2%, rgb(39,210,175) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgba(62, 163, 109,1) 11.2%, rgb(16, 156, 127) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(102,203,149,0.2) 0.1%, rgba(39,210,175,0.2) 90.1% )",
					"foot"=>"rgba(179, 255, 214,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(179, 255, 214,1) 0%, rgb(242, 243, 248) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(255, 122, 194) 11.2%, rgb(216, 19, 137) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgba(222, 93, 163,1) 11.2%, rgb(217, 22, 126) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(255, 122, 194,0.2) 0.1%, rgba(216, 19, 137,0.2) 90.1% )",
					"foot"=>"rgba(255, 209, 234,1)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgba(255, 209, 234,1) 0%, rgb(242, 243, 248) 100% )"
				],
			],
			2 => [
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(26, 188, 156) 11.2%, rgb(26, 188, 156) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(22, 160, 133) 11.2%, rgb(22, 160, 133) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(204, 255, 245) 0.1%, rgb(204, 255, 245) 90.1% )",
					"foot"=>"rgb(204, 255, 245)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(204, 255, 245) 0%, rgb(204, 255, 245) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(52, 152, 219) 11.2%, rgb(52, 152, 219) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(41, 128, 185) 11.2%, rgb(41, 128, 185) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(212, 238, 255) 0.1%, rgb(212, 238, 255) 90.1% )",
					"foot"=>"rgb(212, 238, 255)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(212, 238, 255) 0%, rgb(212, 238, 255) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(46, 204, 113) 11.2%, rgb(46, 204, 113) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(39, 174, 96) 11.2%, rgb(39, 174, 96) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(217, 255, 233) 0.1%, rgb(217, 255, 233) 90.1% )",
					"foot"=>"rgb(217, 255, 233)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(217, 255, 233) 0%, rgb(217, 255, 233) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(230, 126, 34) 11.2%, rgb(230, 126, 34) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(211, 84, 0) 11.2%, rgb(211, 84, 0) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(255, 230, 214) 0.1%, rgb(255, 230, 214) 90.1% )",
					"foot"=>"rgb(255, 230, 214)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(255, 230, 214) 0%, rgb(255, 230, 214) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(231, 76, 60) 11.2%, rgb(231, 76, 60) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(192, 57, 43) 11.2%, rgb(192, 57, 43) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(255, 211, 207) 0.1%, rgb(255, 211, 207) 90.1% )",
					"foot"=>"rgb(255, 211, 207)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(255, 211, 207) 0%, rgb(255, 211, 207) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(253, 121, 168) 11.2%, rgb(253, 121, 168) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(232, 67, 147) 11.2%, rgb(232, 67, 147) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(255, 219, 237) 0.1%, rgb(255, 219, 237) 90.1% )",
					"foot"=>"rgb(255, 219, 237)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(255, 219, 237) 0%, rgb(255, 219, 237) 100% )"
				],
				[
					"light"=>"linear-gradient( 109.5deg,  rgb(52, 73, 94) 11.2%, rgb(52, 73, 94) 91.1% )",
					"hover"=>"linear-gradient( 109.5deg,  rgb(18, 26, 33) 11.2%, rgb(18, 26, 33) 91.1% )",
					"testimoni"=>"radial-gradient( circle farthest-corner at 10% 90%,  rgb(222, 238, 255) 0.1%, rgb(222, 238, 255) 90.1% )",
					"foot"=>"rgb(222, 238, 255)",
					"foot_gradient"=>"linear-gradient( 0deg,  rgb(222, 238, 255) 0%, rgb(222, 238, 255) 100% )"
				],
			]
		];

		$temawarna = $this->globalset("temawarna");
		if($pilih != null){
			$result = (object)$warna[$temawarna][$pilih];
		}else{
			$result = $warna[$temawarna];
		}

		return $result;
	}

	function getBintang($idproduk=0){
		$this->db->where("idproduk",$idproduk);
		$db = $this->db->get("review");
		$total = 0;
		foreach($db->result() as $res){
			$total += $res->nilai;
		}
		$total = ($total > 0 OR $db->num_rows() > 0) ? $total / $db->num_rows() : 0;
		$nilai = round($total,0,PHP_ROUND_HALF_DOWN);
		return array("star"=>$nilai,"jml"=>$db->num_rows());
	}
	
	function getPesanNotif(){
		$this->db->select("id");
		$this->db->where("baca",0);
		$this->db->where("tujuan",$_SESSION["usrid"]);
		$db = $this->db->get("pesan");
		
		return $db->num_rows();
	}

	function getCategory($data="option",$cat=0){
		if($data == "option"){
			$this->db->where("parent","0");
			$sql = $this->db->get("kategori");
			$result = "";

			foreach($sql->result() as $res){
				$select = ($res->id == $cat) ? "selected" : "";
				$result .= "<option value='".$res->id."' ".$select.">".$res->nama."</option>";
			}
			return $result;
		}else{
			return "data not found";
		}
	}

	function getFoto($id,$kat="utama"){
		$server = base_url('cdn/uploads');
		$this->db->where("idproduk",$id);
		if($kat == "utama"){
			$this->db->where("jenis",1);
		}
		$this->db->limit(1);
		$res = $this->db->get("upload");

		$result = base_url("cdn/uploads/no-image.png");
		foreach($res->result() as $re){
			$result = $server.'/'.$re->nama;
		}
		return $result;
	}
	function getUpload($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$res = $this->db->get("upload");

		$result = base_url("cdn/uploads/no-image.png");
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		return $result;
	}
	function getFotoUpload($id,$what,$opo="id"){
		$this->db->where("idproduk",$id);
		if($opo == "utama"){
			$this->db->where("jenis",1);
		}
		$res = $this->db->get("upload");

		$result = base_url("cdn/uploads/no-image.png");
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		return $result;
	}

	// ADD USER TEMP
	function addUserTemp(){
		if(isset($_SESSION["usrid_temp"])){
			return $_SESSION["usrid_temp"];
		}else{
			$this->db->insert("usertemp",["tgl"=>date("Y-m-d H:i:s")]);
			$id = $this->db->insert_id();
			$this->session->set_userdata("usrid_temp",$id);
			return $id;
		}
	}
	
	// RANDOM WASAP
	function getRandomWasap(){
		$this->db->order_by("tgl","ASC");
		$this->db->limit(1);
		$res = $this->db->get("wasap");
		
		$result = 0;
		foreach($res->result() as $r){
			if(substr($r->wasap,0,1) == 0){
				$result = "+62".substr($r->wasap,1);
			}elseif(substr($r->wasap,0,2) == "62"){
				$result = "+".$r->wasap;
			}elseif(substr($r->wasap,0,1) == "+"){
				$result = $r->wasap;
			}
		}
		return $result;
	}

	// RESET USERDATA
	function resetData(){
		$this->session->unset_userdata("securesearch");
	}

	// CEK LOGIN
	function cekLogin(){
		if(isset($_SESSION['usrid']) AND isset($_SESSION['lvl'])){
			$user = $this->getUser($_SESSION['usrid'],"semua");

			if($user->id > 0){
				$this->db->where("id",$_SESSION['usrid']);
				$this->db->update("userdata",array("tgl"=>date("Y-m-d H:i:s")));

				if($_SESSION['lvl'] != $user->level){
					$_SESSION['lvl'] = $user->level;
				}
				return $_SESSION["usrid"];
			}else{
				$this->session->sess_destroy();
				redirect("home/signin");
				exit;
			}
		}else{
			return 0;
		}
	}
	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i <= 16; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	function resetPass($Duser){
		if(!$this->getUser($Duser,"id","username")) {
    		$usrid = $this->getUser($Duser,"id","nohp");
    		if($usrid > 0){
    			$user = $this->getUser($usrid,"semua");
    			$generated = $this->randomPassword();
    			$this->db->where("id",$usrid);
    			$this->db->update("userdata",array("password"=>$this->encode($generated)));
    			$pesanWA = "
				Halo *".$user->nama."* \n\n".
				"Selamat, Reset Password Anda berhasil dan untuk login ke Akun Anda, silahkan menggunakan password dibawah :\n\n".
				"Pass : *".$generated."*\n\n".
				"Segera masuk dan ganti password Anda untuk meningkatkan keamanan Akun Anda kembali.
				";
				$pesan = '
    				<html>
    				<head>
    					<style>
    					.border{width:90%;padding:20px;border:1px solid #ccc;border-radius:3px;margin:auto;}
    					.pesan{margin-bottom:30px;}
    					.link{margin-bottom:20px;}
    					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
    					</style>
    				</head>
    				<body>
    					<div class="border">
    					<div class="pesan">
    						<h3>Halo '.$user->nama.'</h3><p/>
    						Selamat, Reset Password Anda berhasil dan untuk login ke Akun Anda, silahkan menggunakan password dibawah :<br/><br/>
    						Pass : '.$generated.'<p/>
    						Segera masuk dan ganti password Anda untuk meningkatkan keamanan Akun Anda kembali.<p/>
    					</div>
    					<div class="link">
    						<a class="alink" style="color:#fff;" href="'.site_url("home/signin").'">LOGIN DISINI</a>
    					</div>
    					</div>
    				</body>
    				</html>
    			';
    			if($this->sendWA($user->nohp,$pesanWA)){
    			    $this->sendEmail($user->username,$this->getSetting("nama"),$pesan,"Reset Password");
    				return true;
    			}else{
    				return false;
    			}
    		}else{
    			return false;
    		}
		} else {
		    $usrid = $this->getUser($Duser,"id","username");
    		if($usrid > 0){
    			$user = $this->getUser($usrid,"semua");
    			$generated = $this->randomPassword();
    			$this->db->where("id",$usrid);
    			$this->db->update("userdata",array("password"=>$this->encode($generated)));
    
    			$pesan = '
    				<html>
    				<head>
    					<style>
    					.border{width:90%;padding:20px;border:1px solid #ccc;border-radius:3px;margin:auto;}
    					.pesan{margin-bottom:30px;}
    					.link{margin-bottom:20px;}
    					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
    					</style>
    				</head>
    				<body>
    					<div class="border">
    					<div class="pesan">
    						<h3>Halo '.$user->nama.'</h3><p/>
    						Selamat, Reset Password Anda berhasil dan untuk login ke Akun Anda, silahkan menggunakan password dibawah :<br/><br/>
    						Pass : '.$generated.'<p/>
    						Segera masuk dan ganti password Anda untuk meningkatkan keamanan akun Anda kembali.<p/>
    					</div>
    					<div class="link">
    						<a class="alink" style="color:#fff;" href="'.site_url("home/signin").'">LOGIN DISINI</a>
    					</div>
    					</div>
    				</body>
    				</html>
    			';
    			$pesanWA = "
				Halo *".$user->nama."* \n\n".
				"Selamat, Reset Password Anda berhasil dan untuk login ke Akun Anda, silahkan menggunakan password dibawah :\n\n".
				"Pass : *".$generated."*\n\n".
				"Segera masuk dan ganti password Anda untuk meningkatkan keamanan Akun Anda kembali.
				";
    			if($this->sendEmail($user->username,$this->getSetting("nama"),$pesan,"Reset Password")){
    				$this->sendWA($user->nohp,$pesanWA);
    				return true;
    			}else{
    				return false;
    			}
    		}else{
    			return false;
    		}
		}
	}

	// VERIFIKASI
	function sendEmail($tujuan,$judul,$pesan,$subyek,$pengirim=null){
		$data = array(
			"jenis"		=> 1,
			"tujuan"	=> $tujuan,
			"judul"		=> $judul,
			"pesan"		=> $pesan,
			"subyek"	=> $subyek,
			"pengirim"	=> $pengirim,
			"tgl"		=> date("Y-m-d H:i:s"),
			"status"	=> 0
		);
		$this->db->insert("notifikasi",$data);

		return true;
	}
	function sendEmailOK($tujuan,$judul,$pesan,$subyek,$pengirim=null){
		$this->load->library('email');
		$seting = $this->getSetting("semua");
		if($seting->email_jenis == 2){
			$config['protocol'] = "smtp";
			$config['smtp_host'] = $seting->email_server;
			$config['smtp_port'] = $seting->email_port;
			$config['smtp_user'] = $seting->email_notif;
			$config['smtp_pass'] = $seting->email_password;

			if($seting->email_port == 465){
				$config['smtp_crypto'] = "ssl";
			}
		}
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";
		$this->email->initialize($config);

		$this->email->from($seting->email_notif, $judul);
		$this->email->to($tujuan);
		if($pengirim != null){
		$this->email->cc($pengirim);
		}

		$pesan = $this->load->view("main/email_template",array("content"=>$pesan),true);
		$this->email->subject($subyek);
		$this->email->message($pesan);

		if($this->email->send()){
			return true;
		}else{
		//show_error($this->email->print_debugger());
			return false;
		}
	}
	public function sendWA($nomer,$pesan,$img=""){
		$data = array(
			"jenis"		=> 2,
			"tujuan"	=> $nomer,
			"pesan"		=> $pesan,
			"judul"		=> $img,
			"tgl"		=> date("Y-m-d H:i:s"),
			"status"	=> 0
		);
		$this->db->insert("notifikasi",$data);
		
		return true;
	}
	public function sendWAOK($nomer,$pesan,$img=null){
		$set = $this->getSetting("semua");
		$nomer = intval($nomer);

		if($set->api_wasap == "wagw"){
			$number = substr($nomer,0,2) != "0" ? "0".$nomer : $nomer;
			$pesan = ltrim($pesan);
			$url = $set->wagw_domain . 'send-message';
			$data = [
				"sender" => $set->wagw_nomer,
				"number" => $number,
				"message" => $pesan
			];
			if($img != null){
				$url = $set->wagw_domain . 'send-media';
				$a = explode('/',$img);
				$filename = $a[count($a)-1];
				$a2 = explode('.',$filename);
				$namefile = $a2[count($a2)-2];
				$ext = $a2[count($a2)-1];
				unset($data["message"]);
				$data['caption'] = $pesan;
				$data['url'] = $img;
				$data['filename'] = $namefile;
				$data['filetype'] = $ext;
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($ch);
			curl_close($ch);
			/*
			print_r($data);
			print_r($result);
			*/
			
			if($result){
				return true;
			}else{
				return false;
			}
		}else if($set->api_wasap == "watzap"){
		    $nomer = substr($nomer,0,2) != "62" ? "62".$nomer : $nomer;
		    $pesan = ltrim($pesan);
			if($img != null){
				$a = explode('/',$img);
				$filename = $a[count($a)-1];
				$a2 = explode('.',$filename);
				$namefile = $a2[count($a2)-2];
				$ext = $a2[count($a2)-1];
    			$curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $set->watzap_domain.'send-media',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('api_key' => $set->watzap_apikey,'sender' => $set->watzap_nomer,'number' => $nomer,'media_type' => $ext,'caption' => $pesan,'url' => $img),
                ));
                $result = curl_exec($curl);
    			curl_close($curl);
			} else {
			    $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $set->watzap_domain.'send-message',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('api_key' => $set->watzap_apikey,'sender' => $set->watzap_nomer,'number' => $nomer,'message' => $pesan),
                ));
                $result = curl_exec($curl);
    			curl_close($curl);
			}
			
			if($result){
				return true;
			}else{
				return false;
			}
		}elseif($set->api_wasap == "starsender"){
			$apikey = $set->starsender;
			if($apikey == ""){
				return false;
				exit;
			}
			$nomer = substr($nomer,0,2) != "62" ? "62".$nomer : $nomer;
			
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://starsender.online/api/sendText?message='.rawurlencode($pesan).'&tujuan='.rawurlencode($nomer.'@s.whatsapp.net'),
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_HTTPHEADER => array(
				'apikey: '.$apikey
			  ),
			));
			
			$response = curl_exec($curl);
			
			curl_close($curl);
			/*
			echo $response;
			*/
			if($response){
				return true;
			}else{
				return false;
			}
		}elseif($set->api_wasap == "woowa"){
			$key = $set->woowa;
			if($key == ""){
				return false;
				exit;
			}

			$nomer = substr($nomer,0,2) != "62" ? "+62".$nomer : "+".$nomer;
			$url='http://116.203.92.59/api/send_message';
			$data = array(
			"phone_no"	=> $nomer,
			"key"		=> $key,
			"message"	=> $pesan."\n".date("Y/m/d H:i:s")
			);
			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 360);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
			);
			$res = curl_exec($ch);
			curl_close($ch);

			if($res == "Success"){
				return true;
			}else{
				return false;
			}
		}elseif($set->api_wasap == "wablas"){
			$token = $set->wablas;
			if($token == "" OR $set->wablas_server == ""){
				return false;
				exit;
			}

			$nomer = substr($nomer,0,2) != "62" ? "62".$nomer : $nomer;
			$curl = curl_init();
			$payload = [
				"data" => [
					[
						'phone' => $nomer,
						'message' => $pesan
					]
				]
			];
			
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
			curl_setopt($curl, CURLOPT_URL, $set->wablas_server."/api/v2/send-bulk/text");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"Authorization: ".$token
				)
			);
			$result = curl_exec($curl);
			curl_close($curl);
			
			//echo "<pre>";
			$res = json_decode($result);
			if($res->status > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function verifEmail($id=0){
		if($id > 0){
			$profil = $this->getProfil($id,"semua","usrid");
			$user = $this->getUser($id,"semua");
			$verifid = $this->arrEnc(array("id"=>$id,"time"=>date("YmdHis")));
			$subyek = 'Verifikasi Pendaftaran '.$this->getSetting("nama");
			$pesan = '
				<html>
				<head>
					<style>
					.border{padding:20px;border-radius:3px;margin:auto;}
					.pesan{margin-bottom:30px;}
					.link{margin-bottom:20px;}
					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
					</style>
				</head>
				<body>
					<div class="border">
					<div class="pesan">
					<h3>Halo '.$profil->nama.'</h3><p/>
					Terima kasih sudah mendaftar di <b>'.$this->getSetting("nama").'</b>, untuk mengaktifkan Akun Anda, silahkan klik link berikut :<br/><p>
					</div>
					<div class="link">
						<a class="alink" style="color:#fff;" href="'.site_url("home/signup?verify=".$verifid).'">VERIFIKASI AKUN </a>
						<br/>&nbsp;<br/><p>atau link dibawah ini<br/><p>
						<a href="'.site_url("home/signup?verify=".$verifid).'">'.site_url("home/signup?verify=".$verifid).'</a>
					</div>
					</div>
				</body>
				</html>
			';

			if($this->sendEmail($user->username,$this->getSetting("nama"),$pesan,$subyek)) {
				return true;
			} else {
				return false;
			}
		}
	}
	function verifWA($id=0){
		if($id > 0){
			$profil = $this->getProfil($id,"semua","usrid");
			$user = $this->getUser($id,"semua");
			$verifid = $this->arrEnc(array("id"=>$id,"time"=>date("YmdHis")));
			$subyek = 'Verifikasi Pendaftaran '.$this->getSetting("nama");
			$pesan = "
				Halo *".$profil->nama."* \n \n"."Terima kasih sudah mendaftar di *".$this->getSetting("nama")."*, untuk mengaktifkan Akun Anda, silahkan klik link berikut :\n\n".site_url("home/signup?verify=".$verifid)." \n"."_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu*_
			";

			if($this->sendWA($profil->nohp,$pesan)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// SEND NOTIF
	function notiftransfer($order_id=null){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUser($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->getSetting("semua");
			$kurir = $this->getKurir($trx->kurir,"nama")." ".$this->getPaket($trx->paket,"nama");

			$rekening = "";
			$rekeningwa = "";
			$this->db->where("usrid",0);
			$rek = $this->db->get("rekening");
			foreach($rek->result() as $res){
				$bank = strtoupper($this->getBank($res->idbank,"nama"));
				$rekening .= "
					<b>".$bank." - ".$res->norek."</b><br/>
					a/n ".$res->atasnama."<br/>
				";
				$rekeningwa .= "
					*".$bank." - ".$res->norek."* \n
					a/n ".$res->atasnama." \n
				";
			}

			$pesan = "
				Halo <b>".$usr->nama."</b><br/><br/>".
				"Terima kasih, sudah membeli produk kami.<br/>".
				"Segera lakukan pembayaran agar pesananmu segera diproses.<br/> <br/>".
				"<b>Transfer pembayaran ke rekening berikut</b><br/>".
				$rekening.
				"<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice : <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan : <b>Rp ".$this->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim : <b>Rp ".$this->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman : <b>".strtoupper($kurir)."</b><br/> <br/>".
				
				"Informasi cara pembayaran dan status pesananmu langsung di menu :<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->sendEmail($usr->username,$toko->nama,$pesan,"Rincian Pemesanan");
			$pesan = "
				Halo *".$usr->nama."* \n \n".
				"Terima kasih, sudah membeli produk kami. \n".
				"Segera lakukan pembayaran agar pesananmu segera diproses. \n \n".
				"*Transfer pembayaran ke rekening berikut :* \n".
				$rekeningwa."\n".
				" \n".
				"*Detail Pesanan* \n".
				"No Invoice : *#".$bayar->invoice."* \n".
				"Total Pesanan : *Rp ".$this->formUang($bayar->total)."* \n".
				"Ongkos Kirim : *Rp ".$this->formUang($trx->ongkir)."* \n".$diskonwa.
				"Kurir Pengiriman : *".strtoupper($kurir)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima : *".$alamat->nama."*  \n".
				"No HP : *".$alamat->nohp."*  \n".
				"Alamat : *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu : \n".
				"*PESANANKU*
			";
			$this->sendWA($this->getProfil($usr->id,"nohp","usrid"),$pesan);

			// SEND NOTIFICATION MOBILE
			$this->notifMobile("Pesanan #".$bayar->invoice,"Segera lakukan pembayaran agar pesananmu juga segera diproses","",$usr->id);
		}
	}
	function notifbatal($order_id=null,$jenis=1){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUser($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->getSetting("semua");
			$kurir = $this->getKurir($trx->kurir,"nama")." ".$this->getPaket($trx->paket,"nama");
			
			if($jenis == 1){
				$alasan = "DIBATALKAN OLEH ADMIN";
			}elseif($jenis == 2){
				$alasan = "DIBATALKAN OLEH PEMBELI";
			}elseif($jenis == 3){
				$alasan = "TELAH MELEWATI BATAS WAKTU JATUH TEMPO PEMBAYARAN";
			}else{
				$alasan = "-";
			}

			$pesan = "
				Halo <b>".$usr->nama."</b><br/> <br/>".
				"Pesanan Anda telah dibatalkan!<br/>".
				"Status : <br/> <br/>".
				"<b>".$alasan."</b><br/>".
				"<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice : <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan : <b>Rp ".$this->formUang($bayar->total)."</b><br/>".
				"<br/>".
				"Informasi cara pembayaran dan status pesananmu langsung di menu :<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->sendEmail($usr->username,$toko->nama,$pesan,"Pesanan Dibatalkan");
			$pesan = "
				Halo *".$usr->nama."* \n \n".
				"Pesanan Anda telah dibatalkan! \n".
				"Status : \n \n".
				"*".$alasan."* \n".
				" \n".
				"*Detail Pesanan* \n".
				"No Invoice : *#".$bayar->invoice."* \n".
				"Total Pesanan : *Rp ".$this->formUang($bayar->total)."* \n".
				" \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu : \n".
				"*PESANANKU*
			";
			$this->sendWA($this->getProfil($usr->id,"nohp","usrid"),$pesan);

			// SEND NOTIFICATION MOBILE
			$this->notifMobile("Pesanan dibatalkan #".$bayar->invoice,"Pesanan Anda telah dibatalkan karena ".$alasan,"",$usr->id);
		}
	}

	// RETURN KOSONGAN
	private function kosongan($type=null){
		if($type == "text"){
			$result = "";
		}elseif($type == "datetime"){
			$result = "0000-00-00 00:00:00";
		}elseif($type == "int"){
			$result = 0;
		}elseif($type == "bigint"){
			$result = 0;
		}else{
			$result = "data telah dihapus";
		}
		return $result;
	}

	// GET VOUCHERs
	function getVoucher($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("voucher");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('voucher');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}


	// GET WISHLIST
	function getWishlist($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("wishlist");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('wishlist');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function cekWishlist($id,$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where("idproduk",$id);
		$res = $this->db->get("wishlist");

		if($res->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function getWishlistCount(){
		$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
		$this->db->where("usrid",$usrid);
		$res = $this->db->get("wishlist");
		
		return $res->num_rows();
	}

	// GET PREORDER
	function getPreorder($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("preorder");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('preorder');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET KATEGORI
	function getKategori($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kategori");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('kategori');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET PRE PEMBAYARAN
	function getPreBayar($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("pembayaran_pre");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('pembayaran_pre');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET TRANSAKSI
	function getTransaksi($id,$what,$opo="id"){
		//$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
		//$this->db->where("usrid",$usrid);
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("transaksi");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('transaksi');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getTransaksiProduk($id,$what,$opo="id"){
		//$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
		//$this->db->where("usrid",$usrid);
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("transaksiproduk");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('transaksiproduk');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET PESAN
	function getPesan($id,$what,$opo="id"){
		$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
		$this->db->where("(tujuan = ".$usrid." OR dari = ".$usrid.") AND ".$opo." = '".$id."'");
		$this->db->limit(1);
		$res = $this->db->get("pesan");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('pesan');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	/* MULTI GUDANG */
	function getGudang($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("gudang");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('gudang');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET USERDATA
	function getProfil($id,$what,$opo="id"){
		//$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
		//$this->db->where("usrid",$usrid);
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("profil");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('profil');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getUser($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("userdata");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('userdata');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getUserTemp($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("usertemp");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('usertemp');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET REVIEW ULASAN
	function getReview($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("review");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('review');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getReviewProduk($id){
		$this->db->where("idproduk",$id);
		$res = $this->db->get("review");

		$count = 0;
		foreach($res->result() as $r){
			$count += $r->nilai;
		}
		$result = $count > 0 ? round($count/$res->num_rows(),1) : 0;
		$result = ["nilai"=>$result,"ulasan"=>$res->num_rows()];
		return $result;
	}

	// GET BANK
	function getBank($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("rekeningbank");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('rekeningbank');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getRekening($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("rekening");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('rekening');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	// GET PRODUK
	function getProduk($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produk");

		if($res->num_rows() > 0){
			if($what == "semua"){
				$result = null;
				if($res->num_rows() > 0){
					foreach($res->result() as $key => $value){
						$result[$key] = $value;
					}
					$result = $result[0];
				}
			}else{
				$result = null;
				foreach($res->result() as $re){
					if($what == "harga"){
						$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : "";
						if($level == 5){
							$result = $re->hargadistri;
						}elseif($level == 4){
							$result = $re->hargaagensp;
						}elseif($level == 3){
							$result = $re->hargaagen;
						}elseif($level == 2){
							$result = $re->hargareseller;
						}else{
							$result = $re->harga;
						}
					}else{
						$result = $re->$what;
					}
				}
			}
		}else{
			if($what == "semua"){
				$result = new stdClass();
				$result->id = 0;
				$result->idcat = 0;
				$result->harga = 0;
				$result->hargareseller = 0;
				$result->hargaagen = 0;
				$result->hargaagensp = 0;
				$result->hargadistri = 0;
				$result->hargacoret = 0;
				$result->berat = 0;
				$result->minorder = 0;
				$result->stok = 0;
				$result->gudang = 0;
				$result->status = 0;
				$result->preorder = 0;
				$result->pohari = 0;
				$result->tglbuat = "0000-00-00 00:00:00";
				$result->tglpo = "0000-00-00 00:00:00";
				$result->tglupdate = "0000-00-00 00:00:00";
				$result->nama = "produk telah dihapus";
				$result->kode = "produk telah dihapus";
				$result->url = "";
				$result->deskripsi = "produk telah dihapus";
				$result->variasi = "";
				$result->subvariasi = "";
			}else{
				$result = "";
			}
		}
		return $result;
	}
	function getVariasi($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produkvariasi");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('produkvariasi');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getVariasiItem($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("variasi");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('variasi');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getWarna($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("variasiwarna");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('variasiwarna');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSize($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("variasisize");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('variasisize');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function upgradeUser($usridtemp,$usrid){
		$this->db->where("usrid_temp",$usridtemp);
		$this->db->update("alamat",["usrid"=>$usrid]);

		$this->db->where("usrid_temp",$usridtemp);
		$this->db->update("pembayaran",["usrid"=>$usrid]);

		$this->db->where("usrid_temp",$usridtemp);
		$this->db->update("transaksi",["usrid"=>$usrid]);

		$this->db->where("usrid_temp",$usridtemp);
		$this->db->update("transaksiproduk",["usrid"=>$usrid]);
	}
	
	// GET ALAMAT
	function getAlamat($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("alamat");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('alamat');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// KERANJANG BELANJA
	function getKeranjang(){
		if(isset($_SESSION["usrid"])){
			$this->db->where("usrid",$_SESSION["usrid"]);
		}elseif(isset($_SESSION["usrid_temp"])){
			$this->db->where("usrid_temp",$_SESSION["usrid_temp"]);
		}else{
			$this->db->where("usrid","xzact");
		}
		$this->db->where("idtransaksi",0);
		$db = $this->db->get("transaksiproduk");
		$keranjang = $db->num_rows();
		return $keranjang;
	}

	// GET PENGIRIMAN
	function getPengiriman($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("pengiriman");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('pengiriman');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET LOKASI
	function getKec($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kec");
    	
		if($res->num_rows() > 0){
			if($what == "semua"){
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				foreach($res->result() as $re){
					$result = $re->$what;
				}
			}
		}else{
			if($what == "semua"){
				$result = new stdClass();
				$result->id = 0;
				$result->rajaongkir = 0;
				$result->idkab = 0;
				$result->nama = "";
			}else{
				$result = "";
			}
		}
		return $result;
	}
	function getKab($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kab");

		if($res->num_rows() > 0){
			if($what == "semua"){
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				foreach($res->result() as $re){
					$result = $re->$what;
				}
			}
		}else{
			if($what == "semua"){
				$result = new stdClass();
				$result->id = 0;
				$result->rajaongkir = 0;
				$result->idprov = 0;
				$result->nama = "";
			}else{
				$result = "kabupaten tidak ditemukan";
			}
		}
		return $result;
	}
	function getProv($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("prov");

		$result = "provinsi tidak ditemukan";
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		return $result;
	}

	// PEMBAYARAN
	function getBayar($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("pembayaran");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('pembayaran');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// KURIR & PAKET
	function getKurir($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kurir");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('kurir');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getPaket($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("paket");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('paket');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// SALDO WARGA
	function getSaldo($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldo");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('saldo');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSaldodarike($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldodarike");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('saldodarike');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSaldohistory($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldohistory");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('saldohistory');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSaldotarik($id,$what,$opo="id",$mob=false){
		if($mob == false){
			$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
			$this->db->where("usrid",$usrid);
		}
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldotarik");
		
		if($what == "semua"){
			if($res->num_rows() > 0){
				$result = array(0);
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				$fields = $this->db->field_data('saldotarik');
				$result = new stdClass();
				foreach ($fields as $r){
					$nama = $r->name;
					$result->$nama = $this->kosongan($r->type);
				}
			}
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// STATUS PESANAN
	function addStatusPesanan($idtrx,$status,$updater,$ket){

	}

	// ONGKIR
	function getHistoryOngkir($id,$what="id",$opo="id"){
		if(is_array($id)){
			foreach($id as $key => $val){
				$this->db->where($key,$val);
			}
			$this->db->limit(1);
			$res = $this->db->get("historyongkir");

			$result = "tidak ditemukan";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}else{
			$this->db->where($opo,$id);
			$this->db->limit(1);
			$res = $this->db->get("historyongkir");

			$result = "tidak ditemukan";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function beratkg($berat=0,$kurir="jne"){
		$beratkg = ($berat < 1000) ? 1 : round(intval($berat) / 1000,0,PHP_ROUND_HALF_DOWN);
		if($kurir == "jne"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 300){
				$beratkg = $beratkg + 1;
			}
		}elseif($kurir == "jnt"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 200){
				$beratkg = $beratkg + 1;
			}
		}elseif($kurir == "pos"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 200){
				$beratkg = $beratkg + 1;
			}
		}elseif($kurir == "tiki"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 299){
				$beratkg = $beratkg + 1;
			}
		}else{
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 0){
				$beratkg = $beratkg + 1;
			}
		}
		return $beratkg;
	}
	public function cekOngkir($dari,$berat,$tujuan,$kurir,$services){
			$kurir = $this->getKurir($kurir,"semua");
			if($kurir == "jne"){$srvdefault="REG";}
			//elseif($kurir=="pos"){$srvdefault="Paket Kilat Khusus";}
			elseif($kurir=="tiki"){$srvdefault="REG";}
			else{$srvdefault="";}
			$service = $this->getPaket($services,"semua");
			
			// CUSTOM KURIR
			if($kurir->jenis == 2){
				$idkab = $this->getKec($tujuan,"idkab");
				$berat = ($berat >= 1000) ? round(($berat/1000),0) : 1;
				$this->db->where("kurir",$kurir->id);
				$this->db->where("paket",$service->id);
				//$this->db->where("idkab",$idkab);
				$db = $this->db->get("kurircustom");
				if($db->num_rows() > 0){
					$hasils = array();
					foreach($db->result() as $r){
						$biaya = ($r->jenis == 1) ? $r->harga : $r->harga * $berat;
						$hasils[$r->idkab] = array(
							"success"	=> true,
							"dari"		=> $dari,
							"tujuan"	=> $tujuan,
							"kurir"		=> $kurir->nama,
							"service"	=> $service->nama,
							"kuririd"	=> $kurir->id,
							"serviceid"	=> $service->id,
							"cod"		=> $service->cod,
							"etd"		=> $r->estimasi,
							"harga"		=> $biaya,
							"update"	=> date("Y-m-d H:i:s"),
							"hargaperkg"=> $r->harga,
							"token"		=> $this->security->get_csrf_hash()
						);
					}

					if(isset($hasils[$idkab])){
						$hasil = $hasils[$idkab];
					}else{
						if(isset($hasils[0])){
							$hasil = $hasils[0];
						}else{
							$hasil = array(
								"success"	=> false,
								"dari"		=> $dari,
								"tujuan"	=> $tujuan,
								"kurir"		=> $kurir->nama,
								"service"	=> $service->nama,
								"kuririd"	=> $kurir->id,
								"serviceid"	=> $service->id,
								"cod"		=> $service->cod,
								"etd"		=> 1,
								"harga"		=> 0,
								"update"	=> date("Y-m-d H:i:s"),
								"hargaperkg"=> 0,
								"keterangan"=> "ongkir tidak ditemukan",
								"token"		=> $this->security->get_csrf_hash()
							);
						}
					}
				}else{
					$hasil = array(
						"success"	=> false,
						"dari"		=> $dari,
						"tujuan"	=> $tujuan,
						"kurir"		=> $kurir->nama,
						"service"	=> $service->nama,
						"kuririd"	=> $kurir->id,
						"serviceid"	=> $service->id,
						"cod"		=> $service->cod,
						"etd"		=> 1,
						"harga"		=> 0,
						"update"	=> date("Y-m-d H:i:s"),
						"hargaperkg"=> 0,
						"keterangan"=> "ongkir tidak ditemukan",
						"token"		=> $this->security->get_csrf_hash()
					);
				}
				//return $hasil;
				//exit;
			}else{
				$kuririd = $kurir->id;
				$kurir = $kurir->rajaongkir;
				$serviceid = $service->id;
				$servicecod = $service->cod;
				$service = $service->rajaongkir;
			}
			
			//RAJAONGKIR CONVERT KAB
			$dari = $this->getKab($dari,"rajaongkir");
			$datakec = $this->getKec($tujuan,"semua");
			$tujuan = $datakec->rajaongkir;

			$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
			if($datakec->idkab == $dari AND $kurir == "jne"){
				if($_GET["service"] == "REG"){ $service = "CTC"; }
				elseif($_GET["service"] == "YES"){ $service = "CTCYES"; }
			}

			$beratkg = ($berat < 1000) ? 1 : round(intval($berat) / 1000,0,PHP_ROUND_HALF_DOWN);
			if($kurir == "jne"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 300){
					$beratkg = $beratkg + 1;
				}
			}elseif($kurir == "pos"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 200){
					$beratkg = $beratkg + 1;
				}
			}elseif($kurir == "tiki"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 299){
					$beratkg = $beratkg + 1;
				}
			}else{
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 0){
					$beratkg = $beratkg + 1;
				}
			}
			$beratkg = $beratkg < 1 ? 1 : $beratkg;

			if(isset($hasil)){
				return $hasil;
			}else{
				$this->db->where("dari",$dari);
				$this->db->where("tujuan",$tujuan);
				$this->db->where("kurir",strtolower($kurir));
				//$this->db->where("service",$service);
				//$this->db->limit(1);
				$this->db->order_by("id","DESC");
				$results = $this->db->get("historyongkir");
				if($results->num_rows() > 0){
					foreach($results->result() as $res){
						if($res->harga <= 0){
							$just = true;
							return $this->reqOngkir($dari,$berat,$tujuan,$kurir,$service);
							exit;
						}else{
							if(strcasecmp($service,$res->service) == 0){
								$harga = $res->harga * $beratkg;
								$etd = $res->etd != "" OR $res->etd != "-" ? $res->etd : "0";
								$array = array(
									"success"	=> true,
									"dari"		=> $res->dari,
									"tujuan"	=> $res->tujuan,
									"kurir"		=> $res->kurir,
									"service"	=> $res->service,
									"kuririd"	=> $kuririd,
									"serviceid"	=> $serviceid,
									"cod"		=> $servicecod,
									"etd"		=> $etd,
									"harga"		=> $harga,
									"update"	=> $res->update
								);
								return $array;
								$just = true;
							}
						}
					}
				}else{
					return $this->reqOngkir($dari,$berat,$tujuan,$kurir,$service,$kuririd,$serviceid);
				}
			}
	}
	private function reqOngkir($dari,$berat,$tujuan,$kurir,$services,$kuririd,$serviceid){
		$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
		//$kur = $this->getKurir($kuririd,"semua");
		$ser = $this->getPaket($serviceid,"semua");

		$beratkg = round(intval($berat) / 1000,0,PHP_ROUND_HALF_DOWN);
		if($kurir == "jne"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 300){
				$beratkg = $beratkg + 1;
			}
		}elseif($kurir == "pos"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 200){
				$beratkg = $beratkg + 1;
			}
		}elseif($kurir == "tiki"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 299){
				$beratkg = $beratkg + 1;
			}
		}else{
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 0){
				$beratkg = $beratkg + 1;
			}
		}
		$beratkg = $beratkg < 1 ? 1 : $beratkg;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=".$dari."&originType=city&destination=".$tujuan."&destinationType=subdistrict&weight=".$berat."&courier=".$kurir,
			CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded",
			"key: ".$this->globalset("rajaongkir")
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			$arr = json_decode($response);
			//print_r($response);
			//exit;
			//print_r($arr->rajaongkir->results[0]->costs[0]->cost[0]->value);
			$hasil = array("success"=>false,"response"=>"daerah tidak terjangkau!","harga"=>0);

			if(isset($arr->rajaongkir->status->code) AND $arr->rajaongkir->status->code == "200"){
				$hasil = array("success"=>false,"response"=>"daerah tidak terjangkau!","message"=>"service code tidak ada data","harga"=>0,"kurir"=>$kurir,"paket"=>$services,"origin"=>$arr->rajaongkir);
				for($i=0; $i<count($arr->rajaongkir->results[0]->costs); $i++){
					$harga = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value / $beratkg;
					$service = $arr->rajaongkir->results[0]->costs[$i]->service;
					$etd = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->etd;
					$etd = $etd != "" ? $etd : "0";
					$array = array(
						"dari"		=> $dari,
						"tujuan"	=> $tujuan,
						"kurir"		=> $kurir,
						"service"	=> $service,
						"kuririd"	=> $kuririd,
						"serviceid"	=> $serviceid,
						"cod"		=> $ser->cod,
						"harga"		=> $harga,
						"etd"		=> $etd,
						"update"	=> date("Y-m-d H:i:s"),
						"usrid"		=> $usrid
					);
					//print_r(json_encode($array)."<p/>");
					$idhistory = $this->getHistoryOngkir(array("dari"=>$dari,"tujuan"=>$tujuan,"kurir"=>$kurir,"service"=>$service),"id");
					if($idhistory > 0){
						$this->db->where("id",$idhistory);
						$this->db->update("historyongkir",$array);
					}else{
						if($harga > 0){ $this->db->insert("historyongkir",$array); }
					}

					if($services != ""){
						if(strcasecmp($service,$services) == 0){
							$hasil = array(
								"success"	=> true,
								"dari"		=> $dari,
								"tujuan"	=> $tujuan,
								"kurir"		=> $kurir,
								"service"	=> $service,
								"kuririd"	=> $kuririd,
								"serviceid"	=> $serviceid,
								"cod"		=> $ser->cod,
								"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
								"etd"		=> $etd,
								"update"	=> date("Y-m-d H:i:s"),
								"hargaperkg"=> $harga
							);
						}else{
							if($kurir == "jne"){
								if($services == "REG"){
									if(strcasecmp($service,"CTC") == 0){
										$hasil = array(
											"success"	=> true,
											"dari"		=> $dari,
											"tujuan"	=> $tujuan,
											"kurir"		=> $kurir,
											"service"	=> $service,
											"kuririd"	=> $kuririd,
											"serviceid"	=> $serviceid,
											"cod"		=> $ser->cod,
											"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
											"etd"		=> $etd,
											"update"	=> date("Y-m-d H:i:s"),
											"hargaperkg"=> $harga
										);
									}
								}elseif($services == "YES"){
									if(strcasecmp($service,"CTCYES") == 0){
										$hasil = array(
											"success"	=> true,
											"dari"		=> $dari,
											"tujuan"	=> $tujuan,
											"kurir"		=> $kurir,
											"service"	=> $service,
											"kuririd"	=> $kuririd,
											"serviceid"	=> $serviceid,
											"cod"		=> $ser->cod,
											"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
											"etd"		=> $etd,
											"update"	=> date("Y-m-d H:i:s"),
											"hargaperkg"=> $harga
										);
									}
								}else{
									
								}
							}
						}
					}else{
						$etd = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->etd;
						$etd = $etd != "" ? $etd : "0";
						$hasil = array(
							"success"	=> true,
							"dari"		=> $dari,
							"tujuan"	=> $tujuan,
							"kurir"		=> $kurir,
							"service"	=> $arr->rajaongkir->results[0]->costs[$i]->service,
							"kuririd"	=> $kuririd,
							"serviceid"	=> $serviceid,
							"cod"		=> $ser->cod,
							"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
							"etd"		=> $etd,
							"update"	=> date("Y-m-d H:i:s"),
							"hargaperkg"=> $harga
						);
					}
				}
			}
			//echo "dari: ".$dari.", tujuan: ".$tujuan.", berat: ".$berat.", kurir: ".$kurir."<br/>&nbsp;<br/>";
			return $hasil;
		}
	}

	// USABLE FUNCTION
	function elapsed($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'tahun',
			'm' => 'bulan',
			'w' => 'pekan',
			'd' => 'hari',
			'h' => 'jam',
			'i' => 'menit',
			's' => 'detik',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v;
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' yg lalu' : 'baru saja';
	}
	function encode($string){
		return $this->encryption->encrypt($string);
	}
	function decode($string){
		return $this->encryption->decrypt($string);
	}

	function getLabelCOD($cod=1){
		switch($cod){
			case 0: $label = "<span class='label tooltip'>Rekber Saja<span class='tooltiptext'>produk ini hanya bisa dibeli melalui rekber BELIWARGA</span></span>";
			break;
			case 1: $label = "<span class='label tooltip'>Rekber<span class='tooltiptext'>produk ini bisa dibeli melalui rekber BELIWARGA</span></span><span class='label tooltip'>COD<span class='tooltiptext'>produk ini bisa dibeli langsung dengan bertemu penjual tanpa melalui rekber</span></span>";
			break;
			case 2: $label = "<span class='label tooltip'>COD Saja<span class='tooltiptext'>produk ini hanya bisa dibeli langsung dengan bertemu penjual</span></span>";
			break;
		}
		return $label;
	}
	function potong($str,$max,$after=""){
		if(strlen($str) > $max){
			$str = substr($str, 0, $max);
			$str = rtrim($str).$after;
		}
		return $str;
	}
	function formUang($format){
		$format = floatval($format);
		$result= number_format($format,0,",",".");
		return $result;
	}
	function ubahTgl($format, $tanggal="now", $bahasa="id"){
		$en = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		$id = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

		return str_replace($en,$$bahasa,date($format,strtotime($tanggal)));
	}
	function arrEnc($arr,$type="encode"){
		if($type == "encode"){
			$result = base64_encode(serialize($arr));
		}else{
			$result = unserialize(base64_decode($arr));
		}
		return $result;
	}
	function starRating($star=1){
		$star = "<i class='fa fa-star'></i>";
		$staro = "<i class='fa fa-star-o'></i>";
		$starho = "<i class='fa fa-star-half-o'></i>";
		if($star == 1){
			$hasil = $star.$staro.$staro.$staro.$staro;
		}
	}
	function createPagination($rows,$page,$perpage=10,$function="refreshTabel"){
		$tpages = ceil($rows/$perpage);
		$reload = "";
        $adjacents = 2;
		$prevlabel = "&lsaquo;";
		$nextlabel = "&rsaquo;";
		$out = "<div class=\"pagination\">";
		// previous
		if ($page == 1) {
			$out.= "";
		} else {
			$out.="<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(1)\">&laquo;</a>\n";
			$out.="<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(".($page - 1).")\">".$prevlabel."</a>\n";
		}
		$pmin=($page>$adjacents)?($page - $adjacents):1;
		$pmax=($page<($tpages - $adjacents))?($page + $adjacents):$tpages;
		for ($i = $pmin; $i <= $pmax; $i++) {
			if ($i == $page) {
				$out.= "<a href=\"javascript:void(0)\" class='item active'>".$i."</a>\n";
			} elseif ($i == 1) {
				$out.= "<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(".$i.")\">".$i."</a>\n";
			} else {
				$out.= "<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(".$i.")\">".$i. "</a>\n";
			}
		}

		// next
		if ($page < $tpages) {
			$out.= "<a href=\"javascript:void(0)\" onclick=\"".$function."(".($page + 1).")\" class='item'>".$nextlabel."</a>\n";
		}
		if($page < ($tpages - $adjacents)) {
			$out.= "<a href=\"javascript:void(0)\" onclick=\"".$function."(".$tpages.")\" class='item'>&raquo;</a>\n";
		}
		$out.= "</div>";
		return $out;
	}
}
