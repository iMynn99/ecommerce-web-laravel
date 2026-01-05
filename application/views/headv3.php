<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$set = $this->func->globalset("semua");
$nama = (isset($titel)) ? $set->nama." &#8211; ".$titel: $set->nama." &#8211; ".$set->slogan;
$nama = ($this->func->demo() == true) ? $nama." App by @masbil_al 085691257411" : $nama;
$headerclass = (isset($titel)) ? "header-v4" : "";
$keranjang = (isset($_SESSION["usrid"]) || isset($_SESSION["usrid_temp"])) ? $this->func->getKeranjang() : 0;
$wishlist = (isset($_SESSION["usrid"]) || isset($_SESSION["usrid_temp"])) ? $this->func->getWishlistCount() : 0;
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
    <link rel="shortcut icon" type="image/png" href="<?=base_url("cdn/assets/img/".$set->favicon)?>" />
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
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/aos.css') ?>">
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/vendor/datatables/datatables.min.js') ?>"></script>

    <!-- GENERATED CUSTOM COLOR -->
    <style rel="stylesheet">
    .btn-primary {
        background-image: <?=$tema->light?>;
    }

    .badge-primary,
    .bg-primary,
    .hov-primary:hover,
    .btn-primary:hover {
        background-image: <?=$tema->hover?>;
    }

    a.text-primary:hover,
    .text-hov-primary:hover,
    .text-primary {
        background: <?=$tema->hover?>;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    a.text-primary {
        background: <?=$tema->light?>;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .bg-foot,
    .playstore-section {
        background-color: <?=$tema->foot?>;
    }

    .bg-foot-gradient {
        background-image: <?=$tema->foot_gradient?>;
    }

    .sec-title {
        font-family: poppins-black;
        text-transform: uppercase;
        background: <?=$tema->hover?>;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .title {
        font-family: poppins-bold;
        text-transform: uppercase;
        background: <?=$tema->hover?>;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .testimoni-item {
        background-image: <?=$tema->testimoni?>;
    }

    .form-header button {
        background: <?=$tema->light?>;
        color: #fff;
    }

    .bottom-bar {
        background: <?=$tema->hover?>;
        color: #fff;
        box-shadow: 0px 0px 6px 2px <?=$tema->hover?>;
    }

    .form-header button:hover {
        background: <?=$tema->hover?>;
        color: #fff;
    }

    .bg-tipis {
        background: <?=$tema->testimoni?>;
    }

    .toaster {
        background-image: <?=$tema->testimoni?>;
    }

    .pagination .item {
        background-image: <?=$tema->light?>;
    }

    .pagination .item.active,
    .pagination .item:hover {
        background-image: <?=$tema->hover?>;
    }

    .progress-checkout .wrap.active .titles,
    .progress-checkout .wrap.active .fas {
        background: <?=$tema->hover?>;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    </style>
</head>

<body>

    <!-- Header -/->
	<?php if(isset($titel)){ ?>
	<div class="m-b-120"></div>
	<?php }else{ ?>
	<div class="m-b-100"></div>
	<?php } ?>
	-->

    <header class="header1">
        <nav class="navbar navbar-expand-lg navbar-light hidesmall top-menu m-b-24">
            <div class="container">
                <div class="col-md-3">
                    <a class="navbar-brand" href="<?=site_url()?>">
                        <img src="<?= base_url('cdn/assets/img/'.$set->logo) ?>" height="40" />
                    </a>
                </div>
                <div class="col-md-6">
                    <form class="search-top" action="<?=site_url("shop")?>">
                        <div class="form-header row">
                            <input type="text" class="col-10 typedtext" name="cari" value="<?=$cari?>" />
                            <button type="submit" class="col-2"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <a class="top-link p-r-10" href="<?=site_url('home/keranjang')?>">
                        <i class="fas fa-shopping-basket text-primary fs-28"></i> <b
                            class="badge badge-danger p-lr-8 jmlkeranjang"><?=$keranjang?></b>
                    </a>
                    <a class="top-link" href="<?=site_url('home/wishlist')?>">
                        <i class="fas fa-heart text-primary fs-28"></i> <b
                            class="badge badge-danger p-lr-8 wishlistcount"><?=$wishlist?></b>
                    </a>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-tipis fixed-top m-b-24" id="nav-search"
            style="display:none;">
            <div class="container">
                <div class="btn-block">
                    <form class="search-top" action="<?=site_url("shop")?>">
                        <div class="form-header row m-lr-0">
                            <input type="text" class="col-10 typedtext" name="cari" id="m-search" value="<?=$cari?>" />
                            <button type="submit" class="col-2"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-tipis m-b-24 hidesmall" id="navbar-sticky">
            <div class="container">
                <a class="navbar-brand showsmall" href="<?=site_url()?>">
                    <img src="<?= base_url('cdn/assets/img/'.$set->logo) ?>" height="40" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler"
                    aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?=site_url()?>"><i class="fas fa-home text-primary"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("kategori")?>">Kategori</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("blog")?>">Blog</a>
                        </li>
                        <?php if($this->func->cekLogin() != true){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("trackpesanan")?>">Cek Pesanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("konfirmasi")?>">Konfirmasi Pembayaran</a>
                        </li>
                        <?php }else{ ?>
                        <?php 
							$this->db->where("status",1);
							$this->db->limit(2);
							$page = $this->db->get("page");
							foreach($page->result() as $pg){
						?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("page/".$pg->slug)?>"><?=$pg->nama?></a>
                        </li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <?php if($this->func->cekLogin() != true){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("home/signin")?>">
                                <i class="fas fa-sign-in-alt text-primary"></i> Masuk / Daftar
                            </a>
                        </li>
                        <?php }else{ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url('manage/pesanan')?>"><i
                                    class="fas fa-box text-primary"></i> Pesanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url('manage')?>"><i
                                    class="fas fa-user-circle text-primary"></i> Akun</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:voi(0)" onclick="signoutNow()"><i
                                    class="fas fa-power-off text-danger"></i> Logout</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-tipis m-b-24 showsmall">
            <div class="container">
                <a class="navbar-brand" href="<?=site_url()?>">
                    <img src="<?= base_url('cdn/assets/img/'.$set->logo) ?>" height="40" />
                </a>
                <a class="navbar-toggler text-danger m-r--48" href="<?=site_url("home/wishlist")?>">
                    <i class="fas fa-heart"></i>
                </a>
                <button class="navbar-toggler text-primary" type="button" data-toggle="collapse"
                    data-target="#navbarToggler" onclick="$('#menu-show').toggle();$('#menu-hide').toggle()">
                    <i class="fas fa-align-right" id="menu-show"></i>
                    <i class="fas fa-times" id="menu-hide" style="display:none;"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?=site_url()?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("afiliasi")?>">K</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("blog")?>">Blog</a>
                        </li>
                        <?php if($this->func->cekLogin() != true){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("trackpesanan")?>">Cek Pesanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("konfirmasi")?>">Konfirmasi Pembayaran</a>
                        </li>
                        <?php }else{ ?>
                        <?php 
								$this->db->where("status",1);
								$this->db->limit(3);
								$page = $this->db->get("page");
								foreach($page->result() as $pg){
							?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("page/".$pg->slug)?>"><?=$pg->nama?></a>
                        </li>
                        <?php } ?>
                        <?php } ?>
                        <?php if($this->func->cekLogin() != true){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url("home/signin")?>">
                                <i class="fas fa-sign-in-alt text-primary"></i> Masuk / Daftar
                            </a>
                        </li>
                        <?php }else{ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=site_url('home/wishlist')?>"><i
                                    class="fas fa-heart text-danger"></i> &nbsp;Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:voi(0)" onclick="signoutNow()"><i
                                    class="fas fa-power-off text-danger"></i> &nbsp;Logout</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="bottom-bar showsmall">
        <div class="row">
            <?php if($this->func->cekLogin() != true){ ?>
            <div class="col" onclick="window.location.href='<?=site_url()?>'"><i
                    class="fas fa-home"></i><small>home</small></div>
            <div class="col" onclick="$('#nav-search').slideToggle(function(){$('#m-search').focus();})"><i
                    class="fas fa-search"></i><small>cari</small></div>
            <div class="col" onclick="window.location.href='<?=site_url('home/keranjang')?>'"><i
                    class="fas fa-shopping-basket"></i><b
                    class="badge badge-danger p-lr-8 jmlkeranjang"><?=$keranjang?></b><small>keranjang</small></div>
            <div class="col" onclick="window.location.href='<?=site_url('home/signin')?>'"><i
                    class="fas fa-user-circle"></i><small>akunku</small></div>
            <?php }else{ ?>
            <div class="col" onclick="$('#nav-search').slideToggle(function(){$('#m-search').focus();})"><i
                    class="fas fa-search"></i><small>cari</small></div>
            <div class="col" onclick="window.location.href='<?=site_url('home/keranjang')?>'"><i
                    class="fas fa-shopping-basket"></i><b
                    class="badge badge-danger p-lr-8 jmlkeranjang"><?=$keranjang?></b><small>keranjang</small></div>
            <div class="col" onclick="window.location.href='<?=site_url('manage/pesanan')?>'"><i
                    class="fas fa-box"></i><small>pesanan</small></div>
            <div class="col" onclick="window.location.href='<?=site_url('manage')?>'"><i
                    class="fas fa-user-circle"></i><small>akun</small></div>
            <?php } ?>
            <div class="col" onclick='$("#modalpilihpesan").modal()'><i class="fab fa-whatsapp"></i><b
                    class="badge badge-danger p-lr-4 notifchat" style="display:none">0</b><small>chat</small></div>
        </div>
    </div>