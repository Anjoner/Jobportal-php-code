<?php
session_start();
require '../../backend/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'jobseeker') {
    header("Location: ../../auth/login.php");
    exit;
}

?>



<html>
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


      <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width:100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border:1px solid #ddd; padding:8px; text-align: left; height: 100px; }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #f9f9f9; }
        td a{

            color: black;
            font-size: 18px;
        }
        td a:hover{
            color: #fb246a;
            font-weight: bold;
        }
        #keyword { width: 300px; padding: 8px; }
        .single-services {
            cursor: pointer;
        }

        #pagination{
            text-align: center;

        }
        #pagination button{
            color: red;
            /*background: black;*/
            font-size: 25px;
            font-weight: bold;
        }
        .table-wrapper{
            max-height: 500px;
            overflow-y: auto;

            }
        #message p{
            text-align: center;
            padding: 20px;
            font-weight: bold;
            font-size: 20px;
        }

      </style>


   </head>

   <body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="../../assets/img/jlogo.png" alt="aaa">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
    <?php include 'header.html' ?>
    <main>

        <!-- slider Area Start-->
        <div class="slider-area ">
            <!-- Mobile Menu -->
            <div class="slider-active">
                <div class="single-slider slider-height d-flex align-items-center" data-background="../../assets/img/hero/jbp.jpg">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-6 col-lg-9 col-md-10">
                                <div class="hero__caption">
                                    <!-- <h1>Find the most exciting startup jobs</h1> -->
                                    <h1>የስራ ማስታወቂያ - በ0 አመት እና በልምድ</h1>
                                    <h2>በጣም አስደሳች ስራዎችን ያግኙ</h2>

                                </div>
                            </div>
                        </div><br>
                        <!-- Search Box -->
                        <div class="row">
                            <div class="col-xl-8">
                                <!-- form -->
                                <form  action="../../backend/process.php" method="POST" class="search-box" id="searchForm">
                                    <div class="input-form">
                                        <input type="hidden" name="action" value="search">
                                        <input type="text" placeholder="Job Tittle or keyword" name="keyword" autocomplete="off">

                                    </div>  

                                    <button type="submit" class="btn2 head-btn2" id="searchBtn">Search</button>
                                </form>
                                <!-- <input type="text" id="searchInput" placeholder="Search job title or company..." autocomplete="off" class="form-control"> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- slider Area End-->
    
        <div id="message"></div>

        <div class="container">
            <div class="row">
                <div class="table-responsive table-wrapper">
                    <div id="result"></div>
                </div>
            </div>
        </div>

        
        <!-- Our Services Start -->
        <div class="our-services section-pad-t30">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-tittle text-center">
                            <!-- <span>FEATURED TOURS Packages</span> -->
                            <h2>Browse Top Categories </h2>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-contnet-center" id="categoryList">
                </div>
                <!-- More Btn -->
                <!-- Section Button -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="browse-btn2 text-center">
                            <a href="job_listing.php" class="border-btn2">Browse All Sectors</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>




        <!-- Our Services End -->

        <!-- Featured_job_start -->
        <section class="featured-job-area feature-padding">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-tittle text-center">
                            <!-- <span>Recent Job</span> -->
                            <h2>Recent Jobs</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-10">
           
                        <div id="recent_jobs"></div>
                        <div id="pagination"></div>

                    </div>
                </div>
            </div>
        </section>
        <!-- Featured_job_end -->


         <!-- How  Apply Process Start-->
        <div class="apply-process-area apply-bg pt-150 pb-150" data-background="../../assets/img/gallery/how-applybg.png">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-tittle white-text text-center">
                            <span>Apply process</span>
                            <h2> How it works</h2>
                        </div>
                    </div>
                </div>
                <!-- Apply Process Caption -->
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="single-process text-center mb-30">
                            <div class="process-ion">
                                <span class="flaticon-search"></span>
                            </div>
                            <div class="process-cap">
                                <h5>1. Search a job</h5>
                            <p>Search jobs based on category, job title, job description, and job type</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-process text-center mb-30">
                            <div class="process-ion">
                                <span class="flaticon-curriculum-vitae"></span>
                            </div>
                            <div class="process-cap">
                                <h5>2. Apply for job</h5>
                            <p>To apply click the job title or category image or job type, 
                                then the system redirect you to jobs details page, there you can get apply button,
                            so finally apply by providing requested information</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-process text-center mb-30">
                            <div class="process-ion">
                                <span class="flaticon-tour"></span>
                            </div>
                            <div class="process-cap">
                                <h5>3. Get notice of your application</h5>
                            <p>System notify the approval of job status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- How  Apply Process End-->
       

    </main>
    <?php include 'footer.html' ?>

  <!-- JS here -->



<script>
    // Using fetch() for the form
    document.getElementById('searchForm').addEventListener('submit', async function(e) {
      e.preventDefault(); // Stop normal form submission
        
      document.getElementById('message').innerHTML = "";

      const form = e.target;
      const formData = new FormData(form);

      try {
        const response = await fetch(form.getAttribute('action'), {
          method: 'POST',
          body: new URLSearchParams(formData)
        });

        const data = await response.json();
        // console.log(data);
        if (data.status === "success") {
          if (data.jobs.length === 0) {
            document.getElementById('message').innerHTML = "<p class='bg-danger text-white'>No jobs found.</p>";
          }
            let table = "<table class='table table-hover table-borderless' style='table-layout: auto; width: 100%; cursor:pointer;'><tbody>";
            data.jobs.forEach(job => {
              table += `<tr>
                          <td><a href="job_details.php?id=${job.id}"><img src="../../uploads/${job.image}" alt="" width="80px" height="80px"></a></td>
                          <td><a href="job_details.php?id=${job.id}">${job.title} <br><br> ${job.category}</a></td>
                          <td><a href="job_details.php?id=${job.id}">${job.location}</a></td>
                          <td><a href="job_details.php?id=${job.id}">${job.experience}</a></td>
                          <td><a href="job_details.php?id=${job.id}">${job.posted_ago}</a></td>
                          <td><a href="job_details.php?id=${job.id}">Expire at:${job.expire_at}</a></td>
                        </tr>`;
            });
            table += "</tbody></table>";

            document.getElementById('result').innerHTML = table;
       
        } else {
          document.getElementById('message').innerHTML = `<p class='bg-danger text-white'>${data.message}</p>`;
        }
      } catch (error) {
        document.getElementById('message').innerHTML = "<p class='bg-danger text-white'>Something went wrong.</p>";
        console.error(error);
      }
    });
</script>

 
<script>
    async function loadCategories() {
      const response = await fetch("../../backend/process.php?action=categories_with_count");
      const data = await response.json();
      // console.log(data);

      let html = '';
      data.forEach(cat => {
        html += `     
          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="single-services text-center mb-30" onclick="loadJobs(${cat.id})">
                    <div class="services-ion">
                        <span><a href="job_by_category.php?category_id=${cat.id}"><img src="../../uploads/${cat.image}" alt="${cat.name}" width='100px' height='100px'></a></span>
                    </div>
                    <div class="services-cap">
                       <h5><a href="job_by_category.php?category_id=${cat.id}"><strong>${cat.name}</strong></a></h5>
                        <span>${cat.total_jobs} jobs</span>
                    </div>

                </div>
            </div>
    `;
      });



      document.getElementById('categoryList').innerHTML = html;
    }

    function loadJobs(categoryId) {
      window.location.href = `job_by_category.html?category_id=${categoryId}`;
    }

    loadCategories();
</script>


<script>
    let currentPage = 1;
    const limit = 5;

    function loadJobs(page = 1) {
      fetch(`../../backend/process.php?action=recent_jobs&page=${page}&limit=${limit}`)
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          displayJobs(data.jobs);
          displayPagination(data.total_pages, data.current_page);
        });
    }

    function displayJobs(jobs) {
      const container = document.getElementById('recent_jobs');
      container.innerHTML = '';
      if (jobs.length === 0) {
        container.innerHTML = '<p>No jobs available.</p>';
        return;
      }
      jobs.forEach(job => {
        // console.log(job.id);
        const div = document.createElement('div');
        div.className = 'job-item';
        div.innerHTML = `
            <div class="single-job-items mb-30">
                <div class="job-items">
                    <div class="company-img">
                        <a href="job_details.php?id=${job.id}"><img src="../../uploads/${job.image}" alt="" width="80px" height="80px"></a>
                    </div>
                    <div class="job-tittle job-tittle2">
                        <a href="job_details.php?id=${job.id}">
                            <h4>${job.title}</h4>
                        </a>
                        <ul>
                            <li>${job.category}</li>
                            <li><i class="fas fa-map-marker-alt"></i>${job.location}</li>
                            // <li>${job.salary}</li>
                        </ul>
                    </div>
                </div>
                <div class="items-link items-link2 f-right">
                    <a href="job_details.php?id=${job.id}">${job.job_type}</a>
                    <span>${job.posted_ago}</span> <br>
                    <span>Expire at:${job.expire_at}</span>
                </div>
            </div> 
        `;
        container.appendChild(div);
      });
    }

    function displayPagination(totalPages, currentPage) {
      const pag = document.getElementById('pagination');
      // pag.style.color = 'red';
      // pag.style.background-colo = 'red';
      
      pag.innerHTML = '';

      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.innerText = i;
        btn.disabled = i === currentPage;
        btn.onclick = () => loadJobs(i);
        pag.appendChild(btn);
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadJobs(currentPage);
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

<script>
    function loadJobNoticeCount() {
        fetch('../../backend/process.php?action=job_notice_count_jobseeker')
          .then(res => res.json())
          .then(data => {
            const badge = document.getElementById('job-notice-count');
            if (data.count > 0) {
              badge.innerText = data.count;
              badge.style.display = 'inline-block';
            } else {
              badge.style.display = 'none';
            }
          });
    }

    // Call the function after page load
    document.addEventListener('DOMContentLoaded', loadJobNoticeCount);
    setInterval(loadJobNoticeCount, 10000); // Refresh every 10s
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