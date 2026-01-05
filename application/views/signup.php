<!-- Login -->
<div style="margin-top:10vh">
	<div class="container p-b-20">
		<div class="m-b-30 text-center">
			<img src="<?= base_url('cdn/assets/img/'.$set->logo) ?>" style="max-height:60px;max-width:60%;" />
		</div>

		<div class="row p-lr-20">
			<div class="section col-md-6 m-l-auto m-r-auto p-t-30">
				<div class="font-bold text-primary text-center m-b-20 fs-24">Mendaftar</div>
				<div class="p-lr-20 m-lr-0-xl p-lr-15-sm" id="load">
					<?php 
						if($set->login_otp == 0){
					?>
						<form id="signup" class="p-b-40 p-lr-30">
							<div class="m-b-12">
								<input class="form-control" type="text" id="nama" name="nama" placeholder="Nama Lengkap" required >
							</div>
							<div class="m-b-12">
								<input onkeypress="return isNumber(event)" class="form-control" type="text" name="nohp" placeholder="No Whatsapp" required >
							</div>
							<div class="bor8 m-b-12 how-pos4-parent">
								<input class="form-control" type="text" id="email" name="email" placeholder="Alamat Email" required >
							</div>
							<p id="imelerror" class="text-danger" style="display:none;"><small>terjadi kesalahan, mohon formulir dilengkapi dulu</small></p>
							<div class="bor8 m-t-15 m-b-12 how-pos4-parent">
								<input class="form-control" type="password" name="pass" placeholder="Password" required >
							</div>
							<div class="rs1-select2 rs2-select2 bor8 how-pos4-parent m-b-12">
								<select class="form-control js-select2" name="kelamin" required >
									<option value="">Jenis Kelamin</option>
									<option value="1">Laki - laki</option>
									<option value="2">Perempuan</option>
								</select>
								<div class="dropDownSelect2"></div>
							</div>
							<div class="row m-t-10">
								<div class="col-md-12">
									<p class="text-warning imelcek" style="display:none;"><i class="fas fa-spin fa-compact-disc"></i> sedang memeriksa...</p>
									<div id="proses" style="display:none;"><h5 class="cl1"><i class="fas fa-compact-disc fa-spin text-success"></i> Memproses...</h5></div>
									<button id="submit" type="submit" class="btn btn-success btn-lg btn-block">MENDAFTAR</button>
									<button type="button" class="btn btn-medium btn-lg btn-block imelcek" style="display:none;">MENDAFTAR</button>
									<p class="text-center m-t-20 m-b-10">
										Sudah punya akun?&nbsp;
										<a href="<?php echo site_url("home/signin"); ?>" class="font-medium">Masuk</a>
									</p>
									
								</div>
							</div>
						</form>
					<?php
						}else{
					?>
						<form id="signup_otp" class="p-b-40 p-lr-30">
							<div class="m-b-18">
								<input class="form-control p-tb-28 p-lr-24 fs-16 font-medium text-center" type="text" name="nama" placeholder="Masukkan Nama Lengkap" required >
							</div>
							<div class="m-b-12 t-center">
								masukkan nomor whatsapp atau alamat email anda untuk mengirimkan kode otp
							</div>
							<div class="m-b-18">
								<input class="form-control p-tb-28 p-lr-24 fs-20 font-medium text-center" type="text" id="emailhp" name="email" placeholder="No Handphone / Email" required >
								<p id="imelerror" class="text-danger" style="display:none;"><small>terjadi kesalahan, mohon formulir dilengkapi dulu</small></p>
								<p class="text-warning imelcek" style="display:none;"><i class="fas fa-spin fa-compact-disc"></i> sedang memeriksa...</p>
							</div>
							<div class="row m-t-20">
								<div class="col-md-12">
									<div id="proses" style="display:none;"><h5 class="cl1"><i class="fas fa-compact-disc fa-spin text-success"></i> Memproses...</h5></div>
									<button id="submit" type="submit" class="btn btn-success btn-lg btn-block">MENDAFTAR</button>
									<button type="button" class="btn btn-medium btn-lg btn-block imelcek" style="display:none;">MENDAFTAR</button>
									<p class="text-center m-t-20 m-b-10">
										Sudah punya akun?&nbsp;
										<a href="<?php echo site_url("home/signin"); ?>" class="font-medium">Masuk</a>
									</p>
								</div>
							</div>
						</form>
					<?php
						}
					?>
					<div class="line-text p-t-30 p-b-12">
						<div class="text"><span>metode lainnya</span></div>
					</div>
					<div class="text-center p-b-30">
						<a href="<?=$google_url?>" class="btn btn-default btn-lg">
							<img src="<?=base_url("assets/images/google.png")?>" style="height:26px;" class="p-r-12" />
							<small><b>Signup with Google</b></small>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
  	function validation(){
  		return 0;
  	}
  	function isNumber(evt) {
  		evt = (evt) ? evt : window.event;
  		var charCode = (evt.which) ? evt.which : evt.keyCode;
  		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
  			return false;
  		}
  		return true;
  	}

    $(".email").each(function(){
      if($(this).val() != ""){
        $(this).trigger("change");
      }
    });

  	$(function(){
  		localStorage["error"] = 1;

  		$("#signup").on("submit",function(e){
  			e.preventDefault();

  			if(localStorage["error"] == 0){
				if($("#email").val().length > 8){
					$("input,select").prop("readonly",true);
						$("#proses").show();
						$("#submit").hide();
				//	$("#submit").html("<i class='fa fa-spin fa-spinner'></i> tunggu sebentar...");
					var datar = $(this).serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?php echo site_url("home/signup"); ?>",datar,function(msg){
						var res = eval('('+msg+')');
						updateToken(res.token);
						if(res.success == true){
							fbq('track', 'CompleteRegistration',{content_name:$("#nama").val()});
							$("#load").html(res.result);
							$('html, body').animate({ scrollTop: $("#load").offset().top - 300 });
						}else{
							swal.fire("Belum sesuai","Cek kembali alamat email atau nomor handphone apakah sudah benar/sesuai?","error");
						}
					});
				}else{
					swal.fire("Belum sesuai","Cek kembali alamat email atau nomor handphone apakah sudah benar/sesuai?","error");
				}
  			}else{
  				swal.fire("Sudah terdaftar","Alamat email atau nomor handphone sudah terdaftar, silahkan menuju halaman login untuk masuk ke akun","error");
  			}
  		});
  		$("#signup_otp").on("submit",function(e){
  			e.preventDefault();

  			if(localStorage["error"] == 0){
				if($("#emailhp").val().length > 8){
					$("input,select").prop("readonly",true);
					$("#proses").show();
					$("#submit").hide();
					var datar = $(this).serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?php echo site_url("home/signup_otp"); ?>",datar,function(msg){
						var result = eval('('+msg+')');
						updateToken(result.token);
						fbq('track', 'CompleteRegistration',{content_name:$("#emailhp").val()});
						window.location.href="<?=site_url("home/signup_otp/challenge")?>";
					});
				}else{
					swal.fire("Belum sesuai","Cek kembali alamat email atau nomor handphone apakah sudah benar/sesuai?","error");
				}
  			}else{
  				swal.fire("Sudah terdaftar","Alamat email atau nomor handphone sudah terdaftar, silahkan menuju halaman login untuk masuk ke akun","error");
  			}
  		});

  		$("#email,#emailhp").keyup(function(){
			$("#submit").hide();
			$(".imelcek").show();
  			$("#imelerror").hide();
		});
  		$("#email").change(function(){
			$("#submit").hide();
			$(".imelcek").show();
  			if($(this).val().indexOf("@") != -1 && $(this).val().indexOf(".") != -1){
  				$.post("<?php echo site_url("home/signup/cekemail"); ?>",{"email":$("#email").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
					$("#submit").show();
					$(".imelcek").hide();
  					var result = eval('('+msg+')');
					updateToken(result.token);
					if(result.success == true){
  						$("#imelerror").hide();
						localStorage["error"] = 0;
  					}else{
						localStorage["error"] = 1;
  						$("#imelerror").show();
  						$("#imelerror small").html(result.message);
  					}
  				});
  			}else{
				$("#submit").show();
				$(".imelcek").hide();
				localStorage["error"] = 1;
  				$("#imelerror").show();
  				$("#imelerror small").html("masukkan format email dengan benar");
  			}
      	});
  		$("#emailhp").change(function(){
			$("#submit").hide();
			$(".imelcek").show();
  			$.post("<?php echo site_url("home/signup/cekemail"); ?>",{"email":$("#emailhp").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
				$("#submit").show();
				$(".imelcek").hide();
  				var result = eval('('+msg+')');
				updateToken(result.token);
				if(result.success == true){
  					$("#imelerror").hide();
					localStorage["error"] = 0;
  				}else{
  					$("#imelerror").show();
					localStorage["error"] = 1;
  					$("#imelerror small").html(result.message);
				}
			});
      	});

  	});
</script>
