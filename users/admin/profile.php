<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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


    <style type="text/css">
        .success{
            text-align: center;
        }
        .success p{
            padding: 5px;
            font-size: 20px;
            color: white;
            background: green;
        }
        .error{
            text-align: center;
        }
        .error p{
            padding: 5px;
            font-size: 20px;
            color: white;
            background: green;
        }

    </style>

</head>

<body>
    <?php include 'header.html'; ?> <br>

    <main>
        <div class="container m-auto align-items-center">
            <div class="row"> 
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>

                <div class="col-sm-10 col-md-6 col-lg-6 col-xl-6">

                  <h2 style="text-align: center;">My Profile</h2>
                    <div id="success" class="success"></div>
                  <form id="profileForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_profile">

                        <div class="mb-3 mt-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" id="username" class="form-control" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->
                        </div>
                        
                        <div class="mb-3 mt-3">
                            <label for="full_name" class="form-label">Full Name:</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="phone" class="form-label">Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control" autocomplete="off" >
                            <!-- <span class="error" id="titleError"></span> -->
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="bio" class="form-label">Bio:</label>
                            <textarea name="bio" id="bio" rows="4"  class="form-control" ></textarea>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="profile_picture" class="form-label">Profile Picture:</label>
                            <input type="file" name="profile_picture" class="form-control">
                            <!-- <span class="error" id="titleError"></span> -->
                        </div>
                        <div class="img"><img id="current_pic" src="" class="rounded-circle" width="
                          150px" height="150px"></div>

                        <div class="mb-3 mt-3">
                            <label for="full_name" class="form-label">New Password:</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current" class="form-control">
                        </div>
                        <div id="error" class="error"></div>

                    <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                    <button onclick="window.history.back()" class="btn btn-danger btn-lg">cancel</button>
                  </form>

                </div>
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>

            </div>
        </div> <br>

    </main>

    <?php   include 'footer.html' ?>


<script>
  // Load existing profile data
  fetch('../../backend/process.php?action=get_profile')
    .then(res => res.json())
    // .then(data => console.log(data))
    .then(data => {
      console.log(data.profile_image);
      document.getElementById('username').value = data.username;
      document.getElementById('full_name').value = data.full_name;
      document.getElementById('email').value = data.email;
      document.getElementById('phone').value = data.phone;
      document.getElementById('bio').value = data.bio;
      if (data.profile_image) {
        document.getElementById('current_pic').src = '../' + data.profile_image;
      }
    });

  // Submit updated profile
  document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // const formData = new FormData(this);
    fetch("../../backend/process.php", {
        method: "POST",
        body: new FormData(this)
    })
    .then(response => response.json())
    // .then(data=> console.log(data))
    .then(data => {
        if (data.success) {

            document.getElementById("success").innerHTML = `<p> ${data.message} </p>`;
                    
        } else {
            document.getElementById("error").innerHTML = `<p> ${data.message} </p>`;
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
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