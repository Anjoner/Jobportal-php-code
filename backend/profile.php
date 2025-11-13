<?php
session_start();
require 'db.php';
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit;
}

$usersFile = 'users.json';
$email = $_SESSION['email'];
$users = json_decode(file_get_contents($usersFile), true);
$user = $users[$email];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save profile data
    $users[$email]['profile'] = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'gender' => $_POST['gender'],
        'department' => $_POST['department'],
        'institution' => $_POST['institution'],
        'gpa' => $_POST['gpa'],
    ];

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
        $cvPath = 'uploads/' . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
        $users[$email]['profile']['cv'] = $cvPath;
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photoPath = 'uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
        $users[$email]['profile']['photo'] = $photoPath;
    }

    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    echo "<p>Profile updated!</p>";
}
$profile = $user['profile'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Complete Profile</title>
</head>
<body>
<style>
  .profile-picture {
      width: 100%;
      max-width: 150px;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer; /* Change cursor to pointer */
  }
</style>

<?php if (!empty($profile['photo'])): ?>
    <h3>Profile Picture:</h3>
    <img src="<?= $profile['photo'] ?>" class="profile-picture" id="profileImage">
    <input type="file" name="photo" id="photoUpload" style="display: none;">
<?php endif; ?>

<script>
  document.getElementById('profileImage').addEventListener('click', function() {
      document.getElementById('photoUpload').click();
  });

  document.getElementById('photoUpload').addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              document.getElementById('profileImage').src = e.target.result; // Update the image source
          };
          reader.readAsDataURL(file);
      }
  });
</script>

<h2>Welcome, <?= isset($user['username']) ? htmlspecialchars($user['username']) : 'User' ?></h2>

  <form method="post" enctype="multipart/form-data">
 
    First Name: <input type="text" name="first_name" value="<?= $profile['first_name'] ?? '' ?>"><br><br>
    Last Name: <input type="text" name="last_name" value="<?= $profile['last_name'] ?? '' ?>"><br><br>
    Gender:
    <select name="gender">
      <option value="Male" <?= ($profile['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
      <option value="Female" <?= ($profile['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
      <option value="Other" <?= ($profile['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
    </select><br><br>
    Studied Department: <input type="text" name="department" value="<?= $profile['department'] ?? '' ?>"><br><br>
    Graduated Institution: <input type="text" name="institution" value="<?= $profile['institution'] ?? '' ?>"><br><br>
    GPA: <input type="text" name="gpa" value="<?= $profile['gpa'] ?? '' ?>"><br><br>
    Upload CV: <input type="file" name="cv"><br><br>
    Upload Photo: <input type="file" name="photo"><br><br>
    <input type="submit" value="save">
  </form>
  <?php if (!empty($profile['cv'])): ?>
    <h3>CV:</h3>
    <a href="<?= $profile['cv'] ?>" download>Download CV</a>
  <?php endif; ?>
</body>
</html>