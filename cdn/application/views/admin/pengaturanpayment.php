<?php
	$set = $this->func->globalset("semua");
	$read = ($this->func->demo() == true) ? "readonly" : "";
?>
<div class="m-b-20">
	<span class="fas fa-info-circle fs-20 text-primary"></span> &nbsp;untuk mengaktifkan atau menonaktifkan metode pembayaran, klik tombol dibawahnya
</div>
<style rel="stylesheet">
	.op5{-webkit-filter: grayscale(100%);filter: grayscale(100%);}
	.kurir:hover .imgk{
		border-color:#00cc00;
		-webkit-filter: none;
		filter: none;
	}
	.imgk{
		border-radius: 4px;
	}
	.bgijo{
		background:#27ae60;
	}
	.bg1{
		background:#2980b9;
	}
	.bg2{
		background:#89c9eb;
	}
	.bg3{
		background:#d35400;
	}
</style>
<div class="p-tb-10">
		<div class="row m-lr-0 m-b-30">
			<div class="col-md-3 col-6 p-lr-5 m-b-30">
				<div class="imgk p-all-20 bg3">
					<img class="w-full" src="<?php echo base_url("assets/img/payment/cod.png"); ?>" />
				</div>
				<div class="btn-group g-cod col-12 m-lr-0 p-lr-0 m-t-10" role="group">
					<?php 
						$setaktif = ($set->payment_cod == 1) ? "btn-success" : "btn-light";
						$setnonaktif = ($set->payment_cod == 0) ? "btn-danger" : "btn-light";
					?>
					<button id="aktifnotifc" onclick="saveCOD(1)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setaktif?>"><b>AKTIF</b></button>
					<button id="matinotifc" onclick="saveCOD(0)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setnonaktif?>"><b>NON AKTIF</b></button>
				</div>
			</div>
			<div class="col-md-3 col-6 p-lr-5 m-b-30">
				<div class="imgk p-all-20 bgijo">
					<img class="w-full" src="<?php echo base_url("assets/img/payment/transfer.png"); ?>" />
				</div>
				<div class="btn-group g-transfer col-12 m-lr-0 p-lr-0 m-t-10" role="group">
					<?php 
						$setaktif = ($set->payment_transfer == 1) ? "btn-success" : "btn-light";
						$setnonaktif = ($set->payment_transfer == 0) ? "btn-danger" : "btn-light";
					?>
					<button id="aktifnotif" onclick="saveManual(1)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setaktif?>"><b>AKTIF</b></button>
					<button id="matinotif" onclick="saveManual(0)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setnonaktif?>"><b>NON AKTIF</b></button>
				</div>
			</div>
			<div class="col-md-3 col-6 p-lr-5 m-b-30">
				<div class="imgk p-all-20 bg1">
					<img class="w-full" src="<?php echo base_url("assets/img/payment/tripay.png"); ?>" />
				</div>
				<div class="btn-group g-tripay col-12 m-lr-0 p-lr-0 m-t-10" role="group">
					<?php 
						$setaktif = ($set->payment_tripay == 1) ? "btn-success" : "btn-light";
						$setnonaktif = ($set->payment_tripay == 0) ? "btn-danger" : "btn-light";
					?>
					<button id="aktifnotifi" onclick="saveTripay(1)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setaktif?>"><b>AKTIF</b></button>
					<button id="matinotifi" onclick="saveTripay(0)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setnonaktif?>"><b>NON AKTIF</b></button>
				</div>
			</div>
			<div class="col-md-3 col-6 p-lr-5 m-b-30">
				<div class="imgk p-all-20 bg2">
					<img class="w-full" src="<?php echo base_url("assets/img/payment/midtrans.png"); ?>" />
				</div>
				<div class="btn-group g-midtrans col-12 m-lr-0 p-lr-0 m-t-10" role="group">
					<?php 
						$setaktif = ($set->payment_midtrans == 1) ? "btn-success" : "btn-light";
						$setnonaktif = ($set->payment_midtrans == 0) ? "btn-danger" : "btn-light";
					?>
					<button id="aktifnotifm" onclick="saveMidtrans(1)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setaktif?>"><b>AKTIF</b></button>
					<button id="matinotifm" onclick="saveMidtrans(0)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setnonaktif?>"><b>NON AKTIF</b></button>
				</div>
			</div>
		</div>
	<form id="pengaturan">
		<div class="row">
			<div class="col-md-6 m-b-20">
				<div class="form-group titel" style="font-weight: bold;">
					PENGATURAN BAYAR DITEMPAT
				</div>
				<div class="form-group m-b-30">
					<label>Biaya Tambahan COD</label>
					<input type="text" name="biaya_cod" class="form-control col-md-4 col-6" value="<?=$set->biaya_cod?>" />
					<small class="text-danger"><i>apabila nilai dibawah atau sama dengan <b>100</b> maka akan dihitung dalam bentuk persen, apabila diatasnya maka akan dihitung nilai rupiah</i></small>
				</div>
				<div class="form-group titel" style="font-weight: bold;">
					PENGATURAN API TRIPAY
				</div>
				<div class="form-group">
					<label>Merchant Code <b>Tripay</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="tripay_merchant" class="form-control" value="ABCDE1234" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="tripay_merchant" class="form-control" value="<?=$set->tripay_merchant?>" />
					<?php } ?>
				</div>
				<div class="form-group">
					<label>Private Key <b>Tripay</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="tripay_privatekey" class="form-control" value="ABCDE-FGHIJK-LMNOPQ-RSTUVW-XYZ" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="tripay_privatekey" class="form-control" value="<?=$set->tripay_privatekey?>" />
					<?php } ?>
				</div>
				<div class="form-group">
					<label>API Key <b>Tripay</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="tripay_apikey" class="form-control" value="ABCDE-FGHIJK-LMNOPQ-RSTUVW-XYZ" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="tripay_apikey" class="form-control" value="<?=$set->tripay_apikey?>" />
					<?php } ?>
				</div>
				<div class="form-group">
					<label>Payment URL <b>Tripay</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="tripay_url" class="form-control" value="https://payment.tripay.co.id/api-sandbox/" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="tripay_url" class="form-control" value="<?=$set->tripay_url?>" />
					<?php } ?>
				</div>
			</div>
			<div class="col-md-6 m-b-20">
				<div class="form-group titel" style="font-weight: bold;">
					PENGATURAN API MIDTRANS
				</div>
				<div class="form-group">
					<label>Server Key <b>Midtrans</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="midtrans_server" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="midtrans_server" class="form-control" value="<?=$set->midtrans_server?>" />
					<?php } ?>
				</div>
				<div class="form-group">
					<label>Client Key <b>Midtrans</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="midtrans_client" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="midtrans_client" class="form-control" value="<?=$set->midtrans_client?>" />
					<?php } ?>
				</div>
				<div class="form-group">
					<label>Snap.js URL <b>Midtrans</b></label>
					<?php if($this->func->demo() == true){ ?>
					<input type="text" name="midtrans_snap" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
					<?php }else{ ?>
					<input type="text" name="midtrans_snap" class="form-control" value="<?=$set->midtrans_snap?>" />
					<?php } ?>
				</div>
			</div>
			<div class="col-md-12 m-b-20">
				<div class="form-group">
					<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
					<button type="reset" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Reset</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	function saveCOD(val){
		$(".g-cod button").removeClass("btn-success");
		$(".g-cod button").removeClass("btn-danger");
		$(".g-cod button").removeClass("btn-light");
		$.post("<?=site_url('api/savesetting')?>",{"payment_cod":val,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			if(val == 1){
				$("#aktifnotifc").addClass("btn-success");
				$("#matinotifc").addClass("btn-light");
			}else{
				$("#aktifnotifc").addClass("btn-light");
				$("#matinotifc").addClass("btn-danger");
			}
		});
	}
	function saveManual(val){
		$(".g-transfer button").removeClass("btn-success");
		$(".g-transfer button").removeClass("btn-danger");
		$(".g-transfer button").removeClass("btn-light");
		$.post("<?=site_url('api/savesetting')?>",{"payment_transfer":val,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			if(val == 1){
				$("#aktifnotif").addClass("btn-success");
				$("#matinotif").addClass("btn-light");
			}else{
				$("#aktifnotif").addClass("btn-light");
				$("#matinotif").addClass("btn-danger");
			}
		});
	}
	function saveTripay(val){
		$(".g-tripay button").removeClass("btn-success");
		$(".g-tripay button").removeClass("btn-danger");
		$(".g-tripay button").removeClass("btn-light");
		$.post("<?=site_url('api/savesetting')?>",{"payment_tripay":val,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			if(val == 1){
				$("#aktifnotifi").addClass("btn-success");
				$("#matinotifi").addClass("btn-light");
			}else{
				$("#aktifnotifi").addClass("btn-light");
				$("#matinotifi").addClass("btn-danger");
			}
		});
	}
	function saveMidtrans(val){
		$(".g-midtrans button").removeClass("btn-success");
		$(".g-midtrans button").removeClass("btn-danger");
		$(".g-midtrans button").removeClass("btn-light");
		$.post("<?=site_url('api/savesetting')?>",{"payment_midtrans":val,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			if(val == 1){
				$("#aktifnotifm").addClass("btn-success");
				$("#matinotifm").addClass("btn-light");
			}else{
				$("#aktifnotifm").addClass("btn-light");
				$("#matinotifm").addClass("btn-danger");
			}
		});
	}

	$(function(){
		$("#pengaturan").on("submit",function(e){
			e.preventDefault();
            <?php
				if($this->func->demo() == true){
					echo 'swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");';
				}else{
					echo '
					var datar = $(this).serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("'.site_url("api/savesetting").'",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							swal.fire("Berhasil","berhasil menyimpan pengaturan umum","success").then((val)=>{
								loadSettingPayment();
							});
						}else{
							swal.fire("Gagal","gagal menyimpan pengaturan","error");
						}
					});';
				}
            ?>
		});
	});
</script>