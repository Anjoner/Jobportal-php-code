
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
            #job-notices p{
              padding: 8px;
              font-weight: bold;
              font-size: 20px;
              text-align: center;
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
                            <div class="hero-cap text-center">
                                <h2>Get your job</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- Hero Area End -->
          
        <div id="job-notices"></div>

        <!-- Job List Area Start -->
        <div class="job-listing-area pt-120 pb-120">
            <div class="container">
                <div class="row">
                    <!-- Left content -->
                    <div class="col-xl-3 col-lg-3 col-md-4">



                      <div class="row">
                          <div class="col-12">
                                  <div class="small-section-tittle2 mb-45">
                                  <div class="ion"> <svg 
                                      xmlns="http://www.w3.org/2000/svg"
                                      xmlns:xlink="http://www.w3.org/1999/xlink"
                                      width="20px" height="12px">
                                  <path fill-rule="evenodd"  fill="rgb(27, 207, 107)"
                                      d="M7.778,12.000 L12.222,12.000 L12.222,10.000 L7.778,10.000 L7.778,12.000 ZM-0.000,-0.000 L-0.000,2.000 L20.000,2.000 L20.000,-0.000 L-0.000,-0.000 ZM3.333,7.000 L16.667,7.000 L16.667,5.000 L3.333,5.000 L3.333,7.000 Z"/>
                                  </svg>
                                  </div>
                                  <h4>Filter Jobs</h4>
                              </div>
                          </div>
                      </div>
                      <!-- Job Category Listing start -->
                      <div class="job-category-listing mb-50">
                          <!-- single one -->
                          <div class="single-listing">
                             <div class="small-section-tittle2">
                                   <h4>Job Category</h4>
                             </div>
                              <!-- Select job items start -->

                              <div class="select-job-items2">
                                  <select id="category" class="form-control">
                                      <!-- <option value="">All</option> -->
                                  </select>

                              </div>

                              <!--  Select job items End-->
                              <!-- select-Categories start -->
                              <div class="select-Categories pt-80 pb-50">
                                  <div class="small-section-tittle2">
                                      <h4>Job Type</h4>
                                  </div>
                                  <div id="job_type_filters"></div>
                                  <!-- <div id="experience_filters"></div> -->


                              </div>
                              <!-- select-Categories End -->
                          </div>
                          <!-- single two -->
                          <div class="single-listing">
                             <div class="small-section-tittle2">
                                   <h4>Job Location</h4>
                             </div>
                              <!-- Select job items start -->
                              <div class="select-job-items2">
                                  <select id="location" class="form-control">
                                      <!-- <option value="">All</option> -->
                                  </select>
                              </div>
                              <!--  Select job items End-->
                              <!-- select-Categories start -->
                              <div class="select-Categories pt-80 pb-50">
                                  <div class="small-section-tittle2">
                                      <h4>Experience</h4>
                                  </div>
                                  <div id="experience_filters"></div>
                              </div>
                              <!-- select-Categories End -->
                          </div>
                          <!-- single three -->
                          <div class="single-listing">
                              <!-- select-Categories start -->
                              <div class="select-Categories pb-50">
                                  <div class="small-section-tittle2">
                                      <h4>Posted Within</h4>
                                  </div>
                                  <div id="date_filters"></div>

                              </div>
                              <!-- select-Categories End -->
                          </div>
                      </div>
                      <!-- Job Category Listing End -->

                    </div>
                    <!-- Right content -->
                    <div class="col-xl-9 col-lg-9 col-md-8">
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
                </div>
            </div>
        </div>
        <!-- Job List Area End -->
    </main>

    <?php include 'footer.html' ?>

    <!-- JS here -->

<script>
  document.addEventListener('DOMContentLoaded', () => {
    fetch('../../backend/process.php?action=fetch_data')
      .then(res => res.json())
      // .then(data => console.log(data))
      .then(data => {
        if (data.error) return console.error(data.error);
        populateSelect('category', data.categories);
        populateSelect('location', data.locations);
        populateCheckboxes('job_type_filters', data.job_types);
        populateCheckboxes('experience_filters', data.experiences);
        populateCheckboxes('date_filters', [
          {id: '0', name: 'Any'},
          {id: '1', name: 'Today'},
          {id: '2', name: 'Last 2 Days'},
          {id: '7', name: 'Last 7 Days'},
          {id: '30', name: 'Last 30 Days'}
        ]);
      });

    function populateSelect(id, items) {
      const select = document.getElementById(id);
      if (!select) return;

      // If a previous nice-select wrapper exists, remove it
      const niceWrapper = select.nextElementSibling;
      if (niceWrapper && niceWrapper.classList.contains('nice-select')) {
        niceWrapper.remove();
        select.classList.remove('nice-select'); // remove custom class if applied
        select.style.display = ''; // reset default display
      }

      // Clear and repopulate
      select.innerHTML = '<option value="">All</option>';
      items.forEach(item => {
        select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });

      // Optional: Force display
      select.style.display = 'block';
    }


    function populateCheckboxes(containerId, items) {
      const container = document.getElementById(containerId);
      if (!container) return;
      container.innerHTML = '';
      items.forEach(item => {
        const label = document.createElement('label');
        label.className = 'container';
        label.innerHTML = `<input type="checkbox" value="${item.id}" name="${containerId}"> ${item.name} <span class="checkmark"></span>`;
        container.appendChild(label);
      });
    }


    ['category', 'location'].forEach(id => {
      document.getElementById(id)?.addEventListener('change', fetchFilteredJobs);
    });

    ['job_type_filters', 'experience_filters', 'date_filters'].forEach(containerId => {
      document.getElementById(containerId)?.addEventListener('change', fetchFilteredJobs);
    });

    function fetchFilteredJobs() {
      const category = document.getElementById('category')?.value;
      const location = document.getElementById('location')?.value;
      const job_types = getCheckedValues('job_type_filters');
      const experiences = getCheckedValues('experience_filters');
      const days = getCheckedValues('date_filters');

      const params = new URLSearchParams({
        action: 'filter_jobs',
        category,
        location,
        job_types: job_types.join(','),
        experiences: experiences.join(','),
        days: days.join(',')
      });

      fetch(`../../backend/process.php?${params}`)
        .then(res => res.json())
        // .then(data => console.log(data))
        .then(data => {
          displayJobs(data.jobs || []);
        })
        .catch(err => console.error('Filter Error:', err));
    }

    function getCheckedValues(containerId) {
      return Array.from(document.querySelectorAll(`#${containerId} input[type="checkbox"]:checked`)).map(cb => cb.value);
    }

    function displayJobs(jobs) {
      const list = document.getElementById('jobList');
      if (!list) return;
      list.innerHTML = '';
      if (jobs.length === 0) {
        list.innerHTML = '<p>No jobs found.</p>';
        return;
      }
      jobs.forEach(job => {
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
                        <span>${job.posted_ago}</span>
                    </div>
                </div> 
                `;
        list.appendChild(div);
      });
    }

    fetchFilteredJobs();
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
      fetch('../../backend/process.php?action=notice_count')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('notice-count');
          if (data.count > 0) {
            badge.innerText = data.count;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        });
    }
    
  function loadJobs() {
    fetch('../../backend/process.php?action=approved_jobs_for_jobseeker')
      .then(res => res.json())
      .then(data => {
        const notices = document.getElementById('job-notices');

        const newJobs = data.filter(job => job.notified == 0);
        if (newJobs.length > 0) {
          const message = `You have ${newJobs.length} new job(s) available!`;
          notices.innerHTML = `<p class="bg-success text-white">${message}</p>`;
          markJobsAsNotified();
        } else {
          notices.innerHTML = '';
        }
      });
  }

  function markJobsAsNotified() {
    fetch('../../backend/process.php?action=mark_new_jobs_notified').then(() => loadJobNoticeCount());
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadJobs();
    loadJobNoticeCount();
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