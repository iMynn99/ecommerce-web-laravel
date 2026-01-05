<?php
    $jenise = (isset($jenis) AND $jenis == 2) ? "Topup" : "Penarikan";
?>
<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Nama Pengguna</th>
			<th scope="col">Tanggal</th>
			<th scope="col">Jumlah <?=$jenise?></th>
			<th scope="col">Status</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
        $this->db->where("jenis",$jenis);
		$rows = $this->db->get("saldotarik");
		$rows = $rows->num_rows();
		
		$this->db->order_by("status ASC, id DESC");
        $this->db->where("jenis",$jenis);
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("saldotarik");
			
		if($rows > 0){
			$no = 1;
			foreach($db->result() as $r){
				$norek = $this->func->getRekening($r->idrek,"semua");
				$rek = (isset($jenis) AND $jenis == 1) ? "<br/>".$this->func->getBank($norek->idbank,"nama")." ".$norek->norek."<br/>a/n ".$norek->atasnama : "";
                $user = $this->func->getUserdata($r->usrid,"semua");
                $profil = $this->func->getProfil($r->usrid,"semua");
                $status = $r->status == 1 ? "<span class='text-success'><i class='fas fa-check-circle'></i> &nbsp;SELESAI</span><br/><small>".$this->func->ubahTgl("d M Y H:i",$r->selesai)."WIB</small>" : "<span class='text-warning'><i class='fas fa-exclamation-circle'></i> &nbsp;BELUM</span>";
                $status = $r->status == 2 ? "<span class='text-danger'><i class='fas fa-times-circle'></i> &nbsp;BATAL</span><br/><small>".$this->func->ubahTgl("d M Y H:i",$r->selesai)."WIB</small>" : $status;
	?>
			<tr>
				<td><?=$no?></td>
				<td><?="<span class='text-primary'>".$this->security->xss_clean($profil->nama)."</span>".$rek?></td>
				<td><?=$this->func->ubahTgl("d M Y",$r->tgl)?></td>
				<td>Rp. <?=$this->func->formUang($r->total)?></td>
				<td><?=$status?></td>
				<td>
					<?php if($r->bukti != ""){ ?>
					<button onclick="bukti('<?=base_url('konfirmasi/'.$r->bukti)?>')" class="btn btn-xs btn-primary"><i class="fas fa-receipt"></i> Bukti Transfer</button>
					<?php } ?>
					<?php if($r->status == 0){ ?>
					<button onclick="verifikasi(<?=$r->id?>)" class="btn btn-xs btn-success"><i class="fas fa-check"></i> Verifikasi</button>
					<button onclick="batal(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> Batalkan</button>
					<?php } ?>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=6 class='text-center text-danger'>Belum ada transaksi ".$jenise."saldo</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage,"load".$jenise);?>
</div>