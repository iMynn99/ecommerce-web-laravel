<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
            <a href="javascript:tambah()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Pesan Massal</a>
            <h4 class="page-title"><i class="fas fa-podcast text-danger"></i> &nbsp;Pesan Massal</h4>
		</div>
		<div class="card-body">
			<?php if($this->func->demo() == true){ ?>
			<div class="alert alert-warning">
				<i data-notify="icon" class="fas fa-exclamation-circle"></i>
				<span data-notify="message">Untuk mode demo, fitur broadcast ke email dan whatsapp kami nonaktifkan agar tidak mengganggu pengguna aplikasi demo</span>
			</div>
			<?php } ?>
			<div id="load">
				<i class="fas fa-spin fa-spinner"></i> Loading data...
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			loadB(1);
		}, 500);
			
		$(".datepicker").datetimepicker({
			format: "YYYY-MM-DD"
		});
		
		$("#forms").on("submit",function(e){
			e.preventDefault();
			
			$(".progress").show();
			$("#forms").hide();
			var formData = new FormData();
			formData.append("gambar", $("#gambar").get(0).files[0]);
			formData.append($("#names").val(), $("#tokens").val());
			formData.append("judul", $("#judul").val());
			formData.append("isi", $("#isi").val());
			formData.append("tgl", $("#tgl").val());
			formData.append("jam", $("#jam").val());
			formData.append("jenis", $("#jenis").val());
			formData.append("tujuan", $("#tujuan").val());
			$.ajax( {
                url        : '<?=site_url("api/tambahbroadcast")?>',
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
                            $(".progress-bar").css("width", percentComplete+"%");
                            $(".progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$("#forms").show();
					$(".progress").hide();
					var res = eval("("+data+")");
                    updateToken(res.token);
					if(res.success == true){
						$("#modal").modal("hide");
						swal.fire("Berhasil","Berhasil menyimpan data","success");
						loadB(1);
					}
                }
            });
		});
	});

	function loadB(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("api/broadcast?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function tambah(){
		$("#id").val(0);
		$("#judul").val("");
		$("#isi").val("");
		$("#tgl").val("");
		$("#jam").val("");
		$("#jenis").val("");
		$("#tujuan").val("");
		$("#gambar").val("");
		$("#modal").modal();
	}
	function hapus(id){
		swal.fire({
			text: "Yakin akan menghapus broadcast ini?",
			title: "Validasi data",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Tidak Jadi",
			cancelButtonColor: "#ff646d"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/hapusbroadcast")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(data){
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						swal.fire("Berhasil","Berhasil menghapus data","success");
						loadB(1);
					}else{
						swal.fire("Gagal","Gagal mengupdate data","success");
					}
				});
			}
		});
	}
	function resend(id){
		swal.fire({
			text: "Yakin akan mengirim ulang broadcast ini?",
			title: "Validasi data",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Tidak Jadi",
			cancelButtonColor: "#ff646d"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/updatebroadcast")?>",{"id":id,"status":0,[$("#names").val()]:$("#tokens").val()},function(data){
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						swal.fire("Berhasil","Berhasil mengirim ulang broadcast","success");
						loadB(1);
					}else{
						swal.fire("Gagal","Gagal mengupdate data","success");
					}
				});
			}
		});
	}
</script>

<div class="modal fade" id="modal" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Tambah Pesan Massal</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="forms">
                    <div class="form-group m-b-12">
                        <label>Judul Promo</label>
                        <input type="text" id="judul" name="judul" class="form-control" value="" required />
                    </div>
                    <div class="form-group m-b-12">
                        <label>Isi promo</label>
                        <input type="text" id="isi" name="isi" class="form-control" value="" required />
                    </div>
                    <div class="form-group m-b-12">
                        <label>Gambar Promo (opsional)</label>
                        <input type="file" id="gambar" name="gambar" class="form-control" value="" />
                    </div>
                    <div class="form-group m-b-12">
                        <label>Rilis (waktu pengiriman)</label>
						<div class="row">
							<div class="col-8">
                        		<input type="text" id="tgl" name="tgl" class="form-control datepicker" value="" required />
							</div>
							<div class="col-4">
								<select class="form-control" name="jam" id="jam" required>
									<option value="">Pilih Jam</option>
									<?php
										for($i=0; $i<=23; $i++){
											$o = $i < 10 ? "0".$i : $i;
											echo '
												<option value="'.$o.':00:00">'.$o.' : 00</option>
												<option value="'.$o.':15:00">'.$o.' : 15</option>
												<option value="'.$o.':30:00">'.$o.' : 30</option>
												<option value="'.$o.':45:00">'.$o.' : 45</option>
											';
										}
									?>
								</select>
							</div>
						</div>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Tujuan Platform Broadcast</label>
                        <select class="form-control" name="jenis" id="jenis" required>
                            <option value="">Pilih Tujuan</option>
                            <option value="0">Semua Platform (Email, WA, Push Notification)</option>
                            <option value="1">Email</option>
                            <option value="2">Whatsapp</option>
                            <option value="3">Push Notification</option>
                        </select>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Sasaran Pengguna</label>
                        <select class="form-control" name="tujuan" id="tujuan" required>
                            <option value="">Pilih Sasaran</option>
                            <option value="0">Semua Pengguna [ <?=$this->func->getJmlUser()?> user ]</option>
                            <option value="1">Sudah Pernah Beli [ <?=$this->func->getJmlUser(1)?> user ]</option>
                            <option value="2">Belum Pernah Beli [ <?=$this->func->getJmlUser(2)?> user ]</option>
                            <option value="3">Ada Produk di Keranjang Belanja [ <?=$this->func->getJmlUser(3)?> user ]</option>
                        </select>
                    </div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
				<div class="progress" style="display:none;">
					<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
					<div class="text-center m-t-12">menyimpan data...</div>
				</div>
			</div>
		</div>
	</div>
</div>