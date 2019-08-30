jQuery(document).ready(function(){
	jQuery('a[href^="#"]').on('click',function (e) {
		e.preventDefault();
		var target = this.hash,
		$target = jQuery(target);
		jQuery('html, body').stop().animate({
			'scrollTop': $target.offset().top
		}, 900, 'swing', function () {
			//window.location.hash = target;
		});
	});

	jQuery(window).scroll(function(){
	  var sticky = jQuery('header'),
	      scroll = jQuery(window).scrollTop();

	  if (scroll >= 100) sticky.addClass('fixed');
	  else sticky.removeClass('fixed');
	});

});


//revolution slider
var revapi;
jQuery(document).ready(function() {

	revapi = jQuery('.tp-banner').revolution({
		delay:7000,
		startwidth:1170,
		startheight:500,
		hideThumbs:10,
		navigationType:"none",     // use none, bullet or thumb
		navigationArrows:"none",     // nexttobullets, solo (old name verticalcentered), none
		/*fullWidth:"on",
		forceFullWidth:"on"*/
		fullWidth:"off",
		fullScreen:"on",
		fullScreenOffsetContainer: ""
	});

});	//ready

function popup( pagename ) {
	//alert($pageid);
	if( pagename == "login" ) {
		jQuery( '#loginpopup' ).show();
		jQuery( '#registerpopup' ).hide();
		jQuery( '#forgetpopup' ).hide();
	}
	if( pagename == "signup" ) {
		jQuery( '#loginpopup' ).hide();
		jQuery( '#registerpopup' ).show();
		jQuery( '#forgetpopup' ).hide();
	}
	if( pagename == "forget" ) {
		jQuery( '#loginpopup' ).hide();
		jQuery( '#registerpopup' ).hide();
		jQuery( '#forgetpopup' ).show();
	}
	/*jQuery.ajax({
		type: "GET",
		url: "getcontent.php",
		data: {
			'pagename' : $pagename
		},
		dataType: "text",
		success: function(response){
			jQuery("#page_content").show();
			document.getElementById("page_content_sec").innerHTML = response; //sending data to show on list
		}
	});*/
}

function nextsttep() {

	var firstname = document.getElementById('firstname').value;
	var lastname = document.getElementById('lastname').value;
	var emailaddr = document.getElementById('emailaddr').value;
	var paswd = document.getElementById('paswd').value;

	if( firstname != "" && lastname != "" && emailaddr != "" && paswd != "" ) {
		jQuery(".signup_step1").hide();
		jQuery(".signup_step2").show();
		jQuery(".signup_step3").hide();
		jQuery(".signup_step4").hide();
	} else {
		alert("Please fill all fields");
	}
}

function sendmeacode(phonenum) {

	var phne = document.getElementById('phne').value;

	if( phne != "" ) {
		jQuery.ajax({
			type: "GET",
			url: "getcontent.php?sendme=code",
			data: {
				'phnnum' : phonenum
			},
			dataType: "text",
			success: function(response){
				if( response == "200" ) {
					jQuery(".signup_step1").hide();
					jQuery(".signup_step2").hide();
					jQuery(".signup_step3").show();
					jQuery(".signup_step4").hide();
				} else {
					alert(response);
				}
			}
		});

	} else {
		alert("Phone number required");
	}

	
}

function verifyphone(phonenum, codetoverify) {

	var confirmation_code = document.getElementById('confirmation_code').value;

	if( confirmation_code != "" ) {
		jQuery.ajax({
			type: "GET",
			url: "getcontent.php?verify=code",
			data: {
				'phnnum' : phonenum,
				'codetoverify' : codetoverify
			},
			dataType: "text",
			success: function(response){
				if( response == "200" ) {
					jQuery(".signup_step1").hide();
					jQuery(".signup_step2").hide();
					jQuery(".signup_step3").hide();
					jQuery(".signup_step4").show();
				} else {
					alert(response);
				}
			}
		});

	} else {
		alert("Confirmation code required");
	} 
	
}


//Form Validations
$(document).ready(function(){

	//account info
	$("form#accoutinfo").validate({
	    rules: {
	      fname: "required",
	      lname: "required"
	    },
	    messages: {
	      fname: "Required",
	      lname: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//change password 
	$("form#changepass").validate({
	    rules: {
	      oldpas: "required",
	      newpas: "required",
	      renewpas: {
		      equalTo: "#newpas"
		    }
	    },
	    messages: {
	      oldpas: "Required",
	      newpas: "Required",
	      renewpas: "Not matched"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//address form 
	$("form#adrsfrm").validate({
	    rules: {
	      str: "required",
	      apt: "required",
	      cty: "required",
	      stt: "required",
	      zp: {
		      required: true,
		      number: true
		    },
	      cntry: "required",
	      ins: "required",
	      address: "required"
	    },
	    messages: {
	      str: "Required",
	      apt: "Required",
	      cty: "Required",
	      stt: "Required",
	      zp: "Required",
	      cntry: "Required",
	      ins: "Required",
	      address: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//payment methods
	$("form#paymethd").validate({
	    rules: {
	      cardname: "required",
	      cardnum: {
		      required: true,
		      maxlength: 19
		    },
	      cardcvc:  {
		      required: true,
		      number: true,
		      maxlength: 4
		    },
	      cardmn:  {
		      required: true,
		      number: true
		    },
	      cardyr:  {
		      required: true,
		      number: true
		    }
	    },
	    messages: {
	      cardname: "Required",
	      cardnum: "Required",
	      cardcvc: "Required",
	      cardmn: "Required",
	      cardyr: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//login form
	$("form#logform").validate({
	    rules: {
	      eml: {
	        required: true,
	        email: true
	      },
	      pswd: "required"
	    },
	    messages: {
	      eml: "Please enter valid email address",
	      pswd: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//register form
	$("form#regform").validate({
	    rules: {
	      restoname: "required",
	      contname: "required",
	      phne: "required",
	      emailaddr: {
	        required: true,
	        email: true
	      },
	      restaddr: "required",
	      anythingelse: "required"
	    },
	    messages: {
	      restoname: "Required",
	      contname: "Required",
	      phne: "Required",
	      emailaddr: "Please enter valid email address",
	      restaddr: "Required",
	      anythingelse: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//forget form
	$("form#forform").validate({
	    rules: {
	      emailaddr: {
	        required: true,
	        email: true
	      }
	    },
	    messages: {
	      emailaddr: "Please enter valid email address"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//hotel deals
	$("form#hoteldealsfrm").validate({
	    rules: {
	      nm: "required",
	      dsc: "required",
	      expdate: "required",
	      prce:  {
		      required: true,
		      number: true
		    },
	      image: "required",
	      image1: "required",
		  starting_datetime: "required",
		  ending_datetime: "required"
	    },
	    messages: {
	      nm: "Required",
	      dsc: "Required",
	      expdate: "Required",
	      prce: "Required",
	      image: "Required",
	      image1: "Required",
		  starting_datetime: "required",
		  ending_datetime: "required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//bank info for hotel
	$("form#hotelbnkinffrm").validate({
	    rules: {
	      nm: "required",
	      trnum: {
		      required: true,
		      number: true
		    },
	      bnkno: {
		      required: true,
		      number: true
		    },
	      accno: {
		      required: true,
		      number: true
		    },
	    },
	    messages: {
	      nm: "Required",
	      trnum: "Required",
	      bnkno: "Required",
	      accno: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//hotel coupon codes
	$("form#couponcdefrm").validate({
	    rules: {
	      coupon_code: "required",
	      discount: {
		      required: true,
		      number: true
		    },
	      expire_date: "required",
		  limit: "required"
	    },
	    messages: {
	      coupon_code: "Required",
	      discount: "Required",
	      expire_date: "Required",
		  limit: "required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});
	
	
	
		
	//resetpass
	$("form#resetpass").validate({
	    rules: {
	      pass: {
			  required: true,
		      minlength: 6  
		  },
	      Newpass: {
			  required: true,
		      minlength: 6  
		  }
	    },
	    messages: {
	      pass: "min 6 characters",
	      Newpass: "min 6 characters"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//hotel edit information
	$("form#hoteleditinfo").validate({
	    rules: {
	      name: "required",
	      slogan: "required",
	      about: "required",
	      delivery_fee: {
		      required: true,
		      number: true
		    },
	      phone: "required",
	      menu_style: "required",
	      timezone: "required",
	      state: "required",
	      country: "required",
	      city: "required",
	      google_analytics: {
		      required: true,
		      number: true
		    },
	      zip: "required",
	      lat: {
		      required: true,
		      number: true
		    },
	      long: {
		      required: true,
		      number: true
		    },
	      ot1: "required",
	      ct1: "required",
	      ot2: "required",
	      ct2: "required",
	      ot3: "required",
	      ct3: "required",
	      ot4: "required",
	      ct4: "required",
	      ot5: "required",
	      ct5: "required",
	      ot6: "required",
	      ct6: "required",
	      ot7: "required",
	      ct7: "required"
	    },
	    messages: {
	      name: "Required",
	      slogan: "Required",
	      about: "Required",
	      delivery_fee: "Required",
	      phone: "Required",
	      menu_style: "Required",
	      timezone: "Required",
	      state: "Required",
	      country: "Required",
	      city: "Required",
	      google_analytics: "Required",
	      zip: "Required",
	      lat: "Required",
	      long: "Required",
	      ot1: "Required",
	      ct1: "Required",
	      ot2: "Required",
	      ct2: "Required",
	      ot3: "Required",
	      ct3: "Required",
	      ot4: "Required",
	      ct4: "Required",
	      ot5: "Required",
	      ct5: "Required",
	      ot6: "Required",
	      ct6: "Required",
	      ot7: "Required",
	      ct7: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//add new menu form
	$("form#adnewmenfrm").validate({
	    rules: {
	      menu_name: "required",
	      menu_dsc: "required"
	    },
	    messages: {
	      menu_name: "Required",
	      menu_dsc: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//add new menu item form
	$("form#adnewmenuitmfrm").validate({
	    rules: {
	      menu_name: "required",
	      menu_dsc: "required",
	      menu_price: "required"
	    },
	    messages: {
	      menu_name: "Required",
	      menu_dsc: "Required",
	      menu_price: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//add new extra section for menu item 
	$("form#adextrsctfrm").validate({
	    rules: {
	      sec_name: "required"
	    },
	    messages: {
	      sec_name: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//add new extra section item
	$("form#adextrsctitmfrm").validate({
	    rules: {
	      menu_name: "required",
	      menu_price: "required"
	    },
	    messages: {
	      menu_name: "Required",
	      menu_price: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

	//add new extra section item
	$("form#docfrm").validate({
	    rules: {
	      docdesc: "required",
	      docfile: "required"
	    },
	    messages: {
	      docdesc: "Required",
	      docfile: "Required"
	    },
	    submitHandler: function(form) {
	      form.submit();
	    }
	});

});


$(document).ready(function(){
	$( "#datepicker" ).datepicker();
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$( "#datepicker" ).datepicker( "option", "prevText", "&laquo;" );
	$( "#datepicker" ).datepicker( "option", "nextText", "&raquo;" );
	
	$( "#datepicker1" ).datepicker();
	$( "#datepicker1" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$( "#datepicker1" ).datepicker( "option", "prevText", "&laquo;" );
	$( "#datepicker1" ).datepicker( "option", "nextText", "&raquo;" );

	$( "#datepicker2" ).datepicker();
	$( "#datepicker2" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$( "#datepicker2" ).datepicker( "option", "prevText", "&laquo;" );
	$( "#datepicker2" ).datepicker( "option", "nextText", "&raquo;" );

	$('.timepicker').timepicker({ 'timeFormat': 'H:i:s' });
} );



function openmenu(menuid) {
	jQuery(".mainmenus_items").slideUp();
	jQuery(".mainmenus_items#"+menuid).slideDown();
}


jQuery(document).ready(function(){
	//add new menu
	jQuery("#addnewmenu").on("click", function(){
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addmenuheading'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add New Menu</h3><form action='dashboard.php?p=manage_menu&add=menu' method='post' class='form addmenuform' id='adnewmenfrm'><div class='cl33'><div class='col33 left'> <p><input type='text' name='menu_name' id='menu_name' placeholder='Name'></p> </div> <div class='col33 left'> <p><input type='text' name='menu_dsc' id='menu_dsc' placeholder='Description'></p> </div> <div class='col33 left'> <p><input type='submit' value='Add Menu'></p> </div> <div class='clear'></div></div></form>");
	});

	//edit main menu
	jQuery(".main_menu_edit").on("click", function(){
		var menuid = jQuery(this).attr("data-menu-id");
		var menuname = jQuery(this).attr("data-menu-name");
		var menudesc = jQuery(this).attr("data-menu-description");
	
		jQuery("#main_menu_edit_div_"+menuid).html("<form action='dashboard.php?p=manage_menu&edit=menu' method='post' class='form addmenuform' id='adnewmenfrm'><input type='hidden' name='rid' id='rid' value='"+menuid+"'><div class='cl33'><div class='col33 left'> <p><input type='text' name='menu_name' id='menu_name' value='"+menuname+"' required=''><label alt='Name' placeholder='Name'></label></p> </div> <div class='col33 left'> <p><input type='text' name='menu_dsc' id='menu_dsc' value='"+menudesc+"' required=''><label alt='Description' placeholder='Description'></label></p> </div> <div class='col33 left'> <p><input type='submit' value='Update Menu'></p> </div> <div class='clear'></div></div></form>");
	});

	//add new menu item
	jQuery(".addnewmenu_item").on("click", function(){
		var menuid = jQuery(this).attr("data-menu-id");
		//alert(menuid);
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addmenuheading'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add New Menu Item</h3><form action='dashboard.php?p=manage_menu&add=menuitem' method='post' class='form addmenuform' id='adnewmenuitmfrm'><input type='hidden' name='menuid' id='menuid' value='"+menuid+"'><div class='col50 left twocll'> <p><input type='text' name='menu_name' id='menu_name' placeholder='Name'></p> </div> <div class='col50 right twocll'> <p><input type='text' name='menu_dsc' id='menu_dsc' placeholder='Description'></p> </div> <div class='col50 left twocll'> <p><input type='text' name='menu_price' id='menu_price' placeholder='Price'></p> </div> <div class='col50 right twocll'> <p><input type='submit' value='Add Menu Item'></p> </div> <div class='clear'></div></form>");
	});

	//edit main menu item
	jQuery(".main_menu_item_edit").on("click", function(){
		var mainmenuidd = jQuery(this).attr("data-main-menu-id");
		var menuid = jQuery(this).attr("data-menu-id");
		var menuname = jQuery(this).attr("data-menu-name");
		var menudesc = jQuery(this).attr("data-menu-description");
		var menuprce = jQuery(this).attr("data-menu-price");
		var menuoutofstock = jQuery(this).attr("data-out-of-stock");
		if(menuoutofstock=="1")
		{
			var menuoutofstock = "checked";
		}
		
		jQuery("#main_menu_item_edit_div_"+menuid).html("<form action='dashboard.php?p=manage_menu&edit=menuitem' method='post' class='form addmenuform' id='adnewmenuitmfrm'><input type='hidden' name='rid' id='rid' value='"+menuid+"'><input type='hidden' name='menuid' id='menuid' value='"+mainmenuidd+"'><div class='col50 left twocll'> <p><input type='text' name='menu_name' id='menu_name' value='"+menuname+"' required='Name'><label alt='Name' placeholder='Name'></label></p> </div> <div class='col50 right twocll'> <p><input type='text' name='menu_dsc' id='menu_dsc' value='"+menudesc+"' required=''><label alt='Description' placeholder='Description'></label></p> </div> <div class='col50 left twocll'> <p><input type='text' name='menu_price' value='"+menuprce+"' id='menu_price' required='Price'><label alt='Price' placeholder='Price'></label><p style='margin-top: 0px !important'><input type='checkbox' name='outofstock' id='require_items' value='1' "+menuoutofstock+" /> Out Of Stock</p></p> </div> <div class='col50 right twocll'> <p><input type='submit' value='Update Menu Item'></p></div> <div class='clear'></div></form>");
	});

	//add new menu extra section
	jQuery(".addnewmenu_extrasection").on("click", function(){
		var restoid = jQuery(this).attr("data-restaurant-id");
		var restomenuitem = jQuery(this).attr("data-menu-item-id");
		//alert(menuid);
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addnewmenu_extrasection'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add Menu Extra Section</h3><form action='dashboard.php?p=manage_menu&add=menuextrasection' method='post' class='form addmenuform' id='adextrsctfrm'> <input type='hidden' name='restoid' id='restoid' value='"+restoid+"'> <input type='hidden' name='restomenuitem' id='restomenuitem' value='"+restomenuitem+"'> <p> <input type='text' name='sec_name' id='sec_name' placeholder='Section Name'> </p> <p style='text-align:left;'> <input type='checkbox' name='require_items' id='require_items' value='1' /> Required? <span style='display:block;font-size:11px;margin-top:5px;color:#aaa;'>If checked, this section will require to fill.</span></p> <p> <input type='submit' value='Add Extra Section'> </p></form>");
	});

	//edit main menu item section
	jQuery(".main_menu_item_section_edit").on("click", function(){
		var restoid = jQuery(this).attr("data-restaurant-id");
		var restomenuitem = jQuery(this).attr("data-menu-item-id");
		
		var sectidd = jQuery(this).attr("data-section-id");
		var sectnmmm = jQuery(this).attr("data-section-name");
		var sectreq = jQuery(this).attr("data-section-req");
		if( sectreq == "1" ) {
			var sectr = "checked";
		} else {
			var sectr = "";
		}
	
		jQuery("#main_menu_item_section_edit_div_"+sectidd).html("<form action='dashboard.php?p=manage_menu&edit=menuextrasection' method='post' class='form addmenuform' id='adextrsctfrm'> <input type='hidden' name='rid' id='rid' value='"+sectidd+"' /> <input type='hidden' name='restoid' id='restoid' value='"+restoid+"'> <input type='hidden' name='restomenuitem' id='restomenuitem' value='"+restomenuitem+"'> <p> <input type='text' name='sec_name' id='sec_name' value='"+sectnmmm+"' required=''> <label alt='Section Name' placeholder='Section Name'></label></p> <p style='text-align:left;'> <input type='checkbox' name='require_items' id='require_items' "+sectr+" value='1' /> Required? <span style='display:block;font-size:11px;margin-top:5px;color:#aaa;'>If checked, this section will require to fill.</span></p> <p> <input type='submit' value='Update Extra Section'> </p></form>");
	});

	//add new menu section extra item
	jQuery(".addnewmenu_extraitem").on("click", function(){
		var menuextrasectionid = jQuery(this).attr("data-menu-extra-section-id");
		//alert(menuid);
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addnewmenu_extraitem'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add Section Extra Item</h3><form action='dashboard.php?p=manage_menu&add=menuextraitem' method='post' class='form addmenuform' id='adextrsctitmfrm'><input type='hidden' name='menu_extra_sectionid' id='menu_extra_sectionid' value='"+menuextrasectionid+"'> <p> <input type='text' name='menu_name' id='menu_name' placeholder='Name'> </p> <p> <input type='text' name='menu_price' id='menu_price' placeholder='Price'></p><p> <input type='submit' value='Add Section Extra Item'> </p></form>");
	});

	//edit main menu item section item
	jQuery(".main_menu_item_section_item_edit").on("click", function(){
		var menuextrasectionid = jQuery(this).attr("data-menu-extra-section-id");
		
		var sect_item_idd = jQuery(this).attr("data-menu-section-item-id");
		var sect_item_name = jQuery(this).attr("data-menu-section-item-name");
		var sect_item_price = jQuery(this).attr("data-menu-section-item-price");
	
		jQuery("#main_menu_item_section_item_edit_div_"+sect_item_idd).html("<form action='dashboard.php?p=manage_menu&edit=menuextraitem' method='post' class='form addmenuform' id='adextrsctitmfrm'><input type='hidden' name='rid' id='rid' value='"+sect_item_idd+"'><input type='hidden' name='menu_extra_sectionid' id='menu_extra_sectionid' value='"+menuextrasectionid+"'> <p> <input type='text' name='menu_name' id='menu_name' value='"+sect_item_name+"' required=''><label alt='Name' placeholder='Name'></label> </p> <p> <input type='text' name='menu_price' id='menu_price' value='"+sect_item_price+"' required=''><label alt='Price' placeholder='Price'></label></p><p> <input type='submit' value='Update Section Extra Item'> </p></form>");
	});

	//add instructions during purchase
	jQuery(".instructn_heading").on("click", function(){
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addmenuheading'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add Instructions</h3><p><input type='text' name='instructions' id='instructions' placeholder='Instructions'></p>");
	});
	
});

jQuery(document).ready(function(){
	jQuery(".exsection_me input[type='checkbox']").on("change", function(){
		var name = jQuery(this).attr("data-item-name");
		var quantity = jQuery(this).attr("data-item-quantity");
		var price = jQuery(this).attr("data-item-price");

		if( jQuery(this).is(":checked") ) {
			jQuery(this).parent().find(".filds").html("<input type='hidden' name='menu_extra_item_name[]' value='"+name+"'><input type='hidden' name='menu_extra_item_quantity[]' value='"+quantity+"'><input type='hidden' name='menu_extra_item_price[]' value='"+price+"'>");
		} else {
			jQuery(this).parent().find(".filds").html("");
		}
	});

	jQuery(".exsection_me input[type='radio']").on("change", function(){
		var sectionid = jQuery(this).attr("data-section-id");
		var name = jQuery(this).attr("data-item-name");
		var quantity = jQuery(this).attr("data-item-quantity");
		var price = jQuery(this).attr("data-item-price");

		jQuery(".filds_radio_"+sectionid+" #na").val(name);
		jQuery(".filds_radio_"+sectionid+" #qu").val(quantity);
		jQuery(".filds_radio_"+sectionid+" #pr").val(price);
	});
});

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function addtocart(formid, userid, rest_id) {
	var data = $(formid).serialize();

	jQuery.ajax({
		type: "POST",
		url: "addcookie.php?add=pro&userid="+userid+"&rest_id="+rest_id,
		data: data,
		dataType: "text",
		success: function(response){
			//console.log(response);
			//document.getElementById('response').innerHTML = response;
			location.reload(true);
			//$(".cartbox").toggleClass('show');
		}
	});
}

function removefromcart() {
	/*document.cookie = 'cartitem' + 
    '=;';*/
	jQuery.ajax({
		url: "addcookie.php?remve=ok",
		success: function(response){
			//console.log(response);
			//document.getElementById('response').innerHTML = response;
			location.reload(true);
			//$(".cartbox").toggleClass('show');
		}
	});
}