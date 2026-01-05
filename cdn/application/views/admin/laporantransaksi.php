<h4 class="page-title">Riwayat Transaksi Penjualan</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header">
			<div class="row" style="align-items:center;">
				<div class="col-md-6 m-b-10" style="font-size:120%;"><i class="fas fa-filter"></i> &nbsp;Periode Laporan</div>
				<div class="col-md-6 text-right">
					<!--<button onclick="saveDiv('load','Laporan Penjualan')" class="btn btn-warning"><i class="fas fa-file-pdf"></i> Download PDF</button>-->
					<button onclick="printDiv('load','Laporan Penjualan')" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
				</div>
				<div class="col-md-2 p-tb-6">
					<div class="m-b-8">Tanggal Mulai</div>
					<input type="text" id="tglmulai" class="form-control datepicker" value="<?=date("Y-m-d",strtotime("-30 day", strtotime(date("Y-m-d"))))?>" />
				</div>
				<div class="col-md-2 p-tb-6">
					<div class="m-b-8">Tanggal Selesai</div>
					<input type="text" id="tglselesai" class="form-control datepicker" value="<?=date("Y-m-d")?>" />
				</div>
				<div class="col-md-2 p-tb-6">
					<div class="m-b-8">Jenis Produk</div>
					<select id="jenis" onChange="loadRiwayat()" class="form-control">
						<option value="0">Semua Produk</option>
						<option value="1">Produk Fisik</option>
						<option value="2">Produk Digital</option>
					</select>
				</div>
				<div class="col-md-3 p-tb-6">
					<div class="m-b-8">Gudang</div>
					<select id="gudang" onChange="loadRiwayat()" class="form-control">
						<option value="semua">Semua</option>
						<option value="0">PUSAT</option>
						<?php
							$db = $this->db->get("gudang");
							foreach($db->result() as $r){
								$kota = $this->func->getKab($r->idkab,"semua");
								$kota = $kota->tipe." ".$kota->nama;
								echo '<option value="'.$r->id.'">'.$r->nama.' - '.$kota.'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-3 p-tb-6">
					<div class="m-b-8">Status Transaksi</div>
					<select id="status" onChange="loadRiwayat()" class="form-control">
						<option value="0">Semua Transaksi</option>
						<option value="1">Semua Transaksi Yg Sudah Bayar</option>
						<option value="2">Belum Dibayar</option>
						<option value="3">Perlu Dikirim</option>
						<option value="4">Sedang Dikirim</option>
						<option value="5">Selesai</option>
						<option value="6">Dibatalkan</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>
<div id="editor"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<script type="text/javascript">
	$(function(){
		loadRiwayat();

		$(".datepicker").on("dp.change",function(){
			loadRiwayat();
		});

		$(".datepicker").datetimepicker({
			format: "YYYY-MM-DD"
		});
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
		
		$("#rekeningform").on("submit",function(e){
			e.preventDefault();
			swal.fire({
				text: "pastikan lagi data yang anda masukkan sudah sesuai",
				title: "Validasi data",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Cek Lagi"
			}).then((vals)=>{
				if(vals.value){
					var datar = $("#rekeningform").serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?=site_url("api/update")?>",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							loadHalaman(1);
							$("#modal").modal("hide");
							swal.fire("Berhasil","data halaman sudah disimpan","success");
						}else{
							swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
						}
					});
				}
			});
		});
	});
	
	function loadRiwayat(){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("ngadimin/laporantransaksi?load=hal")?>",{"gudang":$("#gudang").val(),"status":$("#status").val(),"jenis":$("#jenis").val(),"tglmulai":$("#tglmulai").val(),"tglselesai":$("#tglselesai").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}

	var doc = new jsPDF();
	var specialElementHandlers = {
            '#editor': function (element, renderer) {
            return true;
        }
    };
	function saveDiv(divId, title) {
		doc.fromHTML(
			`<html><head><title>${title}</title>`+
			`<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">`+
			`<link rel="stylesheet" href="<?=base_url()?>/assets/css/util.css">`+
			`<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">`+
			`</head><body>` + 
			$("#"+divId).html() + 
			`</body></html>`, 5, 5, {
            'width': 170,
                'elementHandlers': specialElementHandlers
        });
		doc.save('div.pdf');
	}
	function printDiv(divId,title) {

		let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

		mywindow.document.write(`<html><head><title>${title}</title>`);
		mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">');
		mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>/assets/css/util.css">');
		mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">');
		mywindow.document.write('</head><body>');
		mywindow.document.write($("#"+divId).html());
		mywindow.document.write('</body></html>');

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		setTimeout(function(){
			mywindow.print()
			setTimeout(function(){
				mywindow.close()
			},1000);
		},1000);

		return true;
	}
</script>