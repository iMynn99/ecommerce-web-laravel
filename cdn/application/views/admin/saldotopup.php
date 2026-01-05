<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
            <a href="javascript:tambahSB()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Topup</a>
            <h4 class="page-title"><i class="fas fa-arrow-up text-success"></i> &nbsp;Topup Saldo</h4>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadTopup(1);
		
		$("#sbforms").on("submit",function(e){
			e.preventDefault();
			
			$(".progress").show();
			$("#sbforms").hide();
			var datar = $(this).serialize();
			datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
			$.post("<?=site_url("api/tambahtopup")?>",datar,function(data){
				$("#sbforms").show();
				$(".progress").hide();
				var res = eval("("+data+")");
				updateToken(res.token);
				$("#modal").modal("hide");
				swal.fire("Berhasil","Berhasil menyimpan data","success");
				loadTopup(1);
			});
		});
	});

	function bukti(img){
		$("#bukti").attr("src",img);
		$("#modalbukti").modal();
	}

	function loadTopup(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("api/topup?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function tambahSB(){
		$("#id").val(0);
		$("#usrid").val("");
		$("#total").val("");
		$("#keterangan").val("");
		$("#modal").modal();
	}
	function verifikasi(id){
		swal.fire({
			text: "pastikan lagi pembayaran sudah masuk dan jumlah sudah sesuai",
			title: "Validasi data",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Cek Lagi",
			cancelButtonColor: "#ff646d"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/updatetopup")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(data){
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						swal.fire("Berhasil","Berhasil memverifikasi topup","success");
						loadTopup(1);
					}else{
						swal.fire("Gagal","Gagal mengupdate topup","success");
					}
				});
			}
		});
	}
	function batal(id){
		swal.fire({
			text: "Yakin akan mmebatalkan transaksi ini?",
			title: "Validasi data",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Tidak Jadi",
			cancelButtonColor: "#ff646d"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/bataltopup")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(data){
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						swal.fire("Berhasil","Berhasil membatalkan topup","success");
						loadTopup(1);
					}else{
						swal.fire("Gagal","Gagal mengupdate topup","success");
					}
				});
			}
		});
	}
</script>

<div class="modal fade" id="modalbukti" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body text-center">
				<img src="" id="bukti" style="max-width:100%;max-height:80vh;"/>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Tambah Topup Saldo</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="sbforms">
					<input type="hidden" name="id" id="id" value="0" />
                    <div class="form-group m-b-12">
                        <label>Nama User</label>
                        <select class="select2" name="usrid" id="usrid" required>
                            <option value="">Pilih atau Cari Pengguna</option>
                            <?php
                                $levels = [
                                    2 => "Reseller",
                                    3 => "Agen",
                                    4 => "Agen Premium",
                                    5 => "Distributor"
                                ];
                                $this->db->order_by("level,nama ASC");
                                $dt = $this->db->get("userdata");
                                foreach($dt->result() as $r){
                                    $level = $r->level > 1 ? "(".$levels[$r->level].")" : "";
                                    $profil = $this->func->getProfil($r->id,"semua","usrid");
                                    $nohp = $profil->nohp != "" ? $profil->nohp : $r->username;
                                    echo "<option value='".$r->id."'>".$profil->nama." ".$nohp." ".$level."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Jumlah Topup</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">RP</span>
                            </div>
                            <input type="number" id="total" name="total" class="form-control" value="" required />
                        </div>
                        <small class="text-danger">hanya masukkan angka saja, contoh 50000, 100000, 200000, dll</small>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Catatan</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control" value=""/>
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