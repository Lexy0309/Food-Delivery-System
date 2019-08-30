<?php require_once("header.php"); ?>
<?php //echo ($_SESSION['id']);?>


<body data-spy="scroll" data-target=".navbar" data-offset="50">

<!--smallHeader-->
<header class="headerTop affix-top" data-spy="affix" data-offset-top="197">

<div class="small_header">
    <div class="container">
		<div class="row">
		 
			<div class="col-lg-10 col-lg-offset-1 col-md-10  col-md-offset-1 col-sm-12">
			 <div class="col-lg-3 col-md-3 col-sm-12">
				<div class="logo">
					<a href="index.php"><img class="img-responsive" src="images/log.png"></a>
				</div>
			</div>
			
			
			   <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-4 col-xs-7">
			      <div class="input-group srcc">
				        <span class="input-group-btn">
							<button class="btn" type="button"><i class="fas fa-search"></i></button>
						</span>
						<input class="form-control" placeholder="Search here" type="email">
						
					</div>
			   </div>
			   	<div class="col-lg-2 col-md-2 col-sm-3 col-xs-5 pull-right text-right">
				     <div class="crt_links">

						<a class="yellow" href="login.php"><i class="fas fa-user"></i></a>
						<a class="yellow" href="#"><i class="fas fa-shopping-cart"></i></a>
					</div>
			   </div>
			  
			</div>
		</div>
	</div>
</div>
<!--End smallHeader -->

<!-- START headerTop -->

	<div class="container">

		<div class="row">
			
			<div class="col-lg-10 col-lg-offset-1 col-md-10  col-md-offset-1 col-sm-12">
				<div class="navbar navbar-default">
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						
						</div>
						<div class="navbar-collapse collapse">
							<ul class="nav navbar-nav">
						
							<li class="">
								<a href="index.php">Home <b class="caret"></b></a>				
								<ul class="megamenu row">
									<li class="dropdown-header">DISHES </li>
									<li class="col-sm-5">
										<ul>
											<div class="col-md-6 ins_ul">
												<li><a href="#">Burgers</a></li>
												<li><a href="#">Sushi</a></li>
												<li><a href="#">Pizza  </a></li>
												<li><a href="#">Tacos </a></li>
											</div>
											<div class="col-md-6 ins_ul">
												<li><a href="#">Pizza</a></li>
												<li><a href="#">Pasta</a></li>
												<li><a href="#">Poke </a></li>
												<li><a href="#">Sandwiches</a></li>
												<li><a href="#">Salads</a></li>
											</div>
										</ul>
									</li>
									<li class="col-sm-2">
										<ul>
											<div class="col-md-12 ins_ul">
												<li><a href="#">Vegan</a></li>
											    <li><a href="#">Vegetarian</a></li>
											    <li><a href="#">Gluten Free</a></li>
											</div>
											
										</ul>
									</li>
								</ul>

							</li>
						   	<li><a href="restaurant.php">RESTAURANTS</a></li>
						   <li><a href="cuisines.php">CUISINES </a></li>
						   <li><a href="services.php">SERVICES </a></li>
						   <li><a href="non-profit.php">NON-PROFITS</a></li>
						   <li><a href="about.php">ABOUT</a></li>
						</ul>
						</div>
					
					</div>
			</div>
			
		</div>
	
		
	</div>
</header>
<!-- END  headerTop -->
		 
<!-- START Banner -->
<!--<section class="banner">
<div class="bannerimg"><img class="img-responsive" src="images/banner-bg2.jpg"></div>

	<div class="col-md-6 col-md-offset-3 col-sm-12">
		<div class="bannerAreaInner">
			<h1>THE<span>SHUSHI</span></h1>
			<div class="get">GET 50% OFF</div>
			<p>ON FIRST ORDER</p>
			<div class="bannerBtn">
				<a class="btn btnTheme" href="#">Order Now</a>
			</div>
		</div>
	</div>
</section>-->


<!-- START banner -->

<section class="banner bannerHome">
	<img src="images/banner-bg2.jpg" alt="New York" style="width:100%;">
</section>
<!-- END Banner -->






<!-- START App Section-->
<section class="app appDesktop">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heding-outer wow bounceInLeft" data-wow-duration="1s">
					<h1>CHECK OUT OUR NEW APP!</h1>
				</div>
			</div>

		
			<!-- Wrapper for slides -->
			<div class="" role="listbox">
				<div class="row app_sec">					  
					<div class="col-md-6 col-sm-6">
						<img  data-wow-duration="3s" class="img-responsive wow bounceInLeft" src="images/mobb.png">
					</div>
					 <div class="col-md-6 col-sm-6 pt">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<img  data-wow-delay="0.4s" data-wow-duration="3s" class="img-responsive wow bounceInRight" src="images/ticc.png">
							</div>
							<div  class="col-md-6 col-sm-6 app_txt ">
								<h2 data-wow-delay="0.5s" data-wow-duration="3s"  class="wow bounceInUp">For even
								<br/></h2> 
								<h2 data-wow-delay="0.7s" data-wow-duration="4s" class="wow bounceInRight">speedier service,<br/></h2>  
								<h2 data-wow-delay="0.8s" data-wow-duration="3s" class="wow bounceInUp">
								download our app! </h2>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 ap_img">
								<img data-wow-duration="4s" data-wow-delay="0.4s" class="img-responsive wow  pulse" data-wow-iteration="infinite" src="images/appp.png">
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END App Section -->


<!-- START App Section Mobile -->
<section class="app appMobile">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heding-outer wow bounceInLeft" data-wow-duration="1s">
					<h1>CHECK OUT OUR NEW APP!</h1>
				</div>
			</div>

		
			<!-- Wrapper for slides -->
			<div class="" role="listbox">
				<div class="row app_sec">					  
					<div class="col-md-6 col-sm-6">
						<img class="img-responsive" src="images/mobb.png">
					</div>
					 <div class="col-md-6 col-sm-6 pt">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<img class="img-responsive"  src="images/ticc.png">
							</div>
							<div  class="col-md-6 col-sm-6 app_txt ">
								<h2>For even <br/></h2> 
								<h2 >speedier service,<br/></h2>  
								<h2>download our app! </h2>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 ap_img">
								<img data-wow-duration="4s" data-wow-delay="0.4s" class="img-responsive wow  pulse" data-wow-iteration="infinite" src="images/appp.png">
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END App Section Mobile -->





<!-- START deliveryMain -->
<section class="deliveryMain">
  <div class="container">
  <div class="row">
	<div class="col-md-4 col-sm-4 col-xs-12 memberPlanRES wow bounceInLeft">
		      <div class="thumbb">
			     <div class="circle">1</div>
			      <figure>
				    <img class="img-responsive" src="images/ic1.png">
				  </figure>
				  <h1>Discover</h1>				  
			  </div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 memberPlanRES wow bounceInUp">
		<div class="thumbb">
			      <div class="circle">2</div>
			      <figure>
				    <img class="img-responsive" src="images/ic2.png">
				  </figure>
				  <h1>Place order</h1>
			  </div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 memberPlanRES wow bounceInRight">
		<div class="thumbb">
			     <div class="circle">3</div>
			      <figure>
				    <img class="img-responsive" src="images/ic3.png">
				  </figure>
				  <h1>Enter location</h1>
			  </div>
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12 memberPlanRES wow bounceInLeft">
		<div class="thumbb">
			       <div class="circle">4</div>
			      <figure>
				    <img class="img-responsive" src="images/ic4.png">
				  </figure>
				  <h1>we deliver</h1>
			  </div>
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12 memberPlanRES wow bounceInUp">
		<div class="thumbb">
			      <div class="circle">5</div>
			      <figure>
				    <img class="img-responsive" src="images/ic5.png">
				  </figure>
				  <h1>Payment</h1>
				  <span>cash on delivery</span>
			  </div>
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12 memberPlanRES wow bounceInRight">
		<div class="thumbb">
			      <div class="circle">6</div>
			      <figure>
				    <img class="img-responsive" src="images/ic6.png">
				  </figure>
				  <h1>Review</h1>
			  </div>
	</div>
    </div>
  </div>
</section>
<!-- END deliveryMain -->


<!--new_product-->
<section class="new_product">
   <div class="container">
    <div class="row">
         <div class="col-md-12">
		 <div class="heding-outer">
		 <h1>Popular Cuisines</h1>
		 </div>
		 </div>
	</div>	
   </div>
   
   
      <div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-2">
		   <a class="item-box">
              <figure>
			  <img class="img-responsive" src="images/newp-1.png">
			  </figure>
			  <div class="hover">
			  <img class="img-responsive" src="images/ct1.png">
			  </div>
			  
            </a>
		 </div>
		  <div class="col-md-4">
		   <a class="item-box">
              <figure>
			  <img class="img-responsive" src="images/newp-2.png">
			  </figure>
			  <div class="hover">
			  <img class="img-responsive" src="images/ct2.png">
			  </div>
			  
            </a>
		 </div>
	</div>	
	<div class="clearfix"></div>
	<div class="row">
        <div class="col-md-4">
		   <a class="item-box">
              <figure>
			  <img class="img-responsive" src="images/newp-3.png">
			  </figure>
			  <div class="hover">
			  <img class="img-responsive" src="images/ct3.png">
			  </div>
			  
            </a>
		 </div>
		  <div class="col-md-4">
		   <a class="item-box">
              <figure>
			  <img class="img-responsive" src="images/newp-4.png">
			  </figure>
			  <div class="hover">
			  <img class="img-responsive" src="images/ct4.png">
			  </div>
			  
            </a>
		 </div>
		  <div class="col-md-4">
		   <a class="item-box">
              <figure>
			  <img class="img-responsive" src="images/newp-5.png">
			  </figure>
			  <div class="hover">
			  <img class="img-responsive" src="images/ct5.png">
			  </div>
			  
            </a>
		 </div>
	</div>	
   </div>

</section>
<!--new_product-->


<!--new_restaurent-->

<section class="new_product">
   <div class="container">
    <div class="row">
         <div class="col-md-12">
		 <div class="heding-outer">
		 <h1> Featured Restaurants</h1>
		 </div>
		 </div>
	</div>
<div class="row">
<div class="col-sm-4 col-md-2 col-lg-2 mt-4">
                <a href="menupage1.php" class="card mycard">
                    <img class="card-img-top img-responsive" src="images/r3.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    
                </a>
            </div>
			<div class="col-sm-4 col-md-2 col-lg-2 mt-4">
                <a href="menupage1.php"class="card mycard">
                    <img class="card-img-top img-responsive" src="images/r1.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    
                </a>
            </div>
			<div class="col-sm-4 col-md-2 col-lg-2 mt-4">
                <a href="menupage1.php" class="card mycard">
                    <img class="card-img-top img-responsive" src="images/r4.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    
                </a>
            </div>
			<div class="col-sm-4 col-md-2 col-lg-2 mt-4">
                <a href="menupage1.php" class="card mycard">
                    <img class="card-img-top img-responsive" src="images/r2.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    
                </a>
            </div>
			<div class="col-sm-4 col-md-2 col-lg-2 mt-4">
                <a href="menupage1.php" class="card mycard">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                   
                </a>
            </div>
			<div class="col-sm-4 col-md-2 col-lg-2 mt-4">
                <a href="menupage1.php"class="card mycard">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    
                </a>
            </div>
	 </div>
	</div>	


</section>
<!--new_restaurent-->






<!-- START Testimonials2 -->
<section class="testimonial">
   <div class="container">
	<div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12">	
	<div class="crouselholder">
		<div class="carousel slide" data-ride="carousel" id="quote-carousel">
			<!-- Bottom Carousel Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#quote-carousel" data-slide-to="0"><img class="img-responsive " src="images/one.jpg" alt="">
				 <small>Someone famous</small>
				 
				</li>
				<li data-target="#quote-carousel" data-slide-to="1" class="active"><img class="img-responsive" src="images/two.jpg" alt="">
				</li>
				<li data-target="#quote-carousel" data-slide-to="2"><img class="img-responsive" src="images/three.jpg" alt="">
				</li>
			</ol>
			<!-- Carousel Slides / Quotes -->
			<div class="carousel-inner text-center">
				<!-- Quote 1 -->
				<div class="item active">
					<blockquote>
						<div class="row">
							<div class="col-md-10 col-md-offset-1 ">
								<div class="testUser">
  								    <h4><em> Food delivery is quick and inexpensive!</em></h4>
									
									<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry. </p>
									<h5>John Denny</h5>
									<small>Georgia | USA</small>
							   </div>
						   </div>
						</div>
					</blockquote>
				</div>
				<!-- Quote 2 -->
				<div class="item">
					<blockquote>
						<div class="row">
							<div class="col-md-10 col-md-offset-1">
								<div class="testUser">
  								    <h4><em> Food delivery is quick and inexpensive!</em></h4>
									
									<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry. </p>
									<h5>John Denny</h5>
									<small>Georgia | USA</small>
							   </div>
						   </div>
						</div>
					</blockquote>
				</div>
				<!-- Quote 3 -->
				<div class="item">
					<blockquote>
						<div class="row">
							<div class="col-md-10 col-md-offset-1">
								<div class="testUser">
  								    <h4><em> Food delivery is quick and inexpensive!</em></h4>
									
									<p>Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry. </p>
									<h5>John Denny</h5>
									<small>Georgia | USA</small>
							   </div>
						   </div>
						</div>
					</blockquote>
				</div>
			</div>


			<!-- Carousel Buttons Next/Prev -->
			<a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
			<a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
		</div>
      </div>
	</div>	
   </div>
</section>
<!-- END Testimonials2 -->




<!-- START jS -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>

	  
<!-- START jQuery WOW -->
<script src="js/wow.js"></script>
<script>
wow = new WOW(
  {
	animateClass: 'animated',
	offset:       100,
	callback:     function(box) {
	  console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
	}
  }
);
wow.init();
// document.getElementById('moar').onclick = function() {
//   var section = document.createElement('section');
//   section.className = 'section--purple wow fadeInDown';
//   this.parentNode.insertBefore(section, this);
// };
</script>
<!-- END jQuery WOW -->

<!-- START testimonials -->	
   <script>
            jQuery(document).ready(function($) {
              var owl = $('#testimonials');
              owl.on('initialize.owl.carousel initialized.owl.carousel ' +
                'initialize.owl.carousel initialize.owl.carousel ' +
                'resize.owl.carousel resized.owl.carousel ' +
                'refresh.owl.carousel refreshed.owl.carousel ' +
                'update.owl.carousel updated.owl.carousel ' +
                'drag.owl.carousel dragged.owl.carousel ' +
                'translate.owl.carousel translated.owl.carousel ' +
                'to.owl.carousel changed.owl.carousel',
                function(e) {
                  $('.' + e.type)
                    .removeClass('secondary')
                    .addClass('success');
                  window.setTimeout(function() {
                    $('.' + e.type)
                      .removeClass('success')
                      .addClass('secondary');
                  }, 500);
                });
              owl.owlCarousel({
                loop: true,
                nav: true,
                lazyLoad: true,
                margin: 15,
                video: true,
				autoplay: true,
				autoplayTimeout: 2000,
				responsiveClass: true,
				
                responsive: {
                  0: {
                    items: 2
                  },
                  600: {
                    items: 3
                  },
                  960: {
                    items: 5,
                  },
                  1200: {
                    items: 7
                  }
                }
              });
            });
          </script>		  
<!-- End testimonials -->	

<!-- START FAQ -->

<script>
if ($(document).width() < 768) {
	$('.dropdown-toggle').click(function(e) {
	    e.preventDefault();
		if ($(document).width() > 768){
			var url = $(this).attr('href');
			if (url !== '#') {
			  window.location.href = url;
			}
		}
	});
}else{
	$('.dropdown-toggle').click(function(e) {
	    e.preventDefault();
		if ($(document).width() > 768){
			window.location = $(this).attr('href');
		}
	});
}
</script>


<script>
function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);
</script>
<!-- END FAQ -->
    
  </body>

<?php //reset password
if(isset($_GET['reset']) && !empty($_POST['emailaddr'])) {
		
		$email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);
		
		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email,
			"role" => "hotel"
		);

		$ch = curl_init( $baseurl.'/forgotPassword' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    var_dump($json_data);

		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $json_data['code'];
		//die;

		if($json_data['code'] !== 200){
			//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";
			@header("Location: index.php?action=error");
				echo "<script>window.location='index.php?action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
			@header("Location: index.php?p=action=success");
				echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);
}
//remove resetpass = end ?>
<?php require_once("footer.php"); ?>