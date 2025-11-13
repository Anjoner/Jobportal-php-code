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

   </head>

   <body>

    <?php include 'header.html' ?>
    
    <main>


        <div class="job-details" id="jobDetails">
          <h2>Loading job details...</h2>
        </div>


    </main>
    <?php include 'footer.html' ?>

	<!-- JS here -->
	

<script>
  // Get job ID from URL
  const params = new URLSearchParams(window.location.search);
  const jobId = params.get('id');
  console.log(jobId);

  if (!jobId) {
    document.getElementById('jobDetails').innerHTML = '<p>Job ID not specified.</p>';
  } else {
    fetch(`../../backend/process.php?action=job_details&id=${jobId}`)
      .then(res => res.json())
      .then(job => {
        if (!job || job.error) {
          document.getElementById('jobDetails').innerHTML = `<p>${job?.error || 'Job not found.'}</p>`;
          return;
        }

        document.getElementById('jobDetails').innerHTML = `

        <!-- Hero Area Start-->
        <div class="slider-area ">
        <div class="single-slider section-overly slider-height2 d-flex align-items-center" data-background="assets/img/hero/about.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap text-center">
                            <h2>${job.category}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- Hero Area End -->
        <!-- job post company Start -->
        <div class="job-post-company pt-120 pb-120">
            <div class="container">
                <div class="row justify-content-between">
                    <!-- Left Content -->
                    <div class="col-xl-7 col-lg-8">
                        <!-- job single -->
                        <div class="single-job-items mb-50">
                            <div class="job-items">
                                <div class="company-img company-img-details">
                                    <a href="#"><img src="../../uploads/${job.image}" alt="" width="80px" height="80px"></a>
                                </div>
                                <div class="job-tittle">
                                    <a href="#">
                                        <h4>${job.title}</h4>
                                    </a>
                                    <ul>
                                        <li><i class="fas fa-map-marker-alt"></i>${job.location}</li>
                                        <li>${job.salary}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                          <!-- job single End -->
                       
                        <div class="job-post-details">
                            <div class="post-details1 mb-50">
                                <!-- Small Section Tittle -->
                                <div class="small-section-tittle">
                                    <h4>Job Description</h4>
                                </div>
                                <p>${job.description}</p>
                            </div>

                            <div class="post-details2  mb-50">
                                 <!-- Small Section Tittle -->
                                <div class="small-section-tittle">
                                    <h4>Experience</h4>
                                </div>
                               <ul>
                                   <li>${job.experience}</li>
                               </ul>
                            </div>
                        </div>

                    </div>
                    <!-- Right Content -->
                    <div class="col-xl-4 col-lg-4">
                        <div class="post-details3  mb-50">
                            <!-- Small Section Tittle -->
                           <div class="small-section-tittle">
                               <h4>Job Overview</h4>
                           </div>
                          <ul>
                              <li>Posted date : <span>${job.posted_ago}</span></li>
                              <li>Location : <span>${job.location}</span></li>
                              <li>Job nature : <span>${job.job_type}</span></li>
                              <li>Salary :  <span>${job.salary}</span></li>
                              <li>Expire date : <span>${job.expire_at}</span></li>
                          </ul>
                         <div class="apply-btn2">
                            
                            <button id="applyBtn" data-id="${job.id}" class="btn2 head-btn2">Apply Now</button>
                         </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- job post company End -->




        `;
      })
      .catch(err => {
        document.getElementById('jobDetails').innerHTML = `<p>Error loading job: ${err}</p>`;
      });
  }
</script>


<script>

    document.addEventListener('click', function (e) {
      if (e.target && e.target.id === 'applyBtn') {
        const jobId = e.target.getAttribute('data-id');

          fetch('../../backend/process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
              action: 'check_user_auth',
              job_id: jobId
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'ok') {
              window.location.href = `apply_job.php?job_id=${jobId}`;
            } else {
              fetch('../../backend/process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                  action: 'set_redirect',
                  redirect: `apply_job.php?job_id=${jobId}`
                })
              }).then(() => {
                window.location.href = 'auth/login.php';
              });
            }
          });
      }
       else {
            console.warn('Apply button not found in DOM.');
          }
    });
</script>


<script>
    function loadNoticeCount() {
      fetch('../../backend/process.php?action=notice_count')
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
    document.addEventListener('DOMContentLoaded', loadNoticeCount);
    setInterval(loadNoticeCount, 10000); // Refresh every 10s

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