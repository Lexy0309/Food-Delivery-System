<?php require_once("header.php"); ?>

<div class="landingimage courier bgimage" style="background-image: url(img/bg-small.jpg);">
  <div class="inner">
    <div class="wdth">
      <div class="topvh">
        <div class="col40 left">
          <h1>Simple dummy title</h1>
          <h3>At foodomia, it's all about delivering what you desire for.</h3>
          <p style="display:none;"><a href="#" class="button">Learn More</a></p>
        </div>
        <?php if( !isset($_SESSION['id']) ){ ?>
        <div class="col40 right">
          <div class="frm">
            <form action="index.php?reset=ok" method="post" id="forform" style="display: none;">
              <h2 class="title">Forgot your password?</h2>
              <p>
              <input type="text" name="emailaddr" required=''>
              <label alt="Email" placeholder="Email">
              </p>
              <p>
                <input type="submit" class="button" value="Recover Password">
              </p>
              <p class="byproceeding"> Already have an account? <a href="javascript:;" id="login">Login</a> </p>
            </form>
            <form action="?log=in" method="post" id="logform" style="display: none;">
              <h2 class="title">Login</h2>
              <p>
              <input type="text" name="eml" required="">
              <label alt="Email" placeholder="Email">
              </p>
              <p>
              <input type="password" name="pswd" required="">
              <label alt="Password" placeholder="Password">
              </p>
              <p>
                <input type="submit" class="button" value="Log In">
              </p>
              <p class="byproceeding"> Not have an account? <a href="javascript:;" id="register">Register</a> </p>
              <p class="byproceeding" style="margin-top: 5px;"> Forgot Password? <a href="javascript:;" id="forgot">Recover</a> </p>
            </form>
            <script>
				$(document).ready(function(){
					$("input#phne").inputmask();
				});
				</script>
            <form action="?reg=ok" method="post" id="regform">
              <h2 class="title">Apply Today</h2>
              <p>
              <span class="left col50" style="position: relative;">
              <input  type="text" name="restoname" required="">
              <label alt="Restaurant Name" placeholder="Restaurant Name">
              </span> <span class="right col50">
              <input required="" type="text" name="contname">
              <label alt="Contact Name" placeholder="Contact Name">
              </span> <span class="clear" style="display: block;"></span>
              </p>
              <p>
              <span class="left col50" style="position: relative;">
              <input required="" type="text" name="phne" id="phne" data-inputmask="'alias': 'phone'">
              <label alt="Contact Phone" placeholder="Contact Phone">
              </span> <span class="right col50">
              <input type="email" name="emailaddr" required="">
              <label alt="Email" placeholder="Email">
              </span> <span class="clear" style="display: block;"></span>
              </p>
              <p>
              <input required="" type="text" name="restaddr">
              <label alt="Restaurant Address" placeholder="Restaurant Address">
              </p>
              <p>
                <textarea placeholder="Anything else we should know?" name="anythingelse"></textarea>
              </p>
              <p>
                <input type="submit" class="button" value="Get Started">
              </p>
              <p class="byproceeding"> Already have an account? <a href="javascript:;" id="login">Login</a> </p>
            </form>
          </div>
        </div>
        <?php } ?>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
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
<div class="threecol section home1 secprimery">
  <div class="wdth">
    <div class="section-title">
      <h2 class="title">Easiest Way To Start</h2>
    </div>
    <ul>
      <li>
        <div class="work-fonts">
          <div class="iconfont"><img src="img/ico1.png" alt="Icon" /></div>
          <!--<i class="fa fa-first-order"></i>--> 
        </div>
        <h4 class="title">Sign up & Set up</h4>
        <p>Give us a call or add your contact number so we can get in touch</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfont"><img src="img/ico2.png" alt="Icon" /></div>
        </div>
        <h4 class="title">We will guide you</h4>
        <p>Our team is there to guide you throughout and provide you all the basic information to get you started</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfont"><img src="img/ico3.png" alt="Icon" /></div>
        </div>
        <h4 class="title">We'll get your restaurant online</h4>
        <p>We'll place your order online and make it available to our community</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfont"><img src="img/ico4.png" alt="Icon" /></div>
          </i></div>
        <h4 class="title">Start Receiving Orders</h4>
        <p>We'll install an upcoming order terminal at your restaurant to help you receive and manage new orders</p>
      </li>
    </ul>
    <div class="clear"></div>
  </div>
</div>
<div class="teamup bgimage" style="background: url(https://image.delfoo.com/data/background/vendor/restaurant-home.jpg); background-position: 0;">
  <div style="background: #0009;">
    <div class="wdth">
      <div class="col100 left section" align="center">
        <h2 class="title">Team Up <span>With Confidence</span></h2>
        <p>Thousands of restaurants trust Foodomia to deliver fresh food, fast. With North America's most advanced food delivery technology, your customers will be delighted to order through Foodomia.</p>
        <p><a id="button" class="button" style=" text-decoration:none;">Join Now</a></p>
      </div>
      <div class="col50 right sec" style="display:none;"> <img src="http://www.ilovealabamafood.com/wp-content/uploads/2011/12/dish-detail-seafood-platter.png" alt="" /> </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
<div class="threecol section home1 white">
  <div class="wdth">
    <div class="section-title">
      <h2 class="title">Features</h2>
    </div>
    <ul>
      <li>
        <div class="work-fonts">
          <div class="iconfontsec feature"></div>
        </div>
        <h4 class="title">Live tracking</h4>
        <p>Know where your order is at all times, from the restaurant to your doorstep. Like never before!</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfontsec1 feature"></div>
        </div>
        <h4 class="title">No delivery area restriction</h4>
        <p>Our superfast delivery for food delivered fresh & on time, anywhere at any place</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfontsec2 feature"></div>
        </div>
        <h4 class="title">Increase your revenue</h4>
        <p>When your sustenance is offered in the app, new clients locate it What's more faithful clients might appreciate it All the more often.</p>
      </li>
    </ul>
    <ul class="clearb">
      <li>
        <div class="work-fonts">
          <div class="iconfontsec3 feature"></div>
        </div>
        <h4 class="title">Free registration</h4>
        <p>Watch your revenue increase quickly. Maximize your kitchen's efficiency and get the business you're missing out on.</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfontsec4 feature"></div>
        </div>
        <h4 class="title">Pick up location</h4>
        <p>We'll send orders to your kitchen. You concentrate on cooking great food. We take care of the rest.</p>
      </li>
      <li>
        <div class="work-fonts">
          <div class="iconfontsec5 feature"></div>
        </div>
        <h4 class="title">Get weekly report</h4>
        <p>You choose your prep times, control the pace of your kitchen, and manage the details for every order.</p>
      </li>
    </ul>
    <div class="clear"></div>
  </div>
</div>
<div class="section quote" style="display:none;">
  <div class="wdth">
    <div class="col50 marginauto">
      <ul>
        <li> <i class="fa fa-quote-left"></i>
          <p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>
          <p class="a">Andrew 26, Calgary</p>
        </li>
        <li> <i class="fa fa-quote-left"></i>
          <p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>
          <p class="a">Andrew 26, Calgary</p>
        </li>
        <li> <i class="fa fa-quote-left"></i>
          <p class="q">The food courier position allows me to set my own availability around my classes and exams. Picking up open shifts makes it really easy to earn some cash when I have spare time to drive.</p>
          <p class="a">Andrew 26, Calgary</p>
        </li>
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="mobrow bgimage" style="background:url(img/bgpatren.png);">
  <div class="wdth">
    <div class="left col40 textcenter" style="padding-top: 60px;"> <img src="img/appscreen.png" style="height: 255px;" alt=""> </div>
    <div class="right col60 section" style="padding-bottom: 0; padding-top: 140px;">
      <h2 class="title" style="margin: 0px 0 10px 0;">Effortless App</h2>
      <p style="margin: 0px 0 10px 0;">Our app is easy to access, your favorite restaurant is just a tap away.</p>
      <p style="margin: 0px 0 10px 0;">Download the app:</p>
      <p class="logos"> <a href="https://play.google.com/store/apps/details?id=com.dinosoftlabs.foodies.android" target="_blank"><img src="img/gplaystore.svg" alt="play store"></a> <a href="https://itunes.apple.com/us/app/foodies-food-delivery/id1453510709?mt=8" target="_blank"><img src="img/appstore.svg" alt="apple store"></a> </p>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="section newsletter mobrow" style="background: #282c35;">
  <div class="wdth">
    <div class="col40 marginauto">
      <h3 class="widgettitle">Want coupons, love notes, and updates from us?</h3>
      <p>Sign up for our newsletter</p>
      <form name="newsletter" action="" id="subsctiption">
        <div class="email-f">
          <input type="email" placeholder="Please enter your email.. " />
        
        <select name="city" class="cityies_selection">
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
			                        ?>
          <option value="<?php echo $cntry['Tax']['city']; ?>"><?php echo $cntry['Tax']['city']; ?></option>
          <?php
			                    }
			                    curl_close($ch);
			                    ?>
        </select>
        </div>
        <div class="buttf">
          <input type="submit" value="Subsctibe" class="subscribe_button" />
        </div>
      </form>
    </div>
    <div class="clear"></div>
  </div>
</div>
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