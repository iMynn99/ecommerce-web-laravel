<div class="m-b-60">
    <div class="card">
        <div class="card-header row">
            <div class="col-md-8">
                <h4 class="page-title" style="margin-bottom:0;">User Manager</h4>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" onchange="cariData()" placeholder="cari pengguna"
                        id="cari" />
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-info w-full" onclick="cariData()"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body" id="load">
            <i class="fas fa-spin fa-spinner"></i> Loading data...
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    loadUser(1);

    $(".tabs-item").on('click', function() {
        $(".tabs-item.active").removeClass("active");
        $(this).addClass("active");
    });
});

function loadUser(page) {
    $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
    $.post("<?=site_url("ngadimin/agen?load=normal&page=")?>" + page, {
        "cari": $("#cari").val(),
        [$("#names").val()]: $("#tokens").val()
    }, function(msg) {
        var data = eval("(" + msg + ")");
        updateToken(data.token);
        $("#load").html(data.result);
    });
}

function cariData() {
    loadUser(1);
}

function hapusUserdata(id) {
    swal.fire({
        text: "user ini akan dihapus secara permanen, termasuk semua riwayat transaksi penjualannya",
        title: "Menghapus user?",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Batal"
    }).then((vals) => {
        if (vals.value) {
            $.post("<?=site_url("api/hapusagen")?>", {
                "id": id,
                [$("#names").val()]: $("#tokens").val()
            }, function(msg) {
                var data = eval("(" + msg + ")");
                updateToken(data.token);
                if (data.success == true) {
                    loadUser(1);
                    $("#modal").modal("hide");
                    swal.fire("Berhasil", "user telah dihapus", "success");
                } else {
                    swal.fire("Gagal!", "gagal menghapus user, coba ulangi beberapa saat lagi",
                    "error");
                }
            });
        }
    });
}
</script>