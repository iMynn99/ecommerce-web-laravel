<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/util.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">
	<style type="text/css">
		table tr.top th, table tr.top th *,
		table tr.top td, table tr.top td * {
			vertical-align: top;
		}
		table tr th,
		table tr td{
			padding: 4px 0;
		}
		table tr.noborder th,
		table tr.noborder td{
			border-color: transparent;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		table tr.dash th,
		table tr.dash td{
			border-style: dashed;
			border-bottom: none;
			border-left: none;
			border-right: none;
		}
		.logo img{
			max-width:100%;
			max-height: 48px;
		}
		.nota{
			border: 2px dashed black;
			padding: 24px;
			max-width: 60%;
		}
		.produk{
			max-width: 60%;
		}
	</style>
</head>
<body onload="setTimeout(function(){window.print();setTimeout(function(){window.close();},1000);},1000);">
<!--  -->
	<div class="nota">
		<?php
			$trxid = (isset($_GET["id"])) ? intval($_GET["id"]) : 0;
			$resmar = true;
			if($trxid != 0){
				$trx = $this->func->getTransaksi($trxid,"semua");
				if($trx->resimarketplace == ""){
					$resmar = false;
					$set = $this->func->globalset("semua");
					$alamat = $this->func->getAlamat($trx->alamat,"semua");
					$kec = $this->func->getKec($alamat->idkec,"semua");
					$kab = $this->func->getKab($kec->idkab,"semua");
					$prov = $this->func->getProv($kab->idprov,"nama");
					$lkp = $kec->nama.", ".$kab->tipe." ".$kab->nama.", ".$prov." ".$alamat->kodepos;
					$kurir = $this->func->getKurir($trx->kurir,"semua");
					$paket = $this->func->getPaket($trx->paket,"nama");
					$lokasi = ($trx->dropship == "") ? "text-right" : "text-center";
					$gudang = $this->func->getGudang($trx->gudang,"semua");
					$kota = ($trx->gudang > 0) ? $this->func->getKab($gudang->idkab,"semua") : $this->func->getKab($set->kota,"semua");
					$kota = $kota->tipe." ".$kota->nama;
					$pengirim = ($trx->gudang > 0) ? $set->nama." - ".$gudang->nama : $set->nama;
					$nomer = ($trx->gudang > 0) ? $gudang->kontak_nohp : $set->notelp;
		?>
			<div class="row m-b-20">
				<?php if($trx->dropship == ""){ ?>
					<div class="col-6">
						<div class="logo">
							<img src="<?=base_url("assets/img/".$this->func->globalset("logo"))?>" />
						</div>
					</div>
				<?php } ?>
				<?php if(file_exists("assets/img/kurir/".$kurir->rajaongkir.".png") AND $kurir->rajaongkir != ""){ ?>
					<div class="col-6">
						<div class="logo <?=$lokasi?>">
							<img src="<?=base_url("assets/img/kurir/".$kurir->rajaongkir.".png")?>" />
						</div>
					</div>
				<?php } ?>
			</div>
			<hr/>
			<div class="p-tb-12 fs-20 font-bold text-center text-danger"><?=strtoupper(strtolower($kurir->nama." - ".$paket))?></div>
			<?php if($trx->cod == 1){ ?>
			<div class="m-t--10 p-b-12 fs-18 font-bold text-center">BAYAR DI TEMPAT (COD)</div>
			<?php } ?>
			<hr/>
			<div class="row p-t-20">
				<?php if($trx->dropship == ""){ ?>
				<div class="col-6">
					<div class="m-b-12">Dari:</div>
					<b><?=$pengirim?></b> <br/>
					<?=$kota?><br/>
					Telp. <b><?=$nomer?></b>
				</div>
				<?php }else{ ?>
				<div class="col-6">
					<div class="m-b-12">Dari:</div>
					<b><?=strtoupper(strtolower($trx->dropship))?></b> <br/>
					<?=$kota?><br/>
					Telp. <b><?=$trx->dropshipnomer?></b>
				</div>
				<?php } ?>
				<div class="col-6">
					<div class="m-b-12">Kepada:</div>
					<b><?=strtoupper(strtolower($alamat->nama))?></b><br/>
					<?=$alamat->alamat."<br/>".$lkp?><br/> 
					Telp. <b><?=$alamat->nohp?></b>
				</div>
			</div>
		<?php
				}else{
					$resmar = true;
					if (strpos($trx->resimarketplace, 'pdf') !== false) {
						redirect($trx->resimarketplace);
					}else{
		?>
			<img src="<?=$trx->resimarketplace?>" class="w-full" />
		<?php
					}
				}
			}
		?>
	</div>
	<?php if($trxid != 0 AND $resmar == false){ ?>
	<div class="p-tb-20 produk">
		<table class="table table-sm fs-12">
			<tr>
				<th>#</th>
				<th>Nama Produk</th>
				<th>SKU</th>
				<th>Variasi</th>
				<th>Qty</th>
			</tr>
			<?php
				$this->db->where("idtransaksi",$trxid);
				$db = $this->db->get("transaksiproduk");
				$no = 1;
				foreach($db->result() as $r){
					$prod = $this->func->getProduk($r->idproduk,"semua");
					$var = $this->func->getVariasi($r->variasi,"semua","id");
					$variasi = ($var->warna > 0) ? $prod->variasi." ".$this->func->getVariasiWarna($var->warna,"nama") : "";
					$variasi .= ($var->size > 0) ? "<br/>".$prod->subvariasi." ".$this->func->getVariasiSize($var->size,"nama") : "";
					echo "
						<tr>
							<td>".$no."</td>
							<td>".$prod->nama."</td>
							<td>".$prod->kode."</td>
							<td>".$variasi."</td>
							<td>".$r->jumlah."</td>
						</tr>
					";
				}
			?>
		</table>
	</div>
	<?php } ?>
</body>
</html>