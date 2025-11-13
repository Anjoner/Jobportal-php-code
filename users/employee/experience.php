<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../../auth/login.php");
    exit;
}

$error = '';
$success = '';

// Handle Add
if (isset($_POST['add'])) {
  $name = $_POST['name'];


$query = "INSERT INTO experiences(name) VALUES('$name')";
if (!mysqli_query($conn, $query)) {
  $error = "DB Error: " . mysqli_error($conn);

} else {
  $success = "Experience added successfully.";
}


}

// Handle Delete
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];
    $query = "DELETE FROM experiences WHERE id = $id";
    if (!mysqli_query($conn, $query)) {
        $error = "Cannot delete this Experience level, to delete this first delete jobs that associated with it";
      
    } else {
      $success = "Experience deleted successfully.";
    }
}

// Handle Edit Request
$edit_experience = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $edit_experience = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM experiences WHERE id = $id"));
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $query = "UPDATE experiences SET name = '$name'";
    $query .= " WHERE id = $id";

    if (!mysqli_query($conn, $query)) {
      $error = "DB Error: " . mysqli_error($conn);
    } else {
      $success = "Experience updated successfully.";
    }
  header("Experience: experience.php");
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
        .table-wrapper{
          max-height: 500px;
          overflow-y: auto;

        }
        td, th{
          white-space: nowrap;
          padding: 5px;


        } 
    </style>
</head>

<body>
    <?php include 'header.html' ?>

    <main>
        <div class="container m-auto align-items-center">
            <div class="row">
                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>

                <div class="col-sm-10 col-md-6 col-lg-6 col-xl-6"> <br>

                    <?php if(isset($success)): ?>
                    <div id="success" style="color: green; text-align: center;"><?= $success ?></div>
                    <?php endif; ?>

                    <div class="form-head">
                        <!-- <h2>Create New Category</h2> -->
                        <h2><?= $edit_experience ? 'Edit' : 'Add' ?> Experience</h2>
                    </div>    
                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?= $edit_experience['id'] ?? '' ?>">
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" name="name" placeholder="Experience level" value="<?= $edit_experience['name'] ?? '' ?>" class="form-control" id="name" required >
                            <!-- <span class="error" id="titleError"></span> -->

                        </div>

                        <br>
                        <?php if(isset($error)): ?>
                        <div id="error" style="color: red;"><?= $error ?></div>
                        <?php endif; ?> <br>

                        <div class="">
                            <button type="submit" name="<?= $edit_experience ? 'update' : 'add' ?>" class="btn btn-primary btn-lg">
                            <?= $edit_experience ? 'Update' : 'Add' ?>
                            </button> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
                            <a href="post_job.php" class="btn btn-danger btn-lg">Back</a>
                        </div>

                    </form> <br> 

                    <hr>
                    <h3>All Locations</h3>
                    <div class="table-responsive table-wrapper">
                        <table class="table table-bordered" border="1" cellpadding="8">
                          <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Action</th>
                          </tr>
                          <?php
                          $res = mysqli_query($conn, "SELECT * FROM experiences");
                          while ($row = mysqli_fetch_assoc($res)):
                          ?>
                            <tr>
                              <td><?= $row['id'] ?></td>
                              <td><?= $row['name'] ?></td>
                              <td>
                                <a href="?edit=<?= $row['id'] ?>"  class="btn btn-primary btn-lg">Edit</a> |
                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this experience level?')"  class="btn btn-danger btn-lg">Delete</a>
                              </td>
                            </tr>
                          <?php endwhile; ?>
                        </table> <br>
                    </div><br>


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