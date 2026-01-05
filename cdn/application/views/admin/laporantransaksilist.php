<div class="text-center m-t-20 m-b-30">
	<h4><b>LAPORAN TRANSAKSI PENJUALAN</b></h4><br/>
	<?php
		if(isset($_POST["gudang"]) AND $_POST["gudang"] != "semua"){
			if($_POST["gudang"] > 0){
				$gudang = $this->func->getGudang($_POST["gudang"],"semua");
				$kota = $this->func->getKab($gudang->idkab,"semua");
				$kota = $kota->tipe." ".$kota->nama;
				echo "<div class='fs-18 m-t--12 m-b-20'><i class='fas fa-map-marker-alt'></i> ".strtoupper(strtolower($gudang->nama))." - ".$kota."</div>";
			}else{
				$set = $this->func->globalset("semua");
				$kota = $this->func->getKab($set->kota,"semua");
				$kota = $kota->tipe." ".$kota->nama;
				echo "<div class='fs-18 m-t--12 m-b-20'><i class='fas fa-map-marker-alt'></i> PUSAT - ".$kota."</div>";
			}
		}
	?>
	Periode: <?=$this->func->ubahTgl("d/m/Y",$_POST["tglmulai"])?> sampai <?=$this->func->ubahTgl("d/m/Y",$_POST["tglselesai"])?>
</div>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Tanggal</th>
			<th scope="col">ID Transaksi</th>
			<th scope="col">Nama</th>
			<th scope="col">Status</th>
			<th scope="col">Metode Pembayaran</th>
			<th scope="col">Total</th>
			<th scope="col">Ongkir</th>
		</tr>
	<?php
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$where = "tgl BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59'";
		$whereupdate = "tglupdate BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59'";
		if(isset($_POST["status"])){
			if($_POST["status"] == 1){
				$where = "status > 0 AND status < 4 AND (".$where.")";
			}elseif($_POST["status"] == 2){
				$where = "status = 0 AND (".$where.")";
			}elseif($_POST["status"] == 3){
				$where = "status = 1 AND (".$whereupdate.")";
			}elseif($_POST["status"] == 4){
				$where = "status = 2 AND (".$whereupdate.")";
			}elseif($_POST["status"] == 5){
				$where = "status = 3 AND (".$whereupdate.")";
			}elseif($_POST["status"] == 6){
				$where = "status = 4 AND (".$whereupdate.")";
			}
		}
		if(isset($_POST["jenis"])){
			if($_POST["jenis"] == 1){
				$where = (isset($_POST["status"]) AND $_POST["status"] >= 1) ? "digital = 0 AND ".$where : "digital = 0 AND (".$where.")";
			}elseif($_POST["jenis"] == 2){
				$where = (isset($_POST["status"]) AND $_POST["status"] >= 1) ? "digital = 1 AND ".$where : "digital = 1 AND (".$where.")";
			}
		}
		if(isset($_POST["gudang"])){
			if($_POST["gudang"] != "semua"){
				$where = (isset($_POST["status"]) AND $_POST["status"] >= 1) ? "gudang = ".$_POST["gudang"]." AND ".$where : "gudang = ".$_POST["gudang"]." AND (".$where.")";
				$where = (isset($_POST["jenis"]) AND $_POST["jenis"] >= 1) ? "gudang = ".$_POST["gudang"]." AND ".$where : $where;
			}
		}
		//echo $where;
		
		$this->db->order_by("status","ASC");
		$this->db->where($where);
		$db = $this->db->get("transaksi");
			
		if($db->num_rows() > 0){
			$no = 1;
			$total = 0;
			$totalongkir = 0;
			foreach($db->result() as $r){
				$this->db->select("id");
				$this->db->where("idbayar",$r->idbayar);
				$kon = $this->db->get("konfirmasi");
				
				$bayar = $this->func->getBayar($r->idbayar,"semua");
				$total += $bayar->total-$bayar->kodebayar;
				$totalongkir += $r->ongkir;
				
				if($r->status == 0){
					$status = "Belum Dibayar";
				}elseif($r->status == 1){
					$status = "Perlu Dikirim";
				}elseif($r->status == 2){
					$status = "Sedang Dikirim";
				}elseif($r->status == 3){
					$status = "Selesai";
				}elseif($r->status == 4){
					$status = "Dibatalkan";
				}else{
					$status = "-";
				}

				$metode = "";
				switch($bayar->metode_bayar){
					case 1: $metode = "Bayar Ditempat (COD)";
					break;
					case 2: $metode = "Transfer";
					break;
					case 3: $metode = "Tripay";
					break;
					case 4: $metode = "Midtrans";
					break;
				}
				$metodes = ($bayar->metode == 2) ? "Saldo" : $metode;
				$metodes = ($bayar->metode == 2 AND $bayar->transfer > 0) ? $metodes.": Rp. ".$this->func->formUang($bayar->saldo)."<br/>" : $metodes;
				$metodes = ($bayar->metode == 2 AND $bayar->transfer > 0) ? $metodes.$metode.": Rp. ".$this->func->formUang($bayar->transfer) : $metodes;
				$nama = strtoupper(strtolower($this->func->getUserTemp($r->usrid_temp,"nama")))."<i class='text-danger m-l-8'>(non member)</i>";
				$nama = ($r->usrid > 0) ? strtoupper(strtolower($this->func->getProfil($r->usrid,"nama","usrid"))) : $nama;
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$this->func->ubahTgl("d/m/Y H:i",$r->tgl)?></td>
				<td><?=$r->orderid?></td>
				<td><?=$nama?></td>
				<td><?=$status?></td>
				<td><?=$metodes?></td>
				<td class='text-right'><?=$this->func->formUang($bayar->total-$bayar->kodebayar)?></td>
				<td class='text-right'><?=$this->func->formUang($r->ongkir)?></td>
			</tr>
	<?php	
				$no++;
			}
			if($total > 0){
				echo "
				<tr>
					<th class='text-right' colspan=6>TOTAL</th>
					<th class='text-right'>Rp. ".$this->func->formUang($total)."</th>
					<th class='text-right'>Rp. ".$this->func->formUang($totalongkir)."</th>
				</tr>
				";
			}else{
				echo "<tr><td colspan=7 class='text-center text-danger'>Belum ada data</td></tr>";
			}
		}else{
			echo "<tr><td colspan=7 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>
</div>