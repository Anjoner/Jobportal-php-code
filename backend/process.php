<?php

header('Content-Type: application/json');
// header('Content-Type: text/HTML');
require 'db.php';
session_start();

// date_default_timezone_set('UTC');

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

// Create users, jobs, applications table if it doesn't exist


// USERS
$conn->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'employer', 'jobseeker') NOT NULL DEFAULT 'jobseeker',
    profile_image VARCHAR(255) DEFAULT 'default.png',
    full_name VARCHAR(100),
    phone VARCHAR(20),
    bio TEXT,
    reset_token TEXT,
    token_expiry DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

)
");


// CATEGORIES
$conn->query("
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    image VARCHAR(255)
)
");

// JOB TYPES
$conn->query("
CREATE TABLE IF NOT EXISTS job_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
)
");


// JOB Experiences
$conn->query("
CREATE TABLE IF NOT EXISTS experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
)
");



// LOCATIONS
$conn->query("
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE
)
");


// JOBS
$conn->query("
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT,
    title VARCHAR(200),
    description TEXT,
    category_id INT,
    location_id INT,
    type_id INT,
    experience_id INT,
    salary VARCHAR(100),
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expire_at DATE,
    is_expired TINYINT(1) DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT DEFAULT NULL,
    notified TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(255),
    token_expiry DATETIME,

    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
    FOREIGN KEY (type_id) REFERENCES job_types(id) ON DELETE CASCADE,
    FOREIGN KEY (experience_id) REFERENCES experiences(id) ON DELETE CASCADE
)
");

// APPLICATIONS
$conn->query("
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    jobseeker_id INT,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(255),
    resume VARCHAR(255),
    cover_letter TEXT,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notified TINYINT(1) DEFAULT 0,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (jobseeker_id) REFERENCES users(id) ON DELETE CASCADE
)
");



// MESSAGES
$conn->query("
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)
");




function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'just now';

    $units = [
        31536000 => 'year',
        2592000  => 'month',
        604800   => 'week',
        86400    => 'day',
        3600     => 'hour',
        60       => 'minute'
    ];

    foreach ($units as $secs => $unit) {
        $quotient = floor($diff / $secs);
        if ($quotient >= 1) {
            return $quotient . ' ' . $unit . ($quotient > 1 ? 's' : '') . ' ago';
        }
    }
    return 'just now';
}

// function timeAgo($datetime) {
//   $time = strtotime($datetime);
//   $diff = time() - $time;

//   if ($diff < 60) return 'Just now';
//   if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
//   if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
//   if ($diff < 604800) return floor($diff / 86400) . ' days ago';

//   return date('Y-m-d', $time);
// }






$today = date('Y-m-d');
mysqli_query($conn, "UPDATE jobs SET is_expired = 1 WHERE expire_at < '$today'");

$action = $_POST['action'] ?? $_GET['action'] ?? '';
    // $action = $_POST['action'];


// ----------------- HANDLE REGISTRATION -----------------
if ($action === 'signup') {
    $email = $_POST['email'];
    $name = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password)) {
        // die("Please fill in all fields. <a href='javascript:history.back()'>Go Back</a>");
        echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
        exit;
    }

    if($password !== $confirm_password){
        echo json_encode(["success" => false, "message" => "Password do not match!"]);
        exit;

    }
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // die("Email already exists. <a href='../auth/signup.php'>Try Again</a>");

        echo json_encode(["success" => false, "message" => "Email already exists."]);
        exit;

    }

    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $insert->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($insert->execute()) {
        echo json_encode(["success" => true, "message" => "Useraccount created successfully"]);
        exit;
    }
    else {
        echo json_encode(["success" => false, "message" => "Error during registration."]);
        exit;
    }

} 

// ----------------- HANDLE LOGIN -----------------
elseif ($action === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit;
    }


    // Check if user exists and password is correct
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    // $stmt->store_result();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user'] = $user;

            // Check for saved redirect
            // $redirect = $_SESSION['redirect_after_login'] ?? '../index.php';
            // unset($_SESSION['redirect_after_login']); // Clear it after use
            // echo json_encode(['status' => 'ok', 'redirect' => $redirect]);

            // Return success response with role
            // echo json_encode([
            //     "success" => true,
            //     "role" => $user['role'],
            //     "message" => "Login successful"
            // ]);

            $redirect = $_SESSION['redirect_after_login'] ?? null;
            unset($_SESSION['redirect_after_login']);

            echo json_encode([
                'status' => 'ok',
                'role' => $user['role'],
                'redirect' => $redirect,
                "message" => "Login successful"
            ]);

   


      }else{
            // $_SESSION['flash_error'] = 'Invalid credentials';
            // header('Location: ../auth/login.php');
            // exit(); 

            // echo "Email and password are required!";
            // exit;

            echo json_encode(["success" => false, "message" => "Invalid email or password"]);
            exit;
      } 

    } else {
        echo json_encode(["success" => false, "message" => "Please register befor try to login."]);
        exit;   
    }

 }


 
// ----------------- HANDLE USER UPDATE -----------------
elseif ($action === 'update_user') {
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();

    $_SESSION['flash_message'] = [
        'type' => 'success',
        'message' => 'Updated successfully'
    ];

    header('Location: ../users/admin/useraccount.php');

    // echo json_encode([
    //     "success" => true,
    //     "message" => "Updated successful"
    // ]);
    // exit;
    // echo "successful";

} 

// ----------------- HANDLE USER DELETION -----------------
elseif ($action === 'delete_user') {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['flash_message'] = [
        'type' => 'success',
        'message' => 'Deleted successfully'
    ];

    header('Location: ../users/admin/useraccount.php');

}


elseif ($action === 'forgot_password') {
  $email = $_POST['email'];
  $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
  if (mysqli_num_rows($result) > 0) {
    $token = bin2hex(random_bytes(16));
    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
    mysqli_query($conn, "UPDATE users SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");
    
    $resetLink = "reset_password.php?token=$token";
     echo json_encode(['success' => true, 'message' => 'Password reset link:', 'link' => $resetLink]);
    // You should send an actual email in production. For demo:
  } else {
        echo json_encode(['success' => false,  'title' =>'Invalid Email', 'message' => 'Email not found']);
  }
}


elseif ($action === 'reset_password') {
  $token = trim($_POST['token']);
  $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

  $query = "SELECT * FROM users WHERE reset_token='$token'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    mysqli_query($conn, "UPDATE users SET password='$new_password', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'");
    
  
    echo json_encode(['success' => true, 'title' =>'Password reset', 'message' => 'Password forgot successfully!']);
 
  } else {
    echo json_encode(['success' => false, 'title' =>'Invalid request!', 'message' => 'Invalid or expired token.']);
  }
}

// elseif ($action  === 'reset_password') {
//     $token = trim($_POST['token']);
//     $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

//     $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? ");
//     $stmt->bind_param("s", $token);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result && $result->num_rows > 0) {
//         $stmt2 = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
//         $stmt2->bind_param("ss", $new_password, $token);
//         $stmt2->execute();

//         echo json_encode(['success' => true, 'message' => 'Password reset successfully.']);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Invalid or expired token.']);
//     }
// }


// ----------------- HANDLE POST JOB -----------------
elseif ($action === 'post_job') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employer') {
        echo "Unauthorized";
        exit();
    }


    $title = $_POST['title'];
    $desc = $_POST['description'];
    $loc = $_POST['location'];
    $salary = $_POST['salary'];
    $category = $_POST['category'];
    $job_type = $_POST['job_type'];
    $experience = $_POST['experience'];
    $expire_at = $_POST['expire_at'];
    $emp_id = $_SESSION['user']['id'];


    $stmt = $conn->prepare("INSERT INTO jobs (employer_id, title, description, location_id, salary, category_id, type_id, experience_id, expire_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $emp_id, $title, $desc, $loc, $salary, $category, $job_type, $experience, $expire_at);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, 'title' => 'Job Created', "message" => "Job posted successfully!!!"]);
        exit();
    } else {
        echo json_encode(["success" => false, 'title' => 'Failed!', "message" => "Failed to post job!!!"]);
        exit;
    }

}


// -----------------  UPDATE JOB -----------------

elseif ($action === 'update_job') {
    $job_id = $_POST['job_id'];
    $title = $_POST['title'];
    $category_id = $_POST['category'];
    $location_id = $_POST['location'];
    $salary = $_POST['salary'];
    $type_id = $_POST['job_type'];
    $experience_id = $_POST['experience'];
    $description = $_POST['description'];
    $expire_at = $_POST['expire_date'];

    if ($title && $category_id && $location_id && $salary && $type_id && $experience_id && $description) {
        $update = "UPDATE jobs SET 
                    title = '$title', 
                    category_id = '$category_id',
                    location_id = '$location_id',
                    salary = '$salary',
                    type_id = '$type_id',
                    experience_id = '$experience_id',
                    description = '$description',
                    expire_at = '$expire_at',
                    status = 'pending'
                   WHERE id = '$job_id'";

        if (mysqli_query($conn, $update)) {
            echo json_encode(["success" => true, "message" => "Job updated successfully!!!"]);
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }
}



elseif ($action === 'categories_with_count') {
    $query = "SELECT c.id, c.name, c.image, COUNT(j.id) AS total_jobs 
              FROM categories c 
              JOIN jobs j ON j.category_id = c.id 
              AND j.status = 'approved' 
              AND (j.expire_at >= CURDATE())
              GROUP BY c.id
              HAVING total_jobs > 0";
    $result = mysqli_query($conn, $query);
    $categories = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    echo json_encode($categories);
    exit;
}


elseif ($action === 'jobs_by_category') {

  $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $offset = ($page - 1) * $limit;

  $total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs");
  $total_row = mysqli_fetch_assoc($total_result);
  $total = $total_row['total'];
  $total_pages = ceil($total / $limit);

    $category_id = intval($_GET['category_id']);
    $query = "SELECT j.id, j.title, j.salary, j.posted_at, c.image As image, c.name As category_name,  t.name As type_name, l.name AS location_name 
              FROM jobs j 
              JOIN categories c ON j.category_id = c.id 
              JOIN job_types t ON j.type_id = t.id 
              JOIN locations l ON j.location_id = l.id 
              WHERE j.category_id = $category_id AND j.status='approved'";
    $result = mysqli_query($conn, $query);

    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['posted_time'] = timeAgo($row['posted_at']);
        $jobs[] = $row;
    }

    // echo json_encode($jobs);
    // exit;
    echo json_encode([
      'jobs' => $jobs,
      'total_pages' => $total_pages,
      'current_page' => $page
    ]);
    exit;

}



elseif ($action === 'fetch_data') {
  // Fetch categories, locations, job types, experiences
  $categories = mysqli_query($conn, "SELECT id, name FROM categories");
  $locations = mysqli_query($conn, "SELECT id, name FROM locations");
  $job_types = mysqli_query($conn, "SELECT id, name FROM job_types");
  $experiences = mysqli_query($conn, "SELECT id, name FROM experiences");

  echo json_encode([
    'categories' => mysqli_fetch_all($categories, MYSQLI_ASSOC),
    'locations' => mysqli_fetch_all($locations, MYSQLI_ASSOC),
    'job_types' => mysqli_fetch_all($job_types, MYSQLI_ASSOC),
    'experiences' => mysqli_fetch_all($experiences, MYSQLI_ASSOC)
  ]);
  exit;
}


elseif ($action === 'filter_jobs') {
  
  $category = $_GET['category'] ?? '';
  $location = $_GET['location'] ?? '';
  $job_types = $_GET['job_types'] ?? '';
  $experiences = $_GET['experiences'] ?? '';
  $days = $_GET['days'] ?? '';

  $where = [];

  if ($category) {
    $where[] = "jobs.category_id = '$category'";
  }
  if ($location) {
    $where[] = "jobs.location_id = '$location'";
  }
  if ($job_types) {
    $types = implode(",", array_map('intval', explode(',', $job_types)));
    $where[] = "jobs.type_id IN ($types)";
  }
  if ($experiences) {
    $exps = implode(",", array_map('intval', explode(',', $experiences)));
    $where[] = "jobs.experience_id IN ($exps)";
  }
  if ($days) {
    $dayList = explode(',', $days);
    $dateConditions = [];
    foreach ($dayList as $day) {
      $day = intval($day);
      $dateConditions[] = "DATE(jobs.posted_at) >= CURDATE() - INTERVAL $day DAY";
    }
    $where[] = '(' . implode(' OR ', $dateConditions) . ')';
  }
  $where[] = "jobs.status='approved' AND jobs.expire_at >= CURDATE()";
  $sql = "SELECT 
            jobs.*, 
            categories.image As image,
            categories.name AS category, 
            locations.name AS location, 
            job_types.name AS job_type, 
            experiences.name AS experience 
          FROM jobs 
          LEFT JOIN categories ON categories.id = jobs.category_id
          LEFT JOIN locations ON locations.id = jobs.location_id
          LEFT JOIN job_types ON job_types.id = jobs.type_id
          LEFT JOIN experiences ON experiences.id = jobs.experience_id";

  if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
  }

  // $sql .= " ORDER BY jobs.posted_at DESC";
  $sql .= " ORDER BY jobs.posted_at DESC";

  $res = mysqli_query($conn, $sql);
  $jobs = [];
  while ($row = mysqli_fetch_assoc($res)) {
    $row['posted_ago'] = timeAgo($row['posted_at']);
    $jobs[] = $row;
  }

  echo json_encode(['jobs' => $jobs]);
  exit;
}



elseif ($action === 'recent_jobs') {

  $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $offset = ($page - 1) * $limit;

  $total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs");
  $total_row = mysqli_fetch_assoc($total_result);
  $total = $total_row['total'];
  $total_pages = ceil($total / $limit);

  // $sql = "SELECT jobs.*, categories.name AS category_name 
  //         FROM jobs 
  //         LEFT JOIN categories ON jobs.category_id = categories.id 
  //         ORDER BY jobs.posted_date DESC 
  //         LIMIT $offset, $limit";


  $sql = "SELECT 
            jobs.*, 
            categories.image As image,
            categories.name AS category, 
            locations.name AS location, 
            job_types.name AS job_type, 
            experiences.name AS experience 
          FROM jobs 
          LEFT JOIN categories ON categories.id = jobs.category_id
          LEFT JOIN locations ON locations.id = jobs.location_id
          LEFT JOIN job_types ON job_types.id = jobs.type_id
          LEFT JOIN experiences ON experiences.id = jobs.experience_id
          WHERE jobs.status = 'approved' AND jobs.expire_at >= CURDATE() 
          ORDER BY jobs.posted_at DESC 
          LIMIT $offset, $limit";


  $result = mysqli_query($conn, $sql);
  $jobs = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $row['posted_ago'] = timeAgo($row['posted_at']);
    $jobs[] = [
      'id' => $row['id'],
      'title' => $row['title'],
      'image' => $row['image'],
      'category' => $row['category'],
      'location' => $row['location'],
      'job_type' => $row['job_type'],
      'experience' => $row['experience'],
      'posted_ago' => $row['posted_ago'],
      'expire_at' => $row['expire_at'],
    ];
  }

  echo json_encode([
    'jobs' => $jobs,
    'total_pages' => $total_pages,
    'current_page' => $page
  ]);
  exit;
}



elseif ($action ===  'job_details' && isset($_GET['id'])) {
  $job_id = intval($_GET['id']);
  $query = "SELECT 
                jobs.*, 
                categories.image As image,
                categories.name AS category, 
                locations.name AS location, 
                job_types.name AS job_type, 
                experiences.name AS experience 
              FROM jobs 
              LEFT JOIN categories ON categories.id = jobs.category_id
              LEFT JOIN locations ON locations.id = jobs.location_id
              LEFT JOIN job_types ON job_types.id = jobs.type_id
              LEFT JOIN experiences ON experiences.id = jobs.experience_id
            -- LEFT JOIN categories ON jobs.category_id = categories.id 
              WHERE jobs.id = $job_id LIMIT 1";



  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $job = mysqli_fetch_assoc($result);
    $job['posted_ago'] = timeAgo($job['posted_at']);

    echo json_encode([
      'id' => $job['id'],
      'title' => $job['title'],
      'salary' => $job['salary'],
      'image' => $job['image'],
      'category' => $job['category'],
      'location' => $job['location'],
      'job_type' => $job['job_type'],
      'experience' => $job['experience'],
      'description' => $job['description'],
      'posted_ago' => $job['posted_ago'],
      'expire_at' => $job['expire_at'],
    ]);
  } else {
    echo json_encode(['error' => 'Job not found.']);
  }
  exit;
}



// ----------------- FETCH JOBS -----------------
elseif ($action === 'get_jobs') {

    $res = $conn->query("SELECT jobs.*, users.name AS employer FROM jobs JOIN users ON jobs.employer_id = users.id");
    $jobs = [];
    while ($row = $res->fetch_assoc()) $jobs[] = $row;
    header('Content-Type: application/json');
    echo json_encode($jobs);
    exit();
}



elseif ($action === 'delete_job') {
    $id = $_POST['id'] ?? '';
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(["success" => true, 'title' => 'Job Deleted', "message" => "Job deleted successfully!"]);

    // $_SESSION['flash_message'] = [
    //     'type' => 'success',
    //     'message' => 'Deleted successfully'
    // ];

    // header('Location: ../users/employee/jobs.php');


}


elseif ($action == 'search') {
    // Initialize response
    $response = [];

    $keyword = isset($_POST['keyword']) ? $conn->real_escape_string($_POST['keyword']) : '';

    if($keyword === ''){
        echo json_encode([
            'status' => 'success',
            'jobs' => []
        ]);
        exit();
    }

        $where = "WHERE j.status='approved' AND j.expire_at >= CURDATE()";
        if (!empty($keyword)) {
            $where .= " AND (j.title LIKE '%$keyword%' OR j.description LIKE '%$keyword%' OR c.name LIKE '%$keyword%')";
        }

        $sql = "SELECT j.*, c.name as category, c.image as image, l.name as location, t.name as job_type, e.name as experience, DATEDIFF(CURDATE(), j.posted_at) AS days_old
                FROM jobs j
                LEFT JOIN categories c ON j.category_id = c.id
                LEFT JOIN locations l ON j.location_id = l.id
                LEFT JOIN job_types t ON j.type_id = t.id
                LEFT JOIN experiences e ON j.experience_id = e.id
                $where
                ORDER BY j.posted_at DESC";

        $res = $conn->query($sql);
        $jobs= [];
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {

                $row['posted_ago'] = $row['days_old'] == 0 ? 'Today' : $row['days_old'] . ' day(s) ago';

                $jobs[] = [

                    'id' => $row['id'],
                    'title' => $row['title'],
                    'image' => $row['image'],
                    // 'category' => $row['description'],
                    'category' => $row['category'],
                    'location' => $row['location'],
                    'experience' => $row['experience'],
                    'posted_ago' => $row['posted_ago'],
                    'expire_at' => $row['expire_at']
                ];
            }
            $response['status'] = 'success';
            $response['jobs'] = $jobs;
        } else {
            $response['status'] = 'success';
            $response['jobs'] = [];
        }


        // Return JSON
        echo json_encode($response);
        exit();

    // }


  } 



  elseif ($action === 'check_user_auth') {
  $job_id = $_POST['job_id'];
  if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'not_logged_in']);
    exit;
  }
  if ($_SESSION['user']['role'] !== 'jobseeker') {
    echo json_encode(['status' => 'not_jobseeker']);
    exit;
  }
  echo json_encode(['status' => 'ok']);
  exit;
}

elseif ($action === 'set_redirect') {
  $_SESSION['redirect_after_login'] = $_POST['redirect'];
  echo json_encode(['status' => 'ok']);
  exit;
}


// ----------------- HANDLE APPLY TO JOB -----------------
elseif ($action === 'apply_job') {

    $job_id = $_POST['job_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $cover_letter = $_POST['coverletter'] ?? '';
    $user_id = $_SESSION['user']['id'];


    $query = "SELECT * FROM applications WHERE jobseeker_id='$user_id' AND  job_id='$job_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      echo json_encode(['success' => false, 'message' => 'Already you applied this job!!!']);
   
    } else {

            if (empty($job_id) || empty($name) || empty($email) || empty($phone) || empty($cover_letter)) {
              echo json_encode(['success' => false, 'message' => 'All fields are required']);
              exit;
          }

          if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== 0) {
              echo json_encode(['success' => false, 'message' => 'Resume upload failed']);
              exit;
          }

          // Resume upload handling
          $uploadDir = '../uploads/resumes/';
          if (!is_dir($uploadDir)) mkdir($uploadDir);

          $filename = basename($_FILES['resume']['name']);
          $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
          $allowed = ['pdf', 'doc', 'docx'];

          if (!in_array($ext, $allowed)) {
              echo json_encode(['success' => false, 'message' => 'Invalid resume file type']);
              exit;
          }

          $newName = uniqid() . '.' . $ext;
          $target = $uploadDir . $newName;

          if (!move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
              echo json_encode(['success' => false, 'message' => 'Resume upload error']);
              exit;
          }


          $stmt = $conn->prepare("INSERT INTO applications (jobseeker_id, job_id, name, email, phone, cover_letter, resume) VALUES (?, ?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("iisssss", $user_id, $job_id, $name, $email, $phone, $cover_letter, $newName);

          if ($stmt->execute()) {

              echo json_encode(['success' => true]);

          } else {

              echo json_encode(['success' => false, 'message' => 'DB insert failed']);
          }

          $stmt->close();
          exit;
    }

}


// Fetch all jobs with their applications
elseif ($action === 'grouped_applications') {
  $jobs = [];
  $user_id = $_SESSION['user']['id'];
  $jobRes = $conn->query("SELECT id, title FROM jobs WHERE employer_id = $user_id AND status='approved'");
  while ($job = $jobRes->fetch_assoc()) {
    $jobId = $job['id'];

    $appRes = $conn->query("SELECT * FROM applications WHERE job_id = $jobId");

    $apps = [];
    while ($app = $appRes->fetch_assoc()) {
      $app['resume_path'] = '../../uploads/resumes/' . $app['resume'];
      $apps[] = $app;
    }
    $job['applications'] = $apps;
    $jobs[] = $job;
  }
  echo json_encode($jobs);
  exit;
}

// Update application status
elseif ($action === 'update_status') {
  $id = intval($_POST['id']);
  $status = $conn->real_escape_string($_POST['status']);
  $conn->query("UPDATE applications SET status='$status' WHERE id=$id");
  echo 'ok';
  exit;
}




elseif ($action === 'accepted_applicants' && isset($_GET['job_id'])) {
  $job_id = intval($_GET['job_id']);
  $res = $conn->query("SELECT name, resume FROM applications WHERE job_id = $job_id AND status = 'accepted'");
  $result = [];
  while ($row = $res->fetch_assoc()) {
    $row['resume_path'] = 'uploads/resumes/' . $row['resume'];
    $result[] = $row;
  }
  echo json_encode($result);
  exit;
}

elseif ($action === 'get_applicants_by_job') {
    $job_id = $_GET['job_id'];
    // $user_id = $_SESSION['user']['id'];

    // Sort accepted applicants first
    $stmt = $conn->prepare("
        SELECT name, email, status 
        FROM applications 
        WHERE job_id = ? 
        ORDER BY 
          CASE 
            WHEN status = 'accepted' THEN 1 
            WHEN status = 'pending' THEN 2 
            WHEN status = 'rejected' THEN 3 
            ELSE 4 
          END, name ASC
    ");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $applicants = [];
    while ($row = $result->fetch_assoc()) {
        $applicants[] = $row;
    }

    echo json_encode($applicants);
    exit;
}



elseif ($action === 'user_applications') {
    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("SELECT a.*, j.title AS job_title 
            FROM applications a 
            JOIN jobs j ON a.job_id = j.id 
            WHERE a.jobseeker_id = ? 
            ORDER BY 
              CASE 
                WHEN a.status = 'accepted' THEN 1
                WHEN a.status = 'rejected' THEN 2
                ELSE 3
              END, a.notified DESC, a.id DESC");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $apps = [];
    while ($row = $result->fetch_assoc()) {
        $apps[] = $row;
    }
    echo json_encode($apps);
    exit;
}

elseif ($action === 'mark_as_notified') {
    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("UPDATE applications SET notified = 1 WHERE jobseeker_id = ? AND status = 'accepted' AND notified = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    echo json_encode(['status' => 'done']);
    exit;
}

elseif ($action === 'notice_count') {
    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM applications WHERE jobseeker_id = ? AND status = 'accepted' AND notified = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode(['count' => (int)$result['count']]);
    exit;
}





// admin page
elseif ($action === 'approve_job') {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE jobs SET status = 'approved' WHERE id = $id");
    echo "Approved";
    exit;
}


// elseif ($action === 'reject_job') {
//     $id = $_POST['id'];
//     $reason = mysqli_real_escape_string($conn, $_POST['reason']);
//     mysqli_query($conn, "UPDATE jobs SET status='rejected', rejection_reason='$reason' WHERE id=$id");
//     echo "Rejected";
//     exit;
// }


elseif ($action === 'pending_jobs') {

    $sql = "SELECT j.*, c.name as category,  l.name as location,  t.name as job_type, e.name as experience
            FROM jobs j
            LEFT JOIN categories c ON j.category_id = c.id
            LEFT JOIN locations l ON j.location_id = l.id
            LEFT JOIN job_types t ON j.type_id = t.id
            LEFT JOIN experiences e ON j.experience_id = e.id
            WHERE status = 'pending'";

    // $result = mysqli_query($conn, "SELECT * FROM jobs WHERE status = 'pending'");
    $result = mysqli_query($conn,  $sql);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    echo json_encode($jobs);
    exit;
}




elseif ($action === 'job_notice_count') {
    $id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM jobs WHERE employer_id=? AND status='rejected' AND notified=0");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode(['count' => (int)$result['count']]);
    exit;
}


elseif ($action === 'employer_jobs') {
    $id = $_SESSION['user']['id']; // Employer ID
    $query = "SELECT j.*, categories.name AS category_name, locations.name AS location_name 
      FROM jobs j
      JOIN categories ON j.category_id = categories.id
      JOIN locations ON j.location_id = locations.id
      WHERE employer_id=$id
      ORDER BY j.posted_at DESC";

    $res = mysqli_query($conn, $query);
    $response = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $row['posted_at'] = date("Y-m-d", strtotime($row['posted_at']));
        $row['expired'] = ($row['expire_at'] && $row['expire_at'] < date("Y-m-d")) ? true: false;
        $response[] = $row;
    }
    echo json_encode($response);
    exit;
}


elseif ($action === 'mark_jobs_as_notified') {
    $id = $_SESSION['user']['id']; // Employer ID
    mysqli_query($conn, "UPDATE jobs SET notified=1 WHERE employer_id=$id AND status='rejected'");
    exit;
}





elseif ($action === 'job_notice_count_jobseeker') {
    $res = mysqli_query($conn, "SELECT COUNT(*) as count FROM jobs WHERE status='approved' AND notified=0");
    $data =  mysqli_fetch_assoc($res);
    echo json_encode(['count' => (int)$data['count']]);
    exit;
}


elseif ($action === 'approved_jobs_for_jobseeker') {
    $res = mysqli_query($conn, "SELECT * FROM jobs WHERE status='approved'");
    $jobs = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $jobs[] = $row;
    }
    echo json_encode($jobs);
    exit;
}


elseif ($action === 'mark_new_jobs_notified') {
    mysqli_query($conn, "UPDATE jobs SET notified=1 WHERE status='approved' AND notified=0");
    exit;
}





// report

elseif ($action === 'admin_report') {
    // Total Stats
    $total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
    // Total employers
    $employers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role = 'employer'"))['c'];

    // Total jobseekers
    $jobseekers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role = 'jobseeker'"))['c'];

    $total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs"))['c'];
    $total_apps = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM applications"))['c'];
    $approved_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'approved'"))['c'];
    $pending_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status= 'pending'"))['c'];
    $rejected_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'rejected' AND rejection_reason IS NOT NULL"))['c'];

    // Applications per Job
    $job_res = mysqli_query($conn, "SELECT j.title, COUNT(a.id) AS apps FROM jobs j LEFT JOIN applications a ON a.job_id = j.id GROUP BY j.id ORDER BY apps DESC LIMIT 5");
    $job_titles = $job_apps = [];
    while ($r = mysqli_fetch_assoc($job_res)) {
    $job_titles[] = $r['title'];
    $job_apps[] = $r['apps'];
    }

    // Jobs per Category
    $cat_res = mysqli_query($conn, "SELECT c.name, COUNT(j.id) AS job_count FROM categories c LEFT JOIN jobs j ON j.category_id = c.id GROUP BY c.id");
    $cat_names = $cat_counts = [];
    while ($r = mysqli_fetch_assoc($cat_res)) {
    $cat_names[] = $r['name'];
    $cat_counts[] = $r['job_count'];
    }

    echo json_encode([
    'total_users' => $total_users,
    'employers' => $employers,
    'jobseekers' => $jobseekers,
    'total_jobs' => $total_jobs,
    'total_apps' => $total_apps,
    'approved_jobs' => $approved_jobs,
    'pending_jobs' => $pending_jobs,
    'rejected_jobs' => $rejected_jobs,
    'jobs' => [
      'titles' => $job_titles,
      'app_counts' => $job_apps
    ],
    'categories' => [
      'names' => $cat_names,
      'counts' => $cat_counts
    ]
    ]);
    exit;
}


// data analysis
elseif ($action === 'admin_data_analysis') {
  // Job trends (last 6 months)
  $job_query = "SELECT DATE_FORMAT(posted_at, '%b %Y') AS month, COUNT(*) AS count 
                FROM jobs WHERE status = 'approved' AND posted_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month ORDER BY MIN(posted_at)";
  $job_res = mysqli_query($conn, $job_query);
  $months = $counts = [];
  while ($row = mysqli_fetch_assoc($job_res)) {
    $months[] = $row['month'];
    $counts[] = $row['count'];
  }


  // Application stats
  $jobs_status = [
    'approved' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'approved'"))['c'],
    'rejected' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'rejected'"))['c'],
    'pending'  => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'pending'"))['c']
  ];

  // Application stats
  $applications = [
    'accepted' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM applications WHERE status = 'accepted'"))['c'],
    'rejected' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM applications WHERE status = 'rejected'"))['c'],
    'pending'  => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM applications WHERE status = 'pending'"))['c']
  ];

  // User roles
  $users = [
    'admin'     => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'admin'"))['c'],
    'employer'  => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'employer'"))['c'],
    'jobseeker' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'jobseeker'"))['c']
  ];

  // Jobs per category
  $cat_res = mysqli_query($conn, "SELECT categories.name, COUNT(jobs.id) AS job_count
                                  FROM categories
                                  LEFT JOIN jobs ON categories.id = jobs.category_id AND jobs.status = 'approved'
                                  GROUP BY categories.id");
  $cat_names = $cat_counts = [];
  while ($row = mysqli_fetch_assoc($cat_res)) {
    $cat_names[] = $row['name'];
    $cat_counts[] = $row['job_count'];
  }

  // Top applied jobs
  $top_res = mysqli_query($conn, "SELECT jobs.title, COUNT(applications.id) AS app_count
                                  FROM applications
                                  JOIN jobs ON applications.job_id = jobs.id
                                  GROUP BY jobs.id
                                  ORDER BY app_count DESC LIMIT 5");
  $top_titles = $top_apps = [];
  while ($row = mysqli_fetch_assoc($top_res)) {
    $top_titles[] = $row['title'];
    $top_apps[] = $row['app_count'];
  }

  echo json_encode([
    'months' => $months,
    'job_counts' => $counts,
    'jobs_status' => $jobs_status,
    'applications' => $applications,
    'users' => $users,
    'categories' => ['names' => $cat_names, 'counts' => $cat_counts],
    'top_jobs' => ['titles' => $top_titles, 'applications' => $top_apps]
  ]);
  exit;
}

elseif ($action === 'employee_data_analysis') {
  $user_id = $_SESSION['user']['id'];

  // Applications per job
  $job_query = "SELECT j.title, COUNT(a.id) AS apps
                FROM jobs j
                LEFT JOIN applications a ON j.id = a.job_id
                WHERE j.employer_id = $user_id
                GROUP BY j.id
                ORDER BY apps DESC
                LIMIT 6";
  $job_res = mysqli_query($conn, $job_query);
  $job_titles = $job_apps = [];
  while ($row = mysqli_fetch_assoc($job_res)) {
    $job_titles[] = $row['title'];
    $job_apps[] = $row['apps'];
  }

  // Job status
  $approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE employer_id = $user_id AND status = 'approved'"))['c'];
  $rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE employer_id = $user_id AND status = 'rejected' AND rejection_reason IS NOT NULL"))['c'];
  $pending  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE employer_id = $user_id AND status='pending'"))['c'];

  echo json_encode([
    'jobs' => [
      'titles' => $job_titles,
      'application_counts' => $job_apps
    ],
    'status' => [
      'approved' => $approved,
      'rejected' => $rejected,
      'pending'  => $pending
    ]
  ]);
  exit;
}




// profile
elseif ($action === 'get_profile') {
  $user_id = $_SESSION['user']['id'];  
  $result = $conn->query("SELECT username,full_name, email, phone, bio, profile_image FROM users WHERE id=$user_id");
  echo json_encode($result->fetch_assoc());
  exit;
}

elseif ($action === 'update_profile') {
    
    $user_id = $_SESSION['user']['id'];
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $bio = $_POST['bio'];
    $profile_picture = '';

    if (!empty($_FILES['profile_picture']['name'])) {
        $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $profile_picture = '../uploads/profile/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
        $conn->query("UPDATE users SET profile_image='$profile_picture' WHERE id=$user_id");
    }
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE id=$user_id");
    }

    $stmt = $conn->prepare("UPDATE users SET username=?, full_name=?, email=?, phone=?, bio=? WHERE id=?");
    $stmt->bind_param("sssssi", $username, $full_name, $email, $phone, $bio, $user_id);
    if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    } else {
    echo json_encode(["success" => false, "message" => "Error updating profile."]);
    }
    exit;
}



// Submit feedback
elseif ($action === 'submit_feedback') {
    $user_id = $_SESSION['user']['id'];  // Make sure user is logged in
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO feedback (user_id, message) VALUES ('$user_id', '$message')";
    if (mysqli_query($conn, $query)) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error submitting feedback.";
    }
    exit;
}

// Get all feedback
elseif ($action === 'get_feedback') {
    $query = "SELECT * FROM feedback ORDER BY submitted_at DESC";
    $result = mysqli_query($conn, $query);
    $feedback = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $feedback[] = $row;
    }

    echo json_encode($feedback);
    exit;
}

// Delete feedback
elseif ($action === 'delete_feedback') {
    $id = intval($_POST['id']);
    $query = "DELETE FROM feedback WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "Feedback deleted successfully.";
    } else {
        echo "Failed to delete feedback.";
    }
    exit;
}



else{
    // echo json_encode(['success' => false, 'message' => "Incorrect Action"]);


    // Read raw JSON from request
    $input = json_decode(file_get_contents('php://input'), true);


    $action1 = $input['action'];

    if ($action1 === 'reject_job') {
        // Validate required fields
        if (empty($input['job_id']) || empty($input['reason'])) {
            echo json_encode([
            'success' => false,
            'message' => 'Job id and reason are required.'
            ]);
            exit;
        }

        $job_id = intval($input['job_id']);
        $reason = $conn->real_escape_string($input['reason']);

        // Update the loan application
        $stmt = $conn->prepare("
        UPDATE jobs 
        SET status = 'rejected', rejection_reason = ? 
        WHERE id = ?
        ");
        $stmt->bind_param("si", $reason, $job_id);
        if ($stmt->execute()) {
            echo json_encode([
            'success' => true,
            'message' => 'Loan application has been rejected.'
            ]);
        } else {
            echo json_encode([
            'success' => false,
            'message' => 'Failed to reject loan: ' . $stmt->error
            ]);
        }
        $stmt->close();
        exit;
    } 

    else {
        echo json_encode([
        'success' => false,
        'message' => 'Invalid action'
        ]);
        exit;
    }

}




$conn->close();

?>