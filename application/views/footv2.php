<?php 
		$set = $this->func->getSetting("semua");
		$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
	?>
<!-- Footer -->
<div class="bg-foot-gradient p-t-40"></div>
<footer class="bg-foot p-t-45 p-b-43">
    <div class="container">
        <div class="row p-b-90">
            <!-- HUBUNGI KAMI -->
            <div class="col-md-4 m-b-30">
                <h4 class="font-medium foot-title p-b-30">HUBUNGI KAMI</h4>

                <div class="m-b-20">
                    <p><?=$set->jamkerja?></p>
                    <table>
                        <tr>
                            <td class='p-r-10'><i class="fas fa-phone color1"></i></td>
                            <td><?=$set->wasap?></td>
                        </tr>
                        <tr>
                            <td class='p-r-10'><i class="fab fa-instagram"></i></td>
                            <td>@<?=$set->instagram?></td>
                        </tr>
                        <tr>
                            <td class='p-r-10'><i class="fas fa-envelope-open"></i></td>
                            <td><?=$set->email?></td>
                        </tr>
                        <tr>
                            <td class='p-r-10'><i class="fas fa-map-marker-alt"></i></td>
                            <td><?=$set->alamat?></td>
                        </tr>
                    </table>
                </div>

                <div class="m-b-20">
                    <a target="_blank" onclick="fbq('track','Contact')"
                        href="https://wa.me/<?=$this->func->getRandomWasap()?>/?text=Halo,%20mohon%20infonya%20untuk%20pembelian%20custom%20*<?=$this->func->getSetting("nama")?>*%20caranya%20bagaimana%20ya?%20dan%20syaratnya%20apa%20saja,%20terima%20kasih"
                        class="btn btn-success btn-block">
                        <i class="fab fa-whatsapp"></i> Hubungi Admin
                    </a>
                    <p class="m-t-10">Dapatkan Potongan Harga Khusus Untuk Pembelian Banyak</p>
                </div>

                <div class="flex-m p-t-10">
                    <a onclick="fbq('track','Contact')" href="https://fb.me/<?=$set->facebook?>" style="color: #2980b9;"
                        class="fs-32 color1 p-r-20 fab fa-facebook-square"></a>
                    <a onclick="fbq('track','Contact')" href="https://instagram.com/<?=$set->instagram?>"
                        style="color: #dd2a7b;" class="fs-32 color1 p-r-20 fab fa-instagram"></a>
                    <a onclick="fbq('track','Contact')" href="mailto:<?=$set->email?>"
                        class="fs-32 color1 p-r-20 fas fa-envelope"></a>
                </div>
            </div>

            <!-- KATEGORI -->
            <div class="col-md-4 m-b-30">
                <h4 class="font-medium foot-title p-b-30">Kategori</h4>
                <ul class="foot-menu">
                    <?php
                        $this->db->where("parent", 0);
                        $this->db->limit(12);
                        $kategori = $this->db->get("kategori");
                        foreach($kategori->result() as $r){
                    ?>
                    <li class="p-b-9">
                        <a href="<?=site_url("kategori/".$r->url)?>">
                            <?=ucwords(strtolower($r->nama))?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- INFORMASI TOKO -->
            <div class="col-md-4 m-b-30">
                <h4 class="font-medium foot-title p-b-30">Informasi Toko</h4>
                <ul class="foot-menu">
                    <?php
                        $this->db->where("status", 1);
                        $this->db->limit(9);
                        $page = $this->db->get("page");
                        foreach($page->result() as $r){
                    ?>
                    <li class="p-b-9">
                        <a href="<?=site_url("page/".$r->slug)?>">
                            <?=ucwords(strtolower($r->nama))?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="t-center p-l-15 p-r-15">
        <div class="t-center p-t-20">
            Copyright © <?=date('Y');?> <?=ucwords(strtolower($set->nama))?>
            <?php if($this->func->demo() == true){ ?> | made with <i class="fas fa-heart text-danger"></i> by
            Masbil</a><?php } ?>
        </div>
    </div>
</footer>




<!-- Back to top
	<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
	</div> -->
<input type="hidden" id="names" value="<?=$this->security->get_csrf_token_name()?>" />
<input type="hidden" id="tokens" value="<?=$this->security->get_csrf_hash();?>" />

<?php if($this->func->cekLogin() == true){ ?>
<script type="text/javascript">
$(function() {
    //$("#modalpesan").modal();
    $("#modalpilihpesan,#modalpesan").on('shown.bs.modal', function() {
        $(".chat-sticky").hide();
    });
    $("#modalpilihpesan,#modalpesan").on('hidden.bs.modal', function() {
        $(".chat-sticky").show();
    });
    $("#modalpesan").on('shown.bs.modal', function() {
        fbq("track", "Contact");
        loadPesan(0);
        var seti = setInterval(() => {
            loadPesan(1);
        }, 10000);
        $("#modalpesan").on('hidden.bs.modal', function() {
            clearInterval(seti);
        });
    });

    $("#kirimpesan").on("submit", function(e) {
        e.preventDefault();
        var datar = $(this).serialize();
        datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
        $("input,button", this).attr("disabled", true);
        $.post("<?=site_url("assync/kirimpesan")?>", datar, function(s) {
            $("#kirimpesan input,#kirimpesan button").attr("disabled", false);
            fbq("track", "Contact");
            var data = eval("(" + s + ")");
            updateToken(data.token);
            $("#kirimpesan input").val("");
            if (data.success == true) {
                $("#pesan").html(
                    '<div class="isipesan"><i class="fas fa-spin fa-compact-disc text-success"></i> memuat pesan...</div>'
                );
                loadPesan(0);
            } else {
                swal.fire("GAGAL!",
                    "terjadi kendala saat mengirim pesan, coba ulangi beberapa saat lagi",
                    "error");
            }
        });
    });

    //$("#modalpilihpesan").modal();

    function loadPesan(nul) {
        $("#pesan").load("<?=site_url("assync/pesanmasuk")?>", function() {
            if (nul != 1) {
                $("#pesan").animate({
                    scrollTop: $("#pesan").prop('scrollHeight')
                }, 1000);
            }
        });
    }

});
</script>
<div class="modal fade" id="modalpesan" tabindex="-1" role="dialog" style="background: rgba(0,0,0,.5);"
    style="bottom:0;right:0;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary font-medium"><i class="fa fa-comments"></i> Live Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pesan" id="pesan">
                <div class="pesanwrap center">
                    <div class="isipesan"><i class="fas fa-spin fa-compact-disc text-success"></i> memuat pesan...</div>
                </div>
            </div>
            <form id="kirimpesan" method="POST">
                <div class="modal-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="ketik pesan..." name="isipesan" required />
                        <div class="input-group-append">
                            <button type="submit" id="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i>
                                KIRIM</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }else{ ?>
<!--<a href="https://wa.me/<?=$this->func->getRandomWasap()?>" class="chat-sticky hidesmall" target="_blank"><i class="fas fa-comment-dots"></i> Live Chat</a>-->
<?php }?>
<div class="modal fade" id="modalpilihpesan" tabindex="-1" role="dialog" style="background: rgba(0,0,0,.5);"
    style="bottom:0;right:0;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-lr-30 p-tb-40 text-center">
            <h3 class="text-primary font-bold">Hubungi Admin</h3><br />
            <a href="https://wa.me/<?=$this->func->getRandomWasap()?>" target="_blank"
                class="btn btn-lg btn-block btn-success m-b-10"><i class="fab fa-whatsapp"></i> &nbsp;Hubungi via
                Whatsapp</a>
            <?php if($this->func->cekLogin() == true){ ?>
            <button onclick="$('#modalpilihpesan').modal('hide');$('#modalpesan').modal()"
                class="btn btn-lg btn-block btn-primary"><i class="fas fa-comments"></i> &nbsp;Live Chat &nbsp;<b
                    class="badge badge-danger p-lr-8 notifchat" style="display:none">0</b></button>
            <?php }else{ ?>
            <a href="<?=site_url("home/signin")?>" class="btn btn-lg btn-block btn-primary"><i
                    class="fas fa-comments"></i> &nbsp;Live Chat</a>
            <?php } ?>
        </div>
    </div>
</div>
<a href="javascript:void(0)" class="chat-sticky hidesmall" onclick='$("#modalpilihpesan").modal()'><b
        class="badge badge-danger p-lr-8 notifchat" style="display:none">0</b><i class="fas fa-comment-dots"></i> Live
    Chat</a>

<?php
		if($this->func->demo() == true AND !isset($_SESSION["demo"])){
	?>

<script type="text/javascript">
$(function() {
    $("#modaldisclaimer").modal();
});
</script>
<?php
			$this->session->set_userdata("demo",true);
		}
	?>

<div class="modal fade" id="modalatc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-medium"><i class="fa fa-shopping-basket text-warning"></i> &nbsp;Tambah ke
                    keranjang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <i class="fas fa-spin fa-compact-disc text-success"></i> &nbsp;Loading...
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url('assets/vendor/select2/select2.min.js') ?>"></script>
<script type="text/javascript">
$(".js-select2").each(function() {
    $(this).select2({
        theme: 'bootstrap4',
        minimumResultsForSearch: 20,
        dropdownParent: $(this).next('.dropDownSelect2')
    });
});
</script>
<script type="text/javascript" src="<?= base_url('assets/vendor/slick/slick.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/vendor/swal/sweetalert2.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/main.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<script type="text/javascript">
window.onscroll = function() {
    myFunction()
};
var navbar = document.getElementById("navbar-sticky");
var sticky = navbar.offsetTop;

function myFunction() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("menu-sticky")
    } else {
        navbar.classList.remove("menu-sticky");
    }
}

var dataText = ["Cari produk favoritmu, ketik disini", "Ketik saja nama produk atau kategori produk"];

function typeWriter(text, i, fnCallback) {
    // chekc if text isn't finished yet
    if (i < (text.length)) {
        // add next character to h1
        $(".typedtext").attr("placeholder", text.substring(0, i + 1));

        // wait for a while and call this function again for next character
        setTimeout(function() {
            typeWriter(text, i + 1, fnCallback)
        }, 100);
    }
    // text finished, call callback if there is a callback function
    else if (typeof fnCallback == 'function') {
        // call callback after timeout
        setTimeout(fnCallback, 2000);
    }
}
// start a typewriter animation for a text in the dataText array
function StartTextAnimation(i) {
    if (typeof dataText[i] == 'undefined') {
        setTimeout(function() {
            StartTextAnimation(0);
        }, 5000);
    }
    // check if dataText[i] exists
    if (i < dataText[i].length) {
        // text exists! start typewriter animation
        typeWriter(dataText[i], 0, function() {
            // after callback (and whole text has been animated), start next text
            StartTextAnimation(i + 1);
        });
    }
}
// start the text animation
StartTextAnimation(0);

//AOS.init();
new ClipboardJS('.clip');

function formUang(data) {
    return data.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
}

function signoutNow() {
    swal.fire({
            title: "Logout",
            text: "Yakin akan logout dari Akun Anda?",
            icon: "warning",
            showDenyButton: true,
            confirmButtonText: "Oke",
            denyButtonText: "Batal"
        })
        .then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.location.href = "<?=site_url("home/signout")?>";
            }
        });
}

function tambahWishlist(id, nama) {
    $.post("<?php echo site_url("assync/tambahwishlist/"); ?>" + id, {
        [$("#names").val()]: $("#tokens").val()
    }, function(msg) {
        var data = eval("(" + msg + ")");
        var wish = parseInt($(".wishlistcount").html());
        updateToken(data.token);
        if (data.success == true) {
            $(".wishlistcount").html(wish + 1);
            swal.fire(nama, "Berhasil ditambahkan ke wishlist", "success");
        } else {
            swal.fire("Gagal", data.msg, "error");
        }
    });
}

function addtocart(id) {
    $("#modalatc").modal();
    $("#modalatc .modal-body").load("<?=site_url("home/formatc")?>/" + id);
}

function closeatc() {
    $("#modalatc").modal("hide");
}

function updateKeranjang() {
    var jum = parseFloat($(".jmlkeranjang").html()) + 1;
    $(".jmlkeranjang").html(jum);
}

function updateToken(token) {
    $("#tokens,.tokens").val(token);
}

$(".block2-wishlist .fas").on("click", function() {
    $(this).removeClass("active");
    $(this).addClass("active");
});

function pesanProduk(id) {
    $.post("<?=site_url("assync/kirimpesan")?>", {
        "idproduk": id,
        "isipesan": "",
        [$("#names").val()]: $("#tokens").val()
    }, function(s) {
        var data = eval("(" + s + ")");
        updateToken(data.token);
        if (data.success == true) {
            $('#modalpesan').modal()
        } else {
            swal.fire("GAGAL!", "terjadi kendala saat mengirim pesan, coba ulangi beberapa saat lagi", "error");
        }
    });
}

setInterval(() => {
    $.post("<?=site_url("assync/notifchat")?>", {
        "id": <?=$usrid?>
    }, function(s) {
        var data = eval("(" + s + ")");
        if (data.notif > 0) {
            $(".notifchat").html(data.notif);
            $(".notifchat").show();
        } else {
            $(".notifchat").hide();
        }
    });
}, 2000);
</script>

<!-- Facebook Pixel Code -->
<script>
! function(f, b, e, v, n, t, s) {
    if (f.fbq) return;
    n = f.fbq = function() {
        n.callMethod ?
            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
    };
    if (!f._fbq) f._fbq = n;
    n.push = n;
    n.loaded = !0;
    n.version = '2.0';
    n.queue = [];
    t = b.createElement(e);
    t.async = !0;
    t.src = v;
    s = b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t, s)
}(window, document, 'script',
    'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?=$set->fb_pixel?>');
fbq('track', 'PageView');
</script>
<noscript>
    <img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=<?=$set->fb_pixel?>&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->

</body>

</html>