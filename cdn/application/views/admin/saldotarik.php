<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
            <!--<a href="javascript:tambahSB()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Penarikan</a>-->
            <h4 class="page-title"><i class="fas fa-arrow-down text-danger"></i> &nbsp;Penarikan Saldo</h4>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadTopup(1);
	});

	function loadTopup(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("api/tarik?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function verifikasi(id){
		swal.fire({
			text: "pastikan lagi jumlah sudah sesuai",
			title: "Validasi data",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Cek Lagi",
			cancelButtonColor: "#ff646d"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/updatetarik")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(data){
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						swal.fire("Berhasil","Berhasil memverifikasi penarikan saldo","success");
						loadTopup(1);
					}else{
						swal.fire("Gagal","Gagal mengupdate penarikan saldo","success");
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
				$.post("<?=site_url("api/bataltarik")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(data){
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						swal.fire("Berhasil","Berhasil membatalkan penarikan saldo","success");
						loadTopup(1);
					}else{
						swal.fire("Gagal","Gagal mengupdate penarikan saldo","success");
					}
				});
			}
		});
	}
</script>