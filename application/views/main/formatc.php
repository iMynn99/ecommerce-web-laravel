<?php
    $prod = $this->func->getProduk($id,"semua");
    if($prod->id == 0){
        echo "Invalid Parameter: ID Produk";
        exit;
    }
    $adasub = false;
    $variasi = [];
    $this->db->where("idproduk",$prod->id);
    $db = $this->db->get("produkvariasi");
    $totalstok = $db->num_rows() > 0 ? 0 : $prod->stok;
    foreach($db->result() as $r){
        $variasi[$r->warna] = $r->id;
        $stokvariasi[$r->warna] = isset($stokvariasi[$r->warna]) ? $stokvariasi[$r->warna]+$r->stok : $r->stok;
        $subvariasi[$r->warna][$r->size] = $r->id;
        $totalstok += $r->stok;
        $stok[$r->id] = $r->stok;
        $harga[$r->id] = $r->harga;
        $hargareseller[$r->id] = $r->hargareseller;
        $hargaagen[$r->id] = $r->hargaagen;
        $hargaagensp[$r->id] = $r->hargaagensp;
        $hargadistri[$r->id] = $r->hargadistri;
        if($r->size != 0){
            $adasub = true;
        }
    }
    $level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 1;
    if($totalstok == 0){
        echo "<div class='text-center alert alert-danger'>Mohon maaf, stok produk telah habis.</div>";
        exit;
    }
?>
<form id="atcart">
    <div class="p-all-12">
        <div class="form-group">
            <label>Nama Produk</label>
            <div class="font-bold m-t--8 text-primary"><?=ucwords(strtolower($prod->nama))?></div>
        </div>
        <div class="form-group">
            <?php 
                if($prod->digital > 0){
                    echo "<span class=\"label bg-primary text-white m-b-12 font-medium\"><i class='fas fa-cloud'></i> &nbsp;PRODUK DIGITAL</span><br/>";
                }
                if($prod->preorder > 0){
                    echo "<span class=\"label bg-warning text-white m-b-12 font-medium\"><i class='fas fa-history'></i> &nbsp;PRE ORDER: &nbsp;<span class='font-bold'>".$prod->pohari."</span> HARI</span><br/>";
                }
            ?>
        </div>
        <?php if($db->num_rows() > 0){ ?>
        <div class="form-group">
            <label>Varian <?=ucwords(strtolower($prod->variasi))?></label>
            <select id="varian" class="form-control" required>
                <option value="">Pilih Varian <?=ucwords(strtolower($prod->variasi))?></option>
                <?php
                    $this->db->select("SUM(stok) as stok,warna,id,hargadistri,hargaagensp,hargaagen,hargareseller,harga");
                    $this->db->where("idproduk",$prod->id);
                    $this->db->group_by("warna");
                    $war = $this->db->get("produkvariasi");
                    foreach($war->result() as $w){
                        if($level == 5){
                            $hg = $w->hargadistri;
                        }elseif($level == 4){
                            $hg = $w->hargaagensp;
                        }elseif($level == 3){
                            $hg = $w->hargaagen;
                        }elseif($level == 2){
                            $hg = $w->hargareseller;
                        }else{
                            $hg = $w->harga;
                        }
                        if($w->stok > 0){
                            echo "<option value='".$w->warna."' data-stok='".$w->stok."' data-harga='".$hg."' data-variasi='".$w->id."'>".$this->func->getWarna($w->warna,"nama")."</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <?php if($adasub){ ?>
        <div class="form-group">
            <label>Sub Varian <?=ucwords(strtolower($prod->subvariasi))?></label>
            <select id="subvarian" class="form-control" required>
                <option value="">Pilih Varian <?=ucwords(strtolower($prod->variasi))?> dulu</option>
            </select>
        </div>
        <?php } ?>
        <?php } ?>
        <div class="form-group">
            <label>Jumlah Pembelian</label>
            <div class="row">
                <div class="input-group col-6">
                    <div class="input-group-prepend">
                        <button class="btn btn-primary" type="button"
                            onclick="$('#jumlah').val(parseFloat($('#jumlah').val())-1).trigger('change')"><i
                                class="fas fa-minus"></i></button>
                    </div>
                    <input type="number" class="form-control text-center" id="jumlah" name="jumlah" value="1"
                        max="<?=$totalstok?>" required />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"
                            onclick="$('#jumlah').val(parseFloat($('#jumlah').val())+1).trigger('change')"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="col-6 text-primary">
                    <i class="fas fa-box"></i> &nbsp;<b id="stok"><?=$totalstok?></b> pcs
                </div>
            </div>
        </div>
        <div class="form-group m-b-20">
            <label>Catatan Tambahan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
        </div>
        <input type="hidden" name="variasi" id="variasi" value="0" />
        <input type="hidden" name="harga" id="harga" value="<?=$prod->harga?>" />
        <input type="hidden" name="idproduk" value="<?=$prod->id?>" />
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block"><i class="fas fa-check"></i> &nbsp;Tambahkan ke
                Keranjang</button>
        </div>
    </div>
</form>

<?php
    foreach($variasi as $key=>$val){
        echo "<div id='sub_".$key."' style='display:none;'>";
        echo '<option value="">Pilih Sub Varian '.ucwords(strtolower($prod->variasi)).'</option>';
        foreach($subvariasi[$key] as $k=>$v){
            if($stok[$v] > 0){
                if($level == 5){
                    $hg = $hargadistri[$v];
                }elseif($level == 4){
                    $hg = $hargaagensp[$v];
                }elseif($level == 3){
                    $hg = $hargaagen[$v];
                }elseif($level == 2){
                    $hg = $hargareseller[$v];
                }else{
                    $hg = $harga[$v];
                }
                echo "<option value='".$k."' data-stok='".$stok[$v]."' data-harga='".$hg."' data-variasi='".$v."'>".$this->func->getSize($k,"nama")."</option>";
            }
        }
        echo "</div>";
    }
?>

<script type="text/javascript">
$(function() {
    <?php if($db->num_rows() > 0){ ?>
    var variasi = true;
    <?php }else{ ?>
    var variasi = false;
    <?php } ?>

    $("#jumlah").on("change", function() {
        if ($(this).val() < 1) {
            $(this).val(1);
        }
        if ($(this).val() > $(this).attr("max")) {
            $(this).val($(this).attr("max"));
        }
    });

    $("#varian").change(function() {
        $("#jumlah").val(1);
        <?php if($adasub){ ?>
        $("#subvarian").html($("#sub_" + $(this).val()).html());
        $("#variasi").val(0);
        $("#stok").html("<?=$totalstok?>");
        <?php }else{ ?>
        $("#variasi").val($(this).find(":selected").data('variasi'));
        $("#jumlah").attr("max", $(this).find(":selected").data('stok'));
        $("#stok").html($(this).find(":selected").data('stok'));
        $("#harga").val($(this).find(":selected").data('harga'));
        <?php } ?>
    });

    $("#subvarian").change(function() {
        $("#jumlah").val(1);
        $("#variasi").val($(this).find(":selected").data('variasi'));
        $("#jumlah").attr("max", $(this).find(":selected").data('stok'));
        $("#harga").val($(this).find(":selected").data('harga'));
        $("#stok").html($(this).find(":selected").data('stok'));
    });

    // TAMBAHKAN KE KERANJANG BELANJA
    $("#atcart").on("submit", function(e) {
        e.preventDefault();
        if (variasi == true && $("#variasi").val() == 0) {
            swal.fire("Pilih Varian",
                "pilih varian produk terlebih dahulu sebelum menambahkan produk ke keranjang",
                "warning");
        } else {
            var submit = $("#submit").html();
            $("#submit").html("<i class='fas fa-compact-disk fa-spin'></i> memproses...");
            // ambil isi summernote sebagai HTML string
            var catatan = $('#keterangan').summernote('code');

            // ambil data dari form
            var datar = $(this).serializeArray();

            // timpa nilai keterangan (jika ada)
            datar = datar.filter(field => field.name !== 'keterangan');
            datar.push({
                name: 'keterangan',
                value: catatan
            });

            // tambahkan CSRF
            datar.push({
                name: $("#names").val(),
                value: $("#tokens").val()
            });


            $.post("<?php echo site_url("assync/prosesbeli"); ?>", datar, function(msg) {
                var data = eval("(" + msg + ")");
                updateToken(data.token);
                closeatc();

                $("#submit").html(submit);
                if (data.success == true) {
                    fbq('track', 'AddToCart', {
                        content_ids: "<?=$prod->id?>",
                        content_type: "<?=$this->func->getKategori($prod->idcat,"nama")?>",
                        content_name: "<?=$prod->nama?>",
                        currency: "IDR",
                        value: data.total
                    });
                    var nameProduct = "<?=$prod->nama?>";
                    updateKeranjang();
                    swal.fire(nameProduct, "berhasil ditambahkan ke keranjang", "success");
                } else {
                    swal.fire("Gagal", "tidak dapat memproses pesanan \n " + data.msg, "error");
                }
            });
        }
    });
});

function initSummernote() {
    $('#keterangan').summernote({
        height: 250,
        placeholder: 'Tambahkan catatan di sini...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                for (let i = 0; i < files.length; i++) {
                    uploadImage(files[i]);
                }
            }
        }
    });
}

function uploadImage(file) {
    let data = new FormData();
    data.append("image", file);
    $.ajax({
        url: "<?=site_url('upload/summernoteImage')?>", // Sesuaikan controller-mu
        method: "POST",
        data: data,
        contentType: false,
        processData: false,
        success: function(url) {
            $('#keterangan').summernote('insertImage', url);
        },
        error: function(xhr) {
            alert("Upload gambar gagal: " + xhr.responseText);
        }
    });
}

// Jalankan saat DOM siap atau saat modal dibuka
$(function() {
    initSummernote();

    // Jika editor muncul di dalam modal, tambahkan:
    $('#modal-id').on('shown.bs.modal', function() {
        initSummernote();
    });
});
</script>