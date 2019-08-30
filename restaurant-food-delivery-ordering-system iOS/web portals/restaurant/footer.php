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
		All trademarks are properties of their respective owners. &copy; 2017 - Foodomia. All rights reserved.
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
<script>
$("#button").click(function() {
    $('html,body').animate({
        scrollTop: $(".landingimage").offset().top},
        'slow');
});

function orderID_notificaiton()
{
	
	var xmlhttp;
	if(window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	  xmlhttp.onreadystatechange=function()
	  {
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//alert(xmlhttp.responseText);
			document.getElementById("bell").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","hotel_order_details_ajax.php?orderID_notificaiton=count");
	xmlhttp.send();
	//alert(str1);
}
setInterval(orderID_notificaiton, 15*1000);
</script>
<div id="bell" style="display:none"></div>


</body>
</html>