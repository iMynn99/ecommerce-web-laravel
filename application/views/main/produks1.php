
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

      <?php
		$set = $this->func->getSetting("semua");
		$kategori = $this->func->getKategori($data->idcat,"semua");
		$kategorinama = (is_object($kategori)) ? $kategori->nama : "";
		$textorder = ($data->preorder == 0) ? "Tambah Ke Keranjang" : "Pre Order";
		$warnaid = array();
		$sizeid = array();
		$sizid = array();
		$aff = (isset($_SESSION["usrid"])) ? "?aff=".$_SESSION["usrid"] : "";
		
		$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
		if($level == 5){
			$result = $data->hargadistri;
		}elseif($level == 4){
			$result = $data->hargaagensp;
		}elseif($level == 3){
			$result = $data->hargaagen;
		}elseif($level == 2){
			$result = $data->hargareseller;
		}else{
			$result = $data->harga;
		}
		
		$this->db->where("idproduk",$data->id);
		$dbv = $this->db->get("produkvariasi");
		$totalstok = 0;
		$hargs = 0;
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
      ?>
			<a href="<?php echo site_url("kategori/".$kategori->url); ?>" class="text-primary">
				<?php echo ucwords(strtolower($kategorinama)); ?>
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="">
				<?php echo $data->nama; ?>
			</span>
		</div>
	</div>


	<!-- Product Detail -->
	<section class="sec-product-detail p-t-30 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
							<?php
								$this->db->where("idproduk",$data->id);
								$this->db->order_by("jenis","DESC");
								$db = $this->db->get("upload");
								$no = 1;
								foreach ($db->result() as $res){
									if($no == 1){
							?>
								<div class="prod-preview">
									<img src="<?php echo base_url("cdn/uploads/".$res->nama); ?>" alt="IMG-PRODUCT" id="prod-img">
								</div>
								<div class="prod-thumb">
									<div class="m-r-26"></div>
							<?php
									}
							?>
									<div class="prod-thumb-item" data-thumb="<?php echo base_url("cdn/uploads/".$res->nama); ?>" style="background-image:url('<?php echo base_url("cdn/uploads/".$res->nama); ?>');"></div>
							<?php
									if($no == $db->num_rows()){ echo "</div>"; }
									$no++;
								}
							?>
					</div>
				</div>

				<div class="col-md-5 p-b-20">
					<div class="section p-all-28">
						<?php
							if($data->digital > 0){
								echo "<div class=\"text-primary m-b-12 font-bold\"><i class='fas fa-cloud'></i> &nbsp;PRODUK DIGITAL</div>";
							}
						?>
						<h4 id="js-name-detail" class="font-medium p-b-14">
							<?php echo ucwords($data->nama); ?>
						</h4>

						<?php if($data->digital == 0){?>
						<div class="text-primary m-b-20">
							<?php
								$kota = ($data->gudang > 0) ? $this->func->getGudang($data->gudang,"idkab") : $set->kota;
								$kota = $this->func->getKab($kota,"semua");
								$kota = $kota->tipe." ".$kota->nama;
							?>
							Dikirim dari <i class="fas fa-map-marker-alt"></i> <b><?=$kota?></b>
						</div>
						<?php } ?>

						<?php if($data->demo != "" || $data->demoadmin != ""){ ?>
						<div class="bg-foot m-b-20 p-all-12 m-lr--12">
							<div class="row">
								<?php if($data->demo != ""){ ?>
								<div class="col-md-6 m-lr-auto">
									<a href="<?=$data->demo?>" target="_blank" class="btn-block btn btn-primary"><i class="fas fa-desktop"></i> &nbsp;Demo</a>
									<div class="m-b-12 showsmall"></div>
								</div>
								<?php } ?>
								<?php if($data->demoadmin != ""){ ?>
								<div class="col-md-6 m-lr-auto">
									<a href="<?=$data->demoadmin?>" target="_blank" class="btn-block btn btn-primary"><i class="fas fa-user-cog"></i> &nbsp;Demo Admin</a>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>

						<?php 
							if($data->preorder > 0){
								echo "<span class=\"label bg-warning text-white m-b-12 font-medium\"><i class='fas fa-history'></i> &nbsp;PRE ORDER: &nbsp;<span class='font-bold'>".$data->pohari."</span> HARI</span><br/>";
							}
							if($data->hargacoret > 0){
								echo "<span class=\"fs-20 m-r-10 harga-coret\">Rp. ".$this->func->formUang($data->hargacoret)."</span>";
							}
						?>
						
						<span id="hargacetak" class="fs-24 font-bold text-success color1">
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

						<p class="mtext-102 cl2 p-t-14">
						  <span class="fs-18">
							<?php
							  $ulasan = $this->func->getBintang($data->id);
							  $star = $ulasan["star"];
							  for($i=1; $i<=5; $i++){
								$color = ($i <= $star) ? "text-warning" : "text-secondary";
								echo '<i class="fa fa-star '.$color.'"></i>';
							  }
							?>
						  </span> &nbsp;
						  <?php echo $ulasan["jml"]; ?> Ulasan
						</p>

						<!--  -->
						<?php if($data->preorder == 0){ ?>
						<!--<div class="p-t-10 p-b-10 p-l-10 p-r-20 m-b-16 m-t-16" style="border-radius:6px;background-color:#dcdde1;color:#c0392b;position:relative;align-items:middle;">
							<span onclick="$(this).parent().hide();" class="pointer" style="position:absolute;right:10px;"><i class="fa fa-times"></i></span>
							Sebelum membeli pastikan terlebih dahulu ketersediaan stok.
						</div>-->
						<?php } ?>
						<?php
							if($dbv->num_rows() == 0){ $totalstok = $data->stok; }
							if($totalstok > 0){
						?>
						<form id="keranjang">
						  <input type="hidden" name="idproduk" value="<?php echo $data->id; ?>" />
						  <input type="hidden" id="variasi" name="variasi" value="0" />
						  <input type="hidden" id="harga" name="harga" value="<?=$result?>" />
							<div class="p-t-10">
								<div class="flex-w p-b-10">
									<?php
										if($dbv->num_rows() > 0){
											foreach($dbv->result() as $var){
												//$warna[] = $this->func->getWarna($var->warna,"nama");
												$warnaid[] = $var->warna;
												if($var->size > 0){
												$sizid[] = $var->size;
												}
												$variasi[$var->warna][] = $var->id;
												$sizeid[$var->warna][] = $var->size;
												$har[$var->warna][] = $var->harga;
												$harreseller[$var->warna][] = $var->hargareseller;
												$haragen[$var->warna][] = $var->hargaagen;
												$haragensp[$var->warna][] = $var->hargaagensp;
												$hardistri[$var->warna][] = $var->hargadistri;
												if(isset($stoks[$var->warna])){
													$stoks[$var->warna] += $var->stok;
												}else{
													$stoks[$var->warna] = $var->stok;
												}
												$stok[$var->warna][] = $var->stok;
												//$size[$var->warna][] = $this->func->getSize($var->size,"nama");
											}
											$warnaid = array_unique($warnaid);
											$warnaid = array_values($warnaid);
											$sizid = array_unique($sizid);
											$sizid = array_values($sizid);
									?>
									<div class="col-12 p-lr-0 m-b-6">
									<?=ucwords(strtolower($data->variasi))?>
									</div>
									<input type="hidden" id="warna" >
									<div class="col-12 p-lr-0 m-b-10" id="pilihwarna">
										<?php
											$this->db->select("SUM(stok) as stok,warna,id,hargadistri,hargaagensp,hargaagen,hargareseller,harga");
											$this->db->where("idproduk",$data->id);
											$this->db->group_by("warna");
											$war = $this->db->get("produkvariasi");
											foreach($war->result() as $w){
												if($level == 5){
													$hg = $w->hargadistri;
												}elseif($level == 4){
													$hg = $w->hargaagensp;
												}elseif($level == 3){
													$hg = $w->hargaagen;
												}elseif($level == 2){
													$hg = $w->hargareseller;
												}else{
													$hg = $w->harga;
												}
												if($w->stok > 0){
													echo "<button type='button' class='btn btn-outline-primary btn-sm p-lr-20 m-r-6 m-b-8' data-warna='".$w->warna."' data-stok='".$w->stok."' data-harga='".$hg."' data-variasi='".$w->id."'>".$this->func->getWarna($w->warna,"nama")."</button>";
												}
											}
										?>
									</div>
									<?php if(count($sizid) > 0){ ?>
									<div class="col-12 p-lr-0 m-b-6">
										<?=ucwords(strtolower($data->subvariasi))?>
									</div>
									<input type="hidden" id="size" >
									<div class="col-12 p-lr-0 m-b-10" id="pilihsize">
										<?php
											for($i=0; $i<count($sizid); $i++){
												echo "<button type='button' class='btn btn-outline-success btn-sm p-lr-20 m-r-6 m-b-8' data-size='".$sizid[$i]."'>".$this->func->getSize($sizid[$i],"nama")."</button>";
											}
										?>
									</div>
									<?php
											}
										}
									?>
									<div class="col-12 p-lr-0">
										Jumlah
									</div>
									<div class="m-b-10 col-12 row p-lr-0 m-lr-0 align-items-center">
										<div class="wrap-num-product input-group m-tb-10 col-6 p-lr-0">
											<div class="btn-num-product-down input-group-prepend cursor-pointer">
												<span class="input-group-text"><i class="fs-16 fa fa-minus"></i></span>
											</div>
											
											<input class="form-control text-center num-product" type="number" min="<?php echo $data->minorder; ?>" name="jumlah" value="<?php echo $data->minorder; ?>" id="jumlahorder" required>
												
											<div class="btn-num-product-up input-group-append cursor-pointer">
												<span class="input-group-text"><i class="fs-16 fa fa-plus"></i></span>
											</div>
										</div>
										<div class="col-6 p-lr-20">
											<span id="stokrefresh" class="btn-block">Stok <b><?=$totalstok?></b> Pcs</span>
											Min Order <b><?=$data->minorder?></b> Pcs
										</div>
									</div>
									<div class="col-12 m-b-0 p-lr-0">
										Catatan Pembeli
									</div>
									<div class="col-12 p-lr-0">
										<input class="form-control" type="text" name="keterangan" value="">
									</div>

									<?php if($this->func->cekLogin() == true){ ?>
									<div class="col-12 m-t-40 p-lr-0">
										<?php if($this->func->cekWishlist($data->id) == false){ ?>
										<button type="button" onclick="tambahWishlist(<?=$data->id?>,'<?=$data->nama?>')" class="btn btn-lg btn-warning btn-block btn-wish m-b-20"><i class="fas fa-heart"></i> &nbsp;Tambah ke Wishlist</button>
										<?php } ?>
										<button type="button" class="btn btn-lg btn-success btn-block m-b-24" onclick="pesanProduk(<?=$data->id?>)"><i class="fas fa-comment-dots"></i> &nbsp;Tanyakan Admin</button>
										<button type="submit" id="submit" class="btn btn-lg btn-primary btn-block"><?=$textorder?></button>
										<!--
										<a href="https://wa.me/<?=$this->func->getRandomWasap()?>/?text=Halo,%20saya%20ingin%20membeli%20produk%20*<?=$data->nama?>*%20apakah%20masih%20tersedia?" class="btn btn-block btn-lg btn-success">
											<i class="fab fa-whatsapp"></i> &nbsp;Beli via Whatsapp
										</a>
										-->
										<span id="proses" class="" style="display:none;"><b><i class="fa fa-spin fa-spinner text-primary"></i> Memproses pesanan</b></span>
										<span id="gagal" class="m-t-20" style="display:none;"><i class="text-danger fa fa-exclamation-triangle"></i> Gagal memproses pesanan.</span>
									</div>
									<?php }  ?>
								</div>
							</div>
						</form>
						<?php }else{ ?>
						<div class="p-tb-10 p-lr-20 m-b-16 m-t-32 btn font-medium bg-danger text-light btn-block">
							Maaf, Stok telah habis
						</div>
						<?php } ?>
						<?php if($this->func->cekLogin() != true){ ?>
						<div class="col-12 m-t-40 p-lr-0">
							<a href="<?php echo site_url("home/signin"); ?>" class="btn btn-lg btn-primary btn-block">
								Beli Produk
							</a>
							<!--
							<a href="https://wa.me/<?=$this->func->getRandomWasap()?>/?text=Halo,%20saya%20ingin%20membeli%20produk%20*<?=$data->nama?>*%20apakah%20masih%20tersedia?" class="btn btn-lg btn-success btn-block">
								<i class="fab fa-whatsapp"></i> &nbsp;Beli via Whatsapp
							</a>
							-->
						</div>
						<?php } ?>
						<div class="m-t-20 sharer">
							<div class="m-b-10 font-medium">Bagikan</div>
							<a href="whatsapp://send?text=<?=site_url("produk/".$data->url.$aff)?>" class="btn btn-success m-r-4 showsmall-inline" target="_blank" data-action="share/whatsapp/share">
								<i class="fab fa-whatsapp fs-20 m-t-4"></i>
							</a>
							<a href="https://api.whatsapp.com/send?text=<?=site_url("produk/".$data->url.$aff)?>" class="btn btn-success m-r-4 hidesmall" target="_blank" data-action="share/whatsapp/share">
								<i class="fab fa-whatsapp fs-20 m-t-4"></i>
							</a>
							<a href="http://www.facebook.com/sharer.php?u=<?=site_url("produk/".$data->url.$aff)?>" class="btn btn-fb m-r-4" target="_blank">
								<i class="fab fa-facebook-f fs-20 m-lr-4 m-t-4"></i>
							</a>
							<!--
							<a href="https://plus.google.com/share?url=<?=site_url("produk/".$data->url.$aff)?>" class="btn btn-gplus m-r-4" target="_blank">
								<i class="fab fa-google-plus-g fs-20 m-t-4"></i>
							</a>
							-->
							<a href="https://twitter.com/share?url=<?=site_url("produk/".$data->url.$aff)?>" class="btn btn-tw m-r-4" target="_blank">
								<i class="fab fa-twitter fs-20 m-t-4"></i>
							</a>
							<a href="mailto:?Subject=Beli%20produk%20ini&amp;Body=<?=site_url("produk/".$data->url.$aff)?>" class="btn btn-warning m-r-4" target="_blank">
								<i class="fas fa-envelope fs-20 m-t-4"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="m-t-30 p-t-24 p-b-30 row">
				<div class="wrap-dropdown-content p-t-15 p-b-14 active-dropdown-content col-md-8">
					<div class="section p-all-24">
						<h5 class="js-toggle-dropdown-content flex-sb-m cursor-pointer text-primary font-medium m-b-24">
							Deskripsi Produk
						</h5>

						<div class="dropdown-content dis-none">
							<p class="s-text8">
								<?=$this->security->xss_clean($data->deskripsi)?>
							</p>
						</div>
					</div>
				</div>
				<div class="wrap-dropdown-content p-t-15 p-b-14 active-dropdown-content col-md-4">
					<div class="section p-all-24">
						<h5 class="js-toggle-dropdown-content flex-sb-m cursor-pointer text-primary font-medium m-b-24">
							Ulasan Pembeli
						</h5>

						<div class="dropdown-content dis-none p-lr-14">
							<p class="s-text8">
								<?php
									$this->db->where("idproduk",$data->id);
									$this->db->limit(4);
									$this->db->order_by("nilai,id DESC");
									$re = $this->db->get("review");
									if($re->num_rows() > 0){
										foreach($re->result() as $rev){
											$staron = "<i class='fa fa-star text-warning'></i>";
											$staroff = "<i class='fa fa-star text-secondary'></i>";
											$star = "";
											for($i=1; $i<=5; $i++){
												$star .= ($i <= $rev->nilai) ? $staron : $staroff;
											}
											echo "
												<div class='ulasan row m-b-16'>
													<div class='col-8 font-medium text-info'>".$this->func->getProfil($rev->usrid,"nama","usrid")."</div>
													<div class='col-4 font-medium text-info'><small>".$this->func->ubahTgl("d/m/Y",$rev->tgl)."</small></div>
													<div class='col-12 m-t-4'>".$star."</div>
													<div class='col-12 m-t-10 keterangan'>
													".$rev->keterangan."
													</div>
												</div>
											";
										}
									}else{
										echo "<i>Belum ada ulasan.</i>";
									}
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- Related Products -->
	<section class="sec-relate-product p-t-45 p-b-105">
		<div class="container">
			<div class="p-b-45">
				<h3 class="t-center font-bold text-primary">
					Produk Terkait
				</h3>
			</div>

			<!-- Slide2 -->
			<div class="wrap-slick2">
				<div class="prod-thumb">
					<div class="m-r-28"></div>
          <?php
            $this->db->where("idcat",$kategori->id);
            $this->db->where("id!=",$data->id);
            $this->db->limit(12);
            $this->db->order_by("id","RANDOM");
            $dbs = $this->db->get("produk");
            foreach($dbs->result() as $re){
				$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
				if($level == 5){
					$result = $re->hargadistri;
				}elseif($level == 4){
					$result = $re->hargaagensp;
				}elseif($level == 3){
					$result = $re->hargaagen;
				}elseif($level == 2){
					$result = $re->hargareseller;
				}else{
					$result = $re->harga;
				}
				$ulasan = $this->func->getReviewProduk($re->id);

				$this->db->where("idproduk",$re->id);
				$dbvs = $this->db->get("produkvariasi");
				$totalstok = ($dbvs->num_rows() > 0) ? 0 : $re->Stok;
				$hargs = 0;
				$harga = array();
				foreach($dbvs->result() as $rv){
					$totalstok += $rv->Stok;
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
          ?>
			<div class="cursor-pointer produk-item m-lr-12 m-tb-24" style="width:240px;" onclick="window.location.href='<?php echo site_url('produk/'.$re->url); ?>'">
				<!-- Block2 -->
				<div class="block2">
					<div class="block2-img wrap-pic-w of-hidden pos-relative" style="background-image:url('<?=$this->func->getFoto($re->id,"utama")?>');"></div>
					<div class="block2-txt">
						<a href="<?php echo site_url('produk/'.$re->url); ?>" class="block2-name dis-block p-b-5">
							<?=$re->nama?>
						</a>
						<span class="block2-price p-r-5 color1">
							<?php 
								if($hargs > 0){
									echo "Rp. ".$this->func->formUang(min($harga))." - ".$this->func->formUang(max($harga));
								}else{
									echo "Rp. ".$this->func->formUang($result);
								}
							?>
						</span>
					</div>
					<div class="row block2-ulasan">
						<div class='col-6'>
							<small><?=$ulasan['ulasan']?> Ulasan</small>
						</div>
						<div class='col-6 text-right'>
							<span class="badge badge-warning bdg-1"><i class='fa fa-star'></i> <?=$ulasan['nilai']?></span>
						</div>
					</div>
				</div>
			</div>
          <?php } ?>

				</div>
			</div>
		</div>
	</section>

  <script>
	<?php if($dbv->num_rows() > 0){ ?>
	var variasi = true;
	<?php }else{ ?>
	var variasi = false;
	<?php }?>
	
	$(function(){
		$("#pilihwarna .btn").click(function(){
			$("#pilihwarna .btn").removeClass("btn-primary");
			$("#pilihwarna .btn").removeClass("btn-outline-primary");
			$("#pilihwarna .btn").addClass("btn-outline-primary");
			$(this).removeClass("btn-outline-primary");
			$(this).addClass("btn-primary");
			$("#warna").val($(this).data("warna"));
			<?php if(count($sizid) == 0){ ?>
				$("#variasi").val($(this).data('variasi'));
				$("#jumlahorder").attr("max",$(this).data('stok'));
				$("#stokmaks").html($(this).data('stok'));
				$("#harga").val($(this).data('harga'));
				$("#hargacetak").html("Rp. "+formUang($(this).data('harga')));
				$("#stokrefresh").html("Stok <b>"+$(this).data('stok')+"</b> Pcs<br/>");
			<?php }else{ ?>
				$("#pilihsize").html($("#warna_"+$(this).data("warna")).html());
				$("#variasi").val("");
				$("#stokrefresh").html("Stok <b>"+$(this).data('stok')+"</b> Pcs");
			<?php } ?>
		});

		<?php if(count($sizid) > 0){ ?>
		$("#pilihsize").on("click","button",function(){
			$("#pilihsize button").removeClass("btn-success");
			$("#pilihsize button").removeClass("btn-outline-success");
			$("#pilihsize button").addClass("btn-outline-success");
			$(this).removeClass("btn-outline-success");
			$(this).addClass("btn-success");
			$("#size").val($(this).data("size"));
			$("#variasi").val($(this).data('variasi'));
			$("#jumlahorder").attr("max",$(this).data('stok'));
			$("#stokmaks").html($(this).data('stok'));
			$("#harga").val($(this).data('harga'));
			$("#hargacetak").html("Rp. "+formUang($(this).data('harga')));
			$("#stokrefresh").html("Stok <b>"+$(this).data('stok')+"</b> Pcs<br/>");
		});
		<?php } ?>

		$(".prod-thumb-item").on("click",function(){
			$("#prod-img").attr("src",$(this).data("thumb"));
		});

		$(".btn-wish").click(function(){
			setTimeout(() => {
				$(this).hide("slow");
			}, 1000);
		});

		$("#keranjang").on("submit",function(e){
			e.preventDefault();
			<?php if($this->func->cekLogin() == true){ ?>
			if(variasi == true && $("#variasi").val() == 0){
				swal.fire("Pilih Varian", "pilih varian produk terlebih dahulu sebelum menambahkan produk ke keranjang", "warning");
			}else{
				var submit = $("#submit").html();
				$("#submit").html("<i class='fas fa-compact-disk fa-spin'></i> memproses...");
				var datar = $(this).serialize();
				datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
				$.post("<?php echo site_url("assync/prosesbeli"); ?>",datar,function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					//$("#proses").hide();
					$("#submit").html(submit);
					if(data.success == true){
						fbq('track', 'AddToCart', {content_ids:"<?=$data->id?>",content_type:"<?=$kategorinama?>",content_name:"<?=$data->nama?>",currency: "IDR", value: data.total});
						var nameProduct = $('#js-name-detail').html();
						swal.fire(nameProduct, "Berhasil ditambahkan ke keranjang", "success").then((value) => {
							window.location.href = "<?php echo site_url("home/keranjang"); ?>";
						});
					}else{
						swal.fire("Gagal", "tidak dapat memproses pesanan \n "+data.msg, "error");
					}
				});
			}
			<?php }else{ ?>
			window.location.href = "<?php echo site_url("home/signin"); ?>";
			<?php } ?>
		});

		$("#jumlahorder").change(function(){
			if(parseInt($(this).val()) < parseInt($(this).attr("min"))){
				$(this).val($(this).attr("min")).trigger("change");
			}
			
			if(parseInt($(this).val()) > parseInt($(this).attr("max"))){
				$(this).val($(this).attr("max")).trigger("change");
			}
		});
		
		/*
		$("#warna").on("change",function(){
			if($(this).val() != ""){
				$("#size").html($("#warna_"+$(this).val()).html());
			}else{
				$("#size").html("<option value=\"\">= Pilih <?=$data->variasi?> dulu =</option>");
			}
			$("#stokrefresh").html("");
		});
		*/
	});
  </script>
  
  <div style="display:none;">
	<?php
		for($i=0; $i<count($warnaid); $i++){
			echo "
				<div id='warna_".$warnaid[$i]."'>
			";
			for($a=0; $a<count($sizeid[$warnaid[$i]]); $a++){
				if($stok[$warnaid[$i]][$a] > 0){
					if($level == 5){
						$result = $hardistri[$warnaid[$i]][$a];
					}elseif($level == 4){
						$result = $haragensp[$warnaid[$i]][$a];
					}elseif($level == 3){
						$result = $haragen[$warnaid[$i]][$a];
					}elseif($level == 2){
						$result = $harreseller[$warnaid[$i]][$a];
					}else{
						$result = $har[$warnaid[$i]][$a];
					}
					echo "<button type='button' class='btn btn-outline-success btn-sm p-lr-20 m-r-6 m-b-8' data-size='".$sizeid[$warnaid[$i]][$a]."' data-stok='".$stok[$warnaid[$i]][$a]."' data-harga='".$result."' data-variasi='".$variasi[$warnaid[$i]][$a]."'>".$this->func->getSize($sizeid[$warnaid[$i]][$a],"nama")."</button>";
				}
			}
			echo "
				</div>
			";
		}
	?>
  </div>
