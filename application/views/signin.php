<!-- Login -->
<div style="margin-top:10vh">
    <div class="container p-b-20">
        <div class="m-b-30 text-center">
            <img src="<?= base_url('cdn/assets/img/'.$set->logo) ?>" style="max-height:60px;max-width:60%;" />
        </div>

        <div class="row p-lr-20">
            <div class="section col-md-6 m-l-auto m-r-auto p-t-30">
                <div class="font-bold text-primary text-center m-b-20 fs-24">Masuk ke Akun Anda</div>
                <div class="p-lr-20 m-lr-0-xl p-lr-15-sm" id="load">
                    <?php 
						if($set->login_otp == 0){
					?>
                    <form id="signin" class="p-b-40 p-lr-30">
                        <div class="m-b-12">
                            <input class="form-control" type="text" name="email" placeholder="Email" required>
                        </div>
                        <div class="m-t-15 m-b-12">
                            <input class="form-control" type="password" name="pass" placeholder="Password" required>
                        </div>
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <button type="submit" id="submit"
                                    class="btn btn-primary btn-block btn-lg">MASUK</button>
                                <p class="text-center m-t-20 m-b-10">
                                    Belum punya akun?&nbsp;
                                    <a href="<?php echo site_url("home/signup"); ?>" class="font-medium">Mendaftar</a>
                                </p>
                            </div>
                        </div>
                    </form>
                    <?php
						}else{
					?>
                    <form id="signin_otp" class="p-b-40 p-lr-30">
                        <div class="m-b-12 t-center">
                            Masukkan nomor whatsapp atau alamat email anda untuk mengirimkan kode otp
                        </div>
                        <div class="m-b-18">
                            <input class="form-control p-tb-28 p-lr-24 fs-20 font-medium text-center" type="text"
                                name="email" placeholder="No Handphone / Email" required>
                        </div>
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <button type="submit" id="submit"
                                    class="btn btn-primary btn-block btn-lg">MASUK</button>
                                <p class="text-center m-t-20 m-b-10">
                                    Belum punya akun?&nbsp;
                                    <a href="<?php echo site_url("home/signup"); ?>" class="font-medium">Mendaftar</a>
                                </p>
                            </div>
                        </div>
                    </form>
                    <?php
						}
					?>
                    <div class="line-text p-t-30 p-b-12">
                        <div class="text"><span>metode lainnya</span></div>
                    </div>
                    <div class="text-center m-b-30">
                        <a href="<?=$google_url?>" class="btn btn-default btn-lg">
                            <img src="<?=base_url("assets/images/google.png")?>" style="height:26px;" class="p-r-12" />
                            <small><b>Login with Google</b></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
$(function() {
    $("#signin").on("submit", function(e) {
        e.preventDefault();

        var submit = $("#submit").html();
        $(".form").prop("readonly", true);
        $("#submit").html("<i class='fas fa-spin fa-compact-disc'></i> Tunggu sebentar...");
        var datar = $(this).serialize();
        datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
        $.post("<?php echo site_url("home/signin"); ?>", datar, function(msg) {
            var data = eval('(' + msg + ')');
            updateToken(data.token);
            if (data.success == true) {
                window.location.href = data.redirect;
            } else {
                $("#submit").html(submit);
                swal.fire("Warning!", "alamat email atau password salah, silahkan cek kembali",
                    "error");
            }
        });
    });

    $("#signin_otp").on("submit", function(e) {
        e.preventDefault();

        var submit = $("#submit").html();
        $(".form").prop("readonly", true);
        $("#submit").html("<i class='fas fa-spin fa-compact-disc'></i> tunggu sebentar...");
        var datar = $(this).serialize();
        datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
        $.post("<?php echo site_url("home/signin_otp"); ?>", datar, function(msg) {
            var data = eval('(' + msg + ')');
            updateToken(data.token);
            if (data.success == true) {
                window.location.href = "<?=site_url("home/signin_otp/challenge")?>";
            } else {
                $("#submit").html(submit);
                swal.fire("Warning!",
                    "alamat email atau no handphone tidak ditemukan, silahkan cek kembali",
                    "error");
            }
        });
    });

    $("#reset").click(function() {
        $("#load").html(
            "<div class='t-center m-tb-40'><i class='fas fa-spin fa-compact-disc text-info'></i> Mohon tunggu sebentar...</div>"
            );
        $("#load").load("<?php echo site_url("home/signin/pwreset"); ?>");
    });
});
</script>