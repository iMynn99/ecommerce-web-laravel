<?php
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$perpage = (isset($_GET["perpage"]) AND $_GET["perpage"] != "") ? $_GET["perpage"] : 10;
			$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
			
			$where = "nama LIKE '%$cari%' OR harga LIKE '%$cari%' OR berat LIKE '%$cari%' OR deskripsi LIKE '%$cari%'";
			if(isset($_POST["status"])){
				if($_POST["status"] == 1){
					$where = "stok > 0 AND (".$where.")";
				}elseif($_POST["status"] == 2){
					$where = "stok = 0 AND (".$where.")";
				}elseif($_POST["status"] == 3){
					$where = "stok > 0 AND stok <= 5 AND (".$where.")";
				}
			}
			$this->db->where($where);
			$row = $this->db->get("produk");
			
			$this->db->where($where);
			$this->db->limit($perpage,($page-1)*$perpage);
			$this->db->order_by("tglupdate DESC");
			$db = $this->db->get("produk");
			
			echo "
				<table class='table'>
					<tr>
						<th>Foto</th>
						<th>Nama Produk</th>
						<th>Detail Harga</th>
						<th style='width:140px'>Stok Produk</th>
						<th style='width:130px;'>Aksi</th>
					</tr>
			";
			if($row->num_rows() == 0){
				echo "
						<tr>
							<th class='text-center text-danger' colspan=4>Belum ada produk.</th>
						</tr>
				";
			}
			$default = base_url("assets/img/no-image.png");
			$no = 1 + (($page-1)*$perpage);
			foreach($db->result() as $r){
				$url = $this->func->getFoto($r->id,"utama");
				$gudangs = $this->func->getGudang($r->gudang,"semua");
				$set = $this->func->globalset("semua");
				$kab = ($gudangs->id > 0) ? $this->func->getKab($gudangs->idkab,"semua") : "";
				$kabs = $this->func->getKab($set->kota,"semua");
				$draf = ($r->status == 0) ? "<div class='m-b-8'><span class='badge badge-danger'><i class='fas fa-keyboard'></i> DRAFT</span></div>" : "";
				$gudang = ($gudangs->id > 0) ? "<div class='text-success m-b-8'><i class='fas fa-map-marker-alt'></i> ".$gudangs->nama." - ".$kab->tipe." ".$kab->nama."</div>" : "<div class='text-primary m-b-8'> <i class='fas fa-map-marker-alt'></i> PUSAT - ".$kabs->tipe." ".$kabs->nama."</div>";
				$po = ($r->preorder == 0) ? "" : "<span class='badge badge-warning m-r-8 m-b-8'><i class='fas fa-history'></i> &nbsp;PRE ORDER</span>";
				$po .= ($r->digital == 1) ? "<span class='badge badge-primary'><i class='fas fa-cloud'></i> &nbsp;Produk Digital</span>" : "";
				$thumbnail = (filter_var($url, FILTER_VALIDATE_URL)) ? $url : $default;
				$thumbnail = "<div style='background-image:url(\"".$thumbnail."\")' class='thumbnail-post m-tb-8'></div>";
				$harga = "Normal: IDR ".$this->func->formUang($r->harga)."<br/>";
				$harga .= "Reseller: IDR ".$this->func->formUang($r->hargareseller)."<br/>";
				$harga .= "Agen: IDR ".$this->func->formUang($r->hargaagen)."<br/>";
				$harga .= "Agen Premium: IDR ".$this->func->formUang($r->hargaagensp)."<br/>";
				$harga .= "Distributor: IDR ".$this->func->formUang($r->hargadistri);
				$harga .= ($r->afiliasi > 0) ? "<br/><span class='text-success'>Komisi Afiliasi: IDR ".$this->func->formUang($r->afiliasi)."</span>" : "";
				$varlist = $this->func->getVariasiJumlah($r->id);
				$stl = ($r->stok > 2) ? " class='text-primary'" : " class='text-danger'";
				$stok = ($varlist > 0) ? "<b".$stl.">".$r->stok."</b><br/><small></i>dari <b>$varlist</b> varian</i></small>" : "<b".$stl.">".$r->stok."</b>";
				$button = "
					<a href='".site_url('ngadimin/produkform/?copy='.$r->id)."' title='copy' class='btn btn-warning'><i class='fas fa-copy'></i></a>
					<a href='".site_url('ngadimin/produkform/'.$r->id)."' title='edit' class='btn btn-primary'><i class='fas fa-pencil-alt'></i></a>
					<a href='javascript:void(0)' onclick='hapus(".$r->id.")' title='hapus' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a>";
									
				echo "
					<tr>
						<td>$thumbnail</td>
						<td><div class='m-b-8'>".ucwords($r->nama)."</div>".$draf.$gudang.$po."</td>
						<td>".$harga."</td>
						<td class='text-center'>".$stok."</td>
						<td style='width:160px;'>
						".$button."
						</td>
					</tr>
				";
				$no++;
			}
			echo "
				</table>
			";
            echo $this->func->createPagination($row->num_rows(),$page,$perpage);

?>