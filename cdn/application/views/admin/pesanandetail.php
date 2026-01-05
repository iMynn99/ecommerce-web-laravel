<?php
	$this->db->where("id",intval($_GET["theid"]));
	$db = $this->db->get("transaksi");
	$set = $this->func->globalset("semua");
	
	foreach($db->result() as $rs){
		$usr  = ($rs->usrid > 0) ? $this->func->getProfil($rs->usrid,"semua","usrid") : $this->func->getUserTemp($rs->usrid_temp,"semua");
		$alamat = $this->func->getAlamat($rs->alamat,"semua");
		$kurir = strtoupper($this->func->getKurir($rs->kurir,"nama"))." - ".strtoupper($this->func->getPaket($rs->paket,"nama"));
		$cod = ($rs->cod == 1) ? "<br/><span class='badge badge-warning' style='font-weight:normal'>Bayar Ditempat (COD)</span>" : "";
		$cod .= ($rs->dropship != "") ? "<br/><span class='badge badge-info' style='font-weight:normal'>Dropship</span>" : "";
		$cod .= ($rs->digital == 1) ? "<br/><span class='badge badge-primary' style='font-weight:normal'><i class='fas fa-cloud'></i> Produk Digital</span>" : "";

		// GUDANG 
		$gudang = $this->func->getGudang($rs->gudang,"semua");
		$kota = ($rs->gudang > 0) ? $this->func->getKab($gudang->idkab,"semua") : $this->func->getKab($set->kota,"semua");
		$kota = $kota->tipe." ".$kota->nama;
		$namagudang = ($rs->gudang > 0) ? $gudang->nama." - ".$kota : "PUSAT - ".$kota;
?>
	<div class="m-b-30">
		<div class="m-b-20">
			<b>Pembeli</b>
			<div class="text-primary m-t-4"><?=$usr->nama."<br/>".$usr->nohp?></div>
		</div>
		<div class="m-b-20">
			<b>Tanggal Pesanan</b>
			<div class="text-primary m-t-4"><?=$this->func->ubahTgl("d M Y H:i",$rs->tgl)?></div>
			<?=$cod?>
		</div>
		<?php if($rs->dropship != ""){ ?>
			<div class="m-b-20">
				<b>Informasi Pengirim</b>
				<div class="w-full">
					<div class="text-primary m-t-8">
						<?=ucwords($rs->dropship." (".$rs->dropshipnomer.")<br/>".$rs->dropshipalamat)?>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($rs->digital != 1){ ?>
			<div class="m-b-20">
				<b>Informasi Penerima</b>
				<div class="w-full">
					<div class="text-primary m-t-8">
						<?=ucwords($alamat->nama." (".$alamat->nohp.")<br/>".$alamat->alamat)?>
					</div>
				</div>
			</div>
			<div class="m-b-20">
				<b>Kurir Pengiriman</b>
				<div class="w-full">
					<div class="text-primary m-t-8">
						<?=$kurir?>
					</div>
				</div>
			</div>
			<?php if($rs->resi != ""){ ?>
			<div class="m-b-20">
				<b>Resi Pengiriman / Kurir Pengirim</b>
				<div class="w-full">
					<div class="text-primary m-t-8">
						<?=$rs->resi?>
					</div>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
		<div class="m-b-20">
			<b>Gudang/Asal Pengiriman</b>
			<div class="w-full">
				<div class="text-primary m-t-8">
					<i class="fas fa-shipping-fast text-primary"></i> <?=$namagudang?>
				</div>
			</div>
		</div>
	</div>
	<div class="m-b-12"><b>PRODUK PESANAN</b></div>
<?php
		$this->db->where("idtransaksi",intval($_GET["theid"]));
		$db = $this->db->get("transaksiproduk");
		
		foreach($db->result() as $r){
			$produk = $this->func->getProduk($r->idproduk,"semua");
			if(is_object($produk)){
				$nama = $produk->nama;
				$vari = ($r->variasi != 0) ? $this->func->getVariasi($r->variasi,"semua","id") : "";
				//print_r($vari);
				$variasi = (is_object($vari)) ? $produk->variasi." ".$this->func->getVariasiWarna($vari->warna,"nama") : "";
				$variasi .= (is_object($vari) AND ($vari->size > 0) )? ", ".$produk->subvariasi." ".$this->func->getVariasiSize($vari->size,"nama") : "";
			}else{
				$nama = "Produk telah dihapus";
				$variasi = "";
			}
			$preo = ($produk->preorder > 0) ? "<i class='fas fa-history'></i> Pre Order: <b>".$produk->pohari."</b> hari<br/>" : "";
			$preo .= ($produk->preorder > 0) ? "<b class='text-danger'>".$this->func->ubahTgl("D, d M Y",date("Y-m-d H:i:s",strtotime($rs->tglupdate . "+".$produk->pohari." days")))."</b>" : $preo;
	?>
	<div class="m-b-12" style="border:1px solid #ccc;border-radius:8px;padding:12px;">
		<div class="row">
			<div class="col-4 col-md-3">
				<img class="col-12" src="<?=$this->func->getFoto($r->idproduk,"utama")?>" />
			</div>
			<div class="col-8 col-md-8 row">
				<div class="col-8 col-md-6">
					<b><?=$nama?></b><br/>
					<small><?=$variasi?></small><br/>
					<div class="text-primary"><?=$r->keterangan?></div>
					<div class="text-warning"><?=$preo?></div>
				</div>
				<div class="col-4 col-md-6">
					<?=$r->jumlah." x Rp ".$this->func->formUang($r->harga)?>
				</div>
			</div>
		</div>
	</div>
<?php
		}
	}
?>