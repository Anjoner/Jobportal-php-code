
<?php
session_start();
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../../auth/login.php");
    exit;
}

// $id = intval($_GET['id']);
// $stmt = $conn->prepare("SELECT * FROM jobs WHERE id=?");
// $stmt->bind_param("i", $id);
// $stmt->execute();
// $result = $stmt->get_result();
// $job = $result->fetch_assoc();


// Fetch job details to preselect current values
$job_id = $_GET['id'];
$job = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jobs WHERE id = '$job_id'"));

// SQL to Fetch Dropdown Options
$categories = mysqli_query($conn, "SELECT id, name FROM categories");
$locations = mysqli_query($conn, "SELECT id, name FROM locations");
$types = mysqli_query($conn, "SELECT id, name FROM job_types");
$experiences = mysqli_query($conn, "SELECT id, name FROM experiences");

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
        <div class="container m-auto align-items-center">
            <div class="row">
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>

                <div class="col-sm-10 col-md-6 col-lg-6 col-xl-6">
                    <h2>Post New Job</h2>
                    <form id="jobEditForm">

                        <input type="hidden" name="action" value="update_job">
                        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                        <div class="mb-3 mt-3">
                            <label for="title" class="form-label">Title:</label>
                            <input type="text" name="title" value="<?= $job['title'] ?>"required class="form-control" id="title" >
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" class="form-control" id="description" required> <?= $job['description'] ?> </textarea>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="location" class="form-label">Location:</label>
                            <select name="location" id="location" class="form-select">
                              <?php while($loc = mysqli_fetch_assoc($locations)): ?>
                                <option value="<?= $loc['id'] ?>" <?= $job['location_id'] == $loc['id'] ? 'selected' : '' ?>>
                                  <?= $loc['name'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>

                        </div>

                        <div class="mb-3 mt-3">
                            <label for="salary" class="form-label">Salary:</label>
                            <input type="text" class="form-control" id="salary" name="salary" value="<?= $job['salary'] ?>" required>
                        </div>


                        <div class="mb-4 mt-3">
                            <label for="category" class="form-label"> Category:</label>
                            <select name="category" class="form-select" id="category">
                              <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $cat['id'] ?>" <?= $job['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                  <?= $cat['name'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>


                        </div> <br>

                        <div class="mb-4 mt-3">
                            <label for="type" class="form-label"> Job Type:</label>
                            <select name="job_type" id="type" class="form-select">
                              <?php while($type = mysqli_fetch_assoc($types)): ?>
                                <option value="<?= $type['id'] ?>" <?= $job['type_id'] == $type['id'] ? 'selected' : '' ?>>
                                  <?= $type['name'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>

                        </div> <br>
                    
                        <div class="mb-3 mt-3">
                            <label for="experience" class="form-label">Experience:</label>
                            <select name="experience" id="experience" class="form-select">
                              <?php while($exper = mysqli_fetch_assoc($experiences)): ?>
                                <option value="<?= $exper['id'] ?>" <?= $job['experience_id'] == $exper['id'] ? 'selected' : '' ?>>
                                  <?= $exper['name'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="expire_date" class="form-label">Title:</label>
                            <input type="date" name="expire_date" value="<?= $job['expire_at'] ?>"required class="form-control" id="expire_date" >
                        </div> 

                        <br>
                        <div id="error" style="color: red;"></div> <br>

                        <input type="submit" value="Update" class="btn btn-primary btn-lg"> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
                        <a href="jobs.php" class="btn btn-danger btn-lg">Cancel</a>

                    </form> <br>     
                </div>
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3 "></div>

            </div>
 

        </div>

    </main>

    <?php include 'footer.html' ?>



  <!-- JS here -->
        <script>

            document.getElementById("jobEditForm").addEventListener("submit", function(e) {
                e.preventDefault();
                console.log("hi");
                
                fetch("../../backend/process.php", {
                    method: "POST",
                    body: new FormData(this)
                })
                .then(response => response.json())
                // .then(data=> console.log(data))
                .then(data => {
                    if (data.success) {
                        console.log("ggggg");

                        // Redirect based on user role
                        localStorage.setItem('success', data.message);
                        window.location.href = "jobs.php";
                                
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