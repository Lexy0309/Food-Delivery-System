<?php require_once("header.php"); ?>

<header class="headerTop affix-top" data-spy="affix" data-offset-top="197">

<div class="small_header">
    <div class="container">
		<div class="row">
		 
			<div class="col-lg-10 col-lg-offset-1 col-md-10  col-md-offset-1 col-sm-12">
			 <div class="col-lg-3 col-md-3 col-sm-12">
				<div class="logo">
					<a href="index.html"><img class="img-responsive" src="images/log.png"></a>
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
<section class="banner">
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
</section>
<!-- END Banner -->

<!--- menu page Start-->
<section class="menuPgae">
	<div class="container-fluid">		
		<div class="row">
			<div class="bottomenu">
				<ul class="menuList" data-spy="affix" data-offset-top="217">
					<li> <a href="#popular"> POPULAR </a> </li>  
					<li><a href="#bruschetta" href="menupage2.html">  BRUSCHETTAS </a></li> 
					<li><a href="#tapas" href="menupage3.html">  TAPAS </a> </li>
					<li><a href="#mains" href="menupage4.html">  MAINs </a> </li>
					<li><a href="#month" href="menupage5.html">  BURGER OF THE MONTH </a></li>
					<li><a href="#desserts" href="menupage6.html">    DESSERTS </a></li>
				</ul>
			</div> 
		</div>
		<div class="row">
			<div class="col-md-2 col-lg-2 col-sm-6">
				<div class="deliveryMenu">
					<div class="del-img">
						<img src="images/m1.png">
					</div>
					<div class="del-middle">
						<h3>EL GARITO</h3>
						<h4>Tapas . Fresh .Artisanal</h4>
					</div>
					<div class="del-bottom">
							<h5>AVAILABLE FOR DELIVERY</h5>
							<p>
							 Monday <span> 6:30 PM - 9:30 PM </span>
							</p>
							<p>
							 Tuesday  <span> 6:30 PM - 9:30 PM </span>
							</p>
							<p class="cOl">
							 Wednesday <span> UNAVAILABLE </span>
							</p>
							<p>
							 Thursday <span> 6:30 PM - 9:30 PM  </span>
							</p>
							<p>
							 Friday  <span> 6:30 PM - 9:30 PM </span>
							</p>
							<p>
							 Saturday <span> 6:30 PM - 9:30 PM </span>
							</p>
							<p class="cOl">
							 Sunday  <span> UNAVAILABLE </span>
							</p>
					</div>
				</div>
				
				<!-- advertisment -->
				<div class="advertisment">
					<iframe id="cartoonVideo" width="100%" height="315" src="https://www.youtube.com/embed/pebOC-8WGgA?autoplay=1" frameborder="0" autostart="false" allow="encrypted-media" allowfullscreen></iframe>
				</div>
				<!-- advertisment -->
			</div>
		
		<div class="col-lg-6 col-md-6 col-sm-12"> 
		<!-- START popular  -->
		<div class="popular" id="popular"> 
			<h3> Popular </h3>

			<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
				<a href="#" data-toggle="modal" data-target="#myModal2">
					<article>
						<div class="VegBur">
							<h4>Vegitarian Burger</h4>
							<img src="images/20x20-GF-1.png" />
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
							<h5><strike>$ 16,050.00</strike>  $ 6,50</h5>
						</div>
					</article>
					<figure>
						<img src="images/menu1.jpg" class="img-responsive"/>
					</figure>
				</a>
			</div>  
			<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
				<a href="#" data-toggle="modal" data-target="#myModal2">
					<article>
						<div class="VegBur">
							<h4>  Vegitarian Burger</h4>
							<img src="images/20x20-GF-1.png" />
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
							<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
						</div>
					</article>
					<figure>
						<img src="images/menu1.jpg" class="img-responsive"/>
					</figure>
				</a>
			</div> 
			<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
				<a href="#" data-toggle="modal" data-target="#myModal2">
					<article>
						<div class="VegBur">
							<h4> Vegitarian Burger</h4>
							<img src="images/20x20-GF-1.png" />
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
						<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
						</div>
					</article>
					<figure>
						<img src="images/menu1.jpg" class="img-responsive"/>
					</figure>
				</a>
			</div> 
			<div class="col-lg-6 col-md-6 col-sm-12 itemDetail" id="bruschetta">
				<a href="#" data-toggle="modal" data-target="#myModal2">
				<article>
					<div class="VegBur">
						<h4>   Vegitarian Burger</h4>
						<img src="images/20x20-GF-1.png" />
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
						<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
					</div>
				</article>
				<figure>
					<img src="images/menu1.jpg" class="img-responsive"/>
				</figure>
			  </a>
		  </div> 
		</div>
		<!-- END popular  -->
		
	<!-- START bruschetta  -->
	<div class="bruschetta"> 
	   <h3> bruschetta </h3>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
				</article>
			</a>
		</div>  
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
				<article>
					<div class="VegBur">
						<h4>   Vegitarian Burger</h4>
						<img src="images/20x20-GF-1.png" />
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
						<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
					</div>
				</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail" id="tapas">
			<a href="#">
				<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
				</article>
			</a>
		</div> 
</div>
	<!-- END bruschetta  -->


	<!-- START Category Title  -->
	<div class="bruschetta1" > 
		   <h3> TAPAS </h3>
	<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
	 <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		 </a>
		  </div>  
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a href="#" data-toggle="modal" data-target="#myModal2">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png">
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  <figure>
		  <img src="images/menu1.jpg" class="img-responsive">
		  </figure>
		  </a>
		  </div>
			  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a href="#" data-toggle="modal" data-target="#myModal2">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png">
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  <figure>
		  <img src="images/menu1.jpg" class="img-responsive">
		  </figure>
		  </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail" id="mains">
		   <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  </a>
		  </div> 
	</div>

	<!-- END Category Title  -->


	<!-- START Mains  -->
	 <div class="bruschetta1" > 
		   <h3> Mains </h3>
	 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a href="#" data-toggle="modal" data-target="#myModal2">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png">
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  <figure>
		  <img src="images/menu1.jpg" class="img-responsive">
		  </figure>
		  </a>
		  </div>
			<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		 <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		 </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a  href="#">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png" />
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  </a>
		  </div> 
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
		   <a href="#" data-toggle="modal" data-target="#myModal2">
		  <article>
		  <div class="VegBur">
		  <h4>  Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png">
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  <figure>
		  <img src="images/menu1.jpg" class="img-responsive">
		  </figure>
		  </a>
		  </div>
		  <div class="col-lg-6 col-md-6 col-sm-12 itemDetail" id="month">
		   <a href="#" data-toggle="modal" data-target="#myModal2">
		  <article>
		  <div class="VegBur">
		  <h4>   Vegitarian Burger</h4>
		  <img src="images/20x20-GF-1.png">
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
		  <h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
		  </div>
		  </article>
		  <figure>
		  <img src="images/menu1.jpg" class="img-responsive">
		  </figure>
		  </a>
		  </div>
	</div>

	<!-- END Mains  -->
	
	<!-- START BURGER OF THE MONTH  -->
	<div class="bruschetta1"> 
		<h3> BURGER OF THE MONTH </h3>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#" data-toggle="modal" data-target="#myModal2">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			<figure>
				<img src="images/menu1.jpg" class="img-responsive">
			</figure>
			</a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
			<article>
				<div class="VegBur">
					<h4>Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
				<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
				</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
			  <article>
				  <div class="VegBur">
					<h4>  Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				  </div>
			  </article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#" data-toggle="modal" data-target="#myModal2">
				<article>
					<div class="VegBur">
						<h4>   Vegitarian Burger</h4>
						<img src="images/20x20-GF-1.png">
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
						<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
					</div>
				</article>
				<figure>
					<img src="images/menu1.jpg" class="img-responsive">
				</figure>
			</a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail" id="desserts">
			<a href="#" data-toggle="modal" data-target="#myModal2">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			<figure>
				<img src="images/menu1.jpg" class="img-responsive">
			</figure>
			</a>
		</div>
	</div>
	<!-- END BURGER OF THE MONTH  -->


	<!-- START desserts  -->
	<div class="bruschetta1"> 
		<h3> desserts </h3>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#" data-toggle="modal" data-target="#myModal2">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			<figure>
				<img src="images/menu1.jpg" class="img-responsive">
			</figure>
			</a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
			<article>
				<div class="VegBur">
					<h4>Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
				<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
				</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a  href="#">
			  <article>
				  <div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png" />
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				  </div>
			  </article>
			</a>
		</div> 
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#" data-toggle="modal" data-target="#myModal2">
				<article>
					<div class="VegBur">
						<h4>   Vegitarian Burger</h4>
						<img src="images/20x20-GF-1.png">
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
						<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
					</div>
				</article>
				<figure>
					<img src="images/menu1.jpg" class="img-responsive">
				</figure>
			</a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 itemDetail">
			<a href="#" data-toggle="modal" data-target="#myModal2">
			<article>
				<div class="VegBur">
					<h4>   Vegitarian Burger</h4>
					<img src="images/20x20-GF-1.png">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum   </p>
					<h5><strike>$ 6,050.00</strike>  $ 6,050</h5>
				</div>
			</article>
			<figure>
				<img src="images/menu1.jpg" class="img-responsive">
			</figure>
			</a>
		</div>
	</div>
	<!-- END desserts  -->

</div>
	  
	  
	  
	<div class="col-lg-4 col-md-4 col-sm-12 checkout mycheckoutmenu"> 
		<div class="for-stick">
			<div class="inner-stick">
				<div class="col-lg-3 col-md-3 col-sm-2">
					<div class="side_tab same_paddng">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs tabs-left">
							<li class="active" style = " height: 48px; margin-top: 17px;"><img class="img-responsive" src="images/1key.png"></li>
							<li><img class="img-responsive" src="images/1vegan.png">Vegan</li>
							<li><img class="img-responsive" src="images/1veg.png">Vegitarian</li>
							<li><img class="img-responsive" src="images/1gf.png"> Gluteen Free</li>
							<li><img class="img-responsive" src="images/1spicy.png">Spicy</li>
						</ul>
					</div>
               </div>
				<div class="col-lg-9 col-md-9 col-sm-10">
					<div class="left_tab">
						 <!-- Tab panes -->
						<div class="tab-content">
							<div class="tab-pane active" id="home">
								<div class="check-pg"> 
										<a href="">Checkout</a>
										<hr>
									<div class="card shopping-cart table-responsive">

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
															<div class="col-sm-3 number"> 
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
															<div class="col-sm-3 number"> 
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
															<div class="col-sm-3 number"> 
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
															<div class="col-sm-3 number"> 
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
</section>

<!---menu page end ---> 




  <!-- START Pop UP -->
      <div class="modal fade" id="myModal2" role="dialog">
      <div class="modal-dialog cartPopDetail">
      <!-- Modal content-->
	  <div class="selectDish">
	     <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
	
         <div class="modal-body">
         <div class="col-md-12 col-sm-12">
		 <h4> Arisanal burger </h4>
		 <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit perferendis aliquam, ducimus explicabo voluptas odit ad amet ratione delectus laborum  </p>
               <img src="images/itpop1.jpg" class="img-responsive">
   
               <div class="col-md-12">
                  <div class="cart_content">
                    <div class="my_checknew">
                        <form>
                           <h4>Add Cheese</h4>
						  <div class="form-group selctB">
						    <div class="col-md-8">
                              <label class="custom ">cheese
							  <input type="radio" checked="checked" name="radio">
							  <span class="checkmark"></span>
							</label>
							</div>
							  <div class="col-md-4">
							<p> +$55.0  </p>
                           </div>
						   </div>
						</form>
                     </div>
					 <div class="my_checknew">
                        <form>
                           <h4>Choose Side</h4>
						  
                           <div class="form-group">
						    <div class="col-md-8">
                              <label class="custom ">Potato wedges
							  <input type="radio" checked="checked" name="radio">
							  <span class="checkmark"></span>
							</label>
                           </div> </div> 
						   
						   <div class="form-group">
                              <div class="col-md-8">
							  <label class="custom ">Regular Fries
							  <input type="radio" checked="checked" name="radio">
							  <span class="checkmark"></span>
							</label>
                           </div>     </div> 
						   <div class="form-group">
                             <div class="col-md-8">
							 <label class="custom ">Regular Fries
							  <input type="radio" checked="checked" name="radio">
							  <span class="checkmark"></span>
							</label>
                           </div></div>
					</form>
                     </div>	 
					 <div class="my_checknewS">
                        <form>
                           <h4>Add a beverage</h4>
						   <div class="form-group">
                              <div class="col-md-8">
                            <label class="customClass">alpine water - 2L
							  <input type="checkbox" ">
							  <span class="checkmark"></span>
							</label>
							</div>
							<div class="col-md-4"> <p>  +$500.00</p> </div>
                           </div>
                           
						   <div class="form-group">
						   <div class="col-md-8">
                            <label class="customClass">alpine water - 2L
							  <input type="checkbox" ">
							  <span class="checkmark"></span>
							</label>
							</div>
							<div class="col-md-4"> <p>  +$500.00</p> </div>
                           </div>
						   <div class="form-group">
                               <div class="col-md-8">
                            <label class="customClass">alpine water - 2L
							  <input type="checkbox" ">
							  <span class="checkmark"></span>
							</label>
							</div>
							<div class="col-md-4"> <p>  +$500.00</p> </div>
                           </div>
                        
					 </form>
					 </div>		
					 <div class="my_checknewS">
                        <form>
                           <h4>Add a desert</h4>
						   <div class="form-group">
                              <div class="col-md-8">
                            <label class="customClass">Nuteela Crepe
							  <input type="checkbox" ">
							  <span class="checkmark"></span>
							</label>
							</div>
							<div class="col-md-4"> <p>  +$500.00</p> </div>
                           </div>
                           
						   <div class="form-group">
						   <div class="col-md-8">
                            <label class="customClass">alpine water - 2L
							  <input type="checkbox" ">
							  <span class="checkmark"></span>
							</label>
							</div>
							<div class="col-md-4"> <p>  $500.00</p> </div>
                           </div>
						   <div class="form-group">
                           <div class="col-md-8">
                            <label class="customClass">alpine water - 2L
							  <input type="checkbox" ">
							  <span class="checkmark"></span>
							</label>
							</div>
							<div class="col-md-4"> <p>  $500.00</p>
							</div>
                           </div>
                        
					 </form>
					 </div>	 
					 <div class="my_checknewS">
                        <form>
                           <h4>Add a note</h4>
						<div class="form-group">
						  
						  <textarea class="form-control rounded-0" id="exampleFormControlTextarea1" rows="10"></textarea>
						</div>                       
					 </form>
					 </div>
                     </div>
                  </div>
               </div>
               </div>
			   	 <div class="modal-footer">
          <div class="col-md-4">
               <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                                          <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="10" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div>
								</div>
									 <div class="col-md-5">
								 <button class="btn btnTheme">Add to cart</button>
								 </div>
								 	 <div class="col-md-3">
								  <p class="cart_price"><strong>$88.80</strong>	</p>
								  	 </div>
								  </div>
            </div>
			</div>
         </div>
		 </div>
      </div>
	  </div>
	  </div>
	  </div>
      <!--- End PopUp --->





<!-- START jS -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>

	  
<!-- START jQuery WOW -->
<script src="js/wow.js"></script>
<script>
var $li = $('.bottomenu li').click(function() {
	console.log("clicked");
    $li.removeClass('active');
    $(this).addClass('active');
	$(this).style('color', 'red');
});
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
<style>
	li.selected {
		color: red;
	}
</style>


<?php require_once("footer.php"); ?>