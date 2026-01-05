
<h4 class="page-title m-b-20">Data User Non Member</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
			<div class="card-title m-b-10">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" onchange="loadB(1)" placeholder="cari pengguna" id="cari" />
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-info w-full" onclick="loadB(1)"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
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
	});

	function loadB(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("api/nonmember?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function hapus(id){
		swal.fire({
			text: "Yakin akan menghapus data ini? semua data yg berkaitan dengan user ini akan dihapus termasuk alamat, transaksi dll",
			title: "Validasi data",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Tidak Jadi",
			cancelButtonColor: "#ff646d"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/nonmember")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(data){
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
</script>