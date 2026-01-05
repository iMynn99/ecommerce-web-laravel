<div class="alert alert-warning text-center">
    <i class="fas fa-exclamation-triangle"></i> &nbsp;untuk mengaktifkan atau menonaktifkan klik kurir dibawah
</div>
<style rel="stylesheet">
	.op5{-webkit-filter: grayscale(100%);filter: grayscale(100%);}
	.kurir:hover .imgk{
		border-color:#00cc00;
		-webkit-filter: none;
		filter: none;
	}
	.imgk{
		border-left: 1px dashed #00cc00;
		border-top: 1px dashed #00cc00;
		border-right: 1px dashed #00cc00;
	}
	.bgijo{
		background:#00cc00;
	}
	.bgabu{
		background:#ccc;
	}
	.kurir:hover .statk{
		background:#00cc00;
	}
</style>
<div class="p-tb-10">
	<form id="kurirform">
		<div class="row m-lr-0">
			<?php
				$kurir = explode("|",$this->func->getGudang($id,"kurir"));
				$db = $this->db->get("kurir");
				foreach($db->result() as $kur){
					$bor = (in_array($kur->id,$kurir)) ? "border-color:#00cc00;" : "";
					$bg = (in_array($kur->id,$kurir)) ? " bgijo" : " bgabu";
					$op = (in_array($kur->id,$kurir)) ? "" : " op5";
					$aktif = (in_array($kur->id,$kurir)) ? "<b>AKTIF</b>" : "<b>NON AKTIF</b>";
					$aktiv = (in_array($kur->id,$kurir)) ? "yes" : "no";
					$txt = (in_array($kur->id,$kurir)) ? "text-primary" : "";
			?>
					<div class="col-md-3 col-6 kurir p-lr-5" data-push="<?php echo $kur->id; ?>" data-aktif="<?php echo $aktiv; ?>">
						<div class="imgk p-all-20 pointer<?php echo $op; ?>" style="<?php echo $bor; ?>">
							<?php if(file_exists("assets/img/kurir/".$kur->rajaongkir.".png")){ ?>
								<img class="w-full" src="<?=base_url("assets/img/kurir/".$kur->rajaongkir.".png"); ?>" />
							<?php }else{ ?>
								<div id="textkurir" class="fs-24 font-weight-bold text-center <?=$txt?>"><?=strtoupper(strtolower($kur->nama))?></div>
							<?php } ?>
						</div>
						<div class="statk m-b-20 p-all-5 txt-center<?php echo $bg; ?>" style="color:#fff;"><?php echo $aktif; ?></div>
					</div>
			<?php
				}
			?>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(function(){
		$(".kurir").each(function(){
			$(this).click(function(){
				var kurir = $(this);
				if($(this).data("aktif") == "no"){
					$.post("<?php echo site_url("gudang/aktifkankurir/".$id); ?>",{"push":$(this).data("push"),[$("#names").val()]:$("#tokens").val()},function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							swal.fire("Berhasil!","kurir telah diaktifkan","success");
							kurir.data("aktif","yes");
							kurir.find("#textkurir").addClass("text-primary");
							kurir.find(".imgk").removeClass("op5");
							kurir.find(".statk").removeClass("bgabu");
							kurir.find(".statk").addClass("bgijo");
							kurir.find(".statk").html("<b>AKTIF</b>");
							kurir.find(".imgk").css("border-color","#00cc00");
						}else{
							swal.fire("Gagal!","Terjadi kendala saat mengaktifkan kurir ini, coba ulangi beberapa saat lagi","error");
						}
					});
				}else{
					if(hitungJumlah() > 1){
						$.post("<?php echo site_url("gudang/nonaktifkankurir/".$id); ?>",{"push":$(this).data("push"),[$("#names").val()]:$("#tokens").val()},function(msg){
							var data = eval("("+msg+")");
							updateToken(data.token);
							if(data.success == true){
								swal.fire("Berhasil!","kurir telah dinonaktifkan","success");
								kurir.data("aktif","no");
								kurir.find(".imgk").addClass("op5");
								kurir.find("#textkurir").removeClass("text-primary");
								kurir.find(".statk").addClass("bgabu");
								kurir.find(".statk").removeClass("bgijo");
								kurir.find(".statk").html("<b>NON AKTIF</b>");
								kurir.find(".imgk").css("border-color","#ccc");
							}else{
								swal.fire("Gagal!","Terjadi kendala saat mengaktifkan kurir ini, coba ulangi beberapa saat lagi","error");
							}
						});
					}else{
						swal.fire("Error!","Tidak dapat menonaktifkan kurir ini, Anda harus mengaktifkan minimal 1 kurir pengiriman untuk toko Anda","error");
					}
				}
			});
		});
	});
	
	function hitungJumlah(){
		var aktifan = 0;
		$('.kurir').each(function(){
			var aktif = $(this).data('aktif');
		  if (aktif == "yes") {
		  	aktifan += 1;
		  }
		});
		return aktifan;
	}
</script>