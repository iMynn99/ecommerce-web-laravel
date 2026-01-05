<?php if($_GET["load"] == "preorder"){ ?>
<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">Invoice</th>
			<th scope="col">Tanggal</th>
			<th scope="col">Pembeli</th>
			<th scope="col">Produk</th>
			<th scope="col">Jumlah</th>
			<th scope="col">Total</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$this->db->where("po >",0);
		$rows = $this->db->get("transaksi");
		$rows = $rows->num_rows();
		
		$this->db->where("po >",0);
		$this->db->order_by("status","ASC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("transaksi");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $rs){
				$this->db->where("idtransaksi",$rs->id);
				if(isset($_GET["idproduk"])){ $this->db->where("idproduk",$_GET["idproduk"]); }
				$dbs = $this->db->get("transaksiproduk");
				$alamat = $this->func->getAlamat($rs->alamat,"semua");
				$profil = $this->func->getProfil($rs->usrid,"semua","usrid");
				$pembeli = "<span class='text-primary'>".strtoupper(strtolower($this->security->xss_clean($profil->nama)))."</span>";
				$pembeli .= "<br/><small>".$this->security->xss_clean($alamat->nama." (".$alamat->nohp).")</small>";
				$pembeli .= "<br/><small class='m-t--4 dis-block'><i>".$this->security->xss_clean($alamat->alamat)."</i></small>";
				foreach($dbs->result() as $r){
					$konfirmasi = $this->func->getKonfirmasi($rs->idbayar,"semua","idbayar");
					$produk = $this->func->getProduk($r->idproduk,"semua");
					switch($rs->status){
						case 0: $status = "<div class='badge badge-sm badge-danger m-b-12'><i class='fas fa-times'></i> belum dibayar</div>";
						break;
						case 1: $status = "<div class='badge badge-sm badge-success m-b-12'><i class='fas fa-check'></i> sudah dibayar</div>";
						break;
						case 2: $status = "<div class='badge badge-sm badge-success m-b-12'><i class='fas fa-shipping-fast'></i> &nbsp;udah dikirim</div>";
						break;
						default: $status = "";
						break;
					}
					if($konfirmasi->id > 0){
						$status .= "<br/><a href='javascript:void(0)' onclick='bukti(\"".base_url("konfirmasi/".$konfirmasi->bukti)."\")'>&raquo; Lihat Bukti Transfer</a>";
					}
					$tgljadi = date('Y-m-d',strtotime($rs->tglupdate . "+".$produk->pohari." days"));
	?>
			<tr>
				<td>#<?=$rs->orderid."<br/>".$status?></td>
				<td>
					tgl pesanan:<br/>
					<span class='text-primary'><?=$this->func->ubahTgl("d/m/Y H:i",$rs->tglupdate)?></span>
					<?php if($rs->status < 2){ ?>
						<br/>perkiraan pesanan siap:<br/>
						<span class='text-danger'><?=$this->func->ubahTgl("d/m/Y",$tgljadi)?></span>
					<?php }else{ ?>
						<br/>tgl pengiriman:<br/>
						<span class='text-danger'><?=$this->func->ubahTgl("d/m/Y",$rs->kirim)?></span>
					<?php } ?>
				</td>
				<td><?=$pembeli?></td>
				<td><?=ucwords($produk->nama)?></td>
				<td><?=$this->func->formUang($r->jumlah)?></td>
				<td>Rp. <?=$this->func->formUang($r->jumlah*$r->harga)?></td>
			</tr>
	<?php	
					$no++;
				}
			}
		}else{
			echo "<tr><td colspan=7 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage);?>
</div>
<script type="text/javascript">
	function konfirm(id){
		swal.fire({
			title: "Perhatian!",
			text: "pastikan uang sudah benar-benar masuk/ditranfer, lebih baik cek kembali mutasi.",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((val)=>{
			if(val.value){
				$.post("<?=site_url("api/updatepreorder")?>",{"id":id,"status":1,[$("#names").val()]:$("#tokens").val()},function(e){
					var data = eval("("+e+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil!","Pesanan siap untuk segera dikirim","success");
						loadPreorder(1);
					}else{
						swal.fire("Gagal!","Terjadi kendala saat mengupdate data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
	
	function bukti(url){
		$("#bukti").attr("src",url);
		$("#modalbukti").modal();
	}
</script>

<div class="modal fade" id="modalbukti" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<img id="bukti" src="<?=base_url('assets/img/no-image.png')?>" style='width:100%;' />
		</div>
	</div>
</div>
<?php }else{ ?>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col" rowspan=2>No</th>
			<th scope="col" rowspan=2>Nama Produk</th>
			<th scope="col" rowspan=2>Stok PO<br/><span class="text-primary">harga normal</span></th>
			<th scope="col" colspan=2 class="text-center">Total PO</th>
			<th scope="col" rowspan=2>Aksi</th>
		</tr>
		<tr>
			<th scope="col"><span class="text-danger">Belum Bayar</span></th>
			<th scope="col"><span class="text-success">Sudah Bayar</span></th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$this->db->where("preorder",1);
		$rows = $this->db->get("produk");
		$rows = $rows->num_rows();
		
		$this->db->where("preorder",1);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("produk");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$this->db->where("idproduk",$r->id);
				$as = $this->db->get("produkvariasi");
				$kuota = 0;$kuotatotal = 0;
				foreach($as->result() as $rs){
					$kuotatotal += $rs->stok * $r->harga;
					$kuota += $rs->stok;
				}
				$this->db->where("po >",0);
				$as = $this->db->get("transaksi");
				$kuotas = 0;$kuotastotal = 0;
				$kuotab = 0;$kuotabtotal = 0;
				foreach($as->result() as $rs){
					$this->db->where("idtransaksi",$rs->id);
					$ass = $this->db->get("transaksiproduk");
					foreach($ass->result() as $rp){
						$kuotatotal += $rp->jumlah*$rp->harga;
						$kuota += $rp->jumlah;
						if($rs->status == 0){
							$kuotabtotal += $rp->jumlah*$rp->harga;
							$kuotab += $rp->jumlah;
						}else{
							$kuotastotal += $rp->jumlah*$rp->harga;
							$kuotas += $rp->jumlah;
						}
					}
				}
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=ucwords($r->nama)?></td>
				<td><?=$this->func->formUang($kuota)." pcs<br/><span class='text-primary'>".$this->func->formUang($kuotatotal)."</span>"?></td>
				<td><?=$this->func->formUang($kuotab)." pcs<br/><span class='text-danger'>".$this->func->formUang($kuotabtotal)."</span>"?></td>
				<td><?=$this->func->formUang($kuotas)." pcs<br/><span class='text-success'>".$this->func->formUang($kuotastotal)."</span>"?></td>
				<td>
					<button type="button" onclick="detailPesanan(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-list"></i> Daftar Pesanan</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage);?>
</div>
<?php } ?>