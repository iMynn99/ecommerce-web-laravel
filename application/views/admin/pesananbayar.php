	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "" AND intval($_GET["page"]) > 0) ? intval($_GET["page"]) : 1;
		$perpage = 10;

		$this->db->where("status",0);
		$this->db->where("usrid",$_SESSION["usrid"]);
		$rows = $this->db->get("pembayaran");
		$rows = $rows->num_rows();

		$this->db->where("status",0);
		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->order_by("status ASC, id DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("pembayaran");
		if($db->num_rows() > 0){
		?>
	<div class="pesanan">
		<?php
			foreach($db->result() as $res){
				$idbyr = $this->func->arrEnc(array("idbayar"=>$res->id),"encode");
				$this->db->where("idbayar",$res->id);
				$konf = $this->db->get("konfirmasi");
				$link = $res->id; //$this->func->arrEnc(array("idbayar"=>$res->id),"encode");
				$klik = "openLink('".site_url("home/invoice?inv=".$link."&ubahmetode=true")."')";
	?>

		<div class="m-b-30">
			<div class="pesanan-item p-lr-30 p-tb-30 m-lr-0-xl">
				<div class="row p-b-30">
					<div class="col-8">
						<span class="text-dark font-medium fs-18">
							Payment ID&nbsp; <span class="text-success">#<?php echo $res->invoice; ?></span>
						</span>
					</div>
					<div class="col-4 text-right">
						<a href="javascript:void(0)" onclick="batal(<?php echo $res->id; ?>)" class="btn btn-danger btn-sm">
							<i class="fas fa-times-circle"></i> batal<span class='hidesmall'>kan pesanan</span>
						</a>
					</div>
				</div>
				<div class="row m-lr-0">
					<div class="col-md-8 p-lr-0 m-b-10">
						<?php
							$this->db->where("idbayar",$res->id);
							$trx = $this->db->get("transaksi");
							$no = 1;
							foreach($trx->result() as $rx){
								$this->db->where("idtransaksi",$rx->id);
								$trp = $this->db->get("transaksiproduk");
								foreach ($trp->result() as $key) {
									$produk = $this->func->getProduk($key->idproduk,"semua");
									$variasee = ($key->variasi != 0) ? $this->func->getVariasi($key->variasi,"semua") : null;
									$variasi = ($key->variasi != 0 AND $variasee != null) ? $this->func->getWarna($variasee->warna,"nama")." ".$produk->subvariasi." ".$this->func->getSize($variasee->size,"nama") : "";
									$variasi = ($key->variasi != 0 AND $variasee != null) ? "<small class='text-primary'>".$produk->variasi.": ".$variasi."</small>" : "";
									//if($no == 1){
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
								<span class="font-medium text-dark btn-block"><?php if($produk != null){ echo $produk->nama; }else{ echo "Produk telah dihapus"; } ?></span>
								<?=$variasi?>
								<div>Rp <?php echo $this->func->formUang($key->harga); ?> <span style="font-size:11px">x<?php echo $key->jumlah; ?></span></div>
								<?php if($produk->id > 0 AND $produk->preorder > 0){ echo "<span class='badge badge-warning fs-14 font-medium'><i class='fas fa-history'></i> PREORDER</span>"; } ?>
							</div>
						</div>
						<?php
									$no++;
								}
							}
							if($no > 2){
						?>
						</div>
						<div class="p-b-30 p-r-10">
							<a href="javascript:void(0)" class="view-product text-info"><i class="fas fa-chevron-circle-down"></i> Lihat produk lainnya</a>
							<a href="javascript:void(0)" class="view-product text-info" style="display:none;"><i class='fas fa-chevron-circle-up'></i> Sembunyikan produk</a>
						</div>
						<?php
							}
						?>
					</div>
					<div class="row m-lr-0 p-lr-12 col-md-4">
						<div class="text-black fs-18 p-lr-0 col-6 col-md-12">Total<span class="hidesmall"> Pembayaran</span></div>
						<div class="text-danger fs-20 p-lr-0 col-6 col-md-12 font-bold">Rp <?php echo $this->func->formUang($res->total); ?></div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 m-b-16">
						<p>Segera lakukan pembayaran dalam <b class="text-danger">1 x 24 jam</b>, atau pesanan Anda akan Otomatis Dibatalkan.</p>
					</div>
					<div class="col-md-6">
						<div class="row">

								<?php
									$this->db->where("idbayar",$res->id);
									$knf = $this->db->get("konfirmasi");

									if($knf->num_rows() > 0){
										echo "<div class='col-md-12 cl1 txt-center'><b>status pembayaran:</b> <i>menunggu verifikasi sistem</i>";
										foreach($knf->result() as $ref){
											echo "<br/><b>waktu konfirmasi:</b> <i>".$this->func->ubahTgl("d M Y H:i",$ref->tgl)." WIB</i>";
										}
										echo "</div>";
									}else{
										if($res->metode_bayar == 4){
											if($res->midtrans_id != ""){
								?>
								<div class="col-md-6">
									<a href="javascript:void(0)" onclick="cekMidtrans(<?=$res->id?>)" class="btn btn-success btn-block m-b-10">
										Cek Status
									</a>
								</div>
								<?php
											}else{
								?>
								<div class="col-md-6">
									<a href="<?=site_url('home/invoice?inv='.$link)?>" class="btn btn-success btn-block m-b-10">
										Bayar Sekarang
									</a>
								</div>
								<?php
											}
								?>
								<div class="col-md-6">
									<a href="javascript:void(0)" onclick="bayarUlang('<?=$res->id?>','<?=$link?>')" class="btn btn-warning btn-block m-b-10">
										Ubah Metode
									</a>
								</div>
								<?php
										}elseif($res->metode_bayar == 3){
								?>
								<div class="col-md-6">
									<a href="<?=site_url('home/invoice?inv='.$link)?>" class="btn btn-success btn-block m-b-10">
										Bayar Sekarang
									</a>
								</div>
								<div class="col-md-6">
									<a href="javascript:void(0)" onclick="<?=$klik?>" class="btn btn-warning btn-block m-b-10">
										Ubah Metode
									</a>
								</div>
								<?php
										}else{
								?>
								<div class="col-md-8">
									<a href="javascript:void(0)" onclick="<?=$klik?>" class="btn btn-success btn-block m-b-10">
										Ubah Metode Pembayaran
									</a>
								</div>
								<div class="col-md-4">
									<a href="javascript:void(0)" onclick="konfirmasi(<?php echo $res->id; ?>)" class="btn btn-warning btn-block m-b-10">
										Konfirmasi
									</a>
								</div>
								<?php
										}
									}
								?>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6 m-b-16">
						<b class="text-danger">LABEL PENGIRIMAN / RESI MARKETPLACE</b><br/>
						Upload resi / label pengiriman dari marketplace untuk pesanan Anda.
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
			echo $this->func->createPagination($rows,$page,$perpage,"refreshBelumbayar");
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
		function openLink(id){
			window.location.href = id;
		}
	</script>