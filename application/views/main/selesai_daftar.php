<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="divider"></div>
<div class="divider"></div>
<div class="alert alert-green text-center">
	Selamat, Anda berhasil mendaftar.<br/>
	<!--  Untuk dapat menggunakan Akun Anda, silahkan klik link konfirmasi yang sudah Kami kirimkan ke Email dan Nomor Whatsapp Anda : -->
	<br><b><?php echo $email; ?></b><br/>
	<b><?php echo $nowa; ?></b><br/><br>
	<!-- Cek kembali di folder SPAM apabila belum ada Email Verifikasi masuk. -->
	<br/><br/>
	Untuk Login <a href="<?php echo site_url("home/signin"); ?>">Klik Disini</a>
</div>
<div class="divider"></div>
<div class="divider"></div>
