<div class="clear"></div>

<footer class="sitefooter"><div class="wdth">
	<div class="subfooter2">
		<div class="left col50">
			<ul>
				<li><a href="#">Privacy</a></li>
				<li><a href="#">Terms</a></li>
				<li><a href="#">API Policy</a></li>
				<li><a href="#">CSR</a></li>
				<li><a href="#">Security</a></li>
				<li><a href="#">Sitemap</a></li>
			</ul>
		</div>
		<div class="right col50">
			<ul class="social">
				<li><a href="#"><i class='fa fa-facebook'></i></a></li>
				<li><a href="#"><i class='fa fa-instagram'></i></a></li>
				<li><a href="#"><i class='fa fa-twitter'></i></a></li>
			</ul>
		</div>
		<div class="clear"></div>
	</div> <?php //subfooter2 ?>

	<div class="copyright">
		By continuing past this page, you agree to our Terms of Service, Cookie Policy, Privacy Policy and Content Policies. All trademarks are properties of their respective owners. &copy; 2017 - foodies. All rights reserved.
	</div> <?php //copyright ?>
</div></footer>

<div class="popup" id="page_content"><div class="popup_container">
	<a href="javascript:;" onClick="javascript:jQuery('#page_content').hide();" id="close">&times;</a>
	<div id="page_content_sec">&nbsp;</div>
</div></div>

<?php 
if( isset($_GET['action']) ) {
	if( $_GET['action'] == "error" ) {
		echo "<div class='notification'><div class='wdth'><div class='alert alert-error'>Some error, try again.. </div></div></div>";
		?>
		<script>setTimeout(function() {
		    $('#mydiv').fadeOut('fast');
		}, 1000); // <-- time in milliseconds</script>
		<?php
	}
	if( $_GET['action'] == "success" ) {
		echo "<div class='notification'><div class='wdth'><div class='alert alert-success'>Saved successfully.. </div></div></div>";
		?>
		<script>
			setTimeout(function() {
		    	$('.notification').fadeOut();
			}, 5000); 
		</script>
		<?php
	}
} //
?>

</body>
</html>