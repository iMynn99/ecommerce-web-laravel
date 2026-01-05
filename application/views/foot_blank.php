<?php 
		$set = $this->func->getSetting("semua");
		$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
	?>
	<!-- Footer -->
	<footer class="p-t-12">
		<div class="t-center p-lr-15">
            Copyright © <?=date('Y');?> <?=ucwords(strtolower($set->nama))?>
            <?php if($this->func->demo() == true){ ?> | made with <i class="fas fa-heart text-danger"></i> by Masbil</a><?php } ?>
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

	<script type="text/javascript" src="<?= base_url('assets/vendor/select2/select2.min.js') ?>"></script>
	<script type="text/javascript">
		$(".js-select2").each(function(){
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
		window.onscroll = function() {myFunction()};
		var navbar = document.getElementById("navbar-sticky");
		var sticky = navbar.offsetTop;
		function myFunction() {
			if (window.pageYOffset >= sticky) {
				navbar.classList.add("menu-sticky")
			} else {
				navbar.classList.remove("menu-sticky");
			}
		}
		
		var dataText = [ "Cari produk favoritmu, ketik disini","Ketik saja nama produk atau kategori produk"];
	
		function typeWriter(text, i, fnCallback) {
			// chekc if text isn't finished yet
			if (i < (text.length)) {
				// add next character to h1
				$(".typedtext").attr("placeholder",text.substring(0, i+1));

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
			if (typeof dataText[i] == 'undefined'){
				setTimeout(function() {
					StartTextAnimation(0);
				}, 5000);
			}
			// check if dataText[i] exists
			if (i < dataText[i].length) {
			// text exists! start typewriter animation
				typeWriter(dataText[i], 0, function(){
				// after callback (and whole text has been animated), start next text
					StartTextAnimation(i + 1);
				});
			}
		}
		// start the text animation
		StartTextAnimation(0);

  		//AOS.init();
		new ClipboardJS('.clip');
		  
		function formUang(data){
			return data.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		}
		function signoutNow(){
			swal.fire({
				title: "Logout",
				text: "Yakin akan logout dari akun anda?",
				icon: "warning",
				showDenyButton: true,
				confirmButtonText: "Oke",
				denyButtonText: "Batal"
			})
			.then((willDelete) => {
				if (willDelete.isConfirmed) {
					window.location.href="<?=site_url("home/signout")?>";
				}
			});
		}

		function tambahWishlist(id,nama){
			$.post("<?php echo site_url("assync/tambahwishlist/"); ?>"+id,{[$("#names").val()]:$("#tokens").val()},function(msg){
				var data = eval("("+msg+")");
				var wish = parseInt($(".wishlistcount").html());
				updateToken(data.token);
				if(data.success == true){
					$(".wishlistcount").html(wish+1);
					swal.fire(nama, "Berhasil ditambahkan ke wishlist", "success");
				}else{
					swal.fire("Gagal", data.msg, "error");
				}
			});
		}
		function addtocart(id){
			<?php if($this->func->cekLogin()){ ?>
				$("#modalatc").modal();
				$("#modalatc .modal-body").load("<?=site_url("home/formatc")?>/"+id);
			<?php }else{ ?>
				window.location.href="<?=site_url("home/signin")?>";
			<?php } ?>
		}
		function closeatc(){
			$("#modalatc").modal("hide");
		}
		function updateKeranjang(){
			var jum = parseFloat($(".jmlkeranjang").html())+1;
			$(".jmlkeranjang").html(jum);
		}

		function updateToken(token){
			$("#tokens,.tokens").val(token);
		}

		$(".block2-wishlist .fas").on("click",function(){
			$(this).removeClass("active");
			$(this).addClass("active");
		});

		function pesanProduk(id){
			$.post("<?=site_url("assync/kirimpesan")?>",{"idproduk":id,"isipesan":"",[$("#names").val()]:$("#tokens").val()},function(s){
				var data = eval("("+s+")");
				updateToken(data.token);
				if(data.success == true){
					$('#modalpesan').modal()
				}else{
					swal.fire("GAGAL!","terjadi kendala saat mengirim pesan, coba ulangi beberapa saat lagi","error");
				}
			});
		}

		setInterval(() => {
			$.post("<?=site_url("assync/notifchat")?>",{"id":<?=$usrid?>},function(s){
				var data = eval("("+s+")");
				if(data.notif > 0){
					$(".notifchat").html(data.notif);
					$(".notifchat").show();
				}else{
					$(".notifchat").hide();
				}
			});
		}, 2000);
	</script>

	<!-- Facebook Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?=$set->fb_pixel?>');
		fbq('track', 'PageView');
		</script>
		<noscript>
		<img height="1" width="1" style="display:none" 
			src="https://www.facebook.com/tr?id=<?=$set->fb_pixel?>&ev=PageView&noscript=1"/>
		</noscript>
	<!-- End Facebook Pixel Code -->

</body>
</html>
