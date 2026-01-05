<?php
    $this->db->where("idproduk",$id);
    $this->db->order_by("warna,size","ASC");
    $db = $this->db->get("produkvariasi");
    $variasi = array();
    $subvariasi = array();
    foreach($db->result() as $res){
        $variasi[] = $res->warna;
        if($res->size > 0){
            $subvariasi[] = $res->size;
        }
    }
    $variasi = array_unique($variasi);
	$variasi = array_values($variasi);
    $subvariasi = array_unique($subvariasi);
	$subvariasi = array_values($subvariasi);

    //if(count($variasi) > 0){
?>
<div class="row m-lr-0 m-b-32">
    <div class="col-md-3">
        <div class="m-b-4"><b>Pilihan Varian</b></div>
		<div class="fs-12">tambahkan pilihan varian produk sesuai kebutuhan, maksimal 10 varian per produk</div>
    </div>
    <div class="col-md-9">
        <?php
            if(count($variasi) > 0){
                for($i=0; $i<count($variasi); $i++){
        ?>
            <div class="var-item">
                <div class="var-wrap">
                    <div class="name"><?=$this->func->getVariasiWarna($variasi[$i],"nama")?></div>
                    <div class="button" onclick="hapusVarian(<?=$variasi[$i]?>)"><span class="fas fa-times"></span></div>
                </div>
            </div>
        <?php
                }
            }
        ?>
        <?php if(count($variasi) <= 40){ ?>
            <div class="var-item">
                <div class="var-wrap">
                    <div onclick="tambahVarian()" class="var-btn"><i class="fas fa-plus"></i> tambah</div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="row m-lr-0 m-b-36">
    <div class="col-md-3">
        <div class="m-b-4"><b>Pilihan Sub Varian</b></div>
		<div class="fs-12">tambahkan pilihan sub varian produk sesuai kebutuhan, maksimal 10 sub varian</div>
    </div>
    <div class="col-md-9">
        <?php
            if(count($subvariasi) > 0){
                for($i=0; $i<count($subvariasi); $i++){
        ?>
            <div class="var-item">
                <div class="var-wrap">
                    <div class="name"><?=$this->func->getVariasiSize($subvariasi[$i],"nama")?></div>
                    <div class="button" onclick="hapusSubvarian(<?=$subvariasi[$i]?>)"><span class="fas fa-times"></span></div>
                </div>
            </div>
        <?php
                }
            }
        ?>
        <?php if(count($variasi) == 0){ echo "<i class='text-danger'>tambahkan varian terlebih dahulu</i>"; } ?>
        <?php if(count($subvariasi) <= 40 AND count($variasi) > 0){ ?>
            <div class="var-item">
                <div class="var-wrap">
                    <div onclick="tambahSubvarian()" class="var-btn"><i class="fas fa-plus"></i> tambah</div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="table-responsive p-lr-12">
    <form id="variansimpan">
        <table class="table table-sm table-bordered table-hover">
            <tr>
                <th style="text-align:center;" rowspan=2>Varian</th>
                <?php if(count($subvariasi) > 0){ ?>
                <th style="text-align:center;" rowspan=2>Sub Varian</th>
                <?php } ?>
                <th style="width:30%;text-align:center;" colspan=2>Harga</th>
                <th style="width:10%;text-align:center;" rowspan=2>Stok</th>
            </tr>
            <tr>
                <th style="width:20%">Normal</th>
                <th style="width:10%">Reseller</th>
            </tr>
            <?php
                foreach($db->result() as $r){
            ?>
            <tr>
                <input type="hidden" name="hargareseller[<?=$r->id?>]" id="reseller_<?=$r->id?>" value="<?=$r->hargareseller?>" />
                <input type="hidden" name="hargaagen[<?=$r->id?>]" id="agen_<?=$r->id?>" value="<?=$r->hargaagen?>" />
                <input type="hidden" name="hargaagensp[<?=$r->id?>]" id="agensp_<?=$r->id?>" value="<?=$r->hargaagensp?>" />
                <input type="hidden" name="hargadistri[<?=$r->id?>]" id="distri_<?=$r->id?>" value="<?=$r->hargadistri?>" />
                <td><?=$this->func->getVariasiWarna($r->warna,"nama")?></td>
                <?php if(count($subvariasi) > 0){ ?>
                <td><?=$this->func->getVariasiSize($r->size,"nama")?></td>
                <?php } ?>
                <td><input type="text" name="harga[<?=$r->id?>]" class="form-control" value="<?=$r->harga?>" /></td>
                <td><button type="button" onclick="hargaReseller(<?=$r->id?>)" class="btn btn-xs btn-secondary btn-block"><i class="fas fa-cog"></i> atur</button></td>
                <td><input type="text" name="stok[<?=$r->id?>]" class="form-control" value="<?=$r->stok?>" /></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(function(){
        $("#variansimpan .form-control").keyup(delay(function(){
            simpanVariasi();
        },1500));
        $("#variansimpan").on("submit",function(e){
            e.preventDefault();
            simpanVariasi();
        });

        $("#simpanharga").on("submit",function(e){
            e.preventDefault();
            var id = $("#varid").val();
            $("#reseller_"+id).val($("#reseller").val());
            $("#agen_"+id).val($("#agen").val());
            $("#agensp_"+id).val($("#agensp").val());
            $("#distri_"+id).val($("#distri").val());
            $("#reseller").val(0);
            $("#agen").val(0);
            $("#agensp").val(0);
            $("#distri").val(0);
            $("#modalharga").modal("hide");
            simpanVariasi();
        });

        $("#simpanvarian").on("submit",function(e){
            e.preventDefault();
            $(".modal").modal("hide");
            var datar = $(this).serialize();
            datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
            
            setTimeout(function(){
                $.post("<?=site_url("api/varianadd")?>",datar,function(msg){
                    var data = eval("("+msg+")");
                    updateToken(data.token);
                    if(data.success == true){
                        loadVariasi();
                    }else{
                        swal.fire("Gagal menyimpan","gagal menyimpan data varian, silahkan refresh halaman ini lalu edit kembali datanya","error");
                    }
                });
            },500);
        });
        $("#simpansubvarian").on("submit",function(e){
            e.preventDefault();
            $(".modal").modal("hide");
            var datar = $(this).serialize();
            datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

            setTimeout(function(){
                $.post("<?=site_url("api/subvarianadd")?>",datar,function(msg){
                    var data = eval("("+msg+")");
                    updateToken(data.token);
                    if(data.success == true){
                        loadVariasi();
                    }else{
                        swal.fire("Gagal menyimpan","gagal menyimpan data varian, silahkan refresh halaman ini lalu edit kembali datanya","error");
                    }
                });
            },500);
        });
    });

    function hargaReseller(id){
        $("#reseller").val($("#reseller_"+id).val());
        $("#agen").val($("#agen_"+id).val());
        $("#agensp").val($("#agensp_"+id).val());
        $("#distri").val($("#distri_"+id).val());
        $("#varid").val(id);
        $("#modalharga").modal();
    }
    function tambahVarian(){
        $("#simpanvarian .form-control").val("");
        $("#modalvarian").modal();
    }
    function tambahSubvarian(){
        $("#simpansubvarian .form-control").val("");
        $("#modalsubvarian").modal();
    }
    function simpanVariasi(){
        var datar = $("#variansimpan").serialize();
        datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

        /*
        $.post("<?=site_url("api/variansave/".$id)?>",datar,function(msg){
            var data = eval("("+msg+")");
            updateToken(data.token);
            if(data.success == true){
                updateStok(data.stok);
            }else{
                swal.fire("Gagal menyimpan","gagal menyimpan data varian, silahkan refresh halaman ini lalu edit kembali datanya","error");
            }
        });
        */
        $.ajax({
            type: "POST",
            url:  "<?=site_url("api/variansave/".$id)?>",
            data: datar,
            statusCode: {
                403: function(responseObject, textStatus, jqXHR) {
                    resetToken();
                    setTimeout(() => {
                        simpanVariasi();
                    }, 1000);
                }          
            }
        })
        .done(function(data){
            var data = eval("("+data+")");
            updateToken(data.token);
            if(data.success == true){
                updateStok(data.stok);
            }else{
                swal.fire("Gagal menyimpan","gagal menyimpan data varian, silahkan refresh halaman ini lalu edit kembali datanya","error");
            }
        })
    }
    function hapusVarian(id){
        swal.fire({
            title: "Yakin menghapus varian ini?",
            text: "data yang sudah dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true
        }).then((val)=>{
            if(val.value){
                $.post("<?=site_url("api/varianhapus")?>",{"id":id,"produk":<?=$id?>,[$("#names").val()]:$("#tokens").val()},function(msg){
                    var data = eval("("+msg+")");
                    updateToken(data.token);
                    if(data.success == true){
                        loadVariasi();
                    }else{
                        swal.fire("Gagal menyimpan","gagal menyimpan data varian, silahkan refresh halaman ini lalu edit kembali datanya","error");
                    }
                });
            }
        });
    }
    function hapusSubvarian(id){
        swal.fire({
            title: "Yakin menghapus sub varian ini?",
            text: "data yang sudah dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true
        }).then((val)=>{
            if(val.value){
                $.post("<?=site_url("api/subvarianhapus")?>",{"id":id,"produk":<?=$id?>,[$("#names").val()]:$("#tokens").val()},function(msg){
                    var data = eval("("+msg+")");
                    updateToken(data.token);
                    if(data.success == true){
                        loadVariasi();
                    }else{
                        swal.fire("Gagal menyimpan","gagal menyimpan data varian, silahkan refresh halaman ini lalu edit kembali datanya","error");
                    }
                });
            }
        });
    }
</script>

<div class="modal fade" id="modalvarian" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title"><i class="fas fa-plus"></i> Tambah Varian</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="simpanvarian" method="POST">
				<input type="hidden" name="produk" value="<?=$id?>" />
				<div class="modal-body">
					<div class="form-group">
						<label>Nama Varian</label>
						<input type="text" class="form-control" name="nama" required />
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modalsubvarian" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title"><i class="fas fa-plus"></i> Tambah Sub Varian</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="simpansubvarian" method="POST">
				<input type="hidden" name="produk" value="<?=$id?>" />
				<div class="modal-body">
					<div class="form-group">
						<label>Nama Sub Varian</label>
						<input type="text" class="form-control" name="nama" required />
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modalharga" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title"><i class="fas fa-cog"></i> Pengaturan Harga Reseller</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="simpanharga" method="POST">
				<input type="hidden" id="varid" />
				<div class="modal-body">
					<div class="form-group">
						<label>Harga Reseller</label>
						<input type="number" class="form-control" min="0" id="reseller" />
					</div>
					<div class="form-group">
						<label>Harga Agen</label>
						<input type="number" class="form-control" min="0" id="agen" />
					</div>
					<div class="form-group">
						<label>Harga Agen Premium</label>
						<input type="number" class="form-control" min="0" id="agensp" />
					</div>
					<div class="form-group">
						<label>Harga Distributor</label>
						<input type="number" class="form-control" min="0" id="distri" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>