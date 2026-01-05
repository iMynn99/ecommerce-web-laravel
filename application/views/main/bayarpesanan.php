<?php
  if($data->num_rows() == 0){
    redirect("home");
    exit;
  }
  $set = $this->func->globalset("semua");
?>

	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-l-0 p-l-0">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<a href="<?php echo site_url("home/keranjang"); ?>" class="text-primary">
				Keranjang Belanja
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Pembayaran
			</span>
		</div>
	</div>

	<!-- Shoping Cart -->
	<form class="bg0 p-t-40 p-b-85" id="cekout">
		<div class="container">
			<div class="row bayarpesanan">
        <div class="col-md-8 m-l-auto m-r-auto m-b-10">
			    <div class="p-lr-24 p-t-40 p-b-30 m-l-0 m-r-0">
            <h4 class="font-bold text-primary">
              Informasi Pengiriman
            </h4>
          </div>
          <div class="section p-lr-32 p-t-30 p-b-40 m-l-0-xl m-r-0-xl p-l-15 p-r-25">
            <?php
              $this->db->where("usrid",$_SESSION["usrid"]);
              $this->db->order_by("status","DESC");
              $row = $this->db->get("alamat");
            ?>
            <div class="p-b-13">
              <input type="hidden" id="tujuan" value="" name="tujuan" />
              <div class="m-b-12 alamatform">
                <b>Alamat Pengiriman</b>
              </div>
              <div class="rs1-select2 rs2-select2 m-b-12 alamatform">
                <select class="js-select2 form-control" name="alamat" id="idalamat">
                  <option value="">- Pilih Alamat Tujuan -</option>
                  <option value="0">+ Tambah Alamat Baru</option>
                  <?php
                    foreach($row->result() as $al){
                      //RAJAONGKIR
                      $kec = $this->func->getKec($al->idkec,"semua");
                      $idkab = $kec->idkab;
                      $keckab = $kec->nama.", ".$this->func->getKab($idkab,"nama");
                      echo '<option value="'.$al->id.'" data-tujuan="'.$al->idkec.'">'.strtoupper(strtolower($al->judul.' - '.$al->nama)).' ('.$keckab.')</option>';
                    }
                  ?>
                </select>
                <div class="dropDownSelect2"></div>
              </div>
              <div class="m-b-12">
                <?php
                  foreach($row->result() as $als){
                    $kec = $this->func->getKec($al->idkec,"semua");
                    $idkab = $kec->idkab;
                    $kec = $kec->nama;
                    $kab = $this->func->getKab($idkab,"nama");
                    echo "
                      <div class='alamat section bg-medium p-tb-20 p-lr-24 m-t-20' id='alamat_".$als->id."' data-tujuan='".$al->idkec."' style='display:none;'>
                        <b class='text-info'>Nama Penerima:</b><br/>".strtoupper(strtolower($als->nama))."<br/>
                        <b class='text-info'>No HP:</b><br/>".$als->nohp."<br/>
                        <b class='text-info'>Alamat Lengkap:</b><br/>".strtoupper(strtolower($als->alamat."<br/>".$kec.", ".$kab))."<br/>KODEPOS ".$als->kodepos."
                      </div>
                    ";
                  }
                ?>
              </div>
            </div>

            <div class="p-b-13">
              <div class="m-b-12 tambahalamat" style="display:none;">
                <b>Tambah Alamat Pengiriman</b>
              </div>
              <div class="tambahalamat" style="display:none;">
                  <div class="m-b-12 col-md-10 p-lr-0">
                    <input class="form-control" type="text" name="judul" placeholder="Simpan Sebagai? ex: Alamat Rumah, Alamat Kantor, Dll">
                  </div>
                  <div class="m-b-12 col-md-8 p-lr-0">
                    <input class="form-control" type="text" name="nama" placeholder="Nama Penerima">
                  </div>
                  <div class="m-b-12 col-md-6 p-lr-0">
                    <input class="form-control" type="text" name="nohp" placeholder="No Handphone Penerima">
                  </div>
                  <div class="m-b-12">
                    <textarea class="form-control" name="alamatbaru" placeholder="Alamat lengkap"></textarea>
                  </div>
                  <div class="row m-lr-0">
                    <div class="rs1-select2 rs2-select2 col-md-5 m-b-12 p-lr-0">
                      <select class="js-select2 form-control" name="negara" readonly>
                        <option value="ID">Indonesia</option>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="col-md-6 p-b-10"></div>
                    <div class="rs1-select2 rs2-select2 col-md-5 m-b-12 p-lr-0">
                      <select class="js-select2 form-control" id="prov">
                        <option value="">Provinsi</option>
                        <?php
                          $prov = $this->db->get("prov");
                          foreach($prov->result() as $pv){
                            echo '<option value="'.$pv->id.'">'.$pv->nama.'</option>';
                          }
                        ?>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="col-md-1 p-b-10"></div>
                    <div class="rs1-select2 rs2-select2 col-md-5 m-b-12 p-lr-0">
                      <select class="js-select2 form-control" id="kab">
                        <option value="">Kabupaten</option>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="col-md-1 p-b-10"></div>
                    <div class="rs1-select2 rs2-select2 col-md-5 m-b-12 p-lr-0">
                      <select class="js-select2 form-control" id="kec" name="idkec">
                        <option value="">Kecamatan</option>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="col-md-1 p-b-10"></div>
                    <div class="m-b-12 col-md-5 p-lr-0">
                      <input class="form-control" type="number" name="kodepos" placeholder="Kode POS">
                    </div>
                  </div>
              </div>
            </div>
            <div class="p-t-10 p-b-10">
              <b>Kurir Pengiriman</b>
            </div>
            <div class="row m-l-0 m-r-0 p-b-20">
              <div class="col-md-5 p-lr-0">
                <div class="rs1-select2 rs2-select2 bor8 bg0 w-full">
                  <select class="js-select2 kurir form-control" id="kurir" name="kurir">
                    <option value="">Pilih Ekspedisi</option>
                    <?php
                      $kur = $this->db->get("kurir");
                      foreach($kur->result() as $kr){
                        $kurir = explode("|",$this->func->getSetting("kurir"));
                        if(in_array($kr->id,$kurir)){
                          echo '<option value="'.$kr->id.'">'.$kr->nama.'</option>';
                        }
                      }
                    ?>
                  </select>
                  <div class="dropDownSelect2"></div>
                </div>
              </div>
              <div class="col-md-1 p-b-10"></div>
              <div class="col-md-5 p-lr-0">
                <div class="rs1-select2 rs2-select2 bor8 bg0 w-full" id="paketform">
                  <select class="js-select2 paket form-control" id="paket" name="paket">
                    <option value="">Pilih Paket Pengiriman</option>
                  </select>
                  <div class="dropDownSelect2"></div>
                </div>
              </div>
              <div class="error text-danger col-md-12 m-t-10" id="error-kurir" style="display:none;">
                <span><i class="fa fa-exclamation-circle"></i> pilihan ekspedisi atau paket pengiriman saat ini sedang tidak tersedia untuk alamat tujuan Anda, silahkan pilih yg lain.</span><p/>
              </div>
            </div>
            <div class="p-t-20">
              <b>Dropship</b>
            </div>
            <div class="dropship">
                <div class="m-t-10">
                  <button type="button" id="nodrop" class="btn btn-primary m-r-10"><i class="fa fa-check-square"></i> Tidak Dropship</button>
                  <div class="showsmall m-t-10"></div>
                  <button type="button" id="yesdrop" class="btn hov-primary"><i class="fa fa-check-square" style="display:none"></i> Dropship</button>
                </div>
                <div class="p-t-20" id="dropform" style="display:none;">
                  <input type="text" name="dropship" class="form-control col-md-8" placeholder="nama/olshop pengirim" />
                  <input type="text" name="dropshipnomer" class="form-control col-md-6 m-t-5" placeholder="no telepon" />
                  <input type="text" name="dropshipalamat" class="form-control m-t-5" placeholder="alamat pengirim" />
                </div>
              </div>
			    </div>
			    <div class="p-lr-24 p-t-40 p-b-30 m-lr-0">
            <h4 class="font-bold text-primary">
              Voucher
            </h4>
          </div>
          <div class="section p-lr-24 p-t-30 p-b-30 m-lr-0">
            <div class="input-group m-t-10">
              <input type="text" class="form-control" placeholder="Masukkan voucher" id="kodevoucher" name="kodevoucher" />
              <input type="hidden" name="diskon" id="diskon" value='0' />
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" onclick="cekVoucher()">Cek Voucher</button>
              </div>
            </div>
            <div class="m-t-10 m-b-20">
              <div class="vouchergagal text-danger" style="display:none;">Maaf, Voucher sudah tidak berlaku</div>
              <div class="vouchersukses text-success" style="display:none;">Selamat, Voucher berhasil dipakai dan nikmati potongannya</div>
            </div>
            <div class="voucher">
              <div class="m-r-20"></div>
              <?php
                $this->db->where("mulai <=",date("Y-m-d"));
                $this->db->where("selesai >=",date("Y-m-d"));
                $this->db->where("public",1);
                $voc = $this->db->get("voucher");
                foreach($voc->result() as $v){
                  $pot = $this->func->formUang($v->potongan);
                  $potongan = $v->tipe == 2 ? "<div class=\"font-bold fs-24 text-success text-center p-tb-12\">Rp ".$pot."</div>" : '<div class="font-bold fs-38 text-success text-center p-tb-0">'.$pot."%</div>";
                  $jenis = $v->jenis == 1 ? "Harga" : "Ongkir";
                  echo '
                  <div class="voucher-item m-lr-10 m-tb-14 cursor-pointer" data-kode="'.$v->kode.'">
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
                  </div>';
                }
              ?>
            </div>
          </div>
			    <div class="p-lr-24 p-t-40 p-b-30 m-l-0 m-r-0">
            <h4 class="font-bold text-primary">
              Produk Pesanan
            </h4>
          </div>
          <?php
            $totalharga = 0;
            $totalbayar = 0;
            $totalberat = 0;
          ?>
			    <div class="produk p-b-40 m-l-0 m-r-0">
            <?php
                foreach($data->result() as $res){
                  $produk = $this->func->getProduk($res->idproduk,"semua");
                  $totalbayar += $res->harga * $res->jumlah;
                  $totalberat += $produk->berat * $res->jumlah;
                  $totalproduks = $res->harga*$res->jumlah;
                  $totalharga += $res->harga*$res->jumlah;
                  $variasee = $this->func->getVariasi($res->variasi,"semua");
                  $subvariasi = ($res->variasi != 0 AND $variasee->size > 0) ? ", ".$produk->subvariasi.": ".$this->func->getSize($variasee->size,"nama") : "";
                  $variasi = ($res->variasi != 0) ? $this->func->getWarna($variasee->warna,"nama").$subvariasi : "";
                  $variasi = ($res->variasi != 0) ? "<br/><small class='text-primary'>".$produk->variasi.": ".$variasi."</small>" : "";
            ?>
						<div class="produk-item row m-b-30 m-lr-0">
							<div class="col-md-2 row m-lr-0 p-lr-0">
								<div class="img" style="background-image:url('<?php echo $this->func->getFoto($produk->id,"utama"); ?>')"></div>
							</div>
							<div class="col-md-7 hidesmall">
								<div class="p-l-10 font-medium"><?php echo $produk->nama.$variasi; ?></div>
							</div>
							<div class="col-3 font-medium text-info">
								<p><?php echo $res->jumlah; ?>x <?php echo $this->func->formUang($res->harga); ?></p>
							</div>

							<input type="hidden" class="idproduks" name="idproduk[]" value="<?php echo $res->id; ?>" />
							<input type="hidden" id="totalproduks_<?php echo $res->id; ?>" value="<?php echo $totalproduks; ?>" />
							<input type="hidden" id="namaproduks_<?php echo $res->id; ?>" value="<?php echo $produk->nama; ?>" />
							<input type="hidden" id="kategoriproduks_<?php echo $res->id; ?>" value="<?php echo $this->func->getKategori($produk->idcat,"nama"); ?>" />
						</div>
            <?php
                }
            ?>
              <input type="hidden" id="totalharga" value="<?php echo $totalharga; ?>" />
              <input type="hidden" id="iscod" value="0" />
              <input type="hidden" name="berat" class="berat" id="berat" value="<?php echo $totalberat; ?>" />
              <input type="hidden" name="ongkir" class="ongkir" id="ongkir" value="0" />
              <!-- RAJAONGKIR -->
              <input type="hidden" name="dari" id="dari" value="<?php echo $this->func->getSetting("kota"); ?>" />
          </div>
		    </div>
        <div class="sticky col-md-4 m-l-auto m-r-auto m-b-50">
			    <div class="p-lr-24 p-t-40 p-b-30 m-l-0 m-r-0">
            <h4 class="font-bold text-primary">
              Pembayaran
            </h4>
          </div>
          <div class="section p-lr-24 p-t-30 p-b-40 m-r-0 m-l-0 m-b-30 bg-info text-light">
            <input type="hidden" id="subtotal" value="<?php echo $totalbayar; ?>" />
            <input type="hidden" id="total" name="total" value="<?php echo $totalbayar; ?>" />
            <input type="hidden" id="biayacod" name="biaya_cod" value="0" />
            <!-- Subtotal -->
            <div class="row">
              <div class="col-md-6">
                <p>Subtotal</p>
              </div>
              <div class="col-md-6">
                <p style="text-align: right">Rp <span id="subtotalbayar"><?php echo $this->func->formUang($totalbayar); ?></p>
              </div>
            </div>
            <hr style="border-color: #fff;">
            <div class="row">
              <div class="col-md-6">
                <p>Ongkos Kirim</p>
              </div>
              <div class="col-md-6">
                <p style="text-align: right">Rp <span id="ongkirshow">0</span></p>
              </div>
            </div>
            <hr class="codon" style="display:none;border-color: #fff;">
            <div class="row codon" style="display:none;">
              <div class="col-md-6">
                <p>Biaya COD</p>
              </div>
              <div class="col-md-6">
                <p style="text-align: right">Rp <span id="byacod">0</span></p>
              </div>
            </div>
            <hr style="border-color: #fff;">
            <div class="row">
              <div class="col-md-6">
                <p>Voucher</p>
              </div>
              <div class="col-md-6">
                <p style="text-align: right">- Rp <span id="diskonshow">0</span></p>
              </div>
            </div>
            <hr style="border-color: #fff;">
            <div class="row">
              <div class="col-md-4">
                <h5>Total</h5>
              </div>
              <div class="col-md-8 font-bold">
                <h5 style="text-align: right">Rp <span id="totalbayar"><?php echo $this->func->formUang($totalbayar); ?></span></h5>
              </div>
            </div>
          </div>
          <div class="section p-lr-24 p-tb-30 m-l-0 m-l-0 p-r-15 p-l-15 m-b-40">
            <div class="font-bold text-primary text-center fs-16 m-b-20">SALDO <?=strtoupper(strtolower($set->nama))?></div>
            <div class="metode m-b-20">
              <div class="m-b-12 p-all-12 row m-lr-0 metodebayar" id="bayarsaldo" data-bayar="cod">
                <i class="fas fa-check-circle"></i>
                <b class="fs-16 font-medium text-success"><i class="fas fa-wallet"></i> &nbsp;Bayar dengan Saldo</b><br/>
                <span class="fs-12">Saldo saat ini Rp. <?=$this->func->formUang($saldo)?></span>
              </div>
              <p class="text-danger text-center fs-12" id="saldokosong" style="display:none">pembayaran menggunakan saldo tidak dapat diaktifkan karena saldo Anda saat ini Rp. 0</p>
              <div class="bg-medium p-all-8 text-center fs-12" style="border-radius:8px;display:none" id="saldokurang">saldo Anda saat ini kurang dari total tagihan pembayaran, maka sisanya silahkan lunasi dengan pilihan metode pembayaran dibawah</div>
            </div>
            <input type="hidden" id="saldoval" value="<?=$saldo;?>" />
            <input type="hidden" name="saldo" id="saldopotong" value="0" />
            <input type="hidden" name="metode" id="metode" value="1" />
            <div class="metode m-b-40" id="metodelainnya">
              <div class="font-bold text-primary text-center fs-16 m-b-20">METODE PEMBAYARAN LAINNYA</div>
              <input type="hidden" id="metode_bayar" value="0" name="metode_bayar" />
              <?php
                $set = $this->func->getSetting("semua");
                if($set->payment_cod == 1){
              ?>
              <div class="m-b-12 row m-lr-0 metodebayar methods" id="bayarcod" data-metode="1" data-bayar="cod">
                <i class="fas fa-check-circle"></i>
                <div class="col-md-6 p-lr-0">
                  <div class="bg-logo p-all-4"><img src="<?=base_url("assets/images/cod.png")?>" style="width:100%" /></div>
                </div>
                <div class="col-md-6">
                  COD
                </div>
              </div>
              <?php
                }
                if($set->payment_transfer == 1){
              ?>
              <div class="m-b-12 row m-lr-0 metodebayar methods" id="bayartransfer" data-metode="2" data-bayar="transfer">
                <i class="fas fa-check-circle"></i>
                <div class="col-md-6 p-lr-0">
                  <div class="bg-logo p-all-4"><img src="<?=base_url("assets/images/transfer.png")?>" style="width:100%" /></div>
                </div>
                <div class="col-md-6">
                  TRANSFER MANUAL
                </div>
              </div>
              <?php
                }
                if($set->payment_tripay == 1){
              ?>
              <div class="m-b-12 row m-lr-0 metodebayar methods" id="bayartripay" data-metode="3" data-bayar="tripay">
                <i class="fas fa-check-circle"></i>
                <div class="col-md-6 p-lr-0">
                  <div class="bg-logo p-all-4"><img src="<?=base_url("assets/images/tripay.png")?>" style="width:100%" /></div>
                </div>
                <div class="col-md-6">
                  <small>VA, Gopay, OVO, Alfamart dll</small>
                </div>
              </div>
              <?php
                }
                if($set->payment_midtrans == 1){
              ?>
              <div class="m-b-12 row m-lr-0 metodebayar methods" id="bayarmidtrans" data-metode="4" data-bayar="midtrans">
                <i class="fas fa-check-circle"></i>
                <div class="col-md-6 p-lr-0">
                  <div class="bg-logo p-all-4"><img src="<?=base_url("assets/images/midtrans.png")?>" style="width:100%" /></div>
                </div>
                <div class="col-md-6">
                  <small>VA, Gopay, OVO, Alfamart dll</small>
                </div>
              </div>
              <?php
                }
              ?>
            </div>
            <div class="error text-danger" id="error-bayar">
              <i>Belum dapat menyelesaikan pesanan, silahkan lengkapi alamat dan total beserta ongkos kirim terlebih dahulu.</i><p>
            </div>
            <div class="text-warning" id="proses" style="display:none;">
              <h5><i class="fas fa-compact-disk fa-spin"></i> <i>Memproses pesanan, mohon tunggu sebentar</i></h5><p>
            </div>
            <div class="pembayaran" style="display:none;">
            	<a href="javascript:void(0);" onclick="checkoutNow();" class="btn btn-lg btn-primary btn-block m-b-12">
            		<i class="fas fa-chevron-circle-right"></i> &nbsp;Lanjut Pembayaran
            	</a>
            	<a href="javascript:void(0);" onclick="checkoutWA();" class="btn btn-lg btn-success btn-block">
            		<i class="fab fa-whatsapp"></i> &nbsp;Checkout Whatsapp
            	</a>
          	</div>
          </div>
        </div>
			</div>
		</div>
    <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" class="csrf_token" value="<?=$this->security->get_csrf_hash();?>" />
	</form>
	<input type="hidden" id="csrf_name" value="<?=$this->security->get_csrf_token_name()?>" />
	<input type="hidden" id="csrf_token" class="csrf_token" value="<?=$this->security->get_csrf_hash();?>" />

  <script type="text/javascript">
	$(function(){
    $(".methods").on("click",function(){
      if($("#paket").val() > 0){
        var valmet = $("#metode_bayar").val();
        $("#metode_bayar").val($(this).data("metode"));
        $(".methods").removeClass("active");
        $("#bayar"+$(this).data("bayar")).addClass("active");
        if($(this).data("metode") == 1){
          $(".codon").show();
          var total = parseInt($("#total").val()) + parseInt($("#biayacod").val());
          //$("#total").val(total);
          $("#totalbayar").html(formUang(total.toString()));
        }else{
          if(valmet == 1){
            var total = parseInt($("#total").val());// - parseInt($("#biayacod").val());
            //$("#total").val(total);
            $("#totalbayar").html(formUang(total.toString()));
            $(".codon").hide();
          }
        }
      }else{
        swal.fire("Terjadi kesalahan!","pilih kurir pengiriman terlebih dahulu sebelum memilih metode pembayaran","error");
      }
    });

		$("#cekout").on("submit",function(e){
		  e.preventDefault();
		});

    $(".voucher-item").on("click",function(){
      var kode = $(this).data("kode");
      //confirm(kode);//
      $("#kodevoucher").val(kode);
      setTimeout(cekVoucher(),1000);
    });
		
		$("#nodrop").click(function(){
			$("#yesdrop").removeClass("btn-primary");
			$("#yesdrop").addClass("hov-primary");
			$(this).removeClass("hov-primary");
			$(this).addClass("btn-primary");
			$(".fa",this).show()
			$("#yesdrop .fa").hide();
			$("#dropform").hide();
			$("#dropform input").val("");
			$("#dropform input").prop("required",false);
		});
		$("#yesdrop").click(function(){
			$("#nodrop").removeClass("btn-primary");
			$("#nodrop").addClass("hov-primary");
			$(this).removeClass("hov-primary");
			$(this).addClass("btn-primary");
			$("#dropform").show();
			$(".fa",this).show()
			$("#nodrop .fa").hide();
			$("#dropform input").prop("required",true);
		});

		$(".ongkir").change(function(){
		  var ongkir = $(this).val();
		  //if(ongkir > 0){
			var harga = $("#totalharga").val();
			var totalharga = Number(harga) + Number(ongkir);

			//$("#ongkirtoko").html(formUang(ongkir));
			//$("#totaltoko").html(formUang(totalharga));
		  //}
		});

		$("#idalamat").change(function(){
		  var idalamat = $(this).val();
		  var tujuan = $("#alamat_"+idalamat).data('tujuan');
		  $("#tujuan").val(tujuan);

		  $(".alamat").hide();
		  if($(this).val() == ""){
        resetOngkir();
        $(".tambahalamat").hide();
        $(".tambahalamat input,.tambahalamat textarea").prop("required",false);
		  }else if($(this).val() == 0){
        resetOngkir();
        $(".tambahalamat").show();
        $(".tambahalamat input,.tambahalamat textarea").prop("required",true);
        if($("#kab").val() != ""){
          $("#tujuan").val($("#kab").val());
          hitungOngkir();
        }else{
          resetOngkir();
        }
		  }else if($(this).val() > 0){
        $("#alamat_"+idalamat).show();
        $(".tambahalamat").hide();
        $(".tambahalamat input,.tambahalamat textarea").prop("required",false);

        hitungOngkir();
		  }
		});
		
		$("#kodevoucher").change(function(){
			$("#diskon").val(0);
			$("#diskonshow").html(0);
			//$("#totalbayar").html(formUang($("#total").val()));
			hitungOngkir();
			$(".vouchergagal").hide();
			$(".vouchersukses").hide();
		});
	});
	
	//CEK COD
	function cekCOD(){
		$.post("<?=site_url("assync/cekcod")?>",{"paket":$("#paket").val(),"total":$("#total").val(),[$("#csrf_name").val()]: $("#csrf_token").val()},function(msg){
			var data = eval("("+msg+")");
      $(".csrf_token").val(data.token);
      updateToken(data.token);
      var totalmin = parseInt($("#total").val());// - data.biaya;

      if(data.result == true){
        $("#bayarcod").show();
        //$("#total").val(total);
        //$("#totalbayar").html(formUang(total.toString()));
        //$(".codon").show();
        $("#biayacod").val(data.biaya);
        $("#byacod").html(formUang(data.biaya.toString()));
      }else{
        if($("#metode_bayar").val() == 1){
          $("#metode_bayar").val(0);
          //$("#total").val(totalmin);
          $("#totalbayar").html(formUang(totalmin.toString()));
          $(".codon").hide();
          calculateOngkir();
        }
        $("#bayarcod").hide();
        $("#bayarcod").removeClass("active");
      }
    });
  }
	
	//CEK VOUCHER
	function cekVoucher(){
		if($("#kodevoucher").val() != ""){
			$.post("<?=site_url("assync/cekvoucher")?>",{"kode":$("#kodevoucher").val(),"harga":$("#totalharga").val(),[$("#csrf_name").val()]: $("#csrf_token").val(),"ongkir":$("#ongkir").val()},function(msg){
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
			swal.fire("Masukkan Kode Voucher!","masukkan kode voucher terlebih dahulu lalu klik tombol cek voucher","warning");
		}
	}

    //HITUNG ONGKIR
    function hitungOngkir(){
      resetMethods();
      cekCOD();
      if(($("#idalamat").val() != "" && $("#idalamat").val() != "0") || $("#tujuan").val() > 0){
        var tujuan = $("#tujuan").val();
        var kurir = $(".kurir").val();
        var service = $(".paket").val();
        var berat = $("#berat").val();
        var dari = $("#dari").val();
        if(kurir != "" && service != ""){
          $.post("<?php echo site_url("assync/cekongkir"); ?>",{"berat":berat,"dari":dari,"tujuan":tujuan,"kurir":kurir,"service":service,[$("#csrf_name").val()]: $("#csrf_token").val()},function(msg){
            var data = eval("("+msg+")");
            $(".csrf_token").val(data.token);
            if(data.success == true){
              $("#ongkir").val(data.harga).trigger("change");
              $("#iscod").val(data.cod);
              if(data.harga == 0 && data.cod != 1){
                errorKurir();
              }
              calculateOngkir();
            }else{
              $("#iscod").val(0);
              $("#ongkir").val(0).trigger("change");
              calculateOngkir();
              errorKurir();
            }
          });
        }
      }else{
        swal.fire("Penting!","mohon cek kembali dan pastikan alamat sudah benar","error");
      }
    }
    function calculateOngkir(){
      var sum = 0;
      var sumi = true;
      $(".ongkir").each(function(){
        sum += parseFloat($(this).val());
        if($(this).val() > 0 && sumi == true){
          sumi = true;
        }else{
          if($("#iscod").val() == 1){
            sumi == true;
          }else{
            sumi = false;
          }
        }
      });
      var totalbayar = sum + parseFloat($("#subtotal").val()) - parseFloat($("#diskon").val());
      if($("#metode_bayar").val() == 1){
        totalbayar = totalbayar + parseFloat($("#biayacod").val());
      }

      if(sumi == false){
        $(".pembayaran").hide();
        $("#error-bayar").show();
      }else{
        
        $(".pembayaran").show();
        $("#error-bayar").hide();
      }

      //$("#total").val(totalbayar);
      $("#totalbayar").html(formUang(totalbayar.toString()));
      //alert($("#totalbayar").html());
      $("#ongkirshow").html(formUang(sum.toString()));
      //$('html, body').animate({ scrollTop: $("#totalbayar").offset().top - 400 });

      //RESET PEMBAYARAN
      $("#transfer").html("Rp "+formUang(totalbayar));
      //$("#saldo").html("Rp 0");
      $("#saldopotong").val(0);
      $("#metode").val(1);
      $("#bayarsaldo").removeClass("active");
      resetMethods();
      //$("#metode option").prop("selected",false);
      //$("#metode option[value=1]").prop("selected",true).trigger("change");
    }
    function resetOngkir(){
	    var total = parseFloat($("#subtotal").val()) - parseFloat($("#diskon").val());
      $("#total").val($("#subtotal").val());
      $("#totalbayar").html(formUang(total));
      //$("#saldo").html("Rp 0");
      $(".ongkir").val(0);
      $("#ongkirshow").html(0);
      //$("#tujuan").val(0);

      //RESET PEMBAYARAN
      $(".pembayaran").hide();
      $("#error-bayar").show();
      $("#transfer").html("Rp "+formUang($("#subtotal").val()));
      $("#saldopotong").val(0);
      $("#metode").val(1);
      //$("#saldo").html("Rp 0");
      $("#bayarsaldo").removeClass("active");
      //$("#metode option").prop("selected",false);
      //$("#metode option[value=1]").prop("selected",true).trigger("change");
      resetMethods();
    }
    function errorKurir(){
      $("#error-kurir").show();
      //$('html, body').animate({ scrollTop: $("#error-kurir-"+idtoko).offset().top - 260 });
    }
    function resetMethods(){
      $("#metode_bayar").val(0);
      var total = parseInt($("#total").val()) + parseFloat($("#ongkir").val()) - parseFloat($("#diskon").val());
      $("#totalbayar").html(formUang(total.toString()));
      $(".codon").hide();
      $(".methods").removeClass("active");
      $("#metodelainnya").show();
    }

    //METODE Bayar
    $("#bayarsaldo").click(function(){
      resetMethods();

      if($(this).hasClass("active") == false){
        var saldo = parseFloat($("#saldoval").val());
        //var total = parseFloat($("#total").val());
        var total = parseInt($("#total").val()) + parseFloat($("#ongkir").val()) - parseFloat($("#diskon").val());

        if(saldo > 0){
          $("#metode").val(2);
          $("#saldokosong").hide();
          $(this).addClass("active");
          if(saldo >= total){
            $(this).addClass("active");
            $("#saldopotong").val(total);
            $("#metodelainnya").hide();
          }else{
            //var selisih = total - saldo;
            $("#metodelainnya").show();
            $("#saldokurang").show();
            $("#saldopotong").val(saldo);
            //$("#saldo").html("Rp "+formUang(saldo));
            //$("#transfer").html("Rp "+formUang(selisih));
          }
        }else{
          $(this).removeClass("active");
          $("#metode").val(1);
          $("#saldopotong").val(0);
          $("#saldokurang").hide();
          $("#saldokosong").show();
          //$("#saldo").html("Rp 0");
          //$("#transfer").html("Rp "+formUang(total));
        }
      }else{
        $("#metode").val(1);
        $("#metodelainnya").show();
        $("#saldopotong").val(0);
        $(this).removeClass("active");
        $("#saldokurang").hide();
        $("#saldokosong").hide();
      }
    });

    //KURIR PENGIRIMAN
    $(".kurir").change(function(){
      $("#error-kurir").hide();
      if($(this).val() != ""){
        var data = $("#service"+$(this).val()).html();
        $(".paket").html(data)
        $(".ongkir").val("0");
        $("#ongkirshow").html("0");
        $(".alamatform").show();

        hitungOngkir();
      }else{
        resetOngkir();
        $(".alamatform").hide();
      }
    });
    $(".paket").change(function(){
      $("#error-kurir").hide();
      hitungOngkir();
    });

    //LOAD KABUPATEN KOTA & KECAMATAN
    $("#prov").change(function(){
      $("#kab").html("<option value=''>Loading...</option>");
      $("#kec").html("<option value=''>Kecamatan</option>");
      resetOngkir();
			$.post("<?php echo site_url("assync/getkab"); ?>",{"id":$(this).val(),[$("#csrf_name").val()]: $("#csrf_token").val()},function(msg){
				var data = eval("("+msg+")");
        $(".csrf_token").val(data.token);
				$("#kab").html(data.html);
			});
		});
		$("#kab").change(function(){
      resetOngkir();
      $("#kec").html("<option value=''>Loading...</option>");
			$.post("<?php echo site_url("assync/getkec"); ?>",{"id":$(this).val(),[$("#csrf_name").val()]: $("#csrf_token").val()},function(msg){
				var data = eval("("+msg+")");
        $(".csrf_token").val(data.token);
				$("#kec").html(data.html);
			});
		});
    $("#kec").change(function(){
      var data = $(this).find(":selected").val();
      $("#tujuan").val(data);
			hitungOngkir();
    });

    function checkoutNow(){
      $(".pembayaran").hide();
      $("#proses").show();
      var saldo = parseFloat($("#saldoval").val());
      var total = parseInt($("#total").val()) + parseFloat($("#ongkir").val()) - parseFloat($("#diskon").val());
      var oke = false;

      if($("#metode").val() == 1){
        if($("#metode_bayar").val() != 0){
          oke = true;
        }
      }else if($("#metode").val() == 2){
        if(saldo >= total){
          //confirm("brazilll");
          oke = true;
        }else{
          //confirm("error");
          if($("#metode_bayar").val() != 0){
            oke = true;
          }
        }
      }else{
        if($("#metode_bayar").val() != 0){
          oke = true;
        }
      }

      if(oke == true){
        $(".idproduks").each(function(){
          var id = $(this).val();
          var nama = $("#namaproduks_"+id).val();
          var kategori = $("#kategoriproduks_"+id).val();
          var total = $("#totalproduks_"+id).val();
          fbq('track', 'Purchase', {content_ids:id,content_type:kategori,content_name:nama,currency: "IDR", value: total});
        });

        $.post("<?php echo site_url("assync/bayarpesanan"); ?>",$("#cekout").serialize(),function(msg){
          var data = eval("("+msg+")");
          $(".csrf_token").val(data.token);
          if(data.success == true){
            window.location.href = data.url;
          }else{
            $(".pembayaran").show();
            $("#proses").hide();
          }
        });
      }else{
        swal.fire("Pilih metode pembayaran","pilih metode pembayaran terlebih dahulu","warning").then(vale => {
          $(".pembayaran").show();
          $("#proses").hide();
        });
      }
    }
    function checkoutWA(){
      $(".pembayaran").hide();
      $("#proses").show();
      var saldo = parseFloat($("#saldoval").val());
      var total = parseInt($("#total").val()) + parseFloat($("#ongkir").val()) - parseFloat($("#diskon").val());
      var oke = false;

      if($("#metode").val() == 1){
        if($("#metode_bayar").val() != 0){
          oke = true;
        }
      }else if($("#metode").val() == 2){
        if(saldo >= total){
          //confirm("brazilll");
          oke = true;
        }else{
          //confirm("error");
          if($("#metode_bayar").val() != 0){
            oke = true;
          }
        }
      }else{
        if($("#metode_bayar").val() != 0){
          oke = true;
        }
      }

      if(oke == true){
        $(".idproduks").each(function(){
          var id = $(this).val();
          var nama = $("#namaproduks_"+id).val();
          var kategori = $("#kategoriproduks_"+id).val();
          var total = $("#totalproduks_"+id).val();
          fbq('track', 'Purchase', {content_ids:id,content_type:kategori,content_name:nama,currency: "IDR", value: total});
        });

        $.post("<?php echo site_url("assync/bayarpesanan/wasap"); ?>",$("#cekout").serialize(),function(msg){
          var data = eval("("+msg+")");
          $(".csrf_token").val(data.token);
          if(data.success == true){
            window.location.href = "https://wa.me/<?=$this->func->getRandomWasap()?>/?text="+data.text;
          }else{
            $(".pembayaran").show();
            $("#proses").hide();
          }
        });
      }else{
        swal.fire("Pilih metode pembayaran","pilih metode pembayaran terlebih dahulu","warning").then(vale => {
          $(".pembayaran").show();
          $("#proses").hide();
        });
      }
    }
  </script>

  <!-- ADDITIONAL DATA FOR RAJAONGKIR -->
  <?php
    $kr = $this->db->get("kurir");
    foreach($kr->result() as $kur){
  ?>
  <select id="service<?php echo $kur->id; ?>" style="display:none;">
  	<option value="">Pilih Paket</option>
    <?php
      $this->db->where("idkurir",$kur->id);
      $pak = $this->db->get("paket");
      $no = 1;
      foreach($pak->result() as $pk){
        $select = ($no == 1) ? "selected" : "";
  	    echo '<option value="'.$pk->id.'" '.$select.'>'.$pk->nama.'</option>';
        $no++;
      }
    ?>
  </select>
  <?php
    }
  ?>
