<?php
session_start();
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

            .table-wrapper{
              max-height: 500px;
              overflow-y: auto;

            }               
        </style>

</head>

<body>
    <?php include 'header.html'; ?> <br><br>

    <main>
        <div class="container m-auto align-items-center">
            <div class="row"> 
                <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                  <h2 style="text-align: center; font-weight: bold;">Pending Job Posts</h2>
                  <div class="table-responsive table-wrapper">
                    <table id="job-table" class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>

                <div class="col-sm-7 col-md-7 col-lg-7 col-xl-7">

                  <h2 style="text-align: center; font-weight: bold;">Job Details</h2>
                  <div class="details" id="job-details">
                    <p>Select a job to view details.</p>
                  </div>

                </div>

            </div>
        </div> <br>

    </main>

    <?php   include 'footer.html' ?>


<script>
    let selectedJob = null;

    function loadPendingJobs() {
      fetch('../../backend/process.php?action=pending_jobs')
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          const tbody = document.querySelector('#job-table tbody');
          tbody.innerHTML = '';
          data.forEach(job => {
            const row = `<tr onclick="showDetails(${job.id}, \`${job.title}\`, \`${job.category}\`, \`${job.location}\`, \`${job.job_type}\`, \`${job.experience}\`, \`${job.description}\`)">
              <td style="cursor: pointer;">${job.title}</td>
              <td>
                <button class="btn btn-outline-success" onclick="event.stopPropagation(); approveJob(${job.id})">Approve</button>
                <button class="btn btn-outline-danger" onclick="event.stopPropagation(); rejectJob(${job.id})">Reject</button>
              </td>
            </tr>`;
            tbody.innerHTML += row;
          });
        });
    }

    function showDetails(id, title, category, location, job_type, experience, description) {
      selectedJob = id;
      document.getElementById('job-details').innerHTML = `
        <h3>${title}</h3>
        <p>Category: ${category}</p>
        <p>Location:${location}</p>
        <p>Job type:${job_type}</p>
        <p>Experience: ${experience}</p>
        <p>${description}</p>
        <button class="btn btn-success" onclick="approveJob(${id})">Approve</button>
        <button class="btn btn-danger" onclick="rejectJob(${id})">Reject</button>
      `;
    }

    function approveJob(id) {
      fetch('../../backend/process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=approve_job&id=${id}`
      }).then(() => {
        loadPendingJobs();
        document.getElementById('job-details').innerHTML = `<p>Job #${id} approved.</p>`;
      });
    }



    function rejectJob(id) {
      const reason ="";
      // if (reason === null || reason.trim() === "") return; // Cancelled or empty

        Swal.fire({
          title: 'Enter rejection reason',
          input: 'text',
          inputLabel: 'Reason',
          inputPlaceholder: 'Type reason here',
          showCancelButton: true
        }).then((result) => {
          if (result.isConfirmed) {
           reason=result.value;
          }
        });


      fetch('../../backend/process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=reject_job&id=${id}&reason=${encodeURIComponent(reason)}`
      }).then(() => {
        loadPendingJobs();
        document.getElementById('job-details').innerHTML = `<p>Job #${id} rejected.</p>`;
      });
    }


    function rejectJob(id) {
      Swal.fire({
        title: 'Reject Job',
        input: 'textarea',
        inputLabel: 'Reason for rejection',
        inputPlaceholder: 'Type your reason here...',
        showCancelButton: true,
        confirmButtonText: 'Reject',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
          if (!value) {
            return 'Rejection reason is required!'
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {

          const payload = {
            action: 'reject_job',
            job_id: id,
            reason: result.value
          };

          fetch('../../backend/process.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },

            body: JSON.stringify(payload)
          })
          .then(res => res.json())
          // .then(data => console.log(data))
          .then(data => {
            if (data.success) {
              Swal.fire('Rejected!', data.message, 'success');
              loadPendingLoans();
            } else {
              Swal.fire('Error', data.message, 'error');
            }
          })
          .catch(err => Swal.fire('Error', 'Something went wrong', 'error'));
        }
      });
    }

    document.addEventListener('DOMContentLoaded', loadPendingJobs);
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