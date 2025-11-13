<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../../auth/login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$role = $_SESSION['role'];

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

        <style>
          body { font-family: Arial, sans-serif; padding: 20px; }
          .job-container {
            margin-bottom: 40px;
            border: 1px solid #ccc;
            padding: 15px;
          }
          .grid {
            display: grid;
            grid-template-columns: 2fr 3fr;
            gap: 20px;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
          }
          th, td {
            padding: 6px;
            border: 1px solid #ddd;
          }
          iframe {
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
          }
            .table-wrapper{
              max-height: 500px;
              overflow-y: auto;

            }
            td, th{
              white-space: nowrap;
              padding: 5px;


            } 
        </style>
</head>

<body>
    <?php include 'header.html'; ?> <br>

    <main>
        <div class="container-fluid mt-3">

          <h1 style="text-align: center;">Applications Grouped by Job</h1>
          <div id="jobs_wrapper"></div>

        </div> <br>

    </main>

    <?php   include 'footer.html' ?>


  <script>
    function loadApplications() {
      fetch('../../backend/process.php?action=grouped_applications')
        .then(res => res.json())
        .then(data => {
          const container = document.getElementById('jobs_wrapper');
          container.innerHTML = '';

          data.forEach(job => {
            const jobDiv = document.createElement('div');
            jobDiv.className = 'job-container';

            const appsRows = job.applications.map(app => `
              <tr>
                <td>${app.name}</td>
                <td>${app.email}</td>
                <td>
                  <button onclick="showResume('${app.resume_path}', ${job.id})" class="btn btn-primary">View</button>
                </td>
                <td>${app.status}</td>
                <td>
                  <button onclick="updateStatus(${app.id}, 'accepted')" class="btn btn-outline-success">Accept</button>
                  <button onclick="updateStatus(${app.id}, 'rejected')" class="btn btn-outline-danger">Reject</button>
                </td>
              </tr>
            `).join('');

            const appTable = `
              <div class="grid">
                <div>
                  <h3>${job.title}</h3>
                  <table class="table-responsive table-wrapper">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Resume</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>${appsRows}</tbody>
                  </table><br><br>
                  <a href="applicants.php?job_id=${job.id}" class="btn btn-outline-primary">View All Applicants</a>
                </div>
                <div>
                  <h4>Resume Viewer</h4>
                  <iframe id="viewer-${job.id}"></iframe>
                </div>
              </div>
            `;

            jobDiv.innerHTML = appTable;
            container.appendChild(jobDiv);
          });
        });
    }

    function showResume(path, jobId) {
      document.getElementById('viewer-' + jobId).src = path;
    }

    function updateStatus(appId, status) {
      fetch('../../backend/process.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=update_status&id=${appId}&status=${status}`
      }).then(() => loadApplications());
    }

    document.addEventListener('DOMContentLoaded', loadApplications);
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