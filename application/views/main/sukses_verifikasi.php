<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$id = (isset($_SESSION['id'])) ? $_SESSION['id'] : 0;
?>
<?php if(isset($belumverif) AND $belumverif == true){ ?>
	<div class="wrapper" id="load">
		<div id="main" class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;">
			Maaf, akun Anda belum aktif, silahkan melakukan verifikasi melalui link yang kami kirim ke alamat email dan nomor whatsapp Anda.<br/>
			atau Anda belum menerima email verifikasi?<br/><br/>
			<a href="javascript:void(0)" class="btn btn-primary p-tb-10" onclick="kirimUlang()"><i class="fas fa-sync-alt"></i> &nbsp;KIRIM ULANG</a>
			<br/><br/>
			Untuk Login <a href="<?php echo site_url("home/signin"); ?>">Klik Disini</a>
		</div>
		<div id="sukses" class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;display:none;">
			Silahkan cek kembali email Anda, kami sudah mengirimkan ulang link verifikasi ke alamat email dan nomor whatsapp Anda. Apabila belum menerima email dari kami, silahkan cek folder <b>spam</b>.<br/>atau<br/>
			<a href="javascript:void(0)" class="btn btn-primary p-tb-10" onclick="kirimUlang()"><i class="fas fa-sync-alt"></i> &nbsp;KIRIM ULANG</a>
			<br/><br/>
			Untuk Login <a href="<?php echo site_url("home/signin"); ?>">Klik Disini</a>
		</div>
		<div id="gagal" class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;display:none;">
			Maaf, kami sedang mengalami kendala pada server kami. Silahkan ulangi beberapa saat lagi.<br/><br/>
			<a href="javascript:void(0)" class="btn btn-primary p-tb-10" onclick="kirimUlang()"><i class="fas fa-sync-alt"></i> &nbsp;KIRIM ULANG</a>
			<br/><br/>
			Untuk Login <a href="<?php echo site_url("home/signin"); ?>">Klik Disini</a>
		</div>
	</div>
<?php
	}else{
		if($selesai == false){
?>
	<div class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;">
		<form method="POST">
			<input type="hidden" name="verify" value="<?=$_GET["verify"]?>" />
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
			Untuk memverifikasi akun Anda, silahkan klik tombol dibawah.<br/><br>
			<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> &nbsp;VERIFIKASI</button>
		</form>
	</div>
<?php 	}else{ ?>
	<div class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;">
		Selamat! Akun Anda telah aktif.<br/><br/>
		Untuk Login :<br/>
		<a href="<?php echo site_url("home/signin"); ?>" class="btn btn-success">Klik Disini</a>
	</div>
<?php
		}
	}
?>

<script type="text/javascript">
	function kirimUlang(){
		$(".fa-sync-alt").addClass("fa-spin");
		$.post("<?php echo site_url("home/signup/kirimulang"); ?>",{"id":"<?php echo $this->func->encode($id); ?>",[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			$(".fa-sync-alt").removeClass("fa-spin");
			updateToken(data.token);
			if(data.success == true){
				$("#main").hide();
				swal.fire("Berhasil!","Berhasil mengirimkan link verifikasi, silahkan cek inbox email dan nomor whatsapp Anda, cek folder spam apabila belum ada email masuk di inbox.","success").then((value) =>{
					window.location.href = "<?php echo site_url("home/signout"); ?>";
				});
			//	$("#sukses").show();
			}else{
				$("#main").hide();
				swal.fire("Gagal!","Gagal mengirimkan link verifikasi ke alamat email Anda, pastikan alamat email benar	.","error");
			}
		});
	}
</script>
