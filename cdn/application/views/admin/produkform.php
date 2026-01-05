<a href="javascript:history.back()" class="btn btn-danger float-right"><i class="la la-times"></i> Batal</a>
<?php 	
	if($id == 0 AND (!isset($_GET["copy"]))){
?>
<h4 class="page-title">Tambah Produk Baru</h4>
<?php 
	}else{
		if(isset($_GET["copy"])){
?>
<h4 class="page-title">Copy Produk</h4>
<?php 	}else{ ?>
<h4 class="page-title">Edit Produk</h4>
<?php 	
		}
	}
?>

<?php
	$this->session->unset_userdata("fotoProduk");
	$varjum = 0;
	if($id != 0 || (isset($_GET["copy"]) AND intval($_GET["copy"]) > 0)){
		if(isset($_GET["copy"])){
			$id = intval($_GET["copy"]);
			$url = site_url("api/tambahproduk");
			$varjum = 0;
			$this->session->unset_userdata("fotoCopy");
		}else{
			$url = site_url("api/updateproduk");
			$varjum = $this->func->getVariasiJumlah($id);
		}
		$data = $this->func->getProduk($id,"semua");
		if($data->id == 0){
			redirect("ngadimin/produk");
			exit;
		}else{
			$this->db->where("idproduk",$id);
			$up = $this->db->get("upload");
			
			$_SESSION["uploadedPhotos"] = $up->num_rows();
			if(isset($_GET["copy"])){
				foreach($up->result() as $ra){
					$_SESSION["fotoCopy"][] = $ra->id;
				}
			}else{
				foreach($up->result() as $ra){
					$_SESSION["fotoProduk"][] = $ra->id;
				}
			}
		}
	}else{
		$url = site_url("api/tambahproduk");
	}
	$set = $this->func->globalset("semua");
	$kabs = $this->func->getKab($set->kota,"semua");
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Foto Produk</div>
    </div>
    <div class="card-body">
        <div class="m-b-20 overflow-hidden btn-block">
            <div id="foto-produk" class="uploadfoto-result">
            </div>
            <div class="uploadfoto">
                <label class="form-uploadfoto">
                    <input type="file" name="fotoProduk" id="fotoUpload"
                        accept="image/x-png,image/gif,image/jpeg"></input>
                    <img src="<?php echo base_url("assets/img/add-product.png"); ?>" />
                </label>
                <span id="prosesUpload"></span>
            </div>
        </div>
        <div class="text-danger">
            <small><i>Ukuran file maksimal 2MB, resolusi maksimal 2000 pixel</i></small>
        </div>
    </div>
</div>

<form id="produk" action="" method="POST">
    <input type="hidden" name="id" value="<?php if($id > 0 AND !isset($_GET["copy"])){ echo $id; }else{ echo 0;} ?>" />
    <input type="hidden" name="preorder" value="0" />
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Nama & Kategori Produk
            </div>
        </div>
        <div class="card-body">
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Nama Produk</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Nama min. 5 kata, terdiri dari jenis produk, merek, dan keterangan seperti warna,
                        bahan, atau tipe.</div>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="nama"
                        value="<?php echo ($id != 0) ? $data->nama : ""; ?>" required />
                </div>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Kode Produk</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Masukkan kode unik untuk produk dan pastikan tidak sama dengan produk yang lain.
                    </div>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control col-md-6" name="kode"
                        value="<?php echo ($id != 0 AND !isset($_GET["copy"])) ? $data->kode : date("dHis"); ?>"
                        required />
                </div>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Ketersediaan Produk</b> &nbsp;<span class="badge badge-form">wajib</span>
                    </div>
                    <div class="fs-12">Pilih produk dengan stok tersedia (ready) atau Pre Order terlebih dahulu.</div>
                </div>
                <div class="col-md-8">
                    <button type="button" id="nopo" onclick="setPO(0)" class="btn btn-primary"><i
                            class="fa fa-check-circle"></i> Stok Ready</button>
                    <button type="button" id="po" onclick="setPO(1)" class="btn btn-outline-primary"><i
                            class="fa fa-check-circle" style="display:none;"></i> Pre Order</button>
                    <input type="hidden" class="form-control" id="preorder" name="preorder"
                        value="<?php echo ($id != 0) ? $data->preorder : "0"; ?>" required />
                </div>
            </div>
            <div class="row m-lr-0 m-b-24 tglpo" style="display:none;">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Durasi Pengerjaan Pre Order</b> &nbsp;<span
                            class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Mauskkan jumlah hari yang dibutuhkan untuk mengerjakan pesanan.</div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control col-6" name="pohari"
                            value="<?php echo ($id != 0) ? $data->pohari : ""; ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text">Hari</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Kategori</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Pilih kategori yang sesuai dengan produk.</div>
                </div>
                <div class="col-md-6">
                    <select class="select2" name="idcat" required>
                        <option value="">- Pilih Kategori -</option>
                        <?php
							$kat = $this->db->get("kategori");
							foreach($kat->result() as $r){
								$selec = ($id!=0 AND $data->idcat == $r->id) ? "selected" : "";
								echo "<option value='".$r->id."' $selec>".$r->nama."</option>";
							}
						?>
                    </select>
                </div>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Jenis Produk</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Tentukan jenis produknya apakah produk fisik atau digital.</div>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="digital" name="digital" required>
                        <option value="">- Pilih Jenis Produk -</option>
                        <option value='0' <?php echo ($id != 0 AND $data->digital == 0) ? "selected" : ""; ?>>Produk
                            Fisik</option>";
                        <option value='1' <?php echo ($id != 0 AND $data->digital == 1) ? "selected" : ""; ?>>Produk
                            Digital</option>";
                    </select>
                </div>
            </div>
            <div class="row m-lr-0 m-b-24 digital"
                <?php if($id == 0 OR (isset($data->digital) AND $data->digital != 1)){ echo "style='display:none'"; } ?>>
                <div class="col-md-4">
                    <div class="m-b-4"><b>URL Akses Produk</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Masukkan alamat URL untuk akses produk untuk pembeli, seperti URL download file
                        apabila produk dlm bentuk file dan sejenisnya.</div>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="akses" name="akses"
                        value="<?php echo ($id != 0) ? $data->akses : ""; ?>"
                        placeholder="https://urlweb.com/path/to/file/download.zip"
                        <?php if(isset($data->digital) AND $data->digital == 1){ echo "required"; } ?> />
                </div>
            </div>
            <div class="row m-lr-0 m-b-24 digital"
                <?php if($id == 0 OR (isset($data->digital) AND $data->digital != 1)){ echo "style='display:none'"; } ?>>
                <div class="col-md-4">
                    <div class="m-b-4"><b>Link Demo Web/Aplikasi</b></div>
                    <div class="fs-12">Masukkan alamat URL untuk pembeli mengakses demo dari produk.</div>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="demo"
                        value="<?php echo ($id != 0) ? $data->demo : ""; ?>" placeholder="https://urlweb.com/demo" />
                </div>
            </div>
            <div class="row m-lr-0 m-b-24 digital"
                <?php if($id == 0 OR (isset($data->digital) AND $data->digital != 1)){ echo "style='display:none'"; } ?>>
                <div class="col-md-4">
                    <div class="m-b-4"><b>Link Demo Admin</b></div>
                    <div class="fs-12">Masukkan alamat URL untuk pembeli mengakses demo halaman admin dari produk.</div>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="demoadmin"
                        value="<?php echo ($id != 0) ? $data->demoadmin : ""; ?>"
                        placeholder="https://urlweb.com/demo/admin" />
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Detail Harga & Stok
            </div>
        </div>
        <div class="card-body">
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Gudang Pengiriman</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Pilih gudang tempat asal pengiriman produk, untuk mengubah gudang bisa dilihat di
                        menu <b>Lokasi Gudang</b>.</div>
                </div>
                <div class="col-md-8">
                    <select class="select2" name="gudang" required>
                        <option value="">- Pilih Gudang Pengiriman -</option>
                        <?=$kabs->tipe." ".$kabs->nama ?></option>
                        <?php
							$this->db->order_by("nama","ASC");
							$kat = $this->db->get("gudang");
							foreach($kat->result() as $r){
								$selec = ($id != 0 AND $data->gudang == $r->id) ? "selected" : "";
								$kab = $this->func->getKab($r->idkab,"semua");
								echo "<option value='".$r->id."' $selec>".$r->nama." - ".$kab->tipe." ".$kab->nama."</option>";
							}
						?>
                    </select>
                </div>
            </div>
            <div class="row m-lr-0 m-b-24 novariasi">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Stok Barang</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">hanya masukkan angka saja. contoh: 200</div>
                </div>
                <div class="col-md-3 col-6">
                    <input type="number" class="form-control" id="stok" name="stok"
                        value="<?php echo ($id != 0) ? $data->stok : 0; ?>" required
                        <?php if($varjum > 0){ echo "readonly"; } ?> />
                </div>
                <?php if($varjum > 0){ ?>
                <div class="col-md-3 col-6">
                    <span class="text-danger">atur stok di variasi produk</span>
                </div>
                <?php } ?>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Minimal Order</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Jumlah produk minimal setiap order.</div>
                </div>
                <div class="col-md-3 col-6">
                    <input type="number" class="form-control" name="minorder"
                        value="<?php echo ($id != 0) ? $data->minorder : 1; ?>" required />
                </div>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Harga Coret</b></div>
                    <div class="fs-12">Harga normal sebelum diskon. hanya masukkan angka saja. cth: 200000</div>
                </div>
                <div class="col-md-3 col-6">
                    <input type="number" class="form-control" name="hargacoret"
                        value="<?php echo ($id != 0) ? $data->hargacoret : 0; ?>" required />
                </div>
            </div>
            <div class="row m-lr-0 m-b-32">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Harga Normal</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">Harga normal setelah diskon. hanya masukkan angka saja. cth: 200000</div>
                </div>
                <div class="col-md-3 col-6">
                    <input type="number" class="form-control" name="harga"
                        value="<?php echo ($id != 0) ? $data->harga : 0; ?>" required />
                </div>
            </div>
            <div class="row m-lr-0 m-b-32">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Nilai Komisi Afiliasi</b> &nbsp;<span class="badge badge-form">wajib</span>
                    </div>
                    <div class="fs-12">Nilai komisi penjualan yg diberikan kepada afiliator. hanya masukkan angka saja.
                        cth: 20000</div>
                </div>
                <div class="col-md-3 col-6">
                    <input type="number" class="form-control" name="afiliasi"
                        value="<?php echo ($id != 0) ? $data->afiliasi : 0; ?>" required />
                </div>
            </div>
            <div class="m-b-24 m-lr-15">
                <b>Harga Reseller Sesuai Level</b>
            </div>
            <div class="row m-lr-0 m-b-24 novariasi">
                <div class="col-md-3">
                    <div class="m-b-4"><b>Harga Reseller</b></div>
                    <div class="fs-12">harga reseller level 1</div>
                </div>
                <div class="col-md-3 col-6 m-b-24">
                    <input type="number" class="form-control" name="hargareseller"
                        value="<?php echo ($id != 0) ? $data->hargareseller : 0; ?>" required />
                </div>
                <div class="col-md-3">
                    <div class="m-b-4"><b>Harga Agen</b></div>
                    <div class="fs-12">harga reseller level 2</div>
                </div>
                <div class="col-md-3 col-6">
                    <input type="number" class="form-control" name="hargaagen"
                        value="<?php echo ($id != 0) ? $data->hargaagen : 0; ?>" required />
                </div>
            </div>
            <div class="row m-lr-0 novariasi">
                <div class="col-md-3">
                    <div class="m-b-4"><b>Harga Agen Premium</b></div>
                    <div class="fs-12">harga reseller level 3</div>
                </div>
                <div class="col-md-3 col-6 m-b-24">
                    <input type="number" class="form-control" name="hargaagensp"
                        value="<?php echo ($id != 0) ? $data->hargaagensp : 0; ?>" required />
                </div>
                <div class="col-md-3">
                    <div class="m-b-4"><b>Harga Distributor</b></div>
                    <div class="fs-12">harga reseller level 4</div>
                </div>
                <div class="col-md-3 col-6 m-b-24">
                    <input type="number" class="form-control" name="hargadistri"
                        value="<?php echo ($id != 0) ? $data->hargadistri : 0; ?>" required />
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Deskripsi Produk
            </div>
        </div>
        <div class="card-body">
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Berat Produk (gram)</b> &nbsp;<span class="badge badge-form">wajib</span>
                    </div>
                    <div class="fs-12">hanya isi dengan angka, misal berat 1kg maka isi: 1000</div>
                </div>
                <div class="col-md-4 col-6">
                    <input type="number" class="form-control" name="berat"
                        value="<?php echo ($id != 0) ? $data->berat : 250; ?>" required />
                </div>
            </div>
            <!--<div class="form-group" id="stok">
				<label>Stok Produk</label>
				<input type="number" class="form-control col-md-4 col-6" name="stok" value="<?php //echo ($id != 0) ? $data->stok : 0; ?>" required />
				<small>apabila ada variasi produk, maka abaikan form stok atau isi dengan 0</small>
			</div>-->
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Deskripsi Produk</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">masukkan deskripsi lengkap produk agar pembeli lebih mudah mengerti produk yang
                        dijual.</div>
                </div>
                <div class="col-md-8">
                    <textarea class="form-control" id="editor"
                        name="deskripsi"><?php echo ($id != 0) ? $data->deskripsi : ""; ?></textarea>
                </div>
            </div>
            <div class="row m-lr-0 m-b-24">
                <div class="col-md-4">
                    <div class="m-b-4"><b>Status Publikasi</b> &nbsp;<span class="badge badge-form">wajib</span></div>
                    <div class="fs-12">publish produk atau simpan sebagai draft.</div>
                </div>
                <div class="col-md-4 col-6">
                    <select class="form-control" name="status" required>
                        <option value="1" <?php echo ($id != 0 AND $data->status == 1) ? "selected" : ""; ?>>Published
                        </option>
                        <option value="0" <?php echo ($id != 0 AND $data->status == 0) ? "selected" : ""; ?>>Draft
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card saveproduk-imp">
        <div class="card-body text-right m-tb-12">
            <button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Simpan</button>
            <button type="reset" class="btn btn-warning"><i class="la la-refresh"></i> Reset</button>
            <button type="button" onclick="history.back()" class="btn btn-danger"><i class="la la-times"></i>
                Batal</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-header row m-lr-0">
        <div class="card-title p-lr-0" id="stokvariasi">Varian Stok Produk</div>
    </div>
    <div class="card-body">
        <div id="judulvarian" style="display:none;">
            <div class="row m-lr-0 m-b-40 m-t-12">
                <div class="col-md-3 text-right">
                    <b>Judul Varian</b>
                </div>
                <div class="col-md-3">
                    <input class="form-control" name="variasi" id="jdlvariasi" placeholder="cth: Warna"
                        value="<?php echo ($id != 0) ? $data->variasi : ""; ?>" />
                </div>
                <div class="col-md-3 text-right">
                    <b>Judul Sub Varian</b>
                </div>
                <div class="col-md-3">
                    <input class="form-control" name="subvariasi" id="jdlsubvariasi" placeholder="cth: Ukuran"
                        value="<?php echo ($id != 0) ? $data->subvariasi : ""; ?>" />
                </div>
            </div>
        </div>
        <div id="loadvar"></div>
        <?php
			$this->db->where("idproduk",$id);
			$this->db->order_by("warna");
			$vars = $this->db->get("produkvariasi");
			if($id != 0 AND !isset($_GET["copy"])){
				if($vars->num_rows() != 0){
		?>
        <script type="text/javascript">
        $(function() {
            $("#judulvarian").show();
            loadVariasi();
        });
        </script>
        <?php		
				}else{
		?>
        <div class="text-center p-tb-24" id="notifar">
            Belum ada pilihan varian untuk produk ini<br />&nbsp;<br />
            <button type="button" class="btn btn-primary"
                onclick="$('#judulvarian').show();$('#notifar').hide();loadVariasi()">Aktifkan Varian Produk</button>
        </div>
        <?php
				}
			}else{
		?>
        <div class="text-center p-tb-24" id="notifar">
            Untuk membuat varian produk, silahkan simpan dulu produk ini setelah itu <b>edit</b> produk yang telah
            disimpan.
        </div>
        <?php
			}
		?>
    </div>
</div>
<div class="m-b-100">
</div>

<script type="text/javascript">
tinymce.init({
    selector: '#editor',
    height: '500px',
    menubar: false,
    statusbar: false
});

$(function() {
    $("#digital").change(function() {
        if ($(this).val() == "1") {
            $(".digital").show();
            $("#akses").attr("required", true);
        } else {
            $(".digital").hide();
            $("#akses").attr("required", false);
        }
    });

    $("#preorder").change(function() {
        if ($(this).val() == 1) {
            $("#po").removeClass("btn-outline-primary");
            $("#po").addClass("btn-primary");
            $("#nopo").addClass("btn-outline-primary");
            $("#nopo").removeClass("btn-primary");
            $("#nopo .fa").hide();
            $(".tglpo").show();
            $("#po .fa").show();
            $("#stokvariasi").html("Kuota Stok Variasi PO");
        } else {
            $("#nopo").removeClass("btn-outline-primary");
            $("#nopo").addClass("btn-primary");
            $("#po").addClass("btn-outline-primary");
            $("#po").removeClass("btn-primary");
            $("#nopo .fa").show();
            $("#po .fa").hide();
            $(".tglpo").hide();
            $("#stokvariasi").html("Variasi Stok Produk");
        }
    });
    $("#preorder").trigger("change");

    $("#variasi").on('click', '.hapusvariasi', function() {
        var therem = $(this).parents(".form-group");
        swal.fire({
            title: "Yakin menghapus variasi?",
            text: "variasi akan dihapus, dan tidak dapat dikembalikan lagi",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal"
        }).then((val) => {
            if (val.value) {
                therem.remove();
                if (!$("#variasi input").val()) {
                    $("#stok").show();
                    $("#belumada").show();
                    $(".novariasi").show();
                }
            }
        });
    });
    $("#variasi").on('click', '.hapusvariasion', function() {
        var therem = $(this).parents(".form-group");
        var varid = $(this).data("varid");

        swal.fire({
            title: "Yakin menghapus variasi?",
            text: "variasi akan dihapus, dan tidak dapat dikembalikan lagi, termasuk stok juga akan habis",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal"
        }).then((val) => {
            if (val.value) {
                $.post("<?=site_url('ngadimin/hapusvariasi')?>", {
                    "theid": varid,
                    [$("#names").val()]: $("#tokens").val()
                }, function(e) {
                    var data = eval("(" + e + ")");
                    updateToken(data.token);
                    if (data.success == true) {
                        therem.remove();
                        if (!$("#variasi input").val()) {
                            $("#stok").show();
                            $("#belumada").show();
                            $(".novariasi").show();
                        }
                    } else {
                        swal.fire("Gagal",
                            "gagal menghapus variasi, coba ulangi beberapa saat lagi",
                            "danger");
                    }
                });
            }
        });
        $("#preorder").trigger('change');
    });

    $("#produk").on("submit", function(e) {
        e.preventDefault();
        var suk = $(".saveproduk-imp .btn-primary").html();
        $(".saveproduk-imp .btn-primary").html("menyimpan...");
        $(".saveproduk-imp .btn-primary").prop("disabled", true);
        var datar = $(this).serialize();
        datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val() + "&variasi=" + $(
            "#jdlvariasi").val() + "&subvariasi=" + $("#jdlsubvariasi").val();

        $.post("<?=$url?>", datar, function(msg) {
            var data = eval("(" + msg + ")");
            updateToken(data.token);
            $(".saveproduk-imp .btn-primary").html(suk);
            $(".saveproduk-imp .btn-primary").prop("disabled", false);
            if (data.success == true) {
                /*
                var fom = $("#variasi .form-control").val();
                if(typeof(fom) != "undefined" && fom !== null) {
                	var datars = $("#variasi").serialize();
                	datars = datars + "&" + $("#names").val() + "=" + $("#tokens").val();
                	$.post("<?=site_url("api/simpanvariasi")?>/"+data.id,datars,function(msg){
                		var datas = eval("("+msg+")");
                		updateToken(datas.token);
                		if(datas.success == true){
                */
                swal.fire("Selesai", "Data produk telah disimpan", "success").then((val) => {
                    window.location.href = "<?=site_url("ngadimin/produk")?>";
                });
                /*
                		}else{
                			swal.fire("Gagal Variasi",datas.msg,"error");
                		}
                	});
                }else{
                	swal.fire("Selesai","Data produk telah disimpan","success").then((val)=>{
                		window.location.href="<?=site_url("ngadimin/produk")?>";
                	});
                }
                */
            } else {
                swal.fire("Gagal", data.msg, "error");
            }
        });
    });

    <?php if($id == 0){ ?>
    hapusFoto("all");
    <?php } ?>


    $("#fotoUpload").change(function() {
        var formData = new FormData();
        formData.append("fotoProduk", $("#fotoUpload").get(0).files[0]);
        formData.append("jenis", 1);
        formData.append("idproduk", <?php echo $id; ?>);
        formData.append($("#names").val(), $("#tokens").val());
        $.ajax({
            url: '<?php echo site_url("api/uploadFotoProduk"); ?>',
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            xhr: function() {
                var jqXHR = null;
                if (window.ActiveXObject) {
                    jqXHR = new window.ActiveXObject("Microsoft.XMLHTTP");
                } else {
                    jqXHR = new window.XMLHttpRequest();
                }
                jqXHR.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded * 100) / evt
                            .total);
                        $("#prosesUpload").html("mengunggah: " + percentComplete +
                            "%");
                    }
                }, false);
                /*jqXHR.addEventListener( "progress", function ( evt )
                {
                    if ( evt.lengthComputable )
                    {
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        //Do something with download progress
                        console.log( 'Downloaded percent', percentComplete );
                    }
                }, false );*/
                return jqXHR;
            },
            success: function(data) {
                var datas = eval("(" + data + ")");
                updateToken(datas.token);
                $("#prosesUpload").html("");
                loadResult();
            }
        });
    });

    $(".dtp").datetimepicker({
        format: "YYYY-MM-DD",
        minDate: "<?=date("Y-m-d")?>"
    });

    loadResult();
});

function setPO(vl) {
    $('#preorder').val(vl).trigger('change');
    /*
    	swal.fire({
    		title: "GAGAL",
    		text: "anda tidak dapat mengubah produk preorder ke ready stok atau sebaliknya",
    		type: "warning"
    	});
    */
}

function loadResult() {
    $("#foto-produk").html("mohon tunggu sebentar...");
    $.post('<?php echo site_url("api/uploadFotoResult/".$id); ?>', {
        "response": 212,
        [$("#names").val()]: $("#tokens").val()
    }, function(msg) {
        var data = eval("(" + msg + ")");
        updateToken(data.token);
        if (data.success == true) {
            $("#foto-produk").html(data.data);
        }
    });
}

function loadVariasi() {
    $("#loadvar").html("mohon tunggu sebentar...");
    $.post('<?php echo site_url("api/variasiform/".$id); ?>', {
        "response": 212,
        [$("#names").val()]: $("#tokens").val()
    }, function(msg) {
        var data = eval("(" + msg + ")");
        updateToken(data.token);
        if (data.success == true) {
            $("#loadvar").html(data.data);
        } else {
            $("#loadvar").html("Gagal memuat variasi");
        }
    });
}

function hapusFoto(id) {
    if (id != "all") {
        swal.fire({
            title: "Yakin menghapus foto?",
            text: "data yang sudah dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true
        }).then((val) => {
            if (val.value) {
                $.post('<?php echo site_url("api/hapusFotoProduk/"); ?>' + id, {
                    "response": 212,
                    [$("#names").val()]: $("#tokens").val()
                }, function(msg) {
                    var data = eval("(" + msg + ")");
                    updateToken(data.token);
                    if (data.success == true) {
                        loadResult();
                    } else {
                        swal.fire({
                            title: "GAGAL",
                            text: "gagal meghapus data",
                            type: "error"
                        });
                    }
                });
            }
        });
    } else {
        $.post('<?php echo site_url("api/hapusFotoProduk/"); ?>' + id, {
            "response": 212,
            [$("#names").val()]: $("#tokens").val()
        }, function(msg) {
            var data = eval("(" + msg + ")");
            updateToken(data.token);
        });
    }
}

function jadikanUtama(id) {
    $.post('<?php echo site_url("api/jadikanFotoUtama/"); ?>' + id, {
        "idproduk": <?php echo $id; ?>,
        [$("#names").val()]: $("#tokens").val()
    }, function(msg) {
        var data = eval("(" + msg + ")");
        updateToken(data.token);
        if (data.success == true) {
            loadResult();
        } else {
            confirm("GAGAL!!!");
        }
    });
}

function updateStok(stok) {
    $("#stok").val(stok);
}
</script>