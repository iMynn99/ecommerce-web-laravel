<?php
	$set = $this->func->globalset("semua");
?>
<form id="pengaturan">
	<input type="hidden" name="tema" id="tema" value="<?=$set->tema?>" />
	<input type="hidden" name="temawarna" id="temawarna" value="<?=$set->temawarna?>" />
	<div class="row">
		<div class="col-md-6 m-b-20">
			<div class="form-group">
				<label>Nama Toko</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" class="form-control" value="<?=$set->nama?>" disabled />
				<small class="text-danger">mohon maaf, fitur kami nonaktifkan untuk mode demo</small>
				<?php }else{ ?>
				<input type="text" name="nama" class="form-control" value="<?=$set->nama?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label>Slogan</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" class="form-control" value="<?=$set->slogan?>" disabled />
				<small class="text-danger">mohon maaf, fitur kami nonaktifkan untuk mode demo</small>
				<?php }else{ ?>
				<input type="text" name="slogan" class="form-control" value="<?=$set->slogan?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label>Link Download Playstore</label>
				<?php if($this->func->demo() == true){ ?>
				<input type="text" class="form-control" value="<?=$set->link_playstore?>" disabled />
				<small class="text-danger">mohon maaf, fitur kami nonaktifkan untuk mode demo</small><br/>
				<?php }else{ ?>
				<input type="text" name="link_playstore" class="form-control" value="<?=$set->link_playstore?>" />
				<?php } ?>
				<small class="text-info"><i>kosongkan apabila ingin menyembunyikan banner aplikasi di halaman depan</i></small>
			</div>
			<!--
			-->
			<div class="form-group">
				<label>Kota Asal (Pengiriman)</label>
				<select class="selectto" name="kota">
					<?php
						$this->db->order_by("nama","ASC");
						$db = $this->db->get("kab");
						foreach($db->result() as $r){
							$select = ($r->id == $set->kota) ? "selected" : "";
							echo "<option value='".$r->id."' ".$select.">".$r->tipe." ".$r->nama."</option>";
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>No Telepon</label>
				<input type="text" name="notelp" class="form-control col-6" value="<?=$set->notelp?>" />
			</div>
			<div class="form-group">
				<label>Jam Kerja</label>
				<input type="text" name="jamkerja" class="form-control col-6" value="<?=$set->jamkerja?>" />
			</div>
			<div class="form-group">
				<label>Whatsapp</label>
				<input type="text" name="wasap" class="form-control col-6" value="<?=$set->wasap?>" />
			</div>
			<div class="form-group">
				<label>Line ID</label>
				<input type="text" name="lineid" class="form-control col-8" value="<?=$set->lineid?>" />
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="text" name="email" class="form-control" value="<?=$set->email?>" />
			</div>
			<div class="form-group">
				<label>Instagram</label>
				<input type="text" name="instagram" class="form-control" value="<?=$set->instagram?>" />
			</div>
			<div class="form-group">
				<label>Facebook</label>
				<input type="text" name="facebook" class="form-control" value="<?=$set->facebook?>" />
			</div>
			<div class="form-group">
				<label>Alamat Lengkap</label>
				<textarea name="alamat" class="form-control" rows=4><?=$set->alamat?></textarea>
			</div>
		</div>
		<div class="col-md-6 m-b-20">
			<div class="logoset">
				<div class="logo">
					<input type="file" name="logo" id="logoUpload" style="display:none;" accept="image/x-png,image/gif,image/jpeg" ></input>
					<div class="title">Logo Utama</div>
					<img id="logo" src="<?=base_url("assets/img/".$this->func->globalset("logo"))?>" />
					<button type="button" class="btn btn-secondary btn-block logouploadbtn" onclick="$('#logoUpload').trigger('click')"><i class="fas fa-sync"></i> Ganti Logo Utama</button>
					<div class="progress progreslogo" style="display:none;">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
			<div class="logoset">
				<div class="favicon">
					<input type="file" name="logo" id="faviconUpload" style="display:none;" accept="image/x-png,image/gif,image/jpeg" ></input>
					<div class="title">Logo Favicon</div>
					<img id="favicon" src="<?=base_url("assets/img/".$this->func->globalset("favicon"))?>" />
					<button type="button" class="btn btn-secondary btn-block faviconuploadbtn" onclick="$('#faviconUpload').trigger('click')"><i class="fas fa-sync"></i> Ganti Logo Favicon</button>
					<div class="progress progresfavicon" style="display:none;">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
			<div class="form-group m-t-20">
				<label><b>PESAN OTOMATIS</b></label>
			</div>
			<div class="form-group">
				<label>Pesan Selamat Datang</label>
				<?php if($this->func->demo()){ ?>
				<textarea class="form-control" rows=4 readonly><?=$set->autoreply?></textarea>
				<?php }else{ ?>
				<textarea name="autoreply" class="form-control" rows=4><?=$set->autoreply?></textarea>
				<?php } ?>
			</div>
			<div class="form-group m-t-20">
				<label><b>PENGATURAN TEMA WEBSITE</b></label>
			</div>
			<div class="btn-group g-warna col-12 m-lr-0 form-group m-b-10 col-md-6 m-b-30" role="group">
				<?php 
					$light = ($set->temawarna == 1) ? "btn-success" : "btn-light";
					$dark = ($set->temawarna == 2) ? "btn-success" : "btn-light";
				?>
				<button id="light" onclick="$('#temawarna').val(1);$('.g-warna button').removeClass('btn-success');$('.g-warna button').addClass('btn-light');$(this).removeClass('btn-light');$(this).addClass('btn-success');" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$light?>"><b>GRADIENT</b></button>
				<button id="dark" onclick="$('#temawarna').val(2);$('.g-warna button').removeClass('btn-success');$('.g-warna button').addClass('btn-light');$(this).removeClass('btn-light');$(this).addClass('btn-success');" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?=$dark?>"><b>FLAT</b></button>
			</div>
			<div class="row m-lr-0 m-b-20" style="align-items:center;">
				<?php 
					$tema = $this->func->tema();
					for($i=0; $i<count($tema); $i++){
						$active = $set->tema == $i ? "active" : "";
				?>
				<div class="p-all-12"><div class="pilihwarna text-center <?=$active?>" onclick="$('#tema').val(<?=$i?>);$('.pilihwarna.active').removeClass('active');$(this).addClass('active')" style="background-image:<?=$tema[$i]["hover"]?>;"><i class="fas fa-check"></i></div></div>
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
	$(function(){
		$('.selectto').select2({theme: "bootstrap",width:'resolve'});
		$("#pengaturan").on("submit",function(e){
			e.preventDefault();
			var datar = $(this).serialize();
			datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
			$.post("<?=site_url("api/savesetting")?>",datar,function(msg){
				var data = eval("("+msg+")");
				updateToken(data.token);
				if(data.success == true){
					swal.fire("Berhasil","berhasil menyimpan pengaturan umum","success").then((val)=>{
						loadSettingUmum();
					});
				}else{
					swal.fire("Gagal","gagal menyimpan pengaturan","error");
				}
			});
		});

		<?php if($this->func->demo() != true){ ?>
		$("#faviconUpload").change(function(){
			var formData = new FormData();
			$(".progresfavicon").show();
			$(".faviconuploadbtn").hide();
			formData.append("logo", $(this).get(0).files[0]);
			formData.append($("#names").val(),$("#tokens").val());
			$.ajax( {
                url        : '<?php echo site_url("api/uploadLogo/2"); ?>',
                type       : 'POST',
                contentType: false,
                cache      : false,
                processData: false,
                data       : formData,
                xhr        : function ()
                {
                    var jqXHR = null;
                    if ( window.ActiveXObject ){
                        jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                    }else{
                        jqXHR = new window.XMLHttpRequest();
                    }
                    jqXHR.upload.addEventListener( "progress", function ( evt ){
                        if ( evt.lengthComputable ){
                            var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                            $(".progresfavicon .progress-bar").css("width", percentComplete+"%");
                            $(".progresfavicon .progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$(".faviconuploadbtn").show("slow");
					$(".progresfavicon").hide();
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						$("#favicon").attr("src","<?=base_url('assets/img/')?>"+res.filename);
					}
                }
            } );
		});

		$("#logoUpload").change(function(){
			var formData = new FormData();
			$(".progreslogo").show();
			$(".logouploadbtn").hide();
			formData.append("logo", $(this).get(0).files[0]);
			formData.append($("#names").val(),$("#tokens").val());
			$.ajax( {
                url        : '<?php echo site_url("api/uploadLogo/1"); ?>',
                type       : 'POST',
                contentType: false,
                cache      : false,
                processData: false,
                data       : formData,
                xhr        : function ()
                {
                    var jqXHR = null;
                    if ( window.ActiveXObject ){
                        jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                    }else{
                        jqXHR = new window.XMLHttpRequest();
                    }
                    jqXHR.upload.addEventListener( "progress", function ( evt ){
                        if ( evt.lengthComputable ){
                            var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                            $(".progreslogo .progress-bar").css("width", percentComplete+"%");
                            $(".progreslogo .progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$(".logouploadbtn").show("slow");
					$(".progreslogo").hide();
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						$("#logo").attr("src","<?=base_url('assets/img/')?>"+res.filename);
					}
                }
            } );
		});
		<?php }else{ ?>
		$("#logoUpload,#faviconUpload").change(function(){
			swal.fire("Mode Demo","mohon maaf, fitur ini tidak kami aktifkan untuk mode demo","error");
		});
		<?php } ?>
	});
</script>