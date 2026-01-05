<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$where = "(username LIKE '%$cari%' OR nama LIKE '%$cari%' OR tgl LIKE '%$cari%')";
		if($_GET["load"] == "distri"){
			$where .= " AND level = 5";
			$fungsi = "loadDistri";
			$head = "Distributor";
		}elseif($_GET["load"] == "reseller"){
			$where .= " AND level = 2";
			$fungsi = "loadReseller";
			$head = "Reseller";
		}elseif($_GET["load"] == "agen"){
			$where .= " AND level = 3";
			$fungsi = "loadAgen";
			$head = "Agen";
		}elseif($_GET["load"] == "agensp"){
			$where .= " AND level = 4";
			$fungsi = "loadAgenSP";
			$head = "Agen Premium";
		}else{
			$where .= " AND level = 1";
			$fungsi = "loadUser";
			$head = "User";
		}
	?>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nama <?php if(isset($head)){ echo $head; }else{ echo "Agen"; } ?></th>
            <th scope="col">No HP</th>
            <th scope="col">Saldo User</th>
            <th scope="col">Total Order</th>
            <th scope="col">Aksi</th>
        </tr>
        <?php
		$this->db->select("id");
		$this->db->where($where);
		$rows = $this->db->get("userdata");
		$rows = $rows->num_rows();
		
		$this->db->where($where);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("userdata");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$user = $this->func->getProfil($r->id,"semua","usrid");
				$this->db->select("SUM(total) AS total,SUM(kodebayar) AS kodebayar");
				$this->db->where("usrid",$r->id);
				$this->db->where("status",1);
				$dbs = $this->db->get("pembayaran");
				foreach($dbs->result() as $res){
					$total = $res->total - $res->kodebayar;
				}
	?>
        <tr>
            <td><?=$no?></td>
            <td><?=$this->security->xss_clean($user->nama)?></td>
            <td><?=$user->nohp?></td>
            <td>Rp. <?=$this->func->formUang($this->func->getSaldo($r->id,"saldo"))?></td>
            <td>Rp. <?=$this->func->formUang($total)?></td>
            <td>
                <button type="button" onclick="hapusUserdata(<?=$r->id?>)" class="btn btn-xs btn-danger"><i
                        class="fas fa-times"></i> Hapus</button>
            </td>
        </tr>
        <?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada ".ucwords($_GET["load"])."</td></tr>";
		}
	?>
    </table>

    <?=$this->func->createPagination($rows,$page,$perpage,$fungsi);?>
</div>