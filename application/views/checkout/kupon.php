<div class="input-group m-t-10">
    <input type="text" class="form-control" placeholder="Masukkan voucher" id="kodevoucher" name="kodevoucher" />
    <input type="hidden" name="diskon" id="diskon" value='0' />
    <div class="input-group-append">
    <button class="btn btn-primary" type="button" onclick="cekVoucher()">Cek Voucher</button>
    </div>
</div>
<div class="m-t-10 m-b-20">
    <div class="vouchergagal text-danger" style="display:none;">Maaf, Voucher sudah tidak berlaku!</div>
    <div class="vouchersukses text-success" style="display:none;">Selamat, Voucher berhasil dipakai dan nikmati potongannya!</div>
</div>
<div class="voucher row">
    <?php
    $this->db->where("mulai <=",date("Y-m-d"));
    $this->db->where("selesai >=",date("Y-m-d"));
    $this->db->where("public",1);
    $voc = $this->db->get("voucher");
    foreach($voc->result() as $v){
        $pot = $this->func->formUang($v->potongan);
        $potongan = $v->tipe == 2 ? "<div class=\"font-bold fs-24 text-success text-center p-tb-12\">Rp ".$pot."</div>" : '<div class="font-bold fs-38 text-success text-center p-tb-0">'.$pot."%</div>";
        $jenis = $v->jenis == 1 ? "Harga" : "Ongkir";
    ?>
        <div class="col-md-4">
            <div class="voucher-item m-lr-10 m-tb-14 cursor-pointer" data-kode="<?=$v->kode?>">
                <!--
                <div class="potongan">
                    '.$potongan.'
                    <div class="m-t-10 m-b-10 t-center fs-12">Potongan '.$jenis.' </div>
                </div>
                <div class="kode">
                    <div class="text-danger fs-20 font-bold p-all-0">'.$v->kode.'</div>
                </div>
                <div class="detail">
                    <div class="p-all-0">
                    <span class="text-success font-medium">'.$v->nama.'</span><br/>
                    <small>
                        '.$v->deskripsi.'<br/>
                        <small>minimal pembelian Rp. '.$this->func->formUang( $v->potonganmin).'</small>
                    </small>
                    </div>
                </div>
                -->
            </div>
        </div>
    <?php
    }
    ?>
</div>

<script type="text/javascript">
    $(function(){
        $(".voucher-item").on("click",function(){
            var kode = $(this).data("kode");
            //confirm(kode);//
            $("#kodevoucher").val(kode);
            setTimeout(cekVoucher(),1000);
        });
    });
    
	//CEK VOUCHER
	function cekVoucher(){
		if($("#kodevoucher").val() != ""){
			$.post("<?=site_url("checkout/kupon")?>",{"kode":$("#kodevoucher").val(),"harga":$("#totalharga").val(),[$("#csrf_name").val()]: $("#csrf_token").val(),"ongkir":$("#ongkir").val()},function(msg){
				var data = eval("("+msg+")");
                $(".csrf_token").val(data.token);
                updateToken(data.token);
				if(data.success == true){ 
					var total = parseFloat($("#total").val()) + parseFloat($("#ongkir").val()) - data.diskon;
					$("#diskon").val(data.diskon);
					$("#diskonshow").html(formUang(data.diskon.toString()));
					$("#totalbayar").html(formUang(total.toString()));
					$("#transfer").html(formUang(total.toString()));
					$(".vouchergagal").hide();
					$(".vouchersukses").show();
				}else{
					$("#diskon").val(0);
					$("#diskonshow").html(0);
					$(".vouchergagal").show();
					$(".vouchersukses").hide();
				}
			});
		}else{
			swal.fire("Masukkan Kode Voucher","Masukkan kode voucher terlebih dahulu lalu klik tombol cek voucher","warning");
		}
	}
</script>