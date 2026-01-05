
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<a href="<?php echo site_url("manage/pesanan?tab=digital"); ?>" class="text-primary">
				Pesanan Produk Digital
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<span class="text-dark">
				Akses Produk
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->
	<form class="p-b-85">
    <div class="container p-t-50 p-b-50">
        <h4 class="text-primary font-bold p-b-20">
            Order ID <span class="text-success">#<?php echo $transaksi->orderid; ?></span>
        </h4>
        <div class="section p-lr-24 p-tb-20 m-lr-0-xl p-lr-15-sm">
            <div class="row">
            <div class="col-md-6 p-b-10 p-t-10">
                <p class="m-b-10">
                Waktu Pemesanan:<br/>
                <i class="font-medium"><?php echo $this->func->ubahTgl("d M Y H:i",$transaksi->tgl); ?> WIB</i>
                </p>
                <p class="">
                Waktu Pembayaran:<br/>
                <i class="font-medium"><?php echo $this->func->ubahTgl("d M Y H:i",$bayar->tgl); ?> WIB</i>
                </p>
            </div>
            <div class="col-md-6">
                <?php if($transaksi->status == 0){ ?>
                    <!-- Belum Dibayar -->
                    <p class="bg-warning m-b-10 status-pesanan">Belum Dibayar</p>
                    <p class="m-b-5">segera lakukan pembayaran maks. 1x24jam untuk menghindari pembatalan otomatis.</p>
                <?php }elseif($transaksi->status == 3){ ?>
                <!-- Selesai -->
                <p class="bg-success m-b-10 status-pesanan">Lunas</p>
                    <p class="m-b-5">pembayaran telah masuk.</p>
                <?php }elseif($transaksi->status == 4){ ?>
                <!-- Selesai -->
                <p class="bg-danger m-b-10 status-pesanan">Pesanan Dibatalkan</p>
                    <p class="m-b-5">pesanan dibatalkan karena <?php echo $transaksi->keterangan; ?></p>
                <?php } ?>
            </div>
            </div>
        </div>
        <h4 class="text-primary font-bold p-t-30 p-b-20">
            Akses Produk Pesanan
        </h4>
        <div class="produk">
            <?php
                $this->db->where("idtransaksi",$transaksi->id);
                $db = $this->db->get("transaksiproduk");
                $total = 0;
                foreach($db->result() as $res){
                    $total += $res->harga * $res->jumlah;
                    $produk = $this->func->getProduk($res->idproduk,"semua");
                    $variasee = $this->func->getVariasi($res->variasi,"semua");
                    $variasi = ($res->variasi != 0 AND isset($variasee->warna)) ? $this->func->getWarna($variasee->warna,"nama")." ".$produk->subvariasi." ".$this->func->getSize($variasee->size,"nama") : "";
                    $variasi = ($res->variasi != 0 AND isset($variasee->warna)) ? "<small class='text-primary'>".$produk->variasi.": ".$variasi."</small>" : "";
            ?>
                <div class="row p-b-30 p-r-10 produk-item m-lr-0">
                    <div class="col-4 col-md-3 m-b-24">
                        <div class="img" style="background-image:url('<?php echo $this->func->getFoto($res->idproduk,"utama"); ?>')" alt="IMG"></div>
                    </div>
                    <div class="col-8 col-md-9">
                        <p class="font-medium"><?php echo $produk->nama; ?></p>
                        <?php echo $variasi; ?>
                        <p>Rp <?php echo $this->func->formUang($res->harga); ?> <span class="fs-14">x <?php echo $res->jumlah; ?></span></p>
                    </div>
                    <?php if($transaksi->status == 3){ ?>
                    <div class="font-medium col-12">
                        Akses Produk:<br/>
                        <a href="<?=$produk->akses?>" target="_blank"><?=$produk->akses?></a>
                    </div>
                    <?php } ?>
                </div>
                <?php
                        }
                ?>
        </div>
	</div>
	</form>
