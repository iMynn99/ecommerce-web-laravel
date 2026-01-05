<div class="container p-t-40 p-b-60">
    <div class="section p-all-24 m-b-36">
        <div class="row">
            <div class="col-md-9">
                <div class="font-medium m-b-12">Link Afiliasimu</div>
                <div class="bg-medium p-tb-8 p-lr-12"><a href="<?=site_url()."?aff=".$_SESSION["usrid"]?>" class="text-dark fs-18 font-medium"><?=site_url()."?aff=".$_SESSION["usrid"]?></a></div>
            </div>
            <div class="col-md-3">
                <div class="font-medium m-b-12">Bagikan</div>
                <div class="">
                    <a href="whatsapp://send?text=<?=site_url()."?aff=".$_SESSION["usrid"]?>" class="btn btn-success m-r-4 showsmall-inline" target="_blank" data-action="share/whatsapp/share">
                        <i class="fab fa-whatsapp fs-20 m-t-4"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?=site_url()."?aff=".$_SESSION["usrid"]?>" class="btn btn-success m-r-4 hidesmall" target="_blank" data-action="share/whatsapp/share">
                        <i class="fab fa-whatsapp fs-20 m-t-4"></i>
                    </a>
                    <a href="http://www.facebook.com/sharer.php?u=<?=site_url()."?aff=".$_SESSION["usrid"]?>" class="btn btn-fb m-r-4" target="_blank">
                        <i class="fab fa-facebook-f fs-20 m-lr-4 m-t-4"></i>
                    </a>
                    <a href="https://twitter.com/share?url=<?=site_url()."?aff=".$_SESSION["usrid"]?>" class="btn btn-tw m-r-4" target="_blank">
                        <i class="fab fa-twitter fs-20 m-t-4"></i>
                    </a>
                    <a href="mailto:?Subject=Yuk%20belanja%20disini&amp;Body=<?=site_url()."?aff=".$_SESSION["usrid"]?>" class="btn btn-warning m-r-4" target="_blank">
                        <i class="fas fa-envelope fs-20 m-t-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="m-b-24">
        <h4 class="font-bold text-primary">Komisi Afiliasi Produk</h4>
    </div>
    <div class="section p-all-20">
        <div class="table-responsive p-all-4">
            <table class="table table-bordered" id="tabel" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Transaksi</th>
                        <th>Tgl Transaksi</th>
                        <th>Pembeli</th>
                        <th>Jumlah Komisi</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        //$("#modal").modal();

        var token = "<?php echo $this->security->get_csrf_hash();?>";
        var table = $('#tabel').DataTable({
            "pageLength": 25,
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "ajax": {
                "url": "<?=site_url("afiliasi/load")?>",
                "type": "POST",
                data: function ( d ) {
                    d.<?php echo $this->security->get_csrf_token_name();?> = $("#tokens").val();
                }
            },
            "columnDefs": [
                {"targets": [0,1,2,3],"orderable": false}
            ],
        });
        table.on('xhr.dt', function ( e, settings, json, xhr ) {
            token = json.<?=$this->security->get_csrf_token_name();?>;
            updateToken(token);
        });

        $("#klik").click(function(){
            table.ajax.reload();
        });
    });
</script>