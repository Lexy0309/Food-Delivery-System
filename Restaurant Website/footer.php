<div class="clear"></div>
<!-- START Footer -->
<footer class="footer_outer">
	<div class="container">		
		<div class="row">
			   
		  <div class="col-lg-4 col-md-4 col-sm-6">
			 <div class="ft_about ft_title">
				 <h5>Contact</h5>
				<ul class="ad_link">
				   <p><i class="fa fa-handshake" aria-hidden="true"></i> Work with Ticotogo</p>
					<p><i class="fa fa-phone"></i> <a href="tel:+">+506 8426 8646</a></p>
					<p><i class="fa fa-envelope"></i> <a href="emailto:"> operator@ticotogo.com</a></p>
					
				</ul>				 
				  <div class="ft_social">
				   <li> 
				      <a href="#" target="_blank">	<i class="fab fa-instagram"></i></a>
				      <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>					 
					  <a href="#" target="_blank"><i class="fab fa-google-plus-g"></i></a>
					   <a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
				   </li>
				</div>				
			 </div>
		  </div>
		  <div class="col-lg-4 col-md-4 col-sm-6">
			 <div class="ft_menu ft_title">
				<h5>Quick Links</h5>
				<ul class="q_link">
				   <li><a href="index.php">Home </a></li>
				   <li><a href="shop.php">services</a></li>
				   <li><a href="about.php">non-profits</a></li>
				   <li><a href="contact.php">about us</a></li>				  
				</ul>
				<ul class="q_link">
				   <li><a href="index.php">RESTAURANTS</a></li>
				   <li><a href="shop.php">CUISINES  </a></li>
				   <li><a href="about.php">beverages</a></li>
				   <li><a href="contact.php">groceries</a></li>
				   <li><a href="about.php">pharmacy</a></li>
				   <li><a href="contact.php">Daily deals</a></li>
				</ul>
			 </div>
		  </div>
		  <div class="col-lg-4 col-md-4 col-sm-6">
			 <div class="ft_menu ft_title timing">
				<h5>Hours</h5>
				<p>
				 Monday<span>1 pm - 10 pm</span>
				</p>
				<p>
				 Tuesday  <span>1 pm - 10 pm</span>
				</p>
				<p>
				 Wednesday    <span>1 pm - Midnight</span>
				</p>
				<p>
				 Thursday     <span>1 pm - 10 pm</span>
				</p>
				<p>
				 Friday    <span>1 pm - Midnight</span>
				</p>
				<p>
				 Saturday    <span>1 pm - 10 pm</span>
				</p>
				<p>
				 Sunday      <span class="clss">Closed</span>
				</p>
			 </div>
		  </div>
		 
	   </div>
	   
	   <div class="row">
		   <div class="col-md-12">
			 <figure class="app_logo">
				<img class="img-responsive" src="images/app_ic.png">
			 </figure>
		   </div>
	   </div>
	   <div class="row">
	     <figure class="f_logo">
		    <img class="img-responsive" src="images/f_logo.png">
		 </figure>
	   </div>
	   
	</div>	
</footer>
<div class="ft_copyright">
	<div class="container">
	   <div class="row">
		  <div class="col-lg-12 col-sm-12 col-md-12">
			 <p>TicoToGo © 2019</p>
		  </div>
	   </div>
	</div>
 </div>
<!-- END Footer -->

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

// function orderID_notificaiton()
// {
	
// 	var xmlhttp;
// 	if(window.XMLHttpRequest)
// 	  {// code for IE7+, Firefox, Chrome, Opera, Safari
// 		xmlhttp=new XMLHttpRequest();
// 	  }
// 	else
// 	  {// code for IE6, IE5
// 		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
// 	  }
	  
// 	  xmlhttp.onreadystatechange=function()
// 	  {
// 		if(xmlhttp.readyState==4 && xmlhttp.status==200)
// 		{
// 			//alert(xmlhttp.responseText);
// 			document.getElementById("bell").innerHTML=xmlhttp.responseText;
// 		}
// 	  }
// 	xmlhttp.open("GET","hotel_order_details_ajax.php?orderID_notificaiton=count");
// 	xmlhttp.send();
// 	//alert(str1);
// }
// setInterval(orderID_notificaiton, 15*1000);
</script>

</html>