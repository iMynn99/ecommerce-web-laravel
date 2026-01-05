<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col" rowspan=2>No</th>
			<th scope="col" rowspan=2>Data User</th>
			<th scope="col" rowspan=2>Data Member</th>
			<th scope="col" colspan=3 class="text-center">Data Transaksi</th>
			<th scope="col" rowspan=2>Aksi</th>
		</tr>
		<tr>
			<th scope="col">Pending</th>
			<th scope="col">Sukses</th>
			<th scope="col">Batal</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		//print_r($cari);
		
		$where = "(nama LIKE '%$cari%' OR nohp LIKE '%$cari%' OR tgl LIKE '%$cari%')";
		$this->db->select("id");
		$this->db->where($where);
		$rows = $this->db->get("usertemp");
		$rows = $rows->num_rows();
		
		$this->db->where($where);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("usertemp");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$alamat = $this->func->getAlamat($r->id,"semua","usrid_temp");
				$usr = $this->func->getUserdata($r->usrid,"semua");
                $member = ($r->usrid > 0) ? "<span class='text-primary'>".$usr->nama."<br/>".$usr->nohp."<br/><small>".$usr->username."</small></span>" : "<i class='text-danger'>belum mendaftar</i>";
                $nama = ($r->nama != "") ? "<span class='text-primary'>".$r->nama." (".$r->nohp.")</span>" : "<i class='text-danger'>belum mengisi</i>";
                $nama .= ($alamat->id > 0) ? "<br/><small>".$alamat->alamat."</small>" : "";

                $pending = 0;
                $sukses = 0;
                $batal = 0;
                $this->db->where("usrid_temp",$r->id);
                $by = $this->db->get("pembayaran");
                foreach($by->result() as $b){
                    if($b->status == 0){
                        $pending += $b->total-$b->kodebayar;
                    }elseif($b->status == 1){
                        $sukses += $b->total-$b->kodebayar;
                    }else{
                        $batal += $b->total-$b->kodebayar;
                    }
                }
	?>
			<tr>
				<td><?=$no?></td>
				<td style="width:25%"><?=$nama?></td>
				<td><?=$member?></td>
				<td>Rp. <?=$this->func->formUang($pending)?></td>
				<td>Rp. <?=$this->func->formUang($sukses)?></td>
				<td>Rp. <?=$this->func->formUang($batal)?></td>
				<td>
					<button type="button" onclick="hapus(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> hapus</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=7 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage,"loadB");?>
</div>