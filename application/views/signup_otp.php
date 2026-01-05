<?php
    $set = $this->func->globalset("semua");
?>
<!-- Login -->
<div style="margin-top:10vh">
    <div class="container p-b-20">
        <div class="m-b-30 text-center">
            <img src="<?=base_url('cdn/assets/img/'.$set->logo)?>" style="max-height:60px;max-width:60%;" />
        </div>

        <div class="row p-lr-20">
            <div class="col-md-6 m-l-auto m-r-auto p-t-30 p-b-40 section">
            <div class="font-bold text-primary text-center m-b-20 fs-24">masukkan kode OTP</div>
                <div class="p-l-20 p-r-20 m-lr-0-xl p-lr-15-sm" id="load">
                    <form id="signin_otp" class="p-lr-30">
                        <div class="m-b-20 text-center">
                            masukkan kode OTP yang telah dikirim ke alamat email dan nomor whatsapp anda
                        </div>
                        <div class="m-b-30 text-center">
                            <a href="javascript:void(0)" style="display:none;" id="kirimulang" onclick="kirimUlang()" class="text-success font-medium"><i class="fas fa-sync"></i> Kirim Ulang</a>
                            <div id="detikan" class="text-success font-medium">Kirim Ulang</div>
                        </div>
                        <div class="m-b-60 row">
                            <div class="col-2 p-lr-4">
                                <input class="form-control p-tb-24 p-lr-10 fs-20 font-bold text-center" type="number" data-split="1" id="otp_1" placeholder="" required >
                            </div>
                            <div class="col-2 p-lr-4">
                                <input class="form-control p-tb-24 p-lr-10 fs-20 font-bold text-center" type="number" data-split="2" id="otp_2" placeholder="" required >
                            </div>
                            <div class="col-2 p-lr-4">
                                <input class="form-control p-tb-24 p-lr-10 fs-20 font-bold text-center" type="number" data-split="3" id="otp_3" placeholder="" required >
                            </div>
                            <div class="col-2 p-lr-4">
                                <input class="form-control p-tb-24 p-lr-10 fs-20 font-bold text-center" type="number" data-split="4" id="otp_4" placeholder="" required >
                            </div>
                            <div class="col-2 p-lr-4">
                                <input class="form-control p-tb-24 p-lr-10 fs-20 font-bold text-center" type="number" data-split="5" id="otp_5" placeholder="" required >
                            </div>
                            <div class="col-2 p-lr-4">
                                <input class="form-control p-tb-24 p-lr-10 fs-20 font-bold text-center" type="number" data-split="6" id="otp_6" placeholder="" required >
                            </div>
                        </div>
                        <div class="m-t-20">
                            <button type="button" id="submit" onclick="submitChallenge()" class="btn btn-primary btn-block btn-lg">LANJUTKAN <i class="fas fa-chevron-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
  	$(function(){
  		$("#signin_otp").on("submit",function(e){
  			e.preventDefault();
        });

        $(".form-control").on("keydown",function(){
            var key = event.keyCode || event.charCode;
            if( key != 8 && key != 46 ){
                if ($(this).val()) {
                    var splits = parseFloat($(this).data("split")) + 1;
                    $("#otp_"+splits).focus();
                    //alert(splits);
                }
            }else{
                if ($(this).val() == "") {
                    var splits = parseFloat($(this).data("split")) - 1;
                    $("#otp_"+splits).focus();
                    //alert(splits);
                }
            }
        });

        $("#otp_6").on("keyup",function(){
            submitChallenge();
        });
        
        startTimer(60);
  	});

    function submitChallenge(){
        var otp = $("#otp_1").val() + $("#otp_2").val() + $("#otp_3").val() + $("#otp_4").val() + $("#otp_5").val() + $("#otp_6").val();
  		var submit = $("#submit").html();
  		$(".form").prop("readonly",true);
		$("#submit").html("<i class='fas fa-spin fa-compact-disc text-info'></i> tunggu sebentar...");
		$.post("<?php echo site_url("home/signup_otp/confirm"); ?>",{"otp":otp,[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval('('+msg+')');
            updateToken(data.token);
			if(data.success == true){
				window.location.href="<?=site_url("manage")?>";
			}else{
				$("#submit").html(submit);
				swal.fire("Warning!","kode otp salah, silahkan cek kembali","error");
			}
		});
    }

    function startTimer(duration) {
        var timer = duration, minutes, seconds;
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10) + 1;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        $("#detikan").html(minutes + ":" + seconds);

        var timers = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $("#detikan").html(minutes + ":" + seconds);

            if (--timer < 0) {
                //timer = duration;
                //$("#detikan").html("Cleared");
                clearInterval(timers);
                $("#kirimulang").show();
                $("#detikan").hide();
            }
        }, 1000);
        $("#kirimulang").hide();
        $("#detikan").show();
    }
    function kirimUlang(){
		$("#detikan").html("<i><i class='fas fa-spin fa-compact-disc text-info'></i> mengirim ulang otp...</i>");
        $("#kirimulang").hide();
        $("#detikan").show();
		$.post("<?php echo site_url("home/signup_otp/resend"); ?>",{"otp":true,[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval('('+msg+')');
            updateToken(data.token);
			if(data.success == true){
                startTimer(90);
			}else{
                startTimer(120);
				swal.fire("Gagal!","terjadi kesalahan saat mengirim otp, cobalah beberapa saat lagi","error");
			}
		});
    }

    // $(document).ready(function() {
    //     $("#submit").click()
    // });
</script>
