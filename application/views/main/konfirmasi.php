<div class="container">
    <div class="m-lr-auto m-b-40 m-t-80">
        <h2 class="font-black text-primary text-center">
            Konfirmasi Pembayaran Pesanan
        </h2>
    </div>
    <div class="section m-lr-auto p-all-24 m-b-80 col-md-8">
        <div class="p-tb-24">
            <form method="POST" enctype="multipart/form-data" action="<?php echo site_url("konfirmasi/kirim"); ?>">
				<input type="hidden" class="tokens" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="form-group">
                    <label>Masukkan Nomor Invoice / Pesanan</label>
                    <input class="form-control" name="invoice" required />
                </div>
                <div class="form-group">
                    <label>Bukti Transfer</label>
                    <input type="file" name="bukti" class="form-control" accept="image/*,application/pdf" required />
                </div>
                <div class="form-group m-b-0">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> &nbsp;Kirim Bukti Pembayaran</button>
                </div>
            </form>
        </div>
        <?php if(isset($_GET["result"]) AND $_GET["result"] == "sukses"){ ?>
        <div class="alert alert-success text-center">
            Terima Kasih, data konfirmasi pembayaran sudah dikirim ke Admin mohon menunggu sampai pembayaran Anda disetujui oleh Admin. Apabila dalam jangka waktu 1x24jam tidak ada perubahan, 
            silahkan konfirmasi kepada Admin melalui tombol bantuan di pojok kanan bawah.
        </div>
        <?php }elseif(isset($_GET["result"]) AND $_GET["result"] == "gagal"){ ?>
        <div class="alert alert-danger text-center">
            Mohon maaf, konfirmasi pembayaran gagal dikirim. Silahkan cek kembali data yang Anda masukkan apakah sudah sesuai atau ada yang perlu diperbaiki. Apabila mengalami kendala, silahkan 
            menghubungi Admin melalui tombol bantuan di pojok kanan bawah.
            <?php if(isset($_GET["msg"])){ ?>
            <div class="p-t-12">
                <b>Pesan Error: </b><br/>
                <?=$_GET["msg"]?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>