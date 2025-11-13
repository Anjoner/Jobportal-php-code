<?php
session_start();

?>


<!doctype html>
<html lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login & Registration Form</title>
        <link rel="stylesheet" href="../assets/css/style2.css">

        <!-- Unicons -->
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="site.webmanifest">
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

		<!-- CSS here -->
      <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
      <link rel="stylesheet" href="../assets/css/flaticon.css">
      <link rel="stylesheet" href="../assets/css/price_rangs.css">
      <link rel="stylesheet" href="../assets/css/slicknav.css">
      <link rel="stylesheet" href="../assets/css/animate.min.css">
      <link rel="stylesheet" href="../assets/css/magnific-popup.css">
      <link rel="stylesheet" href="../assets/css/fontawesome-all.min.css">
      <link rel="stylesheet" href="../assets/css/themify-icons.css">
      <link rel="stylesheet" href="../assets/css/slick.css">
      <link rel="stylesheet" href="../assets/css/nice-select.css">
      <link rel="stylesheet" href="../assets/css/style.css">
   </head>

   <body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="logoo (1).jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
    <header>
        <!-- Header Start -->
       <div class="header-area header-transparrent">
           <div class="headder-top header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-2">
                            <!-- Logo -->
                            <div class="logo">
                                <a href="index.php"><img src="assets/img/jlogo.png" alt=""></a>
                            </div>  
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="menu-wrapper">
                                <!-- Main-menu -->
                                <div class="main-menu">
                                    <nav class="d-none d-lg-block">
                                        <ul id="navigation">
                                            <li><a href="../index.php">Home</a></li>
                                            <li><a href="../service.html">Service</a></li>
                                            <li><a href="../about.html">About</a></li>
                                            <li><a href="../contact.html">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>          
                                <!-- Header-btn -->
                                <div class="header-btn d-none f-right d-lg-block">
                                    <a href="auth/login.php" class="btn2 head-btn2">Login</a>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
           </div>
       </div>
        <!-- Header End -->
    </header>

    <main>
        <div class="bg-img">
            <div class="content">
              <header>Login Page</header>

              <form id="loginForm">
                <input type="hidden" name="action" value="login">
                <div class="field">
                  <span class="fa fa-user"></span>
                  <input type="email" name ="email" required placeholder="Email">
                </div>
                <div class="field space">
                  <span class="fa fa-lock"></span>
                  <input type="password" name= "password" class="pass-key" required placeholder="Password">
                  <span class="show">SHOW</span>
                </div> <br>

                <div id="error" style="color: red;"></div>

                <div class="field">
                  <input type="submit" value="LOGIN">
                </div>
              </form> 

                <div class="pass">
                  <a href="forgot_password.php">Forgot Password?</a>
                </div>
              <div class="signup">Don't have account?
                <a href="signup.php">Signup Now</a>
              </div>
            </div>
          </div>
             
    </main>
    <footer>
        <!-- Footer Start-->
        <div class="footer-area footer-bg footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                       <div class="single-footer-caption mb-50">
                         <div class="single-footer-caption mb-30">
                             <div class="footer-tittle">
                                 <h4>About Us</h4>
                                 <div class="footer-pera">
                                     <p>We are Computer Science student in Arbaminch University, and we have been developing this web based career tracking and job appplication system.</p>
                                </div>
                             </div>
                         </div>

                       </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Contact Info</h4>
                                <ul>
                                    <li>
                                    <p>Address :Arbaminch, South Ethiopia</p>
                                    </li>
                                    <li><a href="tel:+251995533279">Phone : +251 -995-53-3279</a></li>
                                    <li><a href="mailto:Betimake9@gmail.com">Email : Betimake9@gmail.com</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Important Link</h4>
                                <ul>
                                    <li><a href="../contact.html">Contact Us</a></li>
                                    <li><a href="../about.html">About Us</a></li>
                                    <li><a href="../service.html">Our Service</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
               <!--  -->
            </div>
        </div>
        <!-- footer-bottom area -->
        <div class="footer-bottom-area footer-bg">
            <div class="container">
                <div class="footer-border">
                     <div class="row d-flex justify-content-between align-items-center">
                         <div class="col-xl-10 col-lg-10 ">
                             <div class="footer-copy-right">
                                 <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This website is made with AMU students
                                 </p>
                             </div>
                         </div>
                         <div class="col-xl-2 col-lg-2">
                             <div class="footer-social f-right">
                                 <a href="#"><i class="fab fa-facebook-f"></i></a>
                                 <a href="#"><i class="fab fa-twitter"></i></a>
                             </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
        <!-- Footer End-->
    </footer>


  <!-- JS here -->
	

        <script>
            const pass_field = document.querySelector('.pass-key');
            const showBtn = document.querySelector('.show');
            showBtn.addEventListener('click', function(){
             if(pass_field.type === "password"){
               pass_field.type = "text";
               showBtn.textContent = "HIDE";
               showBtn.style.color = "#3498db";
             }else{
               pass_field.type = "password";
               showBtn.textContent = "SHOW";
               showBtn.style.color = "#222";
             }
            });


            document.getElementById("loginForm").addEventListener("submit", function(e) {
                e.preventDefault();
                
                fetch("../backend/process.php", {
                    method: "POST",
                    body: new FormData(this)
                })
                .then(response => response.json())
                // .then(response => response.text())
                // .then(data=> console.log(data))
                .then(data => {
                      if (data.status === 'ok') {
                        // Redirect based on user role
                        switch(data.role) {
                            case 'admin':
                                window.location.href = "../users/admin/admin-dashboard.php";
                                break;
                            case 'employer':
                                window.location.href = "../users/employee/employee-dashboard.php";
                                break;
                            case 'jobseeker':
                                window.location.href = data.redirect || "../users/jobseeker/jobseeker-dashboard.php";
                                break;
                            // default:
                            //     window.location.href = "dashboard.php";
                        }
                    } else {
                        document.getElementById("error").textContent = data.message;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });

        </script>   





		<!-- All JS Custom Plugins Link Here here -->
        <script src="../assets/js/vendor/modernizr-3.5.0.min.js"></script>
		<!-- Jquery, Popper, Bootstrap -->
		    <script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
	    <!-- Jquery Mobile Menu -->
        <script src="../assets/js/jquery.slicknav.min.js"></script>

		<!-- Jquery Slick , Owl-Carousel Plugins -->
        <script src="../assets/js/owl.carousel.min.js"></script>
        <script src="../assets/js/slick.min.js"></script>
        <script src="../assets/js/price_rangs.js"></script>
        
		<!-- One Page, Animated-HeadLin -->
        <script src="../assets/js/wow.min.js"></script>
		    <script src="../assets/js/animated.headline.js"></script>
        <script src="../assets/js/jquery.magnific-popup.js"></script>

		<!-- Scrollup, nice-select, sticky -->
        <script src="../assets/js/jquery.scrollUp.min.js"></script>
        <script src="../assets/js/jquery.nice-select.min.js"></script>
		    <script src="../assets/js/jquery.sticky.js"></script>
        
        <!-- contact js -->
        <script src="../assets/js/contact.js"></script>
        <script src="../assets/js/jquery.form.js"></script>
        <script src="../assets/js/jquery.validate.min.js"></script>
        <script src="../assets/js/mail-script.js"></script>
        <script src="../assets/js/jquery.ajaxchimp.min.js"></script>
        
		<!-- Jquery Plugins, main Jquery -->	
        <script src="../assets/js/plugins.js"></script>
        <script src="../assets/js/main.js"></script>
        
    </body>
</html>