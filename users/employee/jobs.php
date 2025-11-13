<?php
session_start();
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
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
    <?php include 'header.html' ?>
    <main>
        <!-- <div class="container-fluid mt-3"> -->
        <div class="container mt-3">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10 col-12">
              <h2 style="text-align: center;">Your Job Posts</h2>
              <div id="message"></div>
              <div class="table-responsive table-wrapper">
                <div id="job-notices"></div>
                <div id="job-list"></div>
              </div><br>

            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="container mt-3">
                    <a href="post_job.php" class="btn btn-outline-primary btn-lg">Post job</a>
                </div> <br>   
            </div>

        </div>   


        </div>

    </main>

    <?php include 'footer.html' ?>


  <!-- JS here -->
	
  <script>
    const successmsg = localStorage.getItem("success");
    if(successmsg){
      document.getElementById("message").innerHTML = '<p class="bg-success text-white">'+successmsg+ '</p>';
      localStorage.removeItem("success");
    }
  </script>


<script>
    function loadJobNoticeCount() {
      fetch('../../backend/process.php?action=job_notice_count')
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

    function loadEmployerJobs() {
      fetch('../../backend/process.php?action=employer_jobs')
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          const notices = document.getElementById('job-notices');
          const container = document.getElementById('job-list');

          // console.log(data.status);
          // console.log(data.notified);
          
          const rejected = data.filter(job => job.status == 'rejected' && job.notified == 0);
          console.log(rejected.length);
          if (rejected.length > 0) {
            const messages = rejected.map(j => `Job ID ${j.id} rejected: ${j.rejection_reason}`).join('<br>');
            notices.innerHTML = `<p class="bg-danger text-white">${messages}</p>`;
            markJobsAsNotified();
          } else {
            notices.innerHTML = '';
          }


          const rows = data.map(app => {
            const isExpired = app.expired ? '<span style="color:red;">(Expired)</span>': '';
            let rowStyle = '';
            if (app.status === 'approved') {
              rowStyle = 'background-color: #d4edda; color: #155724;'; // success
            } else if (app.status === 'rejected') {
              rowStyle = 'background-color: #f8d7da; color: #721c24;'; // danger
            }

            return `
              <tr style="${rowStyle}">
                <td>${app.title} ${isExpired}</td>
                <td>${app.category_name}</td>
                <td>${app.location_name}</td>
                <td>${app.posted_at}</td>
                <td>${app.expire_at}</td>
                <td>${app.status.toUpperCase()}</td>

                <td><a href='edit_job.php?id=${app.id}' class='btn btn-outline-primary'>Edit</a> |
                <button onclick="deleteJob(${app.id})" class="btn btn-outline-danger">Delete</button>
                </td>
              </tr>
            `;
          }).join('');

          container.innerHTML = `
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Job Title</th>
                  <th>Category</th>
                  <th>Location</th>
                  <th>Posted Date</th>
                  <th>Expiration Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>
          `;
        });
    }

    function markJobsAsNotified() {
      fetch('../../backend/process.php?action=mark_jobs_as_notified')
      .then(() => loadJobNoticeCount());
    }


	async function deleteJob(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: "This action cannot be undone!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
			// Make your fetch/axios/ajax call to delete
			fetch('../../backend/process.php', {
				method: 'POST',
				body: new URLSearchParams({ action: 'delete_job', id: id })
			})
			.then(res => res.json())
			// .then(data => console.log(data))
			.then(data => {

				Swal.fire({
          icon: 'success',
          title: `${data.title}`,
          text: `${data.message}`,
          timer: 3000,
          showConfirmButton: false
          // confirmButtonColor: '#28a745',
                  
      });
				if(data.success){
					location.reload();
					//  loadEmployerJobs();

				}
				// Optionally reload or update the table
			});
			}
		});
		
	
	}



    document.addEventListener('DOMContentLoaded', () => {
      loadEmployerJobs();
      loadJobNoticeCount();
    });
</script>


        <script src="../../assets/js/sweetalert2.all.js"></script>
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