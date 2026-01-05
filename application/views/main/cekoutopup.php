<?php
	$set = $this->func->getSetting("semua");
	$bayartotal = $data->total;
?>
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="<?php echo site_url(); ?>" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Invoice
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->
	<style rel="stylesheet">
		@media only screen and (min-width:721px){
			.mobilefix{
				margin-left: -36px;
			}
		}
	</style>
	<div class="p-t-0 p-b-85">
		<div class="container p-t-10 p-b-50" style="background: #f8f9fa1c;">
			<div class="row">
				<div class="col-md-7 m-l-auto m-r-auto">
					<div class="p-lr-40 p-t-30 p-b-40 m-l-0-xl m-r-0-xl p-r-15-sm p-l-15-sm">
						<div class="row">
							<div class="col-md-2 col-4">
								<i class="fas fa-check-circle text-success fs-54"></i>
							</div>
							<div class="col-md-10 col-8">
								<span class="fs-16">Order ID <?php echo $data->trxid; ?></span><br/>
								<h4 class="text-primary font-medium">Terima Kasih <?php echo $this->func->getProfil($data->usrid,"nama","usrid"); ?></h4>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-10 m-l-auto m-r-auto m-b-30">
					<div class="p-l-40 p-r-40 p-t-30 p-b-40 m-r-0-xl m-l-0-xl p-r-15-sm p-l-15-sm">
						<div class="section p-all-28 m-b-20">
							<div class="m-b-20">
								<!--<h5 class="text-black">Metode Pembayaran: <span class="cl1" style="font-size: 16px;">Virtual Account, E-Wallet, Mini Market, Dll</span> </h5>-->
								<h5 class="text-black">Mohon lakukan pembayaran sejumlah <span style="color: #c0392b; font-size: 20px;"><b>Rp <?php echo $this->func->formUang($bayartotal); ?></b></span></h5>
							</div>
							<div class="m-b-20">
								<h5 class="text-black">Silahkan transfer pembayaran ke rekening berikut :</h5>
							</div>
							<div class="">
								<p></p>
								<?php
									foreach($bank->result() as $bn){
											echo '
												<h5 class="cl2 m-t-10 m-b-10 p-t-10 p-l-10 p-b-10" style="border-left: 8px solid #C0A230;">
													<b class="text-danger">Bank '.$bn->nama.' : </b><b class="text-success">'.$bn->norek.'</b><br/>
													<span style="font-size: 90%">a/n '.$bn->atasnama.'<br/>
													KCP '.$bn->kcp.'</span>
												</h5>
											';
									}
								?>
								<p class="m-b-5 m-t-20">
								<b>PENTING : </b>
								</p>
								<ul style="margin-left: 15px;">
									<li style="list-style-type: disc;">Mohon lakukan pembayaran dalam <b>1 x 24 jam</b></li>
									<!--<li style="list-style-type: disc;">Sistem akan otomatis mendeteksi apabila pembayaran sudah masuk</li>-->
									<li style="list-style-type: disc;">Apabila sudah transfer dan status pembayaran belum berubah, mohon konfirmasi pembayaran manual melalui WA admin di bawah</li>
									<li style="list-style-type: disc;">Pesanan akan dibatalkan secara otomatis jika Anda tidak melakukan pembayaran.</li>
								</ul>
							</div>
						</div>
						<button class="btn btn-primary btn-block btn-lg text-center m-b-20" onclick="konfirmasi()"><i class="fa fa-cloud-upload-alt"></i> &nbsp;<b>UPLOAD BUKTI TRANSFER</b></button>
						<?php if($data->bukti != ""){ ?>
						<button class="btn btn-success btn-block btn-lg text-center m-b-20" onclick="bukti()"><i class="fa fa-receipt"></i> &nbsp;<b>LIHAT BUKTI TRANSFER</b></button>
						<?php } ?>
						<a href="<?php echo site_url("manage"); ?>" class="btn btn-danger btn-block btn-lg text-center bayarotomatis"><i class="fa fa-times"></i> &nbsp;<b>BAYAR NANTI SAJA</b></a>
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	function bayarManual(){
		$(".metode-item").removeClass("active");
		$(".metode-item.manual").addClass("active");
		$(".bayarmanual").show();
		$(".bayarotomatis").hide();
	}
	function bayarOtomatis(){
		$(".metode-item").removeClass("active");
		$(".metode-item.otomatis").addClass("active");
		$(".bayarmanual").hide();
		$(".bayarotomatis").show();
	}
	function konfirmasi(){
		$('#konfirmasimodal').modal();
	}
	function bukti(){
		$('#buktimodal').modal();
	}
</script>

<!-- Modal1 -->
<div class="modal fade" id="konfirmasimodal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Konfirmasi Pembayaran</h5>
				<button type="button" data-dismiss="modal" aria-label="Close">
					<i class="fas fa-times text-danger fs-24 p-all-2"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="row m-lr-0 p-tb-20">
					<div class="col-md-12 p-b-20">
						Upload Bukti Transfer <span class="fs-14">(.jpg, .png, .pdf)</span>
					</div>
					<form id="upload" class="row p-lr-0 m-lr-0 w-full" method="POST" enctype="multipart/form-data" action="<?php echo site_url("manage/konfirmasitopup"); ?>">
						<input name="idbayar" type="hidden" id="bayar" value="<?=$data->id?>"/>
						<input type="hidden" class="tokens" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>" />
						<div class="col-md-12 p-b-20">
							<input type="file" name="bukti" class="form-control" accept="image/*" />
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-success">
								<i class="fas fa-chevron-circle-up"></i> Upload
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal1 -->
<div class="modal fade" id="buktimodal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body text-center">
				<img src="<?=base_url("cdn/konfirmasi/".$data->bukti)?>" style="max-width:100%;max-height:80vh;" />
			</div>
		</div>
	</div>
</div>
