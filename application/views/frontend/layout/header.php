<!DOCTYPE HTML>
<html>
   <head>
      <title>Online Store</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
      <link href="<?php echo base_url('resources/css/bootstrap-3.1.1.min.css') ?>" rel='stylesheet' type='text/css' />
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="<?php echo base_url('resources/js/jquery.min.js') ?>"></script>
      <script src="<?php echo base_url('resources/js/bootstrap.min.js') ?>"></script>
      <!-- font-Awesome -->
      <link href="<?php echo base_url('resources/css/font-awesome.css') ?>" rel="stylesheet">
      <!-- font-Awesome -->
      <!-- ============================  Navigation Start =========================== -->
      
      <script type="text/javascript">
         var BASE_URL = "<?php echo base_url(); ?>";
         var SITE_URL = "<?php echo site_url(); ?>";
         
         $(document).ready(function() {
         if (window.matchMedia("(max-width: 767px)").matches) {
         	$(".navbar-nav a.home_button").css("display", "none");
            } else {
                $('.navbar-nav li.green').removeClass('green');
            }
         });
      </script>
   </head>
   <body>
      <div class="navbar navbar-inverse-blue navbar">
         <div class="navbar-inner">
            <div class="container">
               
               <a class="brand" href="<?php echo base_url() ?>frontend/products" >Products</a>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="show_cart" href="<?php echo base_url()?>frontend/products/show_cart_details"><i title="Cart" class="fa fa-shopping-cart"></i></a>
               <span class="show_no"><?php echo $_SESSION['cart_value']?></span>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?php if($_SESSION['userid']!= '') { ?><a class="brand" href="<?php echo base_url() ?>frontend/user/logout" >Logout</a> <?php } ?>
               <!-- end pull-right -->
               <div class="clearfix"> </div>
               
            </div>
            <!-- end container -->
         </div>
         <!-- end navbar-inner -->
      </div>