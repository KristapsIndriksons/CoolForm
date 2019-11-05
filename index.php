<?php
require_once './Validate.php';
if(isset($_POST['submit'])){
  if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST['username']) && !preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $_POST['password'])){
    echo "Invalid username or password";
  } else {
    $validate = new Validate();
    $validation = $validate->check_user();
    if($validation->passed()){
      echo "passed";
    } else {
      foreach ($validation->errors() as $error) {
        echo $error, "<br>";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <title></title>
  <!-- <link rel="stylesheet" href="./css/style.css" /> -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet" /> -->
</head>

<body>
  <div class="signup-form">
    <form class=""  method="post">
      <h1>Login</h1>
      <input type="text" name="username" placeholder="Username" class="txtb" />
      <input type="password" name="password" placeholder="Password" class="txtb" />
      <input type="submit" name='submit' value="Create Account" class="signup-btn" />
    </form>
  </div>
</body>

</html>