<?php
session_start();
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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
   </head>

   <body>
    
    <?php include 'header.html' ?> <br>

    <main> <br>
        <div class="container">
            <div class="row">

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <h2>User Analysis</h2>
                    <canvas id="userRoleChart" height="150"></canvas><br>

                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <h2>Job Status Analysis</h2>
                    <canvas id="jobStatsChart" height="150"></canvas><br>                 
                </div>
            </div>  
            <div class="row">
                <div class="col-12"> <br>
                    <h2>Application Status Analysis</h2>
                    <canvas id="applicationStatsChart" height="150"></canvas><br>

                    <h2>Job category Analysis</h2>
                    <canvas id="categoryChart" height="150"></canvas><br><br>
                    <h2>Applied job Analysis</h2>
                    <canvas id="topJobsChart" height="150"></canvas><br><br>
                    <h2>Job Trending Analysis</h2>
                    <canvas id="jobTrendsChart" height="150"></canvas> <br><br>                    
                </div>
            </div>
        </div>

    </main> <br>
    <?php  include 'footer.html'?>

  <!-- JS here -->


<script>
    document.addEventListener("DOMContentLoaded", () => {
      fetch("../../backend/process.php?action=admin_data_analysis")
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          renderLineChart("jobTrendsChart", data.months, data.job_counts, "Jobs Posted");
          renderBarChart("applicationStatsChart", ["Accepted", "Rejected", "Pending"], [data.applications.accepted, data.applications.rejected, data.applications.pending], "Applications");

          renderBarChart("jobStatsChart", ["Approved", "Rejected", "Pending"], [data.jobs_status.approved, data.jobs_status.rejected, data.jobs_status.pending], "Applications");

          renderPieChart("userRoleChart", ["Admin", "Employer", "Jobseeker"], [data.users.admin, data.users.employer, data.users.jobseeker]);
          renderHorizontalBarChart("categoryChart", data.categories.names, data.categories.counts, "Jobs per Category");
          renderBarChart("topJobsChart", data.top_jobs.titles, data.top_jobs.applications, "Top Applied Jobs");
        });

      function renderLineChart(id, labels, data, label) {
        new Chart(document.getElementById(id), {
          type: "line",
          data: {
            labels: labels,
            datasets: [{ label: label, data: data, borderColor: "blue", fill: false }]
          }
        });
      }

      function renderBarChart(id, labels, data, label) {
        new Chart(document.getElementById(id), {
          type: "bar",
          data: {
            labels: labels,
            datasets: [{ label: label, data: data, backgroundColor: ["green", "red", "orange"] }]
          }
        });
      }

      function renderPieChart(id, labels, data) {
        new Chart(document.getElementById(id), {
          type: "pie",
          data: {
            labels: labels,
            datasets: [{ data: data, backgroundColor: ["purple", "blue", "gray"] }]
          }
        });
      }

      function renderHorizontalBarChart(id, labels, data, label) {
        new Chart(document.getElementById(id), {
          type: "bar",
          data: {
            labels: labels,
            datasets: [{ label: label, data: data, backgroundColor: "teal" }]
          },
          options: { indexAxis: 'y' }
        });
      }
    });
</script>



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