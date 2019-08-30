

<?php require_once("header.php"); ?>



<div class="landingimage courier bgimage" style="background-image: url(img/bg.jpg);"><div class="inner"><div class="wdth">

	<div class="topvh">

		<div class="col40 left">

			<h1>At foodies</h1>

			<h3>it's all about delivering what you desire for.</h3>

			<p><a href="#" class="button">Learn More</a></p>

		</div>

		<?php if( !isset($_SESSION['id']) ){ ?>

		<div class="col40 right">

			<div class="frm">

				<form action="index.php?reset=ok" method="post" id="forform" style="display: none;">

					<h2 class="title">Forget Passowrd?</h2>

					<p>

						<input placeholder="Email" type="text" name="emailaddr">

					</p>

					<p>

						<input type="submit" class="button" value="Recover Password">

					</p>

					<p class="byproceeding">

						Already have an account? <a href="javascript:;" id="login">Login</a>

					</p>

				</form>

				<form action="?log=in" method="post" id="logform" style="display: none;">

					<h2 class="title">Earn More</h2>

					<p>

						<input placeholder="Email" type="text" name="eml">

					</p>

					<p>

						<input placeholder="Password" type="password" name="pswd">

					</p>

					<p>

						<input type="submit" class="button" value="Log In">

					</p>

					<p class="byproceeding">

						Not have an account? <a href="javascript:;" id="register">Register</a>

					</p>

					<p class="byproceeding" style="margin-top: 5px;">

						Forgot Password? <a href="javascript:;" id="forgot">Recover</a>

					</p>

				</form>

				<script>

				$(document).ready(function(){

					$("input#phne").inputmask();

				});

				</script>

				<form action="?reg=ok" method="post" id="regform">

					<h2 class="title">Register Now</h2>

					<p>

						<span class="left col50" style="position: relative;"><input placeholder="First Name" type="text" name="firstname"></span>

						<span class="right col50"><input placeholder="Last Name" type="text" name="lastname"></span>

						<span class="clear" style="display: block;"></span>

					</p>

					<p>

						<span class="left col50" style="position: relative;"><input placeholder="Email" type="email" name="emailaddr"></span>

						<span class="right col50"><input placeholder="Phone" type="text" name="phne" id="phne" data-inputmask="'alias': 'phone'"></span>

						<span class="clear" style="display: block;"></span>

					</p>

					<p>

						<span class="left col60">

							<span class="left col50" style="position: relative;"><select name="city">

			                    <option value="">City</option> 

			                    <?php 

			                    $url = $baseurl."/showCountries";

			                    $params = "";



			                    $ch = curl_init($url);



			                    curl_setopt($ch, CURLOPT_POST, 1);

			                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

			                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			                    $result = curl_exec($ch);

			                    $json_data = json_decode($result, true);

			                    //var_dump($json_data);

			                    foreach($json_data['cities'] as $cntry) {

			                        ?><option value="<?php echo $cntry['Tax']['city']; ?>"><?php echo $cntry['Tax']['city']; ?></option><?php

			                    }

			                    curl_close($ch);

			                    ?>

			                </select></span>

							<span class="right col50" style="position: relative;"><select name="state">

			                    <option value="">State</option> 

			                    <?php 

			                    $url = $baseurl."/showCountries";

			                    $params = "";



			                    $ch = curl_init($url);



			                    curl_setopt($ch, CURLOPT_POST, 1);

			                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

			                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			                    $result = curl_exec($ch);

			                    $json_data = json_decode($result, true);

			                    //var_dump($json_data);

			                    foreach($json_data['states'] as $cntry) {

			                        ?><option value="<?php echo $cntry['Tax']['state']; ?>"><?php echo $cntry['Tax']['state']; ?></option><?php

			                    }

			                    curl_close($ch);

			                    ?>

			                </select></span>

							<span class="clear" style="display: block;"></span>

						</span>

						<span class="right col40"><select name="country">

							<option value="">Country</option>

							<?php 

		                    $url = $baseurl."/showCountries";

		                    $params = "";



		                    $ch = curl_init($url);



		                    curl_setopt($ch, CURLOPT_POST, 1);

		                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		                    $result = curl_exec($ch);

		                    $json_data = json_decode($result, true);

		                    //var_dump($json_data);

		                    foreach($json_data['countries'] as $cntry) {

		                        ?><option value="<?php echo $cntry['Tax']['country']; ?>"><?php echo $cntry['Tax']['country']; ?></option><?php

		                    }

		                    curl_close($ch);

		                    ?>

						</select></span>

						<span class="clear" style="display: block;"></span>

					</p>

					<p>

						<input placeholder="What address will you normally start your shift?" type="text" name="addrtostart">

					</p>

					<p>

						<textarea name="howuhear" placeholder="How did you hear about the food courier position?"></textarea>

					</p>

					<p>

						<input type="submit" class="button" value="Get Started">

					</p>

					<p class="byproceeding">

						Already have an account? <a href="javascript:;" id="login">Login</a>

					</p>

				</form>

			</div>

		</div>

		<?php } ?>

		<div class="clear"></div>

	</div>

</div></div></div>



<script type="text/javascript">

jQuery(document).ready(function(){

	jQuery("a#forgot").on("click", function(){

		jQuery('form#logform').hide();

		jQuery('form#regform').hide();

		jQuery('form#forform').show();

	});

	jQuery("a#login").on("click", function(){

		jQuery('form#regform').hide();

		jQuery('form#logform').show();

		jQuery('form#forform').hide();

	});

	jQuery("a#register").on("click", function(){

		jQuery('form#logform').hide();

		jQuery('form#regform').show();

		jQuery('form#forform').hide();

	});

});

</script>





<div class="howto section"><div class="wdth">

	<h2 class="title textcenter">How To Get Started</h2>

	<ul style="text-align:center;">

		<li>

			<div class="col80">

				<span class="fa fa-info-circle"></span>

			</div>

			<div class="col80 ">

				<h3>Sign up</h3>

				<p>Join and get you self-incorporated into our supportive team.</p>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col80">

				<span class="fa fa-comment"></span>

			</div>

			<div class="col80 ">

				<h3>Download app</h3>

				<p>Download the application and you're about to get ready to deliver food as soon as our team approve your account </p>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col80">

				<span class="fa fa-usd"></span>

			</div>

			<div class="col80">

				<h3>Start earning</h3>

				<p>Begin gaining with simply straightforward advances</p>

			</div>

			<div class="clear"></div>

		</li>

	</ul>

	<div class="clear"></div>

</div></div>





<div class="section whythis"><div class="wdth">

	<div class="col50 left">

		<h2 class="title">Why foodies?</h2>

		<p>We are Pakistan's fastest growing online food delivery network. We provide part-time work designed to fit your lifestyle. Choose from part-time, full-time, or casual hours. Plan ahead and drive as much or as little as you'd like.</p>

	</div>

	<div class="col50 right quote">

		<ul>

			<li>

				<i class="fa fa-quote-left"></i>

				<p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>

				<p class="a">Tanzil 26, Lahore</p>

			</li>

			<li>

				<i class="fa fa-quote-left"></i>

				<p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>

				<p class="a">Junaid 26, Lahore</p>

			</li>

			<li>

				<i class="fa fa-quote-left"></i>

				<p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>

				<p class="a">Iqra 26, Lahore</p>

			</li>

		</ul>

	</div>

	<div class="clear"></div>

</div></div>





<div class="howto wyn section"><div class="wdth">

	<h2 class="title textcenter">What You Need?</h2>

	<ul>

		<li>

			<div class="col20 left"><span class="digit">1</span></div>

			<div class="col80 left">

				<h3>A Valid License</h3>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col20 left"><span class="digit">2</span></div>

			<div class="col80 left">

				<h3>A Smartphone</h3>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col20 left"><span class="digit">3</span></div>

			<div class="col80 left">

				<h3>A Working Vehicle</h3>

			</div>

			<div class="clear"></div>

		</li>

		<li>

			<div class="col20 left"><span class="digit">3</span></div>

			<div class="col80 left">

				<h3>Proof Of Work Eligibility</h3>

			</div>

			<div class="clear"></div>

		</li>

	</ul>

	<div class="clear"></div>

</div></div>





<div class="threecol textcenter section" style="background:white;"><div class="wdth">

	<ul>

		<li>

			<i class="fa fa-calendar-o"></i>

			<h3>Flexible schedule</h3>

			<p>We do provide flexible schedule and comfortable working hours, there is an easygoing process of working.</p>

		</li>

		<li>

			<i class="fa fa-dollar"></i>

			<h3>Earning opportunities</h3>

			<p>Guess what, once you have collaborated with us you will get a great opportunity to earn with us.</p>

		</li>

		<li>

			<i class="fa fa-calendar-o"></i>

			<h3>Weekly payments</h3>

			<p>What else you could have asked for, once you get weekly payments other than waiting for the whole month.</p>

		</li>

	</ul>

	<div class="clear"></div>

</div></div>



<?php //reset password

if(isset($_GET['reset']) && !empty($_POST['emailaddr'])) {

		

		$email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);

		

		$headers = array(

			"Accept: application/json",

			"Content-Type: application/json"

		);



		$data = array(

			"email" => $email,

			"role" => "rider"

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