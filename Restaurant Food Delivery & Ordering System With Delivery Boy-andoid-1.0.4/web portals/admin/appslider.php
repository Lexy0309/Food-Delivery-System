<?php 

require_once("config.php"); 

if($_SESSION['role']=="1")
{
    @header('Location: index.php');
}

?>
<?php if(isset($_SESSION['id'])) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once("meta.php"); ?>

	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.0.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	
</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View All App Sliders</h2></div>
        <div class="pull-right"><a style="position: relative; top: 10px; cursor:pointer;" onClick='add_slide()' class='btn btn-default'>Add App Slider</a></div>
        <div class="clearfix"></div>
        <br>

        <div class="row">
        <?php  
        if(isset($_GET['del']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            $headers = array(
                "Accept: application/json",
                "Content-Type: application/json",
            );

            $data = array(
                "id" => $id
            );

            $ch = curl_init( $baseurl.'/deleteAppSliderImage' );

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $return = curl_exec($ch);

            $json_data = json_decode($return, true);
            //var_dump($json_data);
            
            //echo $json_data['key'];
            
            $curl_error = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            //echo $json_data['code'];
            //die;

            if($json_data['code'] !== 200){
                echo "<div class='alert alert-danger'>Error in deleting app slide, try again later..</div>";

            } else {
                //echo "<div class='alert alert-success'>Successfully payment method updated..</div>";
                @header("Location: appslider.php");
                echo "<script>window.location='appslider.php'</script>";
                
            }

            curl_close($ch);
        } 
		
        $url = $baseurl."/showAppSliderImages";
        $params = "";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $json_data = json_decode($result, true);
        //var_dump($json_data);
        $i=0;
        foreach($json_data['msg'] as $str => $data) {
            //var_dump($data);
            if(!empty($data['AppSlider']['id'])) {
                ?>
                <div class="col-md-3">
                    <a data-id="<?php echo $data['AppSlider']['id']; ?>" data-link="<?php echo $image_baseurl.$data['AppSlider']['image']; ?>" style="cursor:pointer;position: absolute;top: -8px;right: 25px;background: red;font-size: 8px;color: #fff;width: 16px;height: 16px;line-height: 16px;text-align: center;border-radius: 100%;" class="editslide"><i class="entypo-pencil"></i></a>
                    <a href="appslider.php?del=ok&id=<?php echo $data['AppSlider']['id']; ?>" style="position: absolute;top: -8px;right: 8px;background: red;font-size: 11px;color: #fff;width: 16px;height: 16px;line-height: 16px;text-align: center;border-radius: 100%;" onClick="return confirm('Do you really want to delete app slider?');">&times;</a>
                    <div style="background-image: url(<?php echo $image_baseurl.$data['AppSlider']['image']; ?>); 
                    background-repeat: no-repeat;
                    background-size: cover; 
                    background-position: center; 
                    height: 150px;
                    margin-bottom: 30px;"></div>
                </div>
                <?php
            }
            $i++;
        }

        curl_close($ch);
        ?>
        </div>


        <script type="text/javascript">
        var responsiveHelper;
        var breakpointDefinition = {
            tablet: 1024,
            phone : 480
        };
        var tableContainer;
        
            jQuery(document).ready(function($)
            {
                tableContainer = $("#table-1");
                
                tableContainer.dataTable({
                    "sPaginationType": "bootstrap",
                    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "bStateSave": true,
                    
        
                    // Responsive Settings
                    bAutoWidth     : false,
                    fnPreDrawCallback: function () {
                        // Initialize the responsive datatables helper once.
                        if (!responsiveHelper) {
                            responsiveHelper = new ResponsiveDatatablesHelper(tableContainer, breakpointDefinition);
                        }
                    },
                    fnRowCallback  : function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        responsiveHelper.createExpandIcon(nRow);
                    },
                    fnDrawCallback : function (oSettings) {
                        responsiveHelper.respond();
                    }
                });
                
                $(".dataTables_wrapper select").select2({
                    minimumResultsForSearch: -1
                });
            });
        </script>
         
            
        </div>
    </div>    


<script type="text/javascript">
function add_slide() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}

jQuery(document).ready(function(){
    jQuery(".editslide").on("click", function(){
        var idd = jQuery(this).attr('data-id');
        var link = jQuery(this).attr('data-link');

        jQuery('input#slideid').val(idd);
        jQuery('img#imglinkk').attr("src", link);

        jQuery('#modal-8').modal('show', {backdrop: 'static'});
    });
});
</script>
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>


<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Slide</h4>
            </div>
            
            <div class="modal-body">
                <?php 
                    if(isset($_GET['insert'])){
                        if($_GET['insert']=="ok") {

                            $user_id = $_SESSION['id'];

                            $image_base = file_get_contents($_FILES['upload_image']['tmp_name']);
                            $image = base64_encode($image_base);

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "user_id" => $user_id, 
                                "image" => array("file_data" => $image)
                                );

                               $ch = curl_init( $baseurl.'/addAppSliderImage' );
								
								
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                               curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               $return = curl_exec($ch);

                               $curl_error = curl_error($ch);
                               $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                               
                               //var_dump($http_code);
                               
                               curl_close($ch);

                               if($http_code !== 200){
                                 echo "<div class='alert alert-danger'>".$curl_error."</div>";
                               
                               }else{
                                 //echo "<div class='alert alert-success'>Successfully submitted..</div>";
                                 echo "<script>window.location='appslider.php';</script>";
                               }

                                
                        }
                    }   
                ?>

                <form action="appslider.php?insert=ok" id="docfrm" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label">Image</label>
                        
                        <div class="col-sm-6">
                            
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                    <img src="http://placehold.it/200x150" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Select image</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="upload_image" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6" style="margin-bottom: 30px;">
                            <input type="submit" class="btn btn-primary" value="Add App Slider">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- Modal 8-->
<div class="modal fade custom-width in" id="modal-8">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Slide</h4>
            </div>
            
            <div class="modal-body">
                <?php 
                    if(isset($_GET['edit'])){
                        if($_GET['edit']=="ok") {

                            $user_id = $_SESSION['id'];
                            $slideid = $_POST['slideid'];

                            $image_base = file_get_contents($_FILES['upload_image']['tmp_name']);
                            $image = base64_encode($image_base);

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "id" => $slideid,
                                "user_id" => $user_id, 
                                "image" => array("file_data" => $image)
                                );

                               $ch = curl_init( $baseurl.'/addAppSliderImage' );

                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                               curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               $return = curl_exec($ch);

                               $curl_error = curl_error($ch);
                               $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                               
                               //var_dump($http_code);
                               
                               curl_close($ch);

                               if($http_code !== 200){
                                 echo "<div class='alert alert-danger'>".$curl_error."</div>";
                               
                               }else{
                                 //echo "<div class='alert alert-success'>Successfully submitted..</div>";
                                 echo "<script>window.location='appslider.php';</script>";
                               }

                                
                        }
                    }   
                ?>

                <form action="appslider.php?edit=ok" id="docfrm" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="" id="slideid" name="slideid" />
                    <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label">Image</label>
                        
                        <div class="col-sm-6">
                            
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                    <img id="imglinkk" src="http://placehold.it/200x150" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Select image</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="upload_image" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6" style="margin-bottom: 30px;">
                            <input type="submit" class="btn btn-primary" value="Update App Slider">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>



	<link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css">
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css">

	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/datatables/TableTools.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.js"></script>
	<script src="assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
	<script src="assets/js/datatables/lodash.min.js"></script>
	<script src="assets/js/datatables/responsive/js/datatables.responsive.js"></script>
	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
    <script src="assets/js/fileinput.js"></script>

</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>