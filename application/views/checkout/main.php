<?php
    $sukses = false;
    $tipeco = (isset($_SESSION["usrid"])) ? 0 : 1;
    $berat = 0;
    $total = 0;
    $jenis = null;
    $gudang = null;
    if(isset($_POST["idproduk"]) AND is_array($_POST["idproduk"]) AND count($_POST["idproduk"]) > 0){
        //print_r($_POST["idproduk"]);exit;
        for($i=0; $i<count($_POST["idproduk"]); $i++){
            $pro = $this->func->getTransaksiProduk($_POST["idproduk"][$i],"semua");
            if($pro->id > 0 AND $pro->idtransaksi == 0){
                $prod = $this->func->getProduk($pro->idproduk,"semua");
                if($prod->id > 0){
                    $jenis = ($jenis === null) ? $prod->digital : $jenis;
                    if($jenis == $prod->digital){
                        $gudang = ($gudang === null) ? $prod->gudang : $gudang;
                        if($gudang == $prod->gudang){
                            $sukses = $sukses == false ? true : $sukses;
                            $berat += $prod->berat * $pro->jumlah;
                            $total += $pro->harga * $pro->jumlah;
                        }else{
                            unset($_POST["idproduk"][$i]);
                        }
                    }else{
                        unset($_POST["idproduk"][$i]);
                    }
                }else{
                    unset($_POST["idproduk"][$i]);
                }
            }else{
                unset($_POST["idproduk"][$i]);
            }
        }

        if(isset($_POST["idproduk"]) AND is_array($_POST["idproduk"]) AND count($_POST["idproduk"]) > 0){
            if(isset($_SESSION["prebayar"])){
                if($tipeco == 0){
                    $this->db->where("usrid",$_SESSION["usrid"]);
                }else{
                    $this->db->where("usrid_temp",$_SESSION["usrid_temp"]);
                }
                $this->db->where("tipeco",$tipeco);
                $this->db->where("status",0);
                $this->db->update("pembayaran_pre",["status"=>2]);
                $this->session->unset_userdata("prebayar");
            }
            $set = $this->func->globalset("semua");
            $dari = ($gudang == 0) ? $set->kota : $this->func->getGudang($gudang,"idkab");
            $data = array(
                "tipeco"=> $tipeco,
                "tgl"   => date("Y-m-d H:i:s"),
                "dari"  => $dari,
                "gudang"=> $gudang,
                "total" => $total,
                "digital" => $jenis,
                "berat" => $berat,
                "produk"=> implode("|",$_POST["idproduk"])
            );
            if($tipeco == 0){
                $data["usrid"] = $_SESSION["usrid"];
            }else{
                $data["usrid_temp"] = $_SESSION["usrid_temp"];
            }
            $this->db->insert("pembayaran_pre",$data);
            $id = $this->db->insert_id();
            $this->session->set_userdata("prebayar",$id);
?>
    <div class="progress-wrap col-md-10 m-lr-auto p-lr-0 p-t-40" style="overflow:hidden;">
        <div class="row progress-checkout">
            <div class="line"></div>
            <div class="col-4 alamats">
                <div class="wrap active">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="titles">Alamat</div>
                </div>
            </div>
            <div class="col-4 kurir">
                <div class="wrap">
                    <i class="fas fa-shipping-fast"></i>
                    <div class="titles">Kurir</div>
                </div>
            </div>
            <div class="col-4 bayar">
                <div class="wrap">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <div class="titles hidesmall">Pembayaran</div>
                    <div class="titles showsmall">Bayar</div>
                </div>
            </div>
        </div>
        <div class="p-all-24 m-t-20 m-b-60">
            <div class="load">
                <div class="p-tb-30 text-center">
                    <i class="fas fa-compact-disc fa-spin text-primary"></i> tunggu sebentar...
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
		$(function(){
            <?php if($jenis == 0){ ?>
            $(".load").load("<?=site_url("checkout/alamat")?>");
            <?php }else{ ?>
            $(".progress-checkout").hide();
            loadBayar();
            <?php } ?>
        });

        function loadKurir(){
            $(".progress-checkout .wrap").removeClass("active");
            $(".progress-checkout .kurir .wrap").addClass("active");
            $(".load").html('<div class="p-tb-30 text-center"><i class="fas fa-compact-disc fa-spin text-primary fs-32 m-b-12"></i><br/>memuat pilihan kurir yang dapat mengirim pesanan ke alamat Anda</div>');
            $(".load").load("<?=site_url("checkout/kurir")?>");
        }
        function loadBayar(){
            $(".progress-checkout .wrap").removeClass("active");
            $(".progress-checkout .bayar .wrap").addClass("active");
            $(".load").html('<div class="p-tb-30 text-center"><i class="fas fa-compact-disc fa-spin text-primary"></i> tunggu sebentar...</div>');
            $(".load").load("<?=site_url("checkout/bayar")?>");
        }
    </script>
<?php
        }else{
            $sukses = false;
        }
    }

    if($sukses!=true){
?>
    <script type="text/javascript">
		$(function(){
            swal.fire("Pilih Produk","Sebelum checkout, silahkan pilih produk yang akan Anda bayar terlebih dahulu","error").then(()=>{
                history.back();
            });
        });
    </script>
<?php
    }
?>