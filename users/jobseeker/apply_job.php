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

    <style type="text/css">
        .error {
            color: white;
            background-color: #DC143C;
            font-weight: bold;
        }
        .error p{
            color: white;
            font-size: 20px;
            padding: 10px;
            font-weight: bold;
        }
        .success {
            background-color: #04aa6d;
            text-align: center;
        }
        .success p{
            color: white;
            font-size: 20px;
            padding: 10px;
            font-weight: bold;
        }
        .form-head h2{
            text-align: center;
            margin-top: 20px;
        }
        .links {
            text-align: center;
            padding-top: 40px;

        }
    </style>
</head>

<body>
    <?php include 'header.html' ?>

    <main>
        <div class="container m-auto align-items-center">
            <div class="row">
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>

                <div class="col-sm-10 col-md-6 col-lg-6 col-xl-6">

                    <div id="resultMsg" class="success"></div>
                    <div class="form-head">
                        <h2>Apply for Job</h2>
                    </div>    
                    <form id="applyForm" enctype="multipart/form-data">

                        <!-- <input type="hidden" name="action" value="post_job"> -->
                        <input type="hidden" id="job_id" name="job_id">

                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" name="name" required class="form-control" id="name" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->

                        </div>

                        <div class="mb-3 mt-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" required class="form-control" id="email" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->

                        </div>

                        <div class="mb-3 mt-3">
                            <label for="phonenumber" class="form-label">Phone:</label>
                            <input type="text" name="phone" required class="form-control" id="phonenumber" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->

                        </div>
                        <div class="mb-3 mt-3">
                            <label for="coverletter" class="form-label">Cover Letter:</label>
                            <textarea name="coverletter" rows="4"  class="form-control" id="coverletter"  autocomplete="off" ></textarea>
                            <!-- <span class="error" id="descriptionError"></span> -->

                        </div>

                        <div class="mb-3 mt-3">
                            <label for="resume" class="form-label">Resume (PDF/DOC):</label>
                            <input type="file" class="form-control" accept=".pdf,.doc,.docx" id="resume" name="resume" required>
                            <!-- <span class="error" id="salaryError"></span> -->

                        </div>

                        <br>
                        <div id="error"></div> <br>
                        <!-- <div class="message" id="message"></div> -->

                        <input type="submit" value="Apply" class="btn btn-primary btn-lg"> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
                        <!-- <a href="jobs.php" class="btn btn-danger btn-lg">Cancel</a> -->
                        <button type="button" onclick="window.history.back()" class="btn btn-danger btn-lg">Cancel</button>

                    </form> <br>     
                </div>
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3 ">
                </div>

            </div>
 

        </div>

    </main>

    <?php include 'footer.html' ?>



  <!-- JS here -->


<script>
  // Set job ID from URL
  const jobId = new URLSearchParams(window.location.search).get('job_id');
  document.getElementById('job_id').value = jobId;
  console.log(jobId);

  document.getElementById('applyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'apply_job');

    fetch('../../backend/process.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    // .then(data => console.log(data))
    .then(data => {
      const msg_suc = document.getElementById('resultMsg');
      const msg_err = document.getElementById('error');
      if (data.success) {
        msg_suc.innerHTML = '<p>Application submitted successfully!</p>';
        this.reset();
      } else {
        //   document.getElementById("resultMsg").innerHTML = '<p>'+result.message+'</p>';
        msg_err.innerHTML = `<p style="color:red"> ${data.message }</p>`;
        // msg_err.style.color = 'red';
      }
    })
    .catch(err => {
      document.getElementById('error').innerHTML = '<p>Network error. Try again.</p>';
    });
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