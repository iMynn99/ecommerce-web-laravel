<div class="text-center m-t-20 m-b-30">
	<h4><b>LAPORAN KOMISI AFILIASI PRODUK</b></h4><br/>
	Periode: <?=$this->func->ubahTgl("d/m/Y",$_POST["tglmulai"])?> sampai <?=$this->func->ubahTgl("d/m/Y",$_POST["tglselesai"])?>
</div>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Tanggal</th>
			<th scope="col">ID Transaksi</th>
			<th scope="col">Afiliator</th>
			<th scope="col">Total Pesanan</th>
			<th scope="col">Nilai Komisi</th>
			<th scope="col">Status</th>
		</tr>
	<?php
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$where = "tgl BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59'";
		$where = "(cair BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59') OR (".$where.")";
		if(isset($_POST["status"])){
			if($_POST["status"] == 1){
				$where = "status = 1 AND (".$where.")";
			}elseif($_POST["status"] == 0){
				$where = "status = 0 AND (".$where.")";
			}elseif($_POST["status"] == 2){
				$where = "status = 2 AND (".$where.")";
			}elseif($_POST["status"] == 3){
				$where = "status = 3 AND (".$where.")";
			}
		}
		//echo $where;
		
		$this->db->order_by("status","ASC");
		$this->db->where($where);
		$db = $this->db->get("afiliasi");
			
		if($db->num_rows() > 0){
			$no = 1;
			$total = 0;
			$totalkomisi = 0;
			foreach($db->result() as $r){
				$trx = $this->func->getTransaksi($r->idtransaksi,"semua");
				$bayar = $this->func->getBayar($trx->idbayar,"semua");
				$total += $bayar->total-$bayar->kodebayar;
                $totalkomisi += $r->jumlah;
                $profil = $this->func->getProfil($r->usrid,"semua","usrid");
				
				if($r->status == 0){
					$status = "Pesanan Belum Dibayar";
				}elseif($r->status == 1){
					$status = "Menunggu Pencairan";
				}elseif($r->status == 2){
					$status = "Sudah Cair";
                    $status .= "<br/><span class='text-success'>".$this->func->ubahTgl("d/m/Y H:i",$r->cair)."</span>";
				}elseif($r->status == 3){
					$status = "Dibatalkan";
				}else{
					$status = "-";
				}
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$this->func->ubahTgl("d/m/Y H:i",$r->tgl)?></td>
				<td><?=$trx->orderid?></td>
				<td><?=strtoupper(strtolower($profil->nama."<br/>".$profil->nohp))?></td>
				<td class='text-right'><?=$this->func->formUang($bayar->total-$bayar->kodebayar)?></td>
				<td class='text-right'><?=$this->func->formUang($r->jumlah)?></td>
				<td><?=$status?></td>
			</tr>
	<?php	
				$no++;
			}
			if($total > 0){
				echo "
				<tr>
					<th class='text-right' colspan=4>TOTAL</th>
					<th class='text-right'>Rp. ".$this->func->formUang($total)."</th>
					<th class='text-right'>Rp. ".$this->func->formUang($totalkomisi)."</th>
					<th class='text-right'></th>
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