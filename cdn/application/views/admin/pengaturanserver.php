<?php
	$set = $this->func->globalset("semua");
	$read = ($this->func->demo() == true) ? "readonly" : "";
?>
<form id="pengaturan">
	<div class="row">
		<div class="col-md-6 m-b-20">
			<div class="form-group titel" style="font-weight: bold;">
                PENGATURAN LOGIN OTP
			</div>
			<div class="btn-group g-otp col-12 m-lr-0 form-group m-b-10 col-md-6 m-b-30" role="group">
				<?php 
					$setaktif = ($set->login_otp == 1) ? "btn-success" : "btn-light";
					$setnonaktif = ($set->login_otp == 0) ? "btn-success" : "btn-light";
				?>
				<button id="aktifotp" onclick="saveOTP(1)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setaktif?>"><b>OTP</b></button>
				<button id="aktifmanual" onclick="saveOTP(0)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$setnonaktif?>"><b>MANUAL</b></button>
			</div>
			<div class="form-group titel" style="font-weight: bold;">
                PENGATURAN SERVER EMAIL
			</div>
			<div class="form-group">
				<label>Metode Pengiriman</label>
				<select class="form-control col-6" name="email_jenis" <?=$read?>>
                    <option value="1" <?php if($set->email_jenis == 1){ echo "selected";} ?> >sendMail()</option>
                    <option value="2" <?php if($set->email_jenis == 2){ echo "selected";} ?> >SMTP</option>
				</select>
			</div>
			<div class="form-group">
				<label>Email Pengirim Notifikasi</label>
				<input type="text" name="email_notif" class="form-control" value="<?=$set->email_notif?>" <?=$read?> />
			</div>
			<div class="form-group">
				<label>Password Email</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="password" name="email_password" class="form-control col-6" value="abcdefghijk1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="password" name="email_password" class="form-control col-6" value="<?=$set->email_password?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label>Mail Server Domain</label>
				<input type="text" name="email_server" class="form-control col-10 col-md-8" value="<?=$set->email_server?>" <?=$read?> />
			</div>
			<div class="form-group">
				<label>Mail Server Port</label>
				<input type="text" name="email_port" class="form-control col-6 col-md-3" value="<?=$set->email_port?>" <?=$read?> />
			</div>
		</div>
		<div class="col-md-6 m-b-20">
			<div class="form-group titel" style="font-weight: bold;">
                PENGATURAN API WHATSAPP
			</div>
			<div class="btn-group g-vendor col-12 m-lr-0 form-group m-b-10" role="group">
				<?php 
					$setaktif = ($set->api_wasap == "woowa") ? "btn-success" : "btn-light";
					$setnonaktif = ($set->api_wasap == "wablas") ? "btn-success" : "btn-light";
					$setss = ($set->api_wasap == "starsender") ? "btn-success" : "btn-light";
					$setwagw = ($set->api_wasap == "wagw") ? "btn-success" : "btn-light";
					$setwatzap = ($set->api_wasap == "watzap") ? "btn-success" : "btn-light";
				?>
				<button id="aktifwoowa" onclick="saveApiWasap('woowa')" type="button" style="border: 1px solid #bbb;" class="col-4 btn btn-sm <?=$setaktif?>"><b>WOOWA</b></button>
				<button id="aktifwablas" onclick="saveApiWasap('wablas')" type="button" style="border: 1px solid #bbb;" class="col-4 btn btn-sm <?=$setnonaktif?>"><b>WABLAS</b></button>
				<button id="aktifss" onclick="saveApiWasap('starsender')" type="button" style="border: 1px solid #bbb;" class="col-4 btn btn-sm <?=$setss?>"><b>STARSENDER</b></button>
				<button id="aktifwagw" onclick="saveApiWasap('wagw')" type="button" style="border: 1px solid #bbb;" class="col-4 btn btn-sm <?=$setwagw?>"><b>JADIORDER</b></button>
				<button id="aktifwatzap" onclick="saveApiWasap('watzap')" type="button" style="border: 1px solid #bbb;" class="col-4 btn btn-sm <?=$setwatzap?>"><b>WATZAP</b></button>
			</div>
			<div class="form-group woowa" <?php if($set->api_wasap != "woowa"){ echo 'style="display:none"'; } ?>>
				<label>API Key <b>WooWA</b> (<a href="https://woowa.com/" target="_blank">woowa.com</a>)</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="woowa" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="woowa" class="form-control" value="<?=$set->woowa?>" />
				<?php } ?>
				<small><i class="text-danger">kosongkan apabila ingin menonaktifkan notifikasi Whatsapp</i></small><br/>
			</div>
			<div class="form-group wablas" <?php if($set->api_wasap != "wablas"){ echo 'style="display:none"'; } ?>>
				<label>API Key <b>Wablas</b> (<a href="https://wablas.com/" target="_blank">wablas.com</a>)</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="wablas" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="wablas" class="form-control" value="<?=$set->wablas?>" />
				<?php } ?>
				<small><i class="text-danger">kosongkan apabila ingin menonaktifkan notifikasi Whatsapp</i></small><br/>
			</div>
			<div class="form-group wablas" <?php if($set->api_wasap != "wablas"){ echo 'style="display:none"'; } ?>>
				<label>Domain Server <b>Wablas</b></label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="wablas_server" class="form-control" value="https://domain.wablas.com" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="wablas_server" class="form-control" value="<?=$set->wablas_server?>" />
				<?php } ?>
			</div>
			<div class="form-group ss" <?php if($set->api_wasap != "starsender"){ echo 'style="display:none"'; } ?>>
				<label>API Key <b>Star Sender</b> (<a href="https://starsender.id/" target="_blank">starsender.id</a>)</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="starsender" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="starsender" class="form-control" value="<?=$set->starsender?>" />
				<?php } ?>
				<small><i class="text-danger">kosongkan apabila ingin menonaktifkan notifikasi Whatsapp</i></small><br/>
			</div>
			<div class="form-group watzap" <?php if($set->api_wasap != "watzap"){ echo 'style="display:none"'; } ?>>
				<label>Nomor Sender (diawali dengan kode negara, contoh: 6281234567890)</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="watzap_nomer" class="form-control" value="<?=$set->watzap_nomer?>" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="watzap_nomer" class="form-control" value="<?=$set->watzap_nomer?>" />
				<?php } ?>
			</div>
			<div class="form-group watzap" <?php if($set->api_wasap != "watzap"){ echo 'style="display:none"'; } ?>>
				<label>ApiKey WATZAP</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="watzap_apikey" class="form-control" value="<?=$set->watzap_apikey?>" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="watzap_apikey" class="form-control" value="<?=$set->watzap_apikey?>" />
				<?php } ?>
			</div>
			<div class="form-group watzap" <?php if($set->api_wasap != "watzap"){ echo 'style="display:none"'; } ?>>
				<label>Domain/Subdomain Server Whatsapp</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="watzap_domain" class="form-control" value="<?=$set->watzap_domain?>" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="watzap_domain" class="form-control" value="<?=$set->watzap_domain?>" />
				<?php } ?>
			</div>
			<div class="form-group wagw" <?php if($set->api_wasap != "wagw"){ echo 'style="display:none"'; } ?>>
				<label>Nomor Sender (diawali dengan kode negara, contoh: 6281234567890)</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="wagw_nomer" class="form-control" value="6281234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="wagw_nomer" class="form-control" value="<?=$set->wagw_nomer?>" />
				<?php } ?>
			</div>
			<div class="form-group wagw" <?php if($set->api_wasap != "wagw"){ echo 'style="display:none"'; } ?>>
				<label>Domain/Subdomain Server Whatsapp</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="wagw_domain" class="form-control" value="https://whatsapp.jadiorder.com/" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="wagw_domain" class="form-control" value="<?=$set->wagw_domain?>" />
				<?php } ?>
			</div>
			<div class="form-group wagw" <?php if($set->api_wasap != "wagw"){ echo 'style="display:none"'; } ?>>
				<label>Klik tombol dibawah untuk Cek Koneksi atau Scan QR kalau belum terkoneksi</label>
				<button type="button" onclick="cekWA()" class="btn btn-primary">Cek Koneksi / Scan QR</button>
			</div>
			<div class="form-group titel m-t-30" style="font-weight: bold;">
                PENGATURAN API LAINNYA
			</div>
			<div class="form-group">
				<label>Facebook Pixel ID</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="fb_pixel" class="form-control" value="12345678901234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="fb_pixel" class="form-control" value="<?=$set->fb_pixel?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label>API Key <b>Raja Ongkir PRO</b></label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="rajaongkir" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="rajaongkir" class="form-control" value="<?=$set->rajaongkir?>" />
				<?php } ?>
			</div>
			<div class="form-group titel m-t-30" style="font-weight: bold;">
                GOOGLE AUTH & PUSH NOTIFICATION MOBILE	
			</div>
			<div class="form-group">
				<label>Server Key Token <b>Firebase Cloud Messaging</b></label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="fcm_serverkey" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="fcm_serverkey" class="form-control" value="<?=$set->fcm_serverkey?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label><b>Google OAuth</b> Client ID</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="google_client_id" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="google_client_id" class="form-control" value="<?=$set->google_client_id?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label><b>Google OAuth</b> Client Secret</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" name="google_client_secret" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?=$read?> />
				<?php }else{ ?>
				<input type="text" name="google_client_secret" class="form-control" value="<?=$set->google_client_secret?>" />
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

<script type="text/javascript">
	function saveApiWasap(val){
        <?php
			if($this->func->demo() == true){
				echo 'swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");';
			}else{
				echo '
					$(".g-vendor button").removeClass("btn-success");
					$(".g-vendor button").removeClass("btn-light");
					$.post("'.site_url('api/savesetting').'",{"api_wasap":val,[$("#names").val()]:$("#tokens").val()},function(ev){
						var data = eval("("+ev+")");
						updateToken(data.token);
						if(val == "woowa"){
							$(".woowa").show();
							$(".wablas").hide();
							$(".ss").hide();
							$(".wagw").hide();
							$("#aktifwoowa").addClass("btn-success");
							$("#aktifwablas").addClass("btn-light");
							$("#aktifss").addClass("btn-light");
							$("#aktifwagw").addClass("btn-light");
						}else if(val == "starsender"){
							$(".woowa").hide();
							$(".wablas").hide();
							$(".wagw").hide();
							$(".ss").show();
							$("#aktifwoowa").addClass("btn-light");
							$("#aktifwablas").addClass("btn-light");
							$("#aktifss").addClass("btn-success");
							$("#aktifwagw").addClass("btn-light");
						}else if(val == "wagw"){
							$(".woowa").hide();
							$(".wablas").hide();
							$(".ss").hide();
							$(".wagw").show();
							$("#aktifwoowa").addClass("btn-light");
							$("#aktifwablas").addClass("btn-light");
							$("#aktifss").addClass("btn-light");
							$("#aktifwagw").addClass("btn-success");
						}else if(val == "watzap"){
							$(".woowa").hide();
							$(".wablas").hide();
							$(".ss").hide();
							$(".wagw").hide();
							$(".watzap").show();
							$("#aktifwoowa").addClass("btn-light");
							$("#aktifwablas").addClass("btn-light");
							$("#aktifss").addClass("btn-light");
							$("#aktifwagw").addClass("btn-light");
							$("#aktifwatzap").addClass("btn-success");
						}else{
							$(".woowa").hide();
							$(".wablas").show();
							$(".wagw").hide();
							$(".ss").hide();
							$(".watzap").hide();
							$("#aktifwoowa").addClass("btn-light");
							$("#aktifwablas").addClass("btn-success");
							$("#aktifss").addClass("btn-light");
							$("#aktifwagw").addClass("btn-light");
							$("#aktifwatzap").addClass("btn-light");
						}
					});
				';
			}
        ?>
	}
	function saveOTP(val){
        <?php
			if($this->func->demo() == true){
				echo 'swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");';
			}else{
				echo '
					$(".g-otp button").removeClass("btn-success");
					$(".g-otp button").removeClass("btn-light");
					$.post("'.site_url('api/savesetting').'",{"login_otp":val,[$("#names").val()]:$("#tokens").val()},function(ev){
						var data = eval("("+ev+")");
						updateToken(data.token);
						if(val == 1){
							$("#aktifotp").addClass("btn-success");
							$("#aktifmanual").addClass("btn-light");
						}else{
							$("#aktifotp").addClass("btn-light");
							$("#aktifmanual").addClass("btn-success");
						}
					});
				';
			}
        ?>
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
					datar = datar +  "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("'.site_url("api/savesetting").'",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							swal.fire("Berhasil","berhasil menyimpan pengaturan umum","success").then((val)=>{
								loadSettingServer();
							});
						}else{
							swal.fire("Gagal","gagal menyimpan pengaturan","error");
						}
					});';
				}
            ?>
		});
	});

	<?php if($this->func->demo() == true){ ?>
		function cekWA(){
			swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");
		}
	<?php }elseif($set->wagw_nomer == "" OR $set->wagw_domain == ""){ ?>
		function cekWA(){
			swal.fire("Tidak dapat terhubung","Masukkan dulu nomer sender dan domain/subdomain server WA Jadiorder","error");
		}
	<?php }else{ ?>
	var socket = io('<?=$set->wagw_domain?>', {
		transports: [
			'polling',
			'flashsocket'
		]
	});
	function cekWA(){
        $('#scanModal').modal();
		var nomor = "<?=$set->wagw_nomer?>";
		
        $('.areascanqr').html(`
			<div class="card-body">
				<div id="cardimg-${nomor}" class="text-center ">
					<i class='fas fa-spinner fa-spin fs-100 text-primary'></i><br/>
					Menghubungkan...
				</div>
				<p id="info-${nomor}" class="info-${nomor}"></p>
				<div class="div arealogout"></div>
				<div class="text-center"><button class="btn btn-danger scanbutton" onclick="logoutqr(${nomor})">Logout</button></div>
			</div>
		`);

        socket.emit('create-session', {
            id: nomor
        });
	}
    /// function ini untuk logouot
    function logoutqr(nomor) {
        socket.emit('logout', {
            id: nomor
        });
	}
    
	socket.on('message', function(msg) {
        $('.log').html(`<li>` + msg.text + `</li>`);
    })
    socket.on('qr', function(src) {
        console.log(src)
        $(`#cardimg-${src.id}`).html(`<img src="` + src.src + `" class="card-img-top" alt="cardimg" id="qrcode"
    style="height:250px; width:250px;">`);
        var count = 0;
        var interval = setInterval(function() {
            count++
            $(`.info-${src.id}`).html(`<p>Waktu scan anda adalah 10 detik - <span class="text-danger fs-24">${count}</span></p>`);
            if (count == 10) {
                $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-warning mt-4">Silahkan refresh untuk scan ulang<h2>`);

                clearInterval(interval)
            }
        }, 1000);
    });
    // socket.on('authenticated', function(src) {
    //     $(`#info-${src.id}`).attr('class', 'changed');
    //     $('.changed').html('')
    //     $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-success mt-4">` + src.text + `<h2>`);

    // });
    // ketika terhubung
    socket.on('authenticated', function(src) {
        const nomors = src.data.jid;
        //  const nomor = src.id
        const nomor = nomors.replace(/\D/g, '');
        $(`#cardimg-${src.id}`).html(`
			<div class='text-center'>
				<div class='m-b-12'>Status : <b class='text-success'>TERHUBUNG</b></div>
				<div class='m-b-12'>Nama : ${src.data.name}</div>
				<div class='m-b-12'>Nomor WA : ${src.data.jid}</div>
				<div class='m-b-12'>Tipe HP : ${src.data.phone.device_model}</div>
				<div class='m-b-12'>Versi WA : ${src.data.phone.wa_version}</div>
			</div>
            `);
        //  $('#cardimg').html(`<h2 class="text-center text-success mt-4">Whatsapp Connected.<br>` + src + `<h2>`);

    });
    socket.on('isdelete', function(src) {
        //  $(`.info-${src.id}`).html(`<p><span class="text-danger">disconnect</span></p>`);
        $(`#cardimg-${src.id}`).html(src.text);
    });
    socket.on('close', function(src) {
        console.log(src);
        $(`#cardimg-${src.id}`).html(`<h2 class="text-center text-danger mt-4">` + src.text + `<h2>`);
    });
	<?php } ?>
</script>

<!-- scan Modal-->
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Scan QR</h5>
            </div>
            <div class="card shadow m-3 areascanqr">


            </div>
        </div>
    </div>
</div>