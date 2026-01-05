<div class="table-responsive">
	<table class="table table-condensed table-hover broadcast-list">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Gambar</th>
			<th scope="col">Isi Promo</th>
			<th scope="col">Rilis</th>
			<th scope="col">Jenis</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$rows = $this->db->get("broadcast");
		$rows = $rows->num_rows();
		
		$this->db->order_by("status ASC, id DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("broadcast");
			
		if($rows > 0){
			$no = 1;
			foreach($db->result() as $r){
                $jenis = $r->jenis == 0 ? "<span class='text-primary'><i class='fas fa-layer-group'></i> &nbsp;SEMUA (Email, WA, Push Notif)</span>" : "";
                $jenis = $r->jenis == 1 ? "<span class='text-danger'><i class='fas fa-envelope'></i> &nbsp;Email</span>" : $jenis;
                $jenis = $r->jenis == 2 ? "<span class='text-success'><i class='fab fa-whatsapp'></i> &nbsp;Whatsapp</span>" : $jenis;
                $jenis = $r->jenis == 3 ? "<span class='text-warning'><i class='fas fa-mobile-alt'></i> &nbsp;Push Notif</span>" : $jenis;
				$status = $r->status == 1 ? "<br/><span class='text-success'><i class='fas fa-check-circle'></i> &nbsp;Sudah Terkirim</span>" : "";
				$gambar = $r->gambar != "" ? $r->gambar : "no-image.png";
	?>
			<tr>
				<td><?=$no?></td>
				<td><img src="<?=base_url("promo/".$gambar)?>"/></td>
				<td><?=$this->security->xss_clean("<span class='text-primary'>".$r->judul."</span><br/>".$r->isi)?></td>
				<td><?=$this->func->ubahTgl("d M Y",$r->rilis)."<br/>pukul &nbsp;".$this->func->ubahTgl("H:i",$r->rilis).$status?></td>
				<td><?=$jenis?></td>
				<td>
					<button onclick="resend(<?=$r->id?>)" class="btn btn-xs btn-primary"><i class="fas fa-sync-alt"></i> Resend</button>
					<button onclick="hapus(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> Hapus</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=6 class='text-center text-danger'>Belum ada broadcast promo</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage,"loadB");?>
</div>