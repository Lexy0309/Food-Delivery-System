<div class="sidebar-menu" style="min-height: 100vh;">
  <header class="logo-env"> 
    
    <!-- logo -->
    <div class="logo"> <a href="" style="font-size: 35px;font-weight: bold;"> Foodies </div>
    
    <!-- logo collapse icon 
    <div class="sidebar-collapse"> 
        <a href="#" class="sidebar-collapse-icon with-animation">
      add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition 
      <i class="entypo-menu"></i> 
      </a> 
    </div>-->
    
    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
    <div class="sidebar-mobile-menu visible-xs"> <a href="#" class="with-animation"><!-- add class "with-animation" to support animation --> 
      <i class="entypo-menu"></i> </a> </div>
  </header>
  <ul id="main-menu" class="">
    <!-- add class "multiple-expanded" to allow multiple submenus to open --> 
    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" --> 
    <!-- Search Bar -->

      <li> <a href="dashboard.php"><i class="entypo-users"></i> <span class="title">Dashboard</span> </a></li>
    

      <?php
          if($_SESSION['role']=="0")
          {
            ?>
              <li> <a href="users.php"><i class="entypo-users"></i> <span class="title">All User List</span> </a></li>
            <?php   
          }
      ?>      

      
      <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Manage Restaurants</span></a> 

        <li> <a href="restaurants.php"><i class="entypo-home"></i> <span class="title">Restaurants</span> </a></li>
        <li> <a href="restaurantRequest.php"><i class="entypo-air"></i> <span class="title">Restaurant Request</span> </a></li>
      </li>
    

      <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Manage Orders</span></a> 

        <li> <a href="orders.php?filter=AllOrder"><i class="entypo-air"></i> <span class="title">All Orders</span> </a></li>
        <li> <a href="orders.php"><i class="entypo-air"></i> <span class="title">Active Orders</span> </a></li>
        <li> <a href="orders.php?filter=completed"><i class="entypo-air"></i> <span class="title">Completed Orders</span> </a></li>
        <li> <a href="orders.php?filter=restaurantRejected"><i class="entypo-air"></i> <span class="title">Restaurant Rejected</span> </a></li>

      
      </li>

      <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Manage Rider</span></a> 
         <li> <a href="riders.php"><i class="entypo-users"></i> <span class="title">Our Riders</span></a> </li>
	       <li> <a href="ridersRequest.php"><i class="entypo-users"></i> <span class="title">Rider Request</span></a> </li>
        <li> <a href="chat.php"><i class="entypo-chat"></i> <span class="title">Chat Messages</span></a></li>
      

      
	   
      <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Manage Shift</span></a> 
         <li><a href="Rider-shifts.php"><i class="entypo-clock"></i> <span class="title">Rider Shift</span></a> </li>
         <li><a href="shifts.php"><i class="entypo-clock"></i> <span class="title">Open Shift</span></a> </li>
      
	    </li>
	  
     <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Manage Coupons</span></a> 
	       <li><a href="Coupons.php"><i class="entypo-clock"></i> <span class="title">Restaurant Coupons</span></a> </li>
         <li><a href="Foodomia_Coupons.php"><i class="entypo-clock"></i> <span class="title">Foodomia Coupons</span></a> </li>
	  </li>
      
        <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Earning</span></a> 
           <li><a href="earning.php"><i class="entypo-clock"></i> <span class="title">All Earnings</span></a> </li>
      </li>
    
      <?php
          if($_SESSION['role']=="0")
          {
            ?>
            	
              
              
              <li style="background:rgba(69, 74, 84, 0.3);"> <a href="#" style="padding: 5px 10px;"><span class="title">Web & App setting</span></a> 
                  <li> <a href="appslider.php"><i class="entypo-chat"></i> <span class="title">Mobile App Sliders</span></a> </li>
                  <li> <a href="tax.php"><i class="entypo-chat"></i> <span class="title">Tax Information</span></a> </li>
                  <li> <a href="currency.php"><i class="entypo-chat"></i> <span class="title">Currency</span></a> </li>
                  <li> <a href="adminusers.php"><i class="entypo-users"></i> <span class="title">Admin Users</span></a> </li>
              </li>
            <?php   
          }
      ?>
      
      
      
      
	  
    
  </ul>
</div>
