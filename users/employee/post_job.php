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
            color: red;
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
                        <h2>Post New Job</h2>
                    </div>    
                    <form id="jobpostForm">

                        <input type="hidden" name="action" value="post_job">
                        <div class="mb-3 mt-3">
                            <label for="title" class="form-label">Title:</label>
                            <input type="text" name="title" required class="form-control" id="title" >
                            <span class="error" id="titleError"></span>

                        </div>

                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" class="form-control" id="description" required></textarea>
                            <span class="error" id="descriptionError"></span>

                        </div>

                        <div class="mb-3 mt-3">
                            <label for="location" class="form-label">Location:</label>
                            <select name="location" class="form-select" id="location">

                                <?php
                                $res = $conn->query("SELECT id, name FROM locations");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                } else {
                                    echo "<option value=''>No locations available</option>";
                                }
                                ?>
                                            
                            </select>
                            <span class="error" id="locationError"></span>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="salary" class="form-label">Salary:</label>
                            <input type="text" class="form-control" id="salary" name="salary" required>
                            <span class="error" id="salaryError"></span>

                        </div>

                        <div class="mb-4 mt-3">
                            <label for="category" class="form-label"> Category:</label>
                            <select name="category" class="form-select" id="category"> 
                                <?php
                                $res = $conn->query("SELECT id, name FROM categories");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                } else {
                                    echo "<option value=''>No categories available</option>";
                                }
                                ?>   
                            </select>
                            <span class="error" id="categoryError"></span>

                        </div> <br>

                        <div class="mb-4 mt-3">
                            <label for="type" class="form-label"> Job Type:</label>
                            <select name="job_type" class="form-select" id="type">
                                <?php
                                $res = $conn->query("SELECT id, name FROM job_types");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                } else {
                                    echo "<option value=''>No job types available</option>";
                                }
                                ?>

                            </select>

                            <span class="error" id="typeError"></span>
                        </div> <br>
                        
                        <div class="mb-3 mt-3">
                            <label for="experience" class="form-label">Experience:</label>
                            <select name="experience" class="form-select" id="experience">
                                <?php
                                $res = $conn->query("SELECT id, name FROM experiences");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                } else {
                                    echo "<option value=''>No experience ranges available</option>";
                                }
                                ?>

                            </select>
                            <span class="error" id="experienceError"></span>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="expire_date" class="form-label">Expiration Date:</label>
                            <input type="date" class="form-control" id="expire_date" name="expire_at" required>
                            <span class="error" id="salaryError"></span>

                        </div>

                        <br>
                        <div id="error" style="color: red;"></div> <br>

                        <input type="submit" value="Create" class="btn btn-primary btn-lg"> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
                        <a href="jobs.php" class="btn btn-danger btn-lg">Cancel</a>

                    </form> <br>     
                </div>
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3 ">
                    <div class="links">
                        <p><a href="category.php" class="btn btn-outline-primary btn-lg">Add category</a></p>
                        <p><a href="location.php" class="btn btn-outline-primary btn-lg">Add Location</a></p>
                        <p><a href="jobtype.php" class="btn btn-outline-primary btn-lg">Add Job type</a></p>
                        <p><a href="experience.php" class="btn btn-outline-primary btn-lg">Add Experience</a></p>
                    </div>
                </div>

            </div>
 

        </div>

    </main>

    <?php include 'footer.html' ?>



  <!-- JS here -->



  <script>
    document.getElementById("jobpostForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      // Clear old errors
      document.querySelectorAll(".error").forEach(el => el.textContent = "");
      document.getElementById("resultMsg").innerHTML = "";

      const title = document.getElementById("title").value.trim();
      const salary = document.getElementById("salary").value.trim();
      const category = document.getElementById("category").value;
      const location = document.getElementById("location").value;
      const type = document.getElementById("type").value;
      const experience = document.getElementById("experience").value;
      const description = document.getElementById("description").value.trim();
      const expire_date = document.getElementById("expire_date").value;

      let hasError = false;
      if (!title) { document.getElementById("titleError").textContent = "Title required"; hasError = true; }
      if (!salary) { document.getElementById("salaryError").textContent = "Salary required"; hasError = true; }
      if (!category) { document.getElementById("categoryError").textContent = "Category required"; hasError = true; }
      if (!location) { document.getElementById("locationError").textContent = "Location required"; hasError = true; }
      if (!type) { document.getElementById("typeError").textContent = "Type required"; hasError = true; }
      if (!experience) { document.getElementById("experienceError").textContent = "Experience required"; hasError = true; }
      if (!description) { document.getElementById("descriptionError").textContent = "Description required"; hasError = true; }

      if (hasError) return;

      // Create form data
      const formData = new FormData();
      formData.append("action", "post_job");
      formData.append("title", title);
      formData.append("salary", salary);
      formData.append("category", category);
      formData.append("location", location);
      formData.append("job_type", type);
      formData.append("experience", experience);
      formData.append("description", description);
      formData.append("expire_at", expire_date);

        fetch("../../backend/process.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        // .then(data => console.log(data))
        .then(data => {
            if (data.success) {
                // Redirect based on user role
                Swal.fire({
                icon: 'success',
                title: `${data.title}`,
                text: `${data.message}`,
                timer: 3000,
                showConfirmButton: false
                // confirmButtonColor: '#28a745',
            }).then(()=>{
                document.getElementById("jobpostForm").reset();

            });
                        
            } else {
                // document.getElementById("error").textContent = data.message;
                Swal.fire({
                    icon: 'error',
                    title: `${data.title}`,
                    text: `${data.message}`,
                    // timer: 3000,
                    // showConfirmButton: false
                    confirmButtonColor: 'red',
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });





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