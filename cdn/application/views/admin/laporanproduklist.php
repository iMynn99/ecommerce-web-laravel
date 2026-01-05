<div class="text-center m-t-20 m-b-30">
	<h4><b>LAPORAN PENJUALAN PRODUK</b></h4><br/>
	Periode: <?=$this->func->ubahTgl("d/m/Y",$_POST["tglmulai"])?> sampai <?=$this->func->ubahTgl("d/m/Y",$_POST["tglselesai"])?>
</div>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Produk</th>
			<th scope="col">Jml Produk</th>
			<th scope="col">Total Penjualan</th>
		</tr>
	<?php
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
        $perpage = 10;
        
		$this->db->where("status > 0 AND status < 4 AND tglupdate BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59'");
        $dbs = $this->db->get("transaksi");
        $in = array();
        foreach($dbs->result() as $rs){
            $in[] = $rs->id;
        }
        
        $this->db->select("idproduk,SUM(jumlah) as jml,SUM(jumlah*harga) as total");
		if(count($in) > 0){
        	$this->db->where_in("idtransaksi",$in);
		}else{
			$this->db->where("id",0);
		}
        $this->db->order_by("jml,total","DESC");
        $this->db->group_by("idproduk");
		$db = $this->db->get("transaksiproduk");
		//print_r($db->num_rows());
			
		if($db->num_rows() > 0){
			$no = 1;
			$total = 0;
			$jumlah = 0;
			foreach($db->result() as $r){
                $total += $r->total;
                $jumlah += $r->jml;
                $produk = $this->func->getProduk($r->idproduk,"semua");
                $produks = ($produk->id > 0) ? $produk->nama : "<span class='text-danger'><i class='fas fa-times'></i> &nbsp;Produk telah dihapus</span>";
	?>
			<tr>
				<td><b><?=$no?></b></td>
				<td><b><?=$produks?></b></td>
				<td class='text-right'><b><?=$this->func->formUang($r->jml)?> pcs</b></td>
				<td class='text-right'><b><?=$this->func->formUang($r->total)?></b></td>
			</tr>
	<?php	
				$this->db->select("SUM(jumlah) as jml,SUM(jumlah*harga) as total,idproduk,variasi");
				$this->db->where_in("idtransaksi",$in);
				$this->db->where("idproduk",$r->idproduk);
				//$this->db->where("variasi !=",0);
				$this->db->group_by("variasi");
				$this->db->order_by("variasi","ASC");
				$db = $this->db->get("transaksiproduk");
					
				if($db->num_rows() > 0){
					foreach($db->result() as $pr){
						if($pr->variasi > 0 || ($pr->variasi == 0 AND $r->jml != $pr->jml)){
							$vari = ($pr->variasi != 0) ? $this->func->getVariasi($pr->variasi,"semua","id") : "";
							$variasi = (is_object($vari) AND ($vari->warna > 0)) ? $produk->variasi." ".$this->func->getVariasiWarna($vari->warna,"nama") : "<i class='text-danger'>varian telah dihapus</i>";
							$variasi = ($pr->variasi > 0) ? $variasi : "--";
							$variasi .= (is_object($vari) AND ($vari->size > 0)) ? ", ".$produk->subvariasi." ".$this->func->getVariasiSize($vari->size,"nama") : "";
	?>
			<tr>
				<td></td>
				<td><?=$variasi?></td>
				<td class='text-right'><?=$this->func->formUang($pr->jml)?> pcs</td>
				<td class='text-right'><?=$this->func->formUang($pr->total)?></td>
			</tr>
	<?php	
						}
					}
				}
				$no++;
			}
			echo "
			<tr>
				<th class='text-right' colspan=2>TOTAL</th>
				<th class='text-right'>".$this->func->formUang($jumlah)." pcs</th>
				<th class='text-right'>Rp. ".$this->func->formUang($total)."</th>
			</tr>
			";
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>
</div>