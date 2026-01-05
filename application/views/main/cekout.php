<?php
$set = $this->func->getSetting("semua");
$nama = (isset($_SESSION["usrid"])) ? $this->func->getProfil($usrid->id, "nama", "usrid") : $usrid->nama;
?>

<div class="container p-t-20 p-b-50">
    <h3>Terima Kasih, <?=$nama?></h3>
    <p>Order ID: <?=$data->invoice?></p>

    <?php if ($data->metode_bayar == 4): ?>
    <div class="section p-t-20 p-b-20 p-lr-20 p-lf-20">
        <h4>Total Bayar:</h4>
        <p class="text-danger font-bold">Rp <?=$this->func->formUang($data->transfer + $data->kodebayar)?></p>
        <button onclick="payMidtrans()" class="btn btn-success">Bayar Sekarang via Midtrans</button>
    </div>
    <?php endif; ?>
</div>

<div id="tokenGenerated" style="display:none;"></div>

<!-- Midtrans Snap Script -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-g__IVb0MxlRcHmw1"></script>

<script>
function payMidtrans() {
    $.ajax({
        type: "POST",
        url: "<?=site_url('midtrans/token')?>",
        data: {
            invoice: "<?=$data->invoice?>",
            [$("#names").val()]: $("#tokens").val()
        },
        success: function(response) {
            var data = JSON.parse(response);
            updateToken(data.token);
            $("#tokenGenerated").html(data.midtranstoken);
            payMidtransNow();
        },
        error: function(xhr) {
            alert("Gagal mendapatkan token Midtrans.");
        }
    });
}

function payMidtransNow() {
    snap.pay($("#tokenGenerated").html(), {
        onSuccess: function(result) {
            kirimKeServer(result, 'success');
        },
        onPending: function(result) {
            kirimKeServer(result, 'pending');
        },
        onError: function(result) {
            alert("Pembayaran gagal: " + result.status_message);
        },
        onClose: function() {
            alert("Pembayaran dibatalkan.");
        }
    });
}

function kirimKeServer(result, status) {
    var url = "<?=site_url('midtrans/pay')?>?order_id=<?=$data->invoice?>&status=" + status + "&transaction_id=" +
        result.transaction_id;
    var form = document.createElement("form");
    form.method = "post";
    form.action = url;

    var responseField = document.createElement("input");
    responseField.name = "response";
    responseField.value = JSON.stringify(result);
    form.appendChild(responseField);

    var tokenField = document.createElement("input");
    tokenField.name = $("#names").val();
    tokenField.value = $("#tokens").val();
    form.appendChild(tokenField);

    document.body.appendChild(form);
    form.submit();
}
</script>