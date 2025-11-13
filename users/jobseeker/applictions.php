<?php
session_start();
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'jobseeker') {
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

<style>
    body { font-family: Arial, sans-serif; padding: 20px; }

    .badge {
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 2px 7px;
      font-size: 12px;
      position: absolute;
      top: -5px;
      right: -10px;
    }
    .notice {
      background-color: #dff0d8;
      color: #3c763d;
      border: 1px solid #d6e9c6;
      padding: 10px;
      margin-bottom: 20px;
      font-weight: bold;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      padding: 8px;
      border: 1px solid #ccc;
    }
    th {
      background: #f4f4f4;
    }
  </style>

</head>

<body>
    <?php include 'header.html' ?>

    <main>
        <div class="container m-auto align-items-center">
            <div class="row">
                <div class="col-sm-1 col-md-2 col-lg-2 col-xl-2"></div>

                <div class="col-sm-10 col-md-8 col-lg-8 col-xl-8">

                  <h2 style="text-align: center;">Your Applications</h2>
                  <div id="notice"></div>
                  <div id="application_list"></div> <br>

                </div>

                <div class="col-sm-1 col-md-2 col-lg-2 col-xl-2"></div>

            </div>
 

        </div>

    </main>

    <?php include 'footer.html' ?>


<script>
    function loadNoticeCount() {
      fetch('../../backend/process.php?action=notice_count')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('notice-count');
          if (data.count > 0) {
            badge.innerText = data.count;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        });
    }

    function loadApplications() {
      fetch('../../backend/process.php?action=user_applications')
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          const notice = document.getElementById('notice');
          const container = document.getElementById('application_list');

          const newAccepted = data.filter(app => app.status === 'accepted' && app.notified == 0);
          if (newAccepted.length > 0) {
            const ids = newAccepted.map(app=> app.id).join(', ');
            notice.innerHTML = `<div class="notice">You have been accepted for ${newAccepted.length} job(s) and its application ID are/is [${ids}]</div>`;
            markAsNotified();
          } else {
            notice.innerHTML = '';
          }
          

          const rows = data.map(app => {
            let rowStyle = '';
            if (app.status === 'accepted') {
              rowStyle = 'background-color: #d4edda; color: #155724;'; // success
            } else if (app.status === 'rejected') {
              rowStyle = 'background-color: #f8d7da; color: #721c24;'; // danger
            }

            return `
              <tr style="${rowStyle}">
                <td>${app.id}</td>
                <td>${app.job_title}</td>
                <td>${app.status.toUpperCase()}</td>
              </tr>
            `;
          }).join('');

          container.innerHTML = `
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Job Title</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>
          `;
        });
    }

    function markAsNotified() {
      fetch('../../backend/process.php?action=mark_as_notified')
        .then(() => loadNoticeCount());
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadApplications();
      loadNoticeCount();
    });
</script>




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