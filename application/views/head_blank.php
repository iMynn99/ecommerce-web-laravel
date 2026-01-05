<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$set = $this->func->globalset("semua");
$nama = (isset($titel)) ? $set->nama." &#8211; ".$titel: $set->nama." &#8211; ".$set->slogan;
$nama = ($this->func->demo() == true) ? $nama." App by @masbil_al 085691257411" : $nama;
$headerclass = (isset($titel)) ? "header-v4" : "";
$keranjang = (isset($_SESSION["usrid"]) AND $_SESSION["usrid"] > 0) ? $this->func->getKeranjang() : 0;
$keyw = $this->db->get("kategori");
$keywords = "";
$img = (isset($img)) ? $img : base_url("cdn/assets/img/".$set->favicon);
$url = (isset($url)) ? $url : site_url();
$cari = (isset($_GET["cari"])) ? $this->func->clean($_GET["cari"]) : "";
$desc = (isset($desc)) ? $desc : "Aplikasi toko online ".$nama;
$tema = (isset($set->tema)) ? $set->tema: 0;
$tema = $this->func->tema($tema);
foreach($keyw->result() as $key){ $keywords .= ",".$key->nama; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=$nama?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/png" href="<?=base_url("cdn/assets/img/".$set->favicon)?>"/>
	<meta name="google-site-verification" content="G35UyHn6lX6mRzyFws0NJYYxHQp_aejuAFbagRKCL7c" />
	<meta name="description" content="<?=$desc?>" />
	<!--  Social tags      -->
	<meta name="keywords" content="Aplikasi toko online <?=$nama?>">
	<meta name="description" content="<?=$desc?>">
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="<?=$nama?>">
	<meta itemprop="description" content="<?=$desc?>">
	<meta itemprop="image" content="<?=$img?>">
	<!-- Twitter Card data -->
	<meta name="twitter:card" content="product">
	<meta name="twitter:site" content="@masbil_al">
	<meta name="twitter:title" content="<?=$nama?>">
	<meta name="twitter:description" content="<?=$desc?>">
	<meta name="twitter:creator" content="@masbil_al">
	<meta name="twitter:image" content="<?=$img?>">
	<!-- Open Graph data -->
	<meta property="fb:app_id" content="<?=$set->fb_pixel?>">
	<meta property="og:site_name" content="<?=$nama?>" />
	<?php if(isset($fbproduk)){ ?>
	<meta property="og:title" content="<?=$fbproduk['nama']?>" />
	<meta property="og:url" content="<?=$fbproduk['url']?>" />
	<meta property="og:image" content="<?=$fbproduk['img']?>" />
	<meta property="og:description" content="<?=$fbproduk['deskripsi']?>" />
	<meta property="product:brand" content="Facebook">
	<meta property="product:availability" content="in stock">
	<meta property="product:condition" content="new">
	<meta property="product:price:amount" content="<?=$fbproduk['harga']?>">
	<meta property="product:price:currency" content="IDR">
	<meta property="product:retailer_item_id" content="<?=$fbproduk['url']?>">
	<meta property="product:item_group_id" content="<?=$fbproduk['id']?>">
	<?php }else{ ?>
	<meta property="og:title" content="<?=$nama?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?=$url?>" />
	<meta property="og:image" content="<?=$img?>" />
	<meta property="og:description" content="<?=$desc?>" />
	<?php } ?>

	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/select2/select2.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/select2/select2-bootstrap4.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/slick/slick.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/slick/slick-theme.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/swal/sweetalert2.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/datatables/datatables.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/util.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/main.css?v='.time()) ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/responsive.css?v='.time()) ?>">
	<!--<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/color-themes.css?v='.time()) ?>">-->

	<!--===============================================================================================-->
	<script type="text/javascript" src="<?= base_url('assets/js/jquery-3.5.1.min.js') ?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>

	<!-- GENERATED CUSTOM COLOR -->
	<style rel="stylesheet">
		.btn-primary{
			background-image: <?=$tema->light?>;
		}
		.badge-primary, .bg-primary, .hov-primary:hover, .btn-primary:hover{
			background-image: <?=$tema->hover?>;
		}
		a.text-primary:hover, .text-hov-primary:hover, .text-primary{
			background: <?=$tema->hover?>;
			background-clip: text;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
		a.text-primary{
			background: <?=$tema->light?>;
			background-clip: text;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
		.bg-foot,.playstore-section{
			background-color: <?=$tema->foot?>;
		}
		.bg-foot-gradient{
			background-image: <?=$tema->foot_gradient?>;
		}
		.sec-title{
			font-family: poppins-black;
			text-transform: uppercase;
			background: <?=$tema->hover?>;
			background-clip: text;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
		.title{
			font-family: poppins-bold;
			text-transform: uppercase;
			background: <?=$tema->hover?>;
			background-clip: text;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
		.testimoni-item{
			background-image: <?=$tema->testimoni?>;
		}
		.form-header button{
			background: <?=$tema->light?>;
			color: #fff;
		}
		.bottom-bar{
			background: <?=$tema->hover?>;
			color: #fff;
			box-shadow: 0px 0px 6px 2px <?=$tema->hover?>;
		}
		.form-header button:hover{
			background: <?=$tema->hover?>;
			color: #fff;
		}
		.bg-tipis{
			background: <?=$tema->testimoni?>;
		}
		.toaster{
			background-image: <?=$tema->testimoni?>;
		}
		.pagination .item{
			background-image: <?=$tema->light?>;
		}
		.pagination .item.active,
		.pagination .item:hover{
			background-image: <?=$tema->hover?>;
		}
		.progress-checkout .wrap.active .titles,
		.progress-checkout .wrap.active .fas{
			background: <?=$tema->hover?>;
			background-clip: text;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
	</style>
</head>
<body>