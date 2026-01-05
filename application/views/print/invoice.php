<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?=base_url("assets/css/bootstrap.min.css")?>">
	<link rel="stylesheet" href="<?=base_url("assets/css/font-awesome.min.css")?>">
	<link rel="stylesheet" href="<?=base_url("assets/css/util.min.css")?>">
	<link rel="stylesheet" href="<?=base_url("cdn/assets/css/minmin.css?v=".time())?>">
	<style type="text/css">
		@media screen {
			.nota{
				max-width: 800px;
				margin: auto;
			}
			.footer{
				margin-top: 10%;
			}
		}

		@media print {
			.nota{
				max-width: 100%;
			}
			.bg-dark th{
				background-color: #343a40!important;
				color: #fff;
			}
			.footer{
				position: absolute;
				bottom: 0;
				left: 0;
				font-size: 10pt;
			}
		}
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
		.invoice{
			letter-spacing: 2px;
			font-size: 28px;
			text-align: right;
		}
		.text-upper{
			text-transform: uppercase;
		}
	</style>
</head>
<body onload="setTimeout(function(){window.print();setTimeout(function(){window.close();},1000);},1000);"><!--  -->
	<div class="nota">
		<?php
			$trxid = (isset($_GET["id"])) ? intval($_GET["id"]) : 0;
            $this->db->where("id",$trxid);
			if(isset($_SESSION["usrid"])){
				$this->db->where("usrid",$_SESSION["usrid"]);
			}else{
				$this->db->where("usrid_temp",$_SESSION["usrid_temp"]);
			}
            $db = $this->db->get("transaksi");
			foreach($db->result() as $trx){
				//$trx = $this->func->getTransaksi($trxid,"semua");
				$byr = $this->func->getBayar($trx->idbayar,"semua");
				//$profil = $this->func->getProfil($trx->usrid,"semua","usrid");
				$set = $this->func->globalset("semua");
				$user = isset($_SESSION["usrid"]) ? $this->func->getUser($trx->usrid,"semua") : $this->func->getUserTemp($trx->usrid_temp,"semua");
				
				if($trx->digital == 0){
					$alamat = $this->func->getAlamat($trx->alamat,"semua");
					$kec = $this->func->getKec($alamat->idkec,"semua");
					$kab = $this->func->getKab($kec->idkab,"semua");
					$prov = $this->func->getProv($kab->idprov,"nama");
					$lkp = $kec->nama." ".$kab->nama." ".$prov." ".$alamat->kodepos;
				}
				$kontak = isset($user->username) ? $user->username : "-";
				$kontak = ($user->nohp != "") ? $user->nohp : $kontak;
				$kontak = " (".$kontak.")";
		?>
			<div class="header row m-lr-0 m-t-10 m-b-40">
				<div class="col-6 logo"><img src="<?=base_url("cdn/assets/img/".$set->logo)?>" /></div>
				<div class="col-6 invoice font-bold">INVOICE</div>
				<!--<div class="col-4"></div>-->
			</div>
			<div class="m-b-30">
				<div class="col-8">
					<table>
						<tr>
							<td>No. Invoice</td>
							<th>: <?=$byr->invoice?></th>
						</tr>
						<tr>
							<td>No. Transaksi</td>
							<th>: #<?=$trx->orderid?></th>
						</tr>
						<tr>
							<td>Pembeli</td>
							<th>: <?=strtoupper(strtolower($user->nama)).$kontak?></th>
						</tr>
						<tr>
							<td>Tanggal Pembelian &nbsp; </td>
							<th>: <?=$this->func->ubahTgl("D, d M Y",$trx->tgl)?></th>
						</tr>
					</table>
				</div>
			</div>
			<div class="m-b-20">
				<table class="table">
					<tr class="bg-dark text-white text-upper">
						<th>Kode</th>
						<th>Nama Produk</th>
						<th>QTY</th>
						<th class="text-right">Harga Satuan</th>
						<th class="text-right">Total Harga</th>
					</tr>
					<?php
						//$this->db->select("SUM(jumlah) as jml,idproduk,harga,jumlah,diskon
						$this->db->where("idtransaksi",$trx->id);
						$db = $this->db->get("transaksiproduk");
						$total = 0;
						$totalqty = 0;
						$ket = "";
						foreach($db->result() as $r){
							$prod = $this->func->getProduk($r->idproduk,"semua");
							$total += ($r->diskon+$r->harga)*$r->jumlah;
							$berat = !empty($prod) ? $prod->berat*$r->jumlah : 0;
							$kode = !empty($prod) ? $prod->kode : 0;
							$nama = !empty($prod) ? $prod->nama : "Produk dihapus";
							$totalqty += $r->jumlah;
							$ket .= $r->keterangan."<br/>";
							$vari = ($r->variasi != 0) ? $this->func->getVariasi($r->variasi,"semua","id") : "";
							$variasi = (is_object($vari)) ? $prod->variasi." ".$this->func->getWarna($vari->warna,"nama") : "";
							$variasi .= (is_object($vari) AND ($vari->size > 0)) ? ", ".$prod->subvariasi." ".$this->func->getSize($vari->size,"nama") : "";
							echo "
								<tr>
									<td>".$kode."</td>
									<td>".$nama."<br/><small>".$variasi."</small></td>
									<td class=\"text-center\">".$r->jumlah."</td>
									<td class=\"text-right\">Rp".$this->func->formUang($r->diskon+$r->harga)."</td>
									<td class=\"text-right\">Rp".$this->func->formUang((($r->diskon+$r->harga)*$r->jumlah))."</td>
								</tr>
							";
						}
						$beratkg = $trx->berat/1000;
						$beratkg = round($beratkg,2,PHP_ROUND_HALF_UP);
					?>
					<tr>
						<th colspan=2></th>
						<th colspan=2>TOTAL HARGA<br/><small>(<?=$totalqty?> BARANG)</small></th>
						<th class="text-right">Rp<?=$this->func->formUang($total)?></th>
					</tr>
					<tr class="noborder">
						<td colspan=2 rowspan=5>
							<?php if(!empty($ket)){ ?>
								KETERANGAN:<br/>
								<small><?=$ket?></small>
							<?php } ?>
						</td>
						<td colspan=2>Total Ongkir (<?=$beratkg?>kg)</th>
						<td class="text-right">Rp<?=$this->func->formUang($trx->ongkir)?></td>
					</tr>
					<?php if($byr->biaya_cod > 0){ ?>
					<tr class="noborder">
						<td colspan=2>Biaya COD</td>
						<td class="text-right">Rp<?=$this->func->formUang($trx->biaya_cod)?></td>
					</tr>
					<?php } ?>
					<?php if($byr->kodebayar > 0){ ?>
					<tr class="noborder">
						<td colspan=2>Kode Bayar</td>
						<td class="text-right">Rp<?=$this->func->formUang($byr->kodebayar)?></td>
					</tr>
					<?php } ?>
					<?php if($byr->diskon > 0){ ?>
					<tr class="noborder">
						<td colspan=2>Diskon</td>
						<td class="text-right">-Rp<?=$this->func->formUang($byr->diskon)?></td>
					</tr>
					<?php } ?>
					<tr class="dash">
						<th colspan=2>Grand Total</th>
						<th class="text-right">Rp<?=$this->func->formUang($total+$trx->biaya_cod+$trx->ongkir+$byr->kodebayar-$byr->diskon)?></th>
					</tr>
				</table>
			</div>
			<?php 
				if($trx->digital == 0){
					$gudang = $this->func->getGudang($trx->gudang,"semua");
					$kota = ($trx->gudang > 0) ? $this->func->getKab($gudang->idkab,"semua") : $this->func->getKab($set->kota,"semua");
					$pengirim = ($trx->gudang > 0) ? $set->nama." - ".$gudang->nama : $set->nama;
					$nomor = ($trx->gudang > 0) ? $gudang->kontak_nohp : $set->notelp;
					$kota = $kota->tipe." ".$kota->nama;
			?>
				<div class="m-t-30 p-tb-20" style="border-top: 1px solid #dee2e6;">
					<div class="row">
						<div class="col-6">
							<?php if($trx->dropship != ""){ ?>
								<div class="m-b-10">Pengirim:</div>
								<b><?=strtoupper(strtolower($trx->dropship))?></b><br/>(<?=$trx->dropshipnomer?>)<br/>
							<?php }else{ ?>
								<div class="m-b-10">Pengirim:</div>
								<b><?=strtoupper(strtolower($pengirim))?></b><br/>(<?=$nomor?>)<br/>
							<?php } ?>
							<?=$kota?>
							<div class="p-t-20">
								<div class="m-b-10">Kurir:</div>
								<b><?=$this->func->getKurir($trx->kurir,"nama")." - ".$this->func->getPaket($trx->paket,"nama")?></b>
							</div>
						</div>
						<div class="col-6">
							<div class="m-b-10">Alamat Pengiriman:</div>
							<b><?=strtoupper(strtolower($alamat->nama))?></b> (<?=$alamat->nohp?>)<br/>
							<?=$alamat->alamat."<br/>".$lkp?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="footer">
				Invoice ini sah dan diproses oleh komputer<br/>
				Silakan hubungi <b>Admin <?=ucwords($set->nama)?></b> apabila kamu membutuhkan bantuan.
			</div>
		<?php
			}
		?>
	</div>
</body>
</html>