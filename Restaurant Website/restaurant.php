<?php require_once("header.php"); ?>
<header class="headerTop affix-top" data-spy="affix" data-offset-top="7">

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
<div class="bannerouter"  data-offset-top="17">

<section class="banner" >


  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" style = "    font-size: 55px;">

      <div class="item active">
        <img src="images/banner-bg2.jpg" alt="Los Angeles" style="width:100%;">
        <div class="carousel-caption">
         <!-- <h1>THE<span>SHUSHI</span></h1>
			<div class="get">GET 50% OFF</div>
			<p>ON FIRST ORDER</p>
			<div class="bannerBtn">
				<a class="btn btnTheme" href="#">Order Now</a>
			</div>-->
        </div>
      </div>

      <div class="item">
        <img src="images/banner-bg2.jpg" alt="Chicago" style="width:100%;">
        <div class="carousel-caption">
          <!--<h1>THE<span>SHUSHI</span></h1>
			<div class="get">GET 50% OFF</div>
			<p>ON FIRST ORDER</p>
			<div class="bannerBtn">
				<a class="btn btnTheme" href="#">Order Now</a>
			</div>-->
        </div>
      </div>
    
      <div class="item">
        <img src="images/banner-bg2.jpg" alt="New York" style="width:100%;">
        <div class="carousel-caption">
          <!--<h1>THE<span>SHUSHI</span></h1>
			<div class="get">GET 50% OFF</div>
			<p>ON FIRST ORDER</p>
			<div class="bannerBtn">
				<a class="btn btnTheme" href="#">Order Now</a>
			</div>-->
        </div>
      </div>
  
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

</section>  </div>
<!-- END Banner -->
<!-- END Banner -->
		 
<!-- START Restaurant -->
<section class="rest_sec">
	<div class="container">
	 

	<ul class="item-holder">
            <li class="itembox" >
			<a href="menupage1.php">
                <div class="card mycard wow fadeInLeft " data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/r3.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
            <li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInUp" data-wow-delay="0.4s" data-wow-duration="2s">
                    <img class="card-img-top img-responsive" src="images/r1.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
            <li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInRight" data-wow-delay="0.5s" data-wow-duration="3s">
                    <img class="card-img-top img-responsive" src="images/r4.png">
					<div class="grid__item__offer">
                                    <span>Special</span>
                                    <span>Offer</span>
                                </div>
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
            <li class="itembox unavalable">
				<a href="menupage1.php">
                <div class="card mycard wow fadeInLeft " data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/r2.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInRight" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
		<div class="clearfix"></div>
		    <div class="ad_banner">
		   <div class="row">
		<h2 class="text-center">ADVERTISEMENT SPACE VIDEO ENABLED 1600x300</h2>
        <iframe width="100%" height="300" src="https://www.youtube.com/embed/I4urVZh1uZE" frameborder="0" autostart="false" allow="encrypted-media" allowfullscreen></iframe>
	       </div>
		   </div>
			
			 <li class="itembox" >
			 	<a href="menupage1.php">
                <div class="card mycard wow fadeInLeft " data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox">
				<a href="menupage1.php">
                <div class="card mycard wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInRight" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInLeft " data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInRight" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			<div class="clearfix"></div>
			 <div class="ad_banner">
		   <div class="row">
		<h2 class="text-center">ADVERTISEMENT SPACE VIDEO ENABLED 1600x300</h2>
        <iframe width="100%" height="300" src="https://www.youtube.com/embed/I4urVZh1uZE" frameborder="0" autostart="false" allow="encrypted-media"  allowfullscreen></iframe>
	       </div>
		   </div>
			
			 <li class="itembox" >
			 	<a href="#">
                <div class="card mycard wow fadeInLeft " data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInRight" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInLeft " data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			
			<li class="itembox" >
				<a href="menupage1.php">
                <div class="card mycard wow fadeInRight" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="card-img-top img-responsive" src="images/palceholder.png">
                    <div class="card-block">
                        <h4 class="card-title">LITTLE LUCHA</h4>
                       
                        <div class="card-text">
                            Mexican . Tacos .Burritos
                        </div>
                    </div>
                    <!-- <div class="card-footer">                   
                        <span> 45 <i class="fas fa-star"></i> (344)</span>
                    </div> -->
                </div>
				</a>
            </li>
			 <div class="ad_banner">
		   <div class="row">
		<h2 class="text-center">ADVERTISEMENT SPACE VIDEO ENABLED 1600x300</h2>
        <iframe width="100%" height="300" src="https://www.youtube.com/embed/I4urVZh1uZE" frameborder="0" autostart="false" allow="encrypted-media" allowfullscreen></iframe>
	       </div>
		   </div>
			</ul>
	
	</div>
</section>
<!-- END Restaurant -->



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
  });
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
		window.location = $(this).attr('href');
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
<style>
    
        span.glyphicon.glyphicon-chevron-left{
            font-size:55px;
        }
        span.glyphicon.glyphicon-chevron-right{
            font-size:55px;
        }
    
</style>

<?php require_once("footer.php"); ?>