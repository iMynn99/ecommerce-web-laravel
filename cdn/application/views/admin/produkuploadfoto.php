<!--<div class="uploadfoto">
	<form id="upload" method="POST" enctype="multipart/form-data" action="<?php echo site_url("assync/uploadFotoProduk"); ?>">
		<input type="hidden" name="jenis" value="1" />
		<input type="hidden" name="idproduk" value="0" />
		<label class="form-uploadfoto">
			<input type="file" name="fotoProduk" onchange="this.form.submit()"></input>
			<img src="<?php echo base_url("assets/img/komponen/add-product.png"); ?>"/>
		</label>
	</form>
</div>-->

<?php
	if(isset($_SESSION["fotoCopy"]) AND is_array($_SESSION["fotoCopy"]) AND count($_SESSION["fotoCopy"]) > 0){
		$fotoCopy = $_SESSION["fotoCopy"];
		if(isset($_SESSION["fotoProduk"]) AND is_array($_SESSION["fotoProduk"]) AND count($_SESSION["fotoProduk"]) > 0){
			$fotoCopy = array_merge($fotoCopy,$_SESSION["fotoProduk"]);
		}
		//print_r($fotoCopy);
		$this->db->where_in("id",$fotoCopy);
	}else{
		$this->db->where("idproduk",$idproduk);
	}
	$this->db->order_by("jenis","DESC");
	$db = $this->db->get("upload");
	foreach($db->result() as $res){
		if($res->jenis == 1){
			$btn = "<button type='button' class='utama' disabled>foto utama</button>";
		}else{
			$btn = (isset($_SESSION["fotoCopy"])) ? "" : "<button type='button' class='jadiutama' onclick='jadikanUtama(".$res->id.")'>Utama</button>";
			$btn .= (isset($_SESSION["fotoCopy"])) ? "<button type='button' class='hapus' style='width:100%' onclick='hapusFoto(".$res->id.")'>Hapus</button>" : "<button type='button' class='hapus' onclick='hapusFoto(".$res->id.")'>Hapus</button>";
		}
		echo "
			<div class='uploadfoto-item'>
				<img src='".base_url("uploads/".$res->nama)."' />
				".$btn."
			</div>
		";
	}
?>