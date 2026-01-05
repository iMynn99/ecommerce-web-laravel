<div class="container">
    <div class="m-r-auto m-l-auto m-b-40 m-t-60">
        <h2 class="font-black text-primary text-center">
            Cek Status Pesanan
        </h2>
    </div>
    <div class="section col-md-8 text-center m-lr-auto p-all-24 m-b-80">
        <div class="p-tb-24">
            <form id="cekpesanan">
                <div class="form-group">
                    <label>Masukkan Nomor Invoice/Pesanan</label>
                    <input class="form-control text-center" id="orderid" name="orderid" required />
                </div>
                <div class="form-group m-b-0">
                    <button id="ceksubmit" type="submit" class="btn btn-success"><i class="fas fa-check"></i> &nbsp;Cek Pesanan</button>
                </div>
            </form>
        </div>
        <div class="alert alert-danger text-center" style="display:none;">
            Mohon maaf, nomor yang Anda masukkan tidak kami temukan dalam data pesanan Kami, atau pesanan tersebut sudah dipindahkan ke data pengguna 
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $("#orderid").focus(function(){
            $(".alert").hide();
        });
        
        $("#cekpesanan").on("submit",function(e){
            e.preventDefault();
            var hate = $("#ceksubmit").html();
            $("#ceksubmit").html('<i class="fas fa-compact-disc fa-spin"></i> &nbsp;Tunggu sebentar...');
            $.post("<?=site_url("trackpesanan/cek")?>",{"orderid":$("#orderid").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
                var data = eval("("+msg+")");
                updateToken(data.token);
                $("#ceksubmit").html(hate);

                if(data.success == true){
                    window.location.href="<?=site_url("manage/detailpesanan/")?>?orderid="+data.trxid;
                }else{
                    $(".alert").show();
                }
            });
        })
    });
</script>