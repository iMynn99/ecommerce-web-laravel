<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
	$orderby = (isset($_GET["orderby"]) AND $_GET["orderby"] != "") ? $_GET["orderby"] : "tglupdate DESC, stok DESC";
	$cari = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? $this->func->clean($_GET["cari"]) : "";
	$caris = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? "?cari=".$this->func->clean($_GET["cari"]) : "";
	$perpage = 12;
	$set = $this->func->globalset("semua");
?>
	<!-- Content page -->
	<section class="p-t-30 p-b-65">
		<div class="container">
			<div class="p-b-50">
				<?php if($cari != ""){ ?><button class="btn btn-outline-secondary bg-white pencarian-btn m-r-4 m-r-0-sm" disabled>Hasil Pencarian: "<b class="text-danger"><?=$cari?></b>"</button><?php } ?>
				<div class="btn-group kategori-btn">
					<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Pilih Kategori
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<?php 
							$this->db->where("parent",0);
							$db = $this->db->get("kategori");
							foreach($db->result() as $r){
						?>
							<a href="<?=site_url("kategori/".$r->url).$caris?>" class="dropdown-item">
								<?=ucwords(strtolower($r->nama))?>
							</a>
						<?php
							}
						?>
					</div>
				</div>
			</div>

			<div class="p-b-50">
					<!-- 
					<div class="flex-sb-m flex-w p-b-35">
						<span class="s-text8 p-t-5 p-b-5">
							Showing 1–12 of 16 results
						</span>
					</div> -->

					<!-- Product -->
					<div class="row produk-wrap">
						<?php
							$this->db->select("SUM(stok) AS stok,idproduk");
							$this->db->group_by("idproduk");
							$dbvar = $this->db->get("produkvariasi");
							$notin = array();
							foreach($dbvar->result() as $not){
								if($not->stok <= 0){
									$notin[] = $not->idproduk;
								}
							}
			
							$where = "(nama LIKE '%$cari%' OR harga LIKE '%$cari%' OR hargareseller LIKE '%$cari%' OR hargaagen LIKE '%$cari%' OR deskripsi LIKE '%$cari%') AND preorder = 1 AND status = 1 AND stok > 0";
							$this->db->where($where);
							if(count($notin) > 0){
								$this->db->where_not_in($notin);
							}
							$dbs = $this->db->get("produk");
							
							$this->db->where($where);
							if(count($notin) > 0){
								$this->db->where_not_in($notin);
							}
							$this->db->limit($perpage,($page-1)*$perpage);
							$this->db->order_by($orderby);
							$db = $this->db->get("produk");
							$totalproduk = 0;
							
							foreach($db->result() as $r){
								$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
								if($level == 5){
									$result = $r->hargadistri;
								}elseif($level == 4){
									$result = $r->hargaagensp;
								}elseif($level == 3){
									$result = $r->hargaagen;
								}elseif($level == 2){
									$result = $r->hargareseller;
								}else{
									$result = $r->harga;
								}
								$ulasan = $this->func->getReviewProduk($r->id);
								$ulasan['nilai'] = ($ulasan['nilai'] > 0) ? $ulasan['nilai'] : 5;

								$this->db->where("idproduk",$r->id);
								$dbv = $this->db->get("produkvariasi");
								$totalstok = ($dbv->num_rows() > 0) ? 0 : $r->stok;
								$hargs = 0;
								$harga = array();
								foreach($dbv->result() as $rv){
									$totalstok += $rv->stok;
									if($level == 5){
										$harga[] = $rv->hargadistri;
									}elseif($level == 4){
										$harga[] = $rv->hargaagensp;
									}elseif($level == 3){
										$harga[] = $rv->hargaagen;
									}elseif($level == 2){
										$harga[] = $rv->hargareseller;
									}else{
										$harga[] = $rv->harga;
									}
									$hargs += $rv->harga;
								}

								$totalproduk += 1;
								$wishis = ($this->func->cekWishlist($r->id)) ? "active" : "";
								$hargadapat = $hargs > 0 ? min($harga) : $result;
								$diskon = $r->hargacoret > $hargadapat ? ($r->hargacoret-$hargadapat)/$r->hargacoret*100 : null;
								$kota = ($r->gudang > 0) ? $this->func->getGudang($r->gudang,"idkab") : $set->kota;
								$kota = $this->func->getKab($kota,"semua");
								$kota = $kota->tipe." ".$kota->nama;
						?>
						<div class="col-6 col-md-3 m-b-30 cursor-pointer produk-item">
							<!-- Block2 -->
							<div class="block2">
								<!--<div class="block2-wishlist" onclick="tambahWishlist(<?=$r->id?>,'<?=$r->nama?>')"><i class="fas fa-heart <?=$wishis?>"></i></div>-->
							<?php if($r->digital == 1){ ?><div class="block2-digital bg-primary"><i class="fas fa-cloud"></i> digital</div><?php } ?>
							<?php if($r->preorder == 1){ ?><div class="block2-digital bg-warning"><i class="fas fa-history"></i> preorder</div><?php } ?>
								<div class="block2-img wrap-pic-w of-hidden pos-relative" style="background-image:url('<?=$this->func->getFoto($r->id,"utama")?>');" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'"></div>
								<div class="block2-txt" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'">
									<div class="text-primary m-b-8"><small><i class="fas fa-map-marker-alt"></i> <b><?=$kota?></b></small></div>
									<a href="<?php echo site_url('produk/'.$r->url); ?>" class="block2-name dis-block p-b-5">
										<?=$r->nama?>
									</a>
									<div class="btn-block">
										<?php if($r->hargacoret > $hargadapat){ ?><span class="block2-price-coret">Rp. <?=$this->func->formUang($r->hargacoret)?></span><?php } ?>
										<?php if($diskon != null){ ?><span class="block2-label"><?=round($diskon,0)?>%</span><?php } ?>
									</div>
									<span class="block2-price p-r-5 font-medium">
										<?php 
											if($hargs > 0){
												if(max($harga) > min($harga)){
													echo "Rp. ".$this->func->formUang(min($harga))." - ".$this->func->formUang(max($harga));
												}else{
													echo "Rp. ".$this->func->formUang(min($harga));
												}
											}else{
												echo "Rp. ".$this->func->formUang($result);
											}
										?>
									</span>
								</div>
								<div class="row block2-ulasan" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'">
									<div class='col-7 text-primary font-medium'>
										<i class="fas fa-box"></i> &nbsp;<?=$totalstok?>
									</div>
									<div class='col-5 text-right'>
										<span class="text-warning font-bold"><i class='fa fa-star'></i> <?=$ulasan['nilai']?></span>
									</div>
								</div>
								<div class="row m-lr-0">
									<button type="button" class="col-md-6 btn btn-sm btn-light p-all-12" onclick="tambahWishlist(<?=$r->id?>,'<?=$r->nama?>')"><i class="fas fa-heart text-danger"></i> wishlist</button>
									<button type="button" class="col-md-6 btn btn-sm btn-light p-all-12" onclick="addtocart(<?=$r->id?>)"><i class="fas fa-shopping-basket text-success"></i> +keranjang</button>
								</div>
							</div>
						</div>
						<?php
							}
							
							if($totalproduk == 0){
								echo "<div class='col-12 text-center m-tb-40'><h4><mark><span class='text-danger'>Upss... Produk tidak ditemukan</span></mark></h4></div>";
							}
						?>
					</div>

					<!-- Pagination -->
					<div class="pagination flex-m flex-w p-t-26">
						<?php
							if($totalproduk > 0){
								echo $this->func->createPagination($dbs->num_rows(),$page,$perpage);
							}
						?>
					</div>
				</div>
			</div>
	</section>
	
	<script type="text/javascript">
		function refreshTabel(page){
			window.location.href = "<?=site_url("shop?cari=".$cari)?>&page="+page;
		}
	</script>
