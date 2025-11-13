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
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
          }
          th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
          }
          button {
            margin-top: 15px;
            padding: 8px 16px;
          }
          .table-wrapper{
            max-height: 500px;
            overflow-y: auto;

          }
          td, th{
            white-space: nowrap;
            padding: 5px;


          } 
          #message p, #job-notices p{
            font-size: 20px;
            text-align: center;
            padding: 8px;
          }
        </style>

</head>

<body>
    <?php include 'header.html'; ?> <br>

    <main>
        <div class="container-fluid mt-3">
          <div class="row">
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8 offset-2">
              <div class="table-responsive table-wrapper">

                <h1 style="text-align: center;">Applicants List</h1>
                <button onclick="downloadCSV()" class="btn btn-primary">Download CSV</button>
                <table id="applicants_table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>

              </div>

            </div>

          </div>

        </div> <br>

    </main>

    <?php   include 'footer.html' ?>


        <script>
          const jobId = new URLSearchParams(window.location.search).get('job_id');

          function loadApplicants() {
            fetch(`../../backend/process.php?action=get_applicants_by_job&job_id=${jobId}`)
              .then(res => res.json())
              .then(data => {
                const tbody = document.querySelector('#applicants_table tbody');
                tbody.innerHTML = '';
                data.forEach(app => {
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                    <td>${app.name}</td>
                    <td>${app.email}</td>
                    <td>${app.status}</td>
                  `;
                  tbody.appendChild(tr);
                });
              });
          }

          function downloadCSV() {
            const rows = [];
            document.querySelectorAll('#applicants_table tbody tr').forEach(row => {
              const cols = row.querySelectorAll('td');
              rows.push([cols[0].textContent, cols[1].textContent, cols[2].textContent]);
            });

            let csvContent = "Name,Email,Status\n";
            rows.forEach(row => {
              csvContent += row.join(",") + "\n";
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            if (link.download !== undefined) { // Browsers that support HTML5 download attribute
              const url = URL.createObjectURL(blob);
              link.setAttribute('href', url);
              link.setAttribute('download', `applicants_job_${jobId}.csv`);
              link.click();
            }
          }

          document.addEventListener('DOMContentLoaded', loadApplicants);
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