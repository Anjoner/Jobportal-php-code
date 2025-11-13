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
                body { font-family: Arial; padding: 20px;  }
                h2 { margin-bottom: 20px; }
                .stats { display: flex; gap: 20px; flex-wrap: wrap; }
                .card {
                  background: white; padding: 15px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.1);
                  flex: 1; min-width: 200px; text-align: center;
                }
                canvas { margin-top: 30px; background: white; padding: 10px; border-radius: 10px; }
                .row h2{
                    text-align: center;
                    padding: 10px;
                    font-weight: bold;
                }
            </style>

   </head>

   <body>
    
    <?php include 'header.html' ?> <br>

    <main> <br>
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="stats" id="stats"></div> <br>
            </div>
          </div>
            <div class="row">

                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                  <h2>Job Status Analysis</h2>
                  <canvas id="jobStatusChart" height="100"></canvas>
                </div>

                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                  <h2>Jobs in category Analysis</h2>
                  <canvas id="categoryChart" height="100"></canvas>
                </div>
            </div>  
            <div class="row">
                <div class="col-12"> <br>
                  <h2>Applicants Analysis</h2>
                  <canvas id="applicantsChart" height="100"></canvas>
                </div>
            </div>
        </div>

    </main> <br>
    <?php  include 'footer.html'?>

  <!-- JS here -->


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      fetch('../../backend/process.php?action=admin_report')
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          // Stats Cards
          const stats = document.getElementById('stats');
          stats.innerHTML = `
            <div class="card"><h4>Total Users</h4><p>${data.total_users}</p></div>
            <div class="card"><h4>Total employers</h4><p>${data.employers}</p></div>
            <div class="card"><h4>Total jobseekers</h4><p>${data.jobseekers}</p></div>
            <div class="card"><h4>Total Jobs</h4><p>${data.total_jobs}</p></div>
            <div class="card"><h4>Pending Jobs</h4><p>${data.pending_jobs}</p></div>
            <div class="card"><h4>Rejected Jobs</h4><p>${data.rejected_jobs}</p></div>
            <div class="card"><h4>Approved Jobs</h4><p>${data.approved_jobs}</p></div>
            <div class="card"><h4>Total Applications</h4><p>${data.total_apps}</p></div>
          `;

          // Charts
          renderPie("jobStatusChart", ["Approved", "Pending", "Rejected"], [data.approved_jobs, data.pending_jobs, data.rejected_jobs]);
          renderBar("applicantsChart", data.jobs.titles, data.jobs.app_counts, "Applications per Job");
          renderBar("categoryChart", data.categories.names, data.categories.counts, "Jobs per Category");
        });

      function renderBar(id, labels, values, label) {
        new Chart(document.getElementById(id), {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: label,
              data: values,
              backgroundColor: 'skyblue'
            }]
          }
        });
      }

      function renderPie(id, labels, values) {
        new Chart(document.getElementById(id), {
          type: 'pie',
          data: {
            labels: labels,
            datasets: [{
              data: values,
              backgroundColor: ['green', 'gray', 'red']
            }]
          }
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