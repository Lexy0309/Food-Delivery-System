<?php require_once("header.php"); ?>
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

<!--checkout-->
<section class="checkout">
   <div class="container">
    <div class="row">
	 <div class="col-md-12 col-sm-12"> 
		
		 <div class="col-lg-2 col-md-2 col-sm-12">
                  <div class="side_tab same_paddng">
                     <!-- Nav tabs -->
                     <ul class="nav nav-tabs tabs-left">
					<li class="active">
						
						<a href="#home" data-toggle="tab" aria-expanded="false"><img class="img-responsive" src="images/1key.png"> </a>
						</li>
                        <li class="">
						
						<a class="d-green" href="#profile" data-toggle="tab" aria-expanded="false"><img class="img-responsive" src="images/1vegan.png">Vegan</a>
						</li>
						 <li class="">
						
						<a class="veggi" href="#profile" data-toggle="tab" aria-expanded="false"><img class="img-responsive" src="images/1veg.png">Vegitarian</a>
						</li>
						 <li class="">
						
						<a class="glute" href="#profile" data-toggle="tab" aria-expanded="false"><img class="img-responsive" src="images/1gf.png"> Gluteen Free</a>
						</li>
						 <li class="">
						
						<a class="spicy" href="#profile" data-toggle="tab" aria-expanded="false"><img class="img-responsive" src="images/1spicy.png">Spicy</a>
						</li>
                       
                     </ul>
                  </div>
               </div>
               <div class="col-lg-10 col-md-10 col-sm-12">
                  <div class="left_tab">
                     <!-- Tab panes -->
                     <div class="tab-content">
                        <div class="tab-pane active" id="home">
                          <div class="check-pg"> 
		<a href="">Checkout</a>
		<hr>
		 <div class="card shopping-cart">
           
          	<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:50%">Subtotal(4items)</th>
							<th class="titlee pull-right"style="width:50%">$20.000</th>
							
						</tr>
					</thead>
					<tbody>
						<tr>
							<td data-th="Product">
								<div class="row">
									<div class="col-sm-3"> 
									<input type="number" class="form-control text-center" value="1">
									</div>
									<div class="col-sm-9">
									
										<h4 class="nomargin">Vagiterian Burger</h4>
										<p>Cheese Jalapaon Regular Fries</p>
									</div>
								</div>
							</td>
							
							
						
							<td class="actions" data-th="">
								<button class="tb-btn edit-btn"><i class="far fa-edit"></i></button>
								<button class="tb-btn del-btn"><i class="fa fa-trash" aria-hidden="true"></i></button>								
							</td>
							
								<td data-th="Subtotal" class="text-center stotal">150.00</td>
						</tr>
						
						
							<tr>
							<td data-th="Product">
								<div class="row">
									<div class="col-sm-3"> 
									<input type="number" class="form-control text-center" value="1">
									</div>
									<div class="col-sm-9">
									
										<h4 class="nomargin">Artisanal Burger</h4>
										<p>Cheese Jalapaon Regular Fries</p>
									</div>
								</div>
							</td>
							
							
						
							<td class="actions" data-th="">
								<button class="tb-btn edit-btn"><i class="far fa-edit"></i></button>
								<button class="tb-btn del-btn"><i class="fa fa-trash" aria-hidden="true"></i></button>								
							</td>
							
								<td data-th="Subtotal" class="text-center stotal">150.00</td>
						</tr>
					
				
							<tr>
							<td data-th="Product">
								<div class="row">
									<div class="col-sm-3 "> 
									<input type="number" class="form-control text-center" value="1">
									</div>
									<div class="col-sm-9">
									
										<h4 class="nomargin">Tropical Fish Ticos</h4>
										<p>Cheese Jalapaon Regular Fries</p>
									</div>
								</div>
							</td>
							
							
						
							<td class="actions" data-th="">
								<button class="tb-btn edit-btn"><i class="far fa-edit"></i></button>
								<button class="tb-btn del-btn"><i class="fa fa-trash" aria-hidden="true"></i></button>								
							</td>
							
								<td data-th="Subtotal" class="text-center stotal">150.00</td>
						</tr>
						
						<tr>
							<td data-th="Product">
								<div class="row">
									<div class="col-sm-3"> 
									<input type="number" class="form-control text-center" value="1">
									</div>
									<div class="col-sm-9">
									
										<h4 class="nomargin">Mediterranean Plate</h4>
										<p>Cheese Jalapaon Regular Fries</p>
									</div>
								</div>
							</td>
							
							<td class="actions" data-th="">
								<button class="tb-btn edit-btn"><i class="far fa-edit"></i></button>
								<button class="tb-btn del-btn"><i class="fa fa-trash" aria-hidden="true"></i></button>								
							</td>
							
								<td data-th="Subtotal" class="text-center stotal">150.00</td>
						</tr>
					</tbody>
				</table>
        </div>
		</div> 
                        </div>
                        <div class="tab-pane" id="profile">
                          hjhjh
						    
						   
						    
                        </div>
                     
                     </div>
                  </div>
               </div>
            </div>
			
         </div>
   
	</div>
	 
   </div>
    </div>

</section>
<!--new_product-->





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

<?php require_once("footer.php"); ?>