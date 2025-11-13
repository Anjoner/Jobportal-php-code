<?php
session_start();
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    die("Access denied");
    header("Location: ../../auth/login.php");
    exit;
}

?>

<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>Jobportal website </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="site.webmanifest">
		<link rel="shortcut icon" type="image/x-icon" href="../../assets/img/jlogo.png">

		<!-- CSS here -->
        <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="../../assets/css/flaticon.css">
        <link rel="stylesheet" href="../../assets/css/price_rangs.css">
        <link rel="stylesheet" href="../../assets/css/slicknav.css">
        <link rel="stylesheet" href="../../assets/css/animate.min.css">
        <link rel="stylesheet" href="../../assets/css/magnific-popup.css">
        <link rel="stylesheet" href="../../assets/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="../../assets/css/themify-icons.css">
        <link rel="stylesheet" href="../../assets/css/slick.css">
        <link rel="stylesheet" href="../../assets/css/nice-select.css">
        <link rel="stylesheet" href="../../assets/css/style.css">

        <style type="text/css"> 
            .row h2{
                text-align: center;
                padding: 10px;
            }
        </style>
      <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width:100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border:1px solid #ddd; padding:8px; text-align: left; height: 100px; }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #f9f9f9; }
        td a{

            color: black;
            font-size: 18px;
        }
        td a:hover{
            color: #fb246a;
            font-weight: bold;
        }
        #keyword { width: 300px; padding: 8px; }
        .single-services {
            cursor: pointer;
        }

        #pagination{
            text-align: center;

        }
        #pagination button{
            color: red;
            /*background: black;*/
            font-size: 25px;
            font-weight: bold;
        }
        .table-wrapper{
            max-height: 500px;
            overflow-y: auto;

            }
        #result p{
            text-align: center;
            padding: 20px;
            font-weight: bold;
            font-size: 20px;
        }
        /* Make the image fully responsive */
        .carousel-inner img {
        width: 100%;
        height: 100%;
        }
        .carousel-caption h3, .carousel-caption p{
            color: red;
            font-weight: bold;

        }


      </style>
   </head>

   <body>
    
    <?php include 'header.html' ?> 

    <main><br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                  <div id="demo" class="carousel slide" data-ride="carousel">
                    <ul class="carousel-indicators">
                      <li data-target="#demo" data-slide-to="0" class="active"></li>
                      <li data-target="#demo" data-slide-to="1"></li>
                      <li data-target="#demo" data-slide-to="2"></li>
                    </ul>
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img src="../../assets/img/jobseeker/ab.jpg" alt="image not found" width="1100" height="700">
                        <div class="carousel-caption">
                          <h3>No more board job searching</h3>
                          <p>just simple visit this website and get jobs </p>
                        </div>   
                      </div>
                      <div class="carousel-item">
                        <img src="../../assets/img/jobseeker/aa.jpg" alt="image not found" width="1100" height="700">
                        <div class="carousel-caption">
                          <h3>No more job news paper</h3>
                          <p>simple visit and get jobs from this website</p>
                        </div>   
                      </div>
                      <div class="carousel-item">
                        <img src="../../assets/img/jobseeker/aaa.jpg" alt="image not found" width="1100" height="700">
                        <div class="carousel-caption">
                          <h3>No more pending for jobs</h3>
                          <p>Simple get job status from this website</p>
                        </div>   
                      </div>
                    </div>
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </a>
                  </div> <br>
                </div>
                
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <h2>Job status</h2>
                    <canvas id="jobStatusChart" height="150"></canvas>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <h2>Applications Per Jobs</h2>
                    <canvas id="appsPerJobChart" height="150"></canvas>   
                </div>
            </div>
        </div>
    </main> <br>

    <?php include "footer.html" ?>



<script>
    function loadJobNoticeCount() {
      fetch('../../backend/process.php?action=job_notice_count')
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          const badge = document.getElementById('notice-count');
          if (badge && data.count > 0) {
            badge.innerText = data.count;
            badge.style.display = 'inline-block';
          } else if (badge) {
            badge.style.display = 'none';
          }
        });
    }

    // Call the function after page load
    document.addEventListener('DOMContentLoaded', loadJobNoticeCount);
    setInterval(loadJobNoticeCount, 10000); // Refresh every 10s

</script>


<script>
    document.addEventListener("DOMContentLoaded", () => {
      fetch("../../backend/process.php?action=employee_data_analysis")
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          renderBarChart("appsPerJobChart", data.jobs.titles, data.jobs.application_counts, "Applications per Job");
          renderPieChart("jobStatusChart", ["Approved", "Rejected", "Pending"], [data.status.approved, data.status.rejected, data.status.pending]);
        });

      function renderBarChart(id, labels, data, label) {
        new Chart(document.getElementById(id), {
          type: "bar",
          data: {
            labels: labels,
            datasets: [{
              label: label,
              data: data,
              backgroundColor: "skyblue"
            }]
          }
        });
      }

      function renderPieChart(id, labels, data) {
        new Chart(document.getElementById(id), {
          type: "pie",
          data: {
            labels: labels,
            datasets: [{
              data: data,
              backgroundColor: ["yellow", "red", "green"]
            }]
          }
        });
      }
    });
</script>


  <!-- JS here -->
	
        <script src="../../assets/js/chart.js"></script>
    
        <!-- All JS Custom Plugins Link Here here -->
        <script src="../../assets/js/vendor/modernizr-3.5.0.min.js"></script>
        <!-- Jquery, Popper, Bootstrap -->
            <script src="../../assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="../../assets/js/popper.min.js"></script>
        <script src="../../assets/js/bootstrap.min.js"></script>
        <!-- Jquery Mobile Menu -->
        <script src="../../assets/js/jquery.slicknav.min.js"></script>

        <!-- Jquery Slick , Owl-Carousel Plugins -->
        <script src="../../assets/js/owl.carousel.min.js"></script>
        <script src="../../assets/js/slick.min.js"></script>
        <script src="../../assets/js/price_rangs.js"></script>
        
        <!-- One Page, Animated-HeadLin -->
        <script src="../../assets/js/wow.min.js"></script>
            <script src="../../assets/js/animated.headline.js"></script>
        <script src="../../assets/js/jquery.magnific-popup.js"></script>

        <!-- Scrollup, nice-select, sticky -->
        <script src="../../assets/js/jquery.scrollUp.min.js"></script>
        <script src="../../assets/js/jquery.nice-select.min.js"></script>
            <script src="../../assets/js/jquery.sticky.js"></script>
        
        <!-- contact js -->
        <script src="../../assets/js/contact.js"></script>
        <script src="../../assets/js/jquery.form.js"></script>
        <script src="../../assets/js/jquery.validate.min.js"></script>
        <script src="../../assets/js/mail-script.js"></script>
        <script src="../../assets/js/jquery.ajaxchimp.min.js"></script>
        
        <!-- Jquery Plugins, main Jquery -->    
        <script src="../../assets/js/plugins.js"></script>
        <script src="../../assets/js/main.js"></script>
        
    </body>
</html>