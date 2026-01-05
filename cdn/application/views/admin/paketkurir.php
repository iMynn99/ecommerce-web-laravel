<h4 class="page-title">Pengaturan Custom Kurir</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header row">
			<div class="tabs p-lr-15 m-b-12 col-md-9">
				<a href="javascript:void(0)" onclick="loadSettingKurir(1);$('.tongkir').hide();$('.tkurir').hide();" class="tabs-item active">
					<i class="fas fa-shipping-fast"></i> &nbsp;Pilihan Kurir
				</a>
				<a href="javascript:void(0)" onclick="loadKurir(1);$('.tongkir').hide();$('.tkurir').show();" class="tabs-item">
					<i class="fas fa-truck-loading"></i> &nbsp;Kurir & Paket
				</a>
				<a href="javascript:void(0)" onclick="loadOngkir(1);$('.tongkir').show();$('.tkurir').hide();" class="tabs-item">
					<i class="fas fa-coins"></i> &nbsp;Custom Ongkos Kirim
				</a>
			</div>
			<div class="col-md-3 tkurir" style="display:none;">
				<button class="btn btn-block btn-primary" onclick="tambahKurir()"><i class="fas fa-plus-circle"></i> &nbsp;Tambah Kurir</button>
			</div>
			<div class="col-md-3 tongkir" style="display:none;">
				<button class="btn btn-block btn-primary" onclick="tambahOngkir()"><i class="fas fa-plus-circle"></i> &nbsp;Tambah Ongkir</button>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadSettingKurir(1);
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
		
		$("#kurirform").on("submit",function(e){
			e.preventDefault();
            var datar = $(this).serialize();
            datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
            $.post("<?=site_url("api/kurirsave")?>",datar,function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                if(data.success == true){
                    loadKurir(1);
                    $("#modalkurir").modal("hide");
                    swal.fire("Berhasil","data sudah disimpan","success");
                }else{
                    swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
                }
            });
		});
		$("#paketform").on("submit",function(e){
			e.preventDefault();
            var datar = $(this).serialize();
            datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
            $.post("<?=site_url("api/paketsave")?>",datar,function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                if(data.success == true){
                    loadKurir(1);
                    $("#modalpaket").modal("hide");
                    swal.fire("Berhasil","data sudah disimpan","success");
                }else{
                    swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
                }
            });
		});
		$("#ongkirform").on("submit",function(e){
			e.preventDefault();
            var datar = $(this).serialize();
            datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
            $.post("<?=site_url("api/ongkirsave")?>",datar,function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                if(data.success == true){
                    loadOngkir(1);
                    $("#modalongkir").modal("hide");
                    swal.fire("Berhasil","data sudah disimpan","success");
                }else{
                    swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
                }
            });
		});

        $("#prov").change(function(){
            $.post("<?=site_url("api/getkab")?>",{"id":$(this).val(),[$("#names").val()]:$("#tokens").val()},function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                $("#kab").html(data.html);
            });
        });
        $("#kab").change(function(){
            $.post("<?=site_url("api/getkec")?>",{"id":$(this).val(),[$("#names").val()]:$("#tokens").val()},function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                $("#kec").html(data.html);
            });
        });
        $("#ongkirkurir").change(function(){
            $.post("<?=site_url("api/getpaketdrop")?>",{"id":$(this).val(),[$("#names").val()]:$("#tokens").val()},function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                $("#ongkirpaket").html(data.html);
            });
        });
	});
	
    function selectKec(prov,kab,kec){
        $.post("<?=site_url("api/getkab/")?>"+kab,{"id":prov,[$("#names").val()]:$("#tokens").val()},function(msg){
            var data = eval("("+msg+")");
            updateToken(data.token);
            $("#kab").html(data.html);
            
			/*
            $.post("<?=site_url("api/getkec/")?>"+kec,{"id":kab,[$("#names").val()]:$("#tokens").val()},function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                $("#kec").html(data.html);
            });
			*/
        });
    }
    function selectPaket(kurir,paket){
        $.post("<?=site_url("api/getpaketdrop/")?>"+paket,{"id":kurir,[$("#names").val()]:$("#tokens").val()},function(msg){
            var data = eval("("+msg+")");
            updateToken(data.token);
            $("#ongkirpaket").html(data.html);
        });
    }
	function loadKurir(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$("#load").load("<?=site_url("api/kurir?page=")?>"+page);
	}
	function loadOngkir(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$("#load").load("<?=site_url("api/ongkir?page=")?>"+page);
	}
	function loadSettingKurir(){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$("#load").load("<?=site_url('api/settingkurir')?>");
	}
	function editKurir(id){
		$.post("<?=site_url('api/getkurir')?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			$('#kurirform')[0].reset();
            $('#kuririd').val(data.id);
            $('#kurirnama').val(data.nama);
            $('#kurirnamalengkap').val(data.namalengkap);
			
			$("#modalkurir").modal();
		});
	}
	function editPaket(id){
		$.post("<?=site_url('api/getpaket')?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
            $('#paketform')[0].reset();
            $('#paketid').val(data.id);
            $('#paketkurir').val(data.idkurir);
            $('#paketnama').val(data.nama);
            $('#paketcod').val(data.cod);
            $('#paketketerangan').val(data.keterangan);
			if(data.kurirjenis == 1){
				$(".utama").show();
            	$('#paketkurir').attr("disabled",true);
			}else{
				$(".utama").hide();
            	$('#paketkurir').attr("disabled",false);
			}
			/*
			$("#rekbank option").each(function(){
				if($(this).val() == data.idbank){
					$(this).prop("selected",true);
				}else{
					$(this).prop("selected",false);
				}
			});
			*/
			$("#modalpaket").modal();
		});
	}
	function editOngkir(id){
		$.post("<?=site_url('api/getongkir')?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
            $('#ongkirform')[0].reset();
            $('#ongkirid').val(data.id);
            $('#ongkirestimasi').val(data.estimasi);
            $('#ongkirharga').val(data.harga);
            $('#ongkirkurir').val(data.kurir);
            $('#ongkirjenis').val(data.jenis);
            $('#prov').val(data.prov);
            //$('#kab').val(data.kab);
            //$('#kec').val(data.kec);
            selectKec(data.prov,data.kab,data.kec);
            selectPaket(data.kurir,data.paket);
			
			$("#modalongkir").modal();
		});
	}
	function tambahKurir(){
		$('#kurirform')[0].reset();
		$('#kuririd').val(0);
		$('#kurirnama').val("");
		$('#kurirnamalengkap').val("");
		
		$("#modalkurir").modal();
	}
	function tambahPaket(id){
		$('#paketform')[0].reset();
		$('#paketid').val(0);
		$('#paketkurir').val(id);
		$('#paketkurir').val(id);
		$('#paketnama').val("");
		$('#paketcod').val("");
		$('#paketketerangan').val("");
		$(".utama").hide();
		
		$("#modalpaket").modal();
	}
	function tambahOngkir(){
		$('#ongkirform')[0].reset();
		$('#ongkirid').val(0);
		$('#ongkirestimasi').val(0);
		$('#ongkirharga').val(0);
		$('#ongkirjenis').val(0);
		$('#ongkirkurir').val("");
		$('#ongkirpaket').val("");
		$('#prov').val("");
		$('#kab').val("");
		//$('#kec').val("");
		
		$("#modalongkir").modal();
	}
	function hapusKurir(id){
		swal.fire({
			text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
			title: "Yakin menghapus data ini?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#ff646d",
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/hapuskurir")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadKurir(1);
						swal.fire("Berhasil","data sudah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function hapusPaket(id){
		swal.fire({
			text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
			title: "Yakin menghapus data ini?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#ff646d",
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/hapuspaket")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadKurir(1);
						swal.fire("Berhasil","data sudah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function hapusOngkir(id){
		swal.fire({
			text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
			title: "Yakin menghapus data ini?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#ff646d",
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/hapusongkir")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadOngkir(1);
						swal.fire("Berhasil","data sudah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>

<div class="modal fade" id="modalpaket" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan Paket Custom</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="paketform">
					<input type="hidden" name="id" id="paketid" value="0" />
					<div class="form-group">
						<label>Kurir Pengiriman</label>
						<select id="paketkurir" name="idkurir" class="form-control" required >
							<option value="">- Pilih Kurir -</option>
							<?php
								//$this->db->where("jenis",2);
								$this->db->order_by("nama");
								$db = $this->db->get("kurir");
								foreach($db->result() as $r){
									$utama = $r->jenis == 1 ? "class='utama'" : "";
									echo "<option ".$utama." value='".$r->id."'>".$r->nama."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Nama Paket</label>
						<input type="text" id="paketnama" name="nama" class="form-control" required />
					</div>
					<div class="form-group">
						<label>Bayar Ditempat</label>
						<select id="paketcod" name="cod" class="form-control" required >
							<option value="">- Pilih COD -</option>
							<option value="1">Aktif</option>
							<option value="0">Non Aktif</option>
						</select>
					</div>
					<div class="form-group">
						<label>Catatan Tambahan</label>
						<input type="text" id="paketketerangan" name="keterangan" class="form-control" />
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalongkir" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan Ongkir Custom</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="ongkirform">
					<input type="hidden" name="id" id="ongkirid" value="0" />
					<input type="hidden" name="idkec" value="0" />
					<div class="form-group">
						<label>Kurir</label>
						<select id="ongkirkurir" name="kurir" class="form-control" required >
							<option value="">- Pilih Kurir -</option>
							<?php
								$this->db->where("jenis",2);
								$this->db->order_by("nama");
								$db = $this->db->get("kurir");
								foreach($db->result() as $r){
									echo "<option value='".$r->id."'>".$r->nama."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Paket</label>
						<select id="ongkirpaket" name="paket" class="form-control" required >
							<option value="">- Pilih Kurir Dulu -</option>
						</select>
					</div>
					<div class="form-group">
						<label>Provinsi</label>
						<select id="prov" class="form-control" required >
							<option value="">- Pilih Provinsi -</option>
							<option value="0">Nasional / Seluruh Indonesia</option>
							<?php
								$this->db->order_by("nama");
								$db = $this->db->get("prov");
								foreach($db->result() as $r){
									echo "<option value='".$r->id."'>".$r->nama."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Kabupaten/Kota</label>
						<select id="kab" name="idkab" class="form-control" required >
							<option value="">- Pilih Kabupaten/Kota -</option>
							<?php
								$this->db->order_by("tipe");
								$db = $this->db->get("kab");
								foreach($db->result() as $r){
									echo "<option value='".$r->id."'>".$r->tipe." ".$r->nama."</option>";
								}
							?>
						</select>
					</div>
					<!--
					<div class="form-group">
						<label>Kecamatan</label>
						<select id="kec" name="idkec" class="form-control" required >
							<option value="">- Pilih Kecamatan -</option>
							<?php
								/*
								$this->db->order_by("nama");
								$db = $this->db->get("kec");
								foreach($db->result() as $r){
									echo "<option value='".$r->id."'>".$r->nama."</option>";
								}
								*/
							?>
						</select>
					</div>
					-->
					<div class="form-group">
						<label>Ongkos Kirim & Jenis (Flat/Per KG)</label>
						<div class="row">
                            <div class="col-6"><input type="number" id="ongkirharga" name="harga" class="form-control" required /></div>
                            <div class="col-6">
								<select id="ongkirjenis" name="jenis" class="form-control" required >
									<option value="1">Flat</option>
									<option value="2">Per Kilogram (KG)</option>
								</select>
							</div>
                        </div>
					</div>
					<div class="form-group">
						<label>Estimasi Pengiriman</label>
						<div class="row">
                            <div class="col-6"><input type="text" id="ongkirestimasi" name="estimasi" class="form-control" required /></div>
                            <div class="col-6">hari</div>
                        </div>
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalkurir" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan Kurir</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="kurirform">
					<input type="hidden" name="id" id="kuririd" value="0" />
					<div class="form-group">
						<label>Nama</label>
						<input type="text" id="kurirnama" name="nama" class="form-control" required />
					</div>
					<div class="form-group">
						<label>Nama Lengkap</label>
						<input type="text" id="kurirnamalengkap" name="namalengkap" class="form-control" required />
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>