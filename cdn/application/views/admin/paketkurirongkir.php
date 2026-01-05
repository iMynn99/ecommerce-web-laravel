<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Kurir</th>
			<th scope="col">Paket</th>
			<th scope="col">Kota/Kabupaten</th>
			<th scope="col">Ongkos Kirim</th>
			<th class="text-right" scope="col" width="25%">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$rows = $this->db->get("kurircustom");
		$rows = $rows->num_rows();
		
		$this->db->order_by("kurir,paket,idkab,idkec ASC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("kurircustom");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
                if($r->idkab > 0){
					$kab = $this->func->getKab($r->idkab,"semua");
                	$kab = $kab->tipe." ".$kab->nama;
				}else{
					$kab = "Nasional/Seluruh Indonesia";
				}
				$jenis = ($r->jenis == 1) ? "Flat" : "Per KG";
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=ucwords($this->func->getKurir($r->kurir,"nama"))?></td>
				<td><?=ucwords($this->func->getPaket($r->paket,"nama"))?></td>
				<td><?=ucwords($kab)?></td>
				<!--<td><_?=ucwords($this->func->getKec($r->idkec,"nama"))?_></td>-->
				<td><b><?=$this->func->formUang($r->harga)?></b><br/><?=$jenis?></td>
				<td class="text-right" width="25%">
					<button onclick="editOngkir(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i> edit</button>
					<button onclick="hapusOngkir(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> hapus</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=8 class='text-center text-danger'>Belum ada ongkos kirim custom</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage,"loadOngkir");?>
</div>