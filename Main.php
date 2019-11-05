<?php
require_once './Validate.php';
if(isset($_POST['submit'])){
  $validate = new Validate();
  $validation = $validate->check_user($_POST, array(
      'username' => 'users',
      'password' => 'users' 
  ));
  
  if($validation->passed()){
    echo "passed";
  } else {
    foreach ($validation->errors() as $error) {
      echo $error, "<br>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
<form class="log-in-out-from" id="signupForm" method="POST">
            <label for="username">Lietotājvārds</label>
            <input type="text" name="username" id="username"> <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"><br>
            <input type="submit" value="Apstiprināt" name='submit'>
</body>
</html>