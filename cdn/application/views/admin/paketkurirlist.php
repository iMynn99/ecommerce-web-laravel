<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Kurir</th>
			<th scope="col">Paket</th>
			<th scope="col">Bayar di tempat (COD)</th>
			<th scope="col">Keterangan</th>
			<th class="text-right" scope="col" width="30%">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		//$this->db->where("jenis",2);
		$rows = $this->db->get("kurir");
		$rows = $rows->num_rows();
		
		//$this->db->where("jenis",2);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("kurir");
			
		if($rows > 0){
			$no = (($page-1)*$perpage)+1;
			$total = 0;
			foreach($db->result() as $r){
	?>
			<tr>
				<td><?=$no?></td>
				<td colspan=2><?=ucwords($r->nama)?></td>
				<td></td>
				<td><?=ucwords($r->namalengkap)?></td>
				<td class="text-right" width="30%">
					<?php if($r->jenis == 2){ ?>
					<button onclick="tambahPaket(<?=$r->id?>)" class="btn btn-xs btn-primary"><i class="fas fa-plus"></i> tambah paket</button>
					<button onclick="editKurir(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i> edit</button>
					<button onclick="hapusKurir(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> hapus</button>
					<?php } ?>
				</td>
			</tr>
	<?php	
				$no++;
                $this->db->where("idkurir",$r->id);
                $dbs = $this->db->get("paket");
			    foreach($dbs->result() as $rs){
					$cod = $rs->cod == 1 ? "<b class='text-success'><i class='fas fa-check'></i> aktif</b>" : "<b class='text-danger'><i class='fas fa-times'></i> non aktif</b>";
	?>
			<tr style="background-color:rgba(0,0,0,.03);">
				<td colspan=2></td>
				<td><?=ucwords($rs->nama)?></td>
				<td><?=$cod?></td>
				<td><?=ucwords($rs->keterangan)?></td>
				<td class="text-right" width="30%">
					<button onclick="editPaket(<?=$rs->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i> edit</button>
					<?php if($r->jenis == 2){ ?>
					<button onclick="hapusPaket(<?=$rs->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> hapus</button>
					<?php } ?>
				</td>
			</tr>
	<?php	
			    }
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada kurir custom</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage,"loadKurir");?>
</div>