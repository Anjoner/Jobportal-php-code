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
        <div class="container m-auto align-items-center">
            <div class="row">

                <div class="col-sm-10 col-md-6 col-lg-6 col-xl-6 offset-2">

                  <h2>User Feedback</h2>
                  <div id="feedbackList"></div>
 
                </div>

            </div>
 

        </div>

    </main>

    <?php include 'footer.html' ?>



        <script>
            fetch('../../backend/process.php?action=get_feedback')
              .then(res => res.json())
              .then(data => {
                const container = document.getElementById('feedbackList');
                container.innerHTML = data.map(f => `
                  <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
                    <strong>User ID:</strong> ${f.user_id} <br>
                    <strong>Date:</strong> ${f.submitted_at} <br>
                    <strong>Message:</strong><br> ${f.message}
                  </div>
                `).join('');
              });
          </script>

<script>
  function deleteFeedback(id) {
    if (!confirm("Are you sure you want to delete this feedback?")) return;

    fetch('../../backend/process.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=delete_feedback&id=${id}`
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      loadFeedback(); // Refresh list
    });
  }

  function loadFeedback() {
    fetch('../../backend/process.php?action=get_feedback')
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('feedbackList');
        container.innerHTML = data.map(f => `
          <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
            <strong>User ID:</strong> ${f.user_id} <br>
            <strong>Date:</strong> ${f.submitted_at} <br>
            <strong>Message:</strong><br> ${f.message}<br>
            <button onclick="deleteFeedback(${f.id})" class="btn btn-danger">Delete</button>
          </div>
        `).join('');
      });
  }

  document.addEventListener('DOMContentLoaded', loadFeedback);
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