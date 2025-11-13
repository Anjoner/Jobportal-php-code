
<!DOCTYPE html>
<html lang="en">
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
            select{
                display: block;
            }
        </style>
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

    <?php include 'header.html' ?>

    <main>

        <!-- Hero Area Start-->
        <div class="slider-area ">
            <div class="single-slider section-overly slider-height2 d-flex align-items-center" data-background="assets/img/hero/jb2.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap text-center" id="hero">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero Area End -->
        <!-- Job List Area Start -->
        <div class="job-listing-area pt-120 pb-120">
            <div class="container">
                <div class="row">
                    <!-- Left content -->
                    <div class="col-xl-1 col-lg-1 col-md-1"></div>
                    <!-- Right content -->
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <!-- Featured_job_start -->
                        <section class="featured-job-area">
                            <div class="container">

                                <!-- single-job-content -->
                                <div id="jobList"></div>
                                <!-- single-job-content -->
                                <div id="pagination"></div>
                            </div>
                        </section>
                        <!-- Featured_job_end -->
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-1"></div>
                </div>
            </div>
        </div>
        <!-- Job List Area End -->

    </main>

    <?php include 'footer.html' ?>




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
    let currentPage = 1;
    const limit = 5;
    const params = new URLSearchParams(window.location.search);
    const categoryId = params.get('category_id');

    function loadJobs(page = 1) {
      fetch(`../../backend/process.php?action=jobs_by_category&category_id=${categoryId}&page=${page}&limit=${limit}`)
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          displayJobs(data.jobs);
          displayPagination(data.total_pages, data.current_page);
        });
    }

    function displayJobs(jobs) {
        
        const hero = jobs[0]?.category_name;
        const container = document.getElementById('jobList');
 
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
                            <li>${job.category_name}</li>
                            <li><i class="fas fa-map-marker-alt"></i>${job.location_name}</li>
                            // <li>${job.salary}</li>
                        </ul>
                    </div>
                </div>
                <div class="items-link items-link2 f-right">
                    <a href="job_details.php?id=${job.id}">${job.type_name}</a>
                    <span>${job.posted_time}</span> <br>
                </div>
            </div> 
        `;
        container.appendChild(div);
      });
        
      document.getElementById('hero').innerHTML = `<h2>${hero}</h2>`;

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