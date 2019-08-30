<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=no">
<title>Foodomia Food Delivery & Take Out</title>
</head>
<body class="home">
<link rel="stylesheet" type="text/css" href="https://foodomia.pk/css/style.css?1524482498" />
<script src="js/jquery-1.12.4.js"></script> 
<script>
 jQuery(document).ready(function($) {
    $(".forgetlinka").click(function(){
	$("#forget").show();
	$("#page_content_sec").hide();	
		
})
$(".signinform").click(function(){
	$("#forget").hide();
	$("#page_content_sec").show();	
	})
});
 </script> 
<script src="js/jquery.validate.min.js"></script> 
<script>
    
    // Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='registration']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      pswd: "required",
     
	  
      email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
	  emailaddr: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
     
    },
    // Specify validation error messages
    messages: {
      pswd: "Please enter your Password",
      email: "Please enter a valid email address",
	 },
	// Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
  
   $("form[name='forgetfrm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
    
	  
      emailaddr: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
	 
    },
    // Specify validation error messages
    messages: {
       emailaddr: "Please enter a valid email address",
	 },
	// Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
});
    </script>
<div class="popup_container_login">
  <div class="login-content"><img src="img/2.png" alt="logo" width="220"> </div>
  <div id="page_content_sec">
    <div class="login_form">
      <div class="right col100 rightside">
        <div class="header"> Sign Into Your Account </div>
        <div class="form">
          <form action="#" id="loginfrm" method="post" novalidate="novalidate" name="registration">
            <p>
              <input name="email" id="eml" required="" aria-required="true" type="email">
              <label alt="Email Address" placeholder="Email Address"></label>
            </p>
            <p>
              <input name="pswd" id="pswd" required="" aria-required="true" type="password">
              <label alt="Password" placeholder="Password"></label>
            </p>
            <input name="returnlink" value="http://foodomia.pk/" type="hidden">
            <p>
              <button type="submit">Login</button>
            </p>
          </form>
        </div>
        <div class="footer">Forget your password? <a class="forgetlinka">Forget?</a> </div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div id="forget">
    <div class="login_form">
      <div class="right col100 rightside">
        <div class="header"> Forget Password? </div>
        <div class="form">
          <form name="forgetfrm" action="#" id="forgetfrm" method="post" novalidate>
            <p>
              <input name="emailaddr" id="emailaddr" required aria-required="true" type="email">
              <label alt="Email Address" placeholder="Email Address"> </label>
            </p>
            <p>
              <button type="submit">Recover It</button>
            </p>
          </form>
        </div>
        <div class="footer"> Already have an account? <a class="signinform">Sign in!</a> </div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
<style>
.login_form .col100.rightside .form {
	padding: 40px 20px;
	padding-bottom: 15px;
}
.popup_container_login {
	position: relative;
	margin: 8% auto;
	/* padding: 80px 20px; */
	background: #fff;
	box-shadow: 0 0 5px 0 rgba(0,0,0,0.5);
	border-radius: 5px;
	max-width: 300px;
}
.login_form .col100.rightside .header {
	position: relative;
	background: none;
	border-bottom: 1px solid #dbd9d6;
	height: 54px;
	margin: 0;
	line-height: 54px;
	text-align: left;
	font-size: 18px;
	color: #605d57;
	padding: 0 20px;
	text-align: center;
}

.login-content {
	text-align: center;
	padding-top: 20px;
}
#forget{ display:none;}
.login_form .col100.rightside .footer {
	position: relative;
	height: 54px;
	background: #eeede9;
	border-top: 1px solid #dbd9d6;
	text-align: center;
	font-size: 12px;
	margin: 0;
	padding: 0 20px;
	line-height: 54px;
}
.signinform {
	color: #be2c2c;
	font-weight: bold;
	cursor:pointer;
}
.right.col100.rightside {
	width: 100%;
}
.forgetlinka {
	font-weight: bold;
	cursor: pointer;
}
.forgetlinka {
	color: #be2c2c;
	
}

</style>
</body>
</html>