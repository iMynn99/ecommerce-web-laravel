<a href="javascript:void(0)" onclick="importProduk()" class="btn btn-primary float-right"><i class="fas fa-download"></i> Impor Excel</a>
<a href="<?=site_url("ngadimin/produkform")?>" class="btn btn-success float-right m-r-10"><i class="fas fa-plus-circle"></i> Produk Baru</a>
<h4 class="page-title">Daftar Produk</h4>
<div class="card">
	<div class="card-header row">
		<div class="col-md-4 p-tb-6">
			<input type="text" class="form-control" placeholder="cari produk" id="cari" />
		</div>
		<div class="col-md-3 p-tb-6">
			<button class="btn btn-block" style="background-color:rgb(251, 172, 76, 0.4)">Stok Habis &nbsp;<span class="badge badge-danger p-lr-8 p-tb-2 fs-16"><?=$this->func->getProdukHabis()?></span></button>
		</div>
		<div class="col-md-3 col-8 p-tb-6">
			<select id="status" class="form-control">
				<option value="0">Semua Produk</option>
				<option value="1">Stok Tersedia</option>
				<option value="3">Stok Menipis</option>
				<option value="2">Stok Habis</option>
			</select>
		</div>
		<div class="col-md-2 col-4 p-tb-6">
			<select id="perpage" class="form-control">
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="75">75</option>
				<option value="100">100</option>
			</select>
		</div>
	</div>
	<div class="card-body table-responsive">
		<i class="la la-spin la-spinner"></i> Loading data...
	</div>
</div>

<script type="text/javascript">
	$(function(){
		refreshTabel(1);

		$("#impor").on("submit",function(e){
			e.preventDefault();

			var formData = new FormData();
			$(".progress").show();
			$(this).hide();
			formData.append("fileupload", $("#file").get(0).files[0]);
			formData.append($("#names").val(), $("#tokens").val());
			$.ajax( {
                url        : '<?php echo site_url("api/import"); ?>',
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
                            $(".progress .progress-bar").css("width", percentComplete+"%");
                            $(".progress .progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$("#impor").show();
					$(".progress").hide();
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						$("#modalimpor").modal("hide");
						swal.fire("Berhasil","Data produk telah berhasil di impor","success").then(res=>{
							refreshTabel(1);
						});
					}else{
						swal.fire("Gagal Impor","Terjadi kesalahan saat server memproses file <br/><i class='text-danger'>"+res.msg+"</i>","error");
					}
                }
            } );
		});

		$("#cari,#perpage,#status").change(function(){
			refreshTabel(1);
		});
	});
	
	function refreshTabel(page){
		$(".card-body").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		var perpage = $("#perpage").val();
		$.post("<?=site_url("ngadimin/produk?load=true")?>&page="+page+"&perpage="+perpage,{"cari":$("#cari").val(),"status":$("#status").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$(".card-body").html(data.result);
		});
	}

	function importProduk(){
		$("#modalimpor").modal();
	}
	
	function hapus(id){
		swal.fire({
			title: "Yakin menghapus?",
			text: "data yang sudah dihapus tidak akan bisa dikembalikan",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal",
			confirmButtonText: "Oke"
		}).then((val)=>{
			if(val.value == true){
				$.post("<?=site_url("api/hapusproduk")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil","data telah dihapus","success").then((val)=>{
							window.location.href="<?=site_url("ngadimin/produk")?>";
						});
					}else{
						swal.fire("Gagal!","gagal menghapus data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>


<div class="modal fade" id="modalimpor" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Impor Produk</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					Sebelum mengupload, silahkan ikuti format data untuk impor sesuai template yang telah disediakan.<br/>
					<a href="<?=base_url("import/Template_Import.xlsx")?>" class="btn btn-link"><i class="fas fa-file-download"></i> &nbsp;download template impor</a>
				</div>
				<form id="impor">
					<div class="form-group">
						<label>File Excel (.xls / .xlsx / .csv)</label>
						<input type="file" id="file" name="file" class="form-control" required />
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Impor</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
				<div class="progress" style="display:none;">
					<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
</div>