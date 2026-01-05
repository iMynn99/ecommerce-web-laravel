
<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
			<div class="card-title">
				<a href="javascript:tambahSB()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Gudang</a>
				<h4 class="page-title" style="margin-bottom:0;">Data Gudang Pengiriman</h4>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<div class="modal fade" id="modal" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan Gudang</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="sbforms">
					<input type="hidden" name="id" id="id" value="0" />
                    <div class="form-group m-b-12">
                        <label>Nama Gudang</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="" />
                    </div>
                    <div class="form-group m-b-12">
                        <label>Kota Asal Pengiriman</label>
                        <select class="select2" name="idkab" id="idkab">
                            <?php
                                $this->db->order_by("nama","ASC");
                                $db = $this->db->get("kab");
                                $no = 1;
                                foreach($db->result() as $r){
                                    $select = ($no == 1) ? "selected" : "";
                                    echo "<option value='".$r->id."' ".$select.">".$r->tipe." ".$r->nama."</option>";
                                    $no++;
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" value="" placeholder="Alamat Lengkap"/>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Nama Penanggungjawab</label>
                        <input type="text" id="kontak" name="kontak" class="form-control" value=""/>
                    </div>
                    <div class="form-group m-b-12">
                        <label>No HP Penanggungjawab</label>
                        <input type="text" id="kontak_nohp" name="kontak_nohp" class="form-control" value=""/>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Keterangan Tambahan</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control" value=""/>
                    </div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
				<div class="progress" style="display:none;">
					<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
					<div class="text-center m-t-12">menyimpan data gudang</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalkurir" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan Kurir</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="loadmodal">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadGudang(1);
		
		$("#sbforms").on("submit",function(e){
			e.preventDefault();
			swal.fire({
				text: "pastikan lagi data yang anda masukkan sudah sesuai",
				title: "Validasi data",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Cek Lagi"
			}).then((vals)=>{
				if(vals.value){
					var datar = $("#sbforms").serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?=site_url("gudang/tambah")?>",datar,function(msg){
                        var res = eval("("+msg+")");
                        updateToken(res.token);
                        $("#modal").modal("hide");
                        swal.fire("Berhasil","Berhasil menyimpan data gudang","success");
                        loadGudang(1);
					});
				}
			});
		});
	});

	function loadGudang(page){
		$("#load").html('<i class="fas fa-spin fa-compact-disc"></i> Loading data...');
		$.post("<?=site_url("gudang/data?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function edit(id){
		$.post("<?=site_url('gudang/data')?>",{"formid":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			$("#id").val(id);
			$("#nama").val(data.nama);
			$("#idkab").val(data.idkab);
			$("#idkab").select2().trigger('change');
			$("#alamat").val(data.alamat);
			$("#kontak").val(data.kontak);
			$("#kontak_nohp").val(data.kontak_nohp);
			$("#keterangan").val(data.keterangan);
			
			$("#modal").modal();
		});
	}
	function tambahSB(){
		//$('#sbforms')[0].reset();
		$(".form-control").val("");
		$("#id").val(0);
		$("#modal").modal();
	}
	function editKurir(id){
		$("#loadmodal").load("<?=site_url("gudang/setkurir")?>/"+id,function(){
			$("#modalkurir").modal();
		});
	}
	function hapus(id){
		swal.fire({
			text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
			title: "Yakin menghapus data ini?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#ff646d",
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("gudang/hapus")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadGudang(1);
						swal.fire("Berhasil","data sudah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi<br/><span class='text-danger'>"+data.msg+"</span>","error");
					}
				});
			}
		});
	}
</script>