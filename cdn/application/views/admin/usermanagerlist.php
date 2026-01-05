<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nama Agen</th>
            <th scope="col">No HP</th>
            <th scope="col">Saldo User</th>
            <th scope="col">Total Order</th>
        </tr>
        <?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$where = "(username LIKE '%$cari%' OR nama LIKE '%$cari%' OR tgl LIKE '%$cari%')";
		if($_GET["load"] == "reseller"){
			$where .= " AND level = 2";
		}elseif($_GET["load"] == "agen"){
			$where .= " AND level = 3";
		}else{
			$where .= " AND level = 1";
		}
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
            <td><?=$user->nama?></td>
            <td><?=$user->nohp?></td>
            <td>Rp. <?=$this->func->formUang($this->func->getSaldo($r->id,"saldo"))?></td>
            <td>Rp. <?=$this->func->formUang($total)?></td>
        </tr>
        <?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada ".ucwords($_GET["load"])."</td></tr>";
		}
	?>
    </table>

    <?=$this->func->createPagination($rows,$page,$perpage,"loadUser");?>
</div>