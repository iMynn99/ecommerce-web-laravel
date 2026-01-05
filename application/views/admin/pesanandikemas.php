<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "" AND intval($_GET["page"]) > 0) ? intval($_GET["page"]) : 1;
	$perpage = 10;

	$this->db->where("digital",0);
	$this->db->where("status",1);
	//$this->db->where("resi","");
	$this->db->where("usrid",$_SESSION["usrid"]);
	$rows = $this->db->get("transaksi");
	$rows = $rows->num_rows();

	$this->db->where("digital",0);
	$this->db->where("status",1);
	//$this->db->where("resi","");
	$this->db->where("usrid",$_SESSION["usrid"]);
	$this->db->order_by("id","DESC");
	$this->db->limit($perpage,($page-1)*$perpage);
	$db = $this->db->get("transaksi");
  	if($db->num_rows() > 0){
?>
	<div class="pesanan">
<?php
    foreach($db->result() as $rx){
		$cod = ($rx->dropship != "") ? "<br/><span class='badge badge-info fs-14'>Dropship</span>" : "";
?>

		<div class="m-b-30">
			<div class="pesanan-item p-lr-30 p-tb-30 m-lr-0-xl">
				<div class="row p-b-30">
					<div class="col-6">
						<span class="text-dark font-medium fs-18">
							Order ID&nbsp; <span class="text-success">#<?php echo $rx->orderid; ?></span>
						</span>
					</div>
					<div class="col-6 text-right">
						<a href="<?php echo site_url("manage/detailpesanan/?orderid=").$rx->orderid; ?>" class="btn btn-sm btn-primary"><i class="fas fa-angle-double-right"></i> Rincian<span class="hidesmall"> Pesanan</span></a>
					</div>
				</div>
				<div class="row m-lr-0">
					<div class="col-md-8 p-lr-0 m-b-10">
						<?php
							$this->db->where("idtransaksi",$rx->id);
							$trp = $this->db->get("transaksiproduk");
							$totalproduk = 0;
							$no = 1;
							$po = 0;
							foreach ($trp->result() as $key) {
								$totalproduk += $key->harga * $key->jumlah;
								$produk = $this->func->getProduk($key->idproduk,"semua");
								$variasee = $this->func->getVariasi($key->variasi,"semua");
								$variasi = ($key->variasi != 0 AND $variasee != null) ? $this->func->getWarna($variasee->warna,"nama")." ".$produk->subvariasi." ".$this->func->getSize($variasee->size,"nama") : "";
								$variasi = ($key->variasi != 0 AND $variasee != null) ? "<small class='text-primary'>".$produk->variasi.": ".$variasi."</small>" : "";
								$pohari = date('Y-m-d H:i:s',strtotime($rx->tglupdate. "+".$produk->pohari." days"));
								$po += ($produk->preorder == 1) ? 1 : 0;
								if($no == 2){
						?>
							<div class="m-b-30 show-product">
							<?php
								}
							?>
							<div class="row p-b-30 m-lr-0 produk-item">
								<div class="col-4 col-md-2">
									<div class="img" style="background-image:url('<?php echo $this->func->getFoto($key->idproduk,"utama"); ?>')" alt="IMG"></div>
								</div>
								<div class="col-8 col-md-10">
									<p class="font-medium text-dark btn-block"><?php if($produk != null){ echo $produk->nama; }else{ echo "Produk telah dihapus"; } ?></p>
									<?=$variasi?>
									<p>Rp <?php echo $this->func->formUang($key->harga); ?> <span style="font-size:11px">x<?php echo $key->jumlah; ?></span></p>
								</div>
							</div>
						<?php
								$no++;
							}
							if($no > 2){
						?>
							</div>
							<div class="row p-b-30 m-lr-0">
								<a href="javascript:void(0)" class="view-product text-info"><i class="fas fa-chevron-circle-down"></i> Lihat produk lainnya</a>
								<a href="javascript:void(0)" class="view-product text-info" style="display:none;"><i class='fas fa-chevron-circle-up'></i> Sembunyikan produk</a>
							</div>
						<?php
							}
						?>
					</div>
					<div class="col-md-4">
						Waktu Pemesanan:<br/><i class="text-info p-r-8 font-medium"><?php echo $this->func->ubahTgl("d M Y H:i",$rx->tgl); ?> WIB</i>
						<div class="m-t-10">
							<?php if($rx->cod == 1){ echo "<span class='badge badge-primary fs-14'>Bayar di Tempat</span>"; } ?>
							<?php if($po > 0){ echo "<span class='badge badge-warning fs-14 font-medium'><i class='fas fa-history'></i> PREORDER</span>"; } ?>
							<?php echo $cod; ?>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-4 m-b-12">
						<div class="m-t-14 showsmall"></div>
						<h5 class="text-dark">Total Order &nbsp;<span class="text-success font-bold text-right">Rp <?php echo $this->func->formUang($rx->ongkir + $rx->biaya_cod + $totalproduk); ?></span></h5>
					</div>
					<div class="col-md-8">
						<?php 
							if($po > 0){
							 	echo '<i class="text-warning">Pesanan sedang disiapkan, perkiraan produk siap pada&nbsp; <br/><span class="text-danger">'.$this->func->ubahTgl("D, d M Y",$pohari).'&nbsp; </span></i>';
							}else{
								if($rx->status == 1){
									echo '<i class="text-warning">Menunggu konfirmasi admin</i>';
								}elseif($rx->status == 2){
									echo '<i class="text-success">Pesanan sedang dikemas</i>';
								}
							}
						?>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6 m-b-16">
						<b class="text-danger">LABEL PENGIRIMAN / RESI MARKETPLACE</b><br/>
						Upload resi/label pengiriman dari marketplace untuk pesanan Anda.
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<button onclick="resimarketplace(<?=$rx->id?>)" class="btn btn-success btn-block m-b-10">
									Upload Label
								</button>
							</div>
							<div class="col-md-6">
								<button onclick="lihatResi('<?=$rx->resimarketplace?>')" class="btn btn-primary btn-block m-b-10">
									Lihat Label
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
		}
		echo $this->func->createPagination($rows,$page,$perpage,"refreshDikemas");
?>
	</div>
<?php
	}else{
?>
	<div class="text-center p-tb-40 section m-t-30">
		<i class="fas fa-box-open fs-120 text-danger m-b-20"></i>
		<h5 class="text-dark font-bold">TIDAK ADA PESANAN</h5>
	</div>
<?php
	}
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".show-product").hide();
		$(".view-product").click(function(){
			$(this).parent().parent().find(".show-product").slideToggle();
			$(this).parent().parent().find(".view-product").toggle();
		});
	});
</script>
