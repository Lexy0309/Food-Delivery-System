<?php require_once("config.php"); ?>
<div class="row">
	
	<!-- Profile Info and Notifications -->
	<div class="col-md-6 col-sm-8 clearfix">
		
		<ul class="user-info pull-left pull-none-xsm">
		
						<!-- Profile Info -->
			<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right 

-->				
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="assets/images/profile-picture.jpg" alt="" class="img-circle" width="44" />
					<?php echo $_SESSION['name']; ?>
				</a>
				
				<ul class="dropdown-menu">
					
					<!-- Reverse Caret -->
					<li class="caret"></li>
					
					<!-- Profile sub-links -->
					<li>
						<a href="profile.php">
							<i class="entypo-user"></i>
							Profile
						</a>
					</li>
                    
                    <li>
                        <a href="login.php?log=out">
                        	<i class="entypo-logout right"></i>
                            Log Out 
                        </a>
                    </li>

				</ul>
			</li>
		
		</ul>
	
	</div>
	
	
	<!-- Raw Links -->
	<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
		<ul class="list-inline links-list pull-right">
			
			
			<li class="sep"></li>

			<li>
				<a href="login.php?log=out">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
		
	</div>
	
</div>

<hr />