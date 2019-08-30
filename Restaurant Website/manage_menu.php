<?php if( isset($_SESSION['id']) && $_SESSION['user_type'] == "hotel" ){ ?>

<h2 class="title">Manage Menu</h2>
<?php 
//print_r($_SESSION);
if(isset($_GET['add'])) {
	if($_GET['add']=="menu") {

		$user_id = $_SESSION['id'];
		//$user_id = "5";
		//$restaurant_id = $_SESSION['id'];
		$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
		$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
		$image = "";

		if( !empty($name) && !empty($description) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"user_id" => $user_id,
				"name" => $name,
				"description" => $description
				//"image" => $image
			);

			$ch = curl_init( $baseurl.'/addMenu' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully menu added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu = end


	if($_GET['add']=="menuitem") {
        
        //$user_id = htmlspecialchars($_SESSION['id'], ENT_QUOTES);
		$restaurant_menu_id = htmlspecialchars($_POST['menuid'], ENT_QUOTES);
		$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
		$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
		$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
		

		if( !empty($restaurant_menu_id) && !empty($name) && !empty($description) && !empty($price) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"restaurant_menu_id" => $restaurant_menu_id,
				"name" => $name,
				"description" => $description,
				"price" => $price,
				"out_of_order" => "0"
			);

			$ch = curl_init( $baseurl.'/addMenuItem' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully menu added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu item = end


	if($_GET['add'] == "menuextrasection") {

		//$restaurant_id = htmlspecialchars($_POST['restoid'], ENT_QUOTES);
	    $user_id = $_SESSION['id'];
		$name = htmlspecialchars($_POST['sec_name'], ENT_QUOTES);
		$restaurant_menu_item_id = htmlspecialchars($_POST['restomenuitem'], ENT_QUOTES);
		
		if(isset($_POST['require_items'])) {
			$required = "1";
		} else {
			$required = "0";
		}

		if( !empty($name) && !empty($restaurant_menu_item_id) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"user_id" => $user_id,
				"name" => $name,
				"restaurant_menu_item_id" => $restaurant_menu_item_id,
				"required" => $required
			);

			$ch = curl_init( $baseurl.'/addMenuExtraSection' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding menu extra section, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully menu extra section added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu extra section = end


	if($_GET['add'] == "menuextraitem") {

		$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
		$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
		$restaurant_menu_extra_section_id = htmlspecialchars($_POST['menu_extra_sectionid'], ENT_QUOTES);

		if( !empty($name) && !empty($restaurant_menu_extra_section_id) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"name" => $name,
				"price" => $price,
				"restaurant_menu_extra_section_id" => $restaurant_menu_extra_section_id
			);

			$ch = curl_init( $baseurl.'/addMenuExtraItem' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu extra item = end
}

/// add section  =end

if(isset($_GET['edit'])) {
	if($_GET['edit']=="menu") {
		
		$id = $_POST['rid'];
		$user_id = $_SESSION['id'];
		$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
		$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);

		if( !empty($name) && !empty($description) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"id" => $id,
				"user_id" => $user_id,
				"name" => $name,
				"description" => $description
			);

			$ch = curl_init( $baseurl.'/addMenu' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully menu added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu = end


	if($_GET['edit']=="menuitem") {
        
        $id = $_POST['rid'];
		$restaurant_menu_id = htmlspecialchars($_POST['menuid'], ENT_QUOTES);
		$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
		$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
		$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
		$outofstock = htmlspecialchars(@$_POST['outofstock'], ENT_QUOTES);
		if($outofstock=="")
		{
			$outofstock = "0";
		}
	
		if( !empty($restaurant_menu_id) && !empty($name) && !empty($description) && !empty($price) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"id" => $id,
				"restaurant_menu_id" => $restaurant_menu_id,
				"name" => $name,
				"description" => $description,
				"price" => $price,
				"out_of_order" => $outofstock
			);
			//echo json_encode($data);
			$ch = curl_init( $baseurl.'/addMenuItem' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully menu added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu item = end


	if($_GET['edit'] == "menuextrasection") {

		$id = $_POST['rid'];
	    $user_id = $_SESSION['id'];
		$name = htmlspecialchars($_POST['sec_name'], ENT_QUOTES);
		$restaurant_menu_item_id = htmlspecialchars($_POST['restomenuitem'], ENT_QUOTES);
		
		if(isset($_POST['require_items'])) {
			$required = "1";
		} else {
			$required = "0";
		}
		
		if( !empty($name) && !empty($restaurant_menu_item_id) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"id" => $id,
				"user_id" => $user_id,
				"name" => $name,
				"restaurant_menu_item_id" => $restaurant_menu_item_id,
				"required" => $required
			);

			$ch = curl_init( $baseurl.'/addMenuExtraSection' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding menu extra section, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully menu extra section added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu extra section = end


	if($_GET['edit'] == "menuextraitem") {
		
		$id = $_POST['rid'];
		$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
		$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
		$restaurant_menu_extra_section_id = htmlspecialchars($_POST['menu_extra_sectionid'], ENT_QUOTES);

		if( !empty($name) && !empty($restaurant_menu_extra_section_id) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"id" => $id,
				"name" => $name,
				"price" => $price,
				"restaurant_menu_extra_section_id" => $restaurant_menu_extra_section_id
			);

			$ch = curl_init( $baseurl.'/addMenuExtraItem' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=error");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
				@header("Location: dashboard.php?p=manage_menu&action=success");
				echo "<script>window.location='dashboard.php?p=manage_menu&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=manage_menu&action=error");
			echo "<script>window.location='dashboard.php?p=manage_menu&action=error'</script>";
		} //

	} //menu extra item = end
}

//edit section  = end



	$user_id = $_SESSION['id'];

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);

	$data = array(
		"user_id" => $user_id
	);

	$ch = curl_init( $baseurl.'/showMainMenus' );

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$return = curl_exec($ch);

	$json_data = json_decode($return, true);
    //var_dump($json_data);

	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	//echo $json_data['code'];
	//die;

	if($json_data['code'] !== 200){
		//echo "<div class='alert alert-danger'>Error in fetching menus, try again later..</div>";
		?>
<div style="margin: 70px 0;" class="textcenter"> <img src="img/nomenu.png" style="display: inline-block;" alt="" />
  <h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
</div>
<?php
	} else {
		///show restaurant menu  .. 
		echo "<ul class='mainmenus'>";
		foreach( $json_data['msg'] as $str => $val ) {
			//var_dump($val);
			$cc = count($val['RestaurantMenu']);
			$currency=$val['Currency']['symbol'];
			foreach ($val['RestaurantMenu'] as $menukey => $menuvalue) {
				//var_dump($menuvalue);

				$restaurant_id = $menuvalue['restaurant_id'];
				?>
<li>
  <div class="inrdiv" onClick="openmenu(<?php echo $menuvalue['id']; ?>)">
    <div class="left col80">
      <h3><?php echo $menuvalue['name']; ?> <span class="editlink"><a href="javascript:;" data-menu-id="<?php echo $menuvalue['id']; ?>" data-menu-name="<?php echo $menuvalue['name']; ?>" data-menu-description="<?php echo $menuvalue['description']; ?>" class="main_menu_edit"><i class="fa fa-pencil"></i></a></span></h3>
      <p><?php echo $menuvalue['description']; ?></p>
    </div>
    <div class="right textcenter">
      <p class="icon"><img src="img/down-arrow.png" width="20" alt="" /></p>
    </div>
    <div class="clear"></div>
  </div>
  <div id="main_menu_edit_div_<?php echo $menuvalue['id']; ?>"></div>
  <?php //show restaurant menu item .. ?>
  <ul class="mainmenus_items" id="<?php echo $menuvalue['id']; ?>">
    <?php $totalrows = count($menuvalue['RestaurantMenuItem']);
						foreach ($menuvalue['RestaurantMenuItem'] as $key => $value) {
							//var_dump($value);
							?>
    <li>
      <div class="left col80">
        <h3><?php echo $value['name']; ?> <span class="editlink"> <a href="javascript:;" data-main-menu-id="<?php echo $menuvalue['id']; ?>" data-menu-id="<?php echo $value['id']; ?>" data-menu-name="<?php echo $value['name']; ?>" data-menu-description="<?php echo $value['description']; ?>" data-menu-price="<?php echo $value['price']; ?>" data-out-of-stock="<?php echo $value['out_of_order']; ?>" class="main_menu_item_edit"><i class="fa fa-pencil"></i></a> </span></h3>
        <p><?php echo $value['description']; ?></p>
      </div>
      <div class="right textcenter">
        <p class="price"> <?php echo $currency; echo $value['price']; ?>
          <?php $outofstock = $value['out_of_order'];
									
									if($outofstock == 1){
										
										echo "<br><p style='color:red;'>"."Out Of Stock"."</p>";
										} else{
											
											echo "<br><p style='color:green;'>"."Available"."</p>";
											
											}
									
									 ?>
        </p>
      </div>
      <div class="clear"></div>
      <div id="main_menu_item_edit_div_<?php echo $value['id']; ?>"></div>
      <ul class="menuextrasection_ul">
        <?php //show restaurant extra menu section
								foreach ($value["RestaurantMenuExtraSection"] as $sectionkey => $sectionvalue) {
									//var_dump($sectionvalue);
									?>
        <li>
          <h4><?php echo $sectionvalue['name']; if($sectionvalue['required']=='1') { echo "<span style='margin-left:10px; font-size: 14px; color: #aaa;'>(Required)</span>"; } ?> <span class="editlink"> <a href="javascript:;" data-section-id="<?php echo $sectionvalue['id']; ?>" data-section-name="<?php echo $sectionvalue['name']; ?>" data-section-req="<?php echo $sectionvalue['required']; ?>" data-restaurant-id="<?php echo $restaurant_id; ?>" data-menu-item-id="<?php echo $value['id']; ?>" class="main_menu_item_section_edit"><i class="fa fa-pencil"></i></a> </span></h4>
          <div id="main_menu_item_section_edit_div_<?php echo $sectionvalue['id']; ?>"></div>
          <ul class="menuextrasection_item">
            <?php foreach ($sectionvalue['RestaurantMenuExtraItem'] as $keyy => $valuee) {
												?>
            <li>
              <div class="left col70"><?php echo $valuee['name']; ?> <span style="margin-top:2px;" class="editlink"> <a href="javascript:;" data-menu-section-item-id="<?php echo $valuee['id']; ?>" data-menu-section-item-name="<?php echo $valuee['name']; ?>" data-menu-section-item-price="<?php echo $valuee['price']; ?>" data-menu-extra-section-id="<?php echo $sectionvalue['id']; ?>" class="main_menu_item_section_item_edit"><i class="fa fa-pencil"></i></a> </span></div>
              <div class="right col30 textright"><?php echo $currency; echo $valuee['price']; ?></div>
              <div class="clear"></div>
              <div id="main_menu_item_section_item_edit_div_<?php echo $valuee['id']; ?>"></div>
            </li>
            <?php
											} ?>
            <li style="padding: 0;">
              <div class="addmenu" style="margin-top: 0;">
                <h3 class="addnewmenu_extraitem" data-menu-extra-section-id="<?php echo $sectionvalue['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add Section Extra Item</h3>
              </div>
            </li>
          </ul>
          <div class="clear"></div>
        </li>
        <?php
								}
								?>
        <li style="padding: 0;">
          <div class="addmenu" style="margin-top: 0;">
            <h3 class="addnewmenu_extrasection" data-restaurant-id="<?php echo $restaurant_id; ?>" data-menu-item-id="<?php echo $value['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add Menu Extra Section</h3>
          </div>
        </li>
      </ul>
      <div class="clear"></div>
      <?php //show restaurant extra menu section = end ?>
    </li>
    <?php
						} ?>
    <li style="padding: 0;">
      <div class="addmenu" style="margin-top: 0;">
        <h3 class="addnewmenu_item" data-menu-id="<?php echo $menuvalue['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add New Menu Item</h3>
      </div>
    </li>
  </ul>
  <?php //}
					//show restaurant menu item = end... ?>
</li>
<?php
			}
		}
		echo "</ul>";
		?>
<div class="addmenu">
  <h3 id="addnewmenu"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add New Menu</h3>
</div>
<?php
		///show restaurant menu = end  .. 
	}

	curl_close($ch);
?>
<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
