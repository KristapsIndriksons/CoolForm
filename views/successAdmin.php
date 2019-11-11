<?php session_start();

$username = $_SESSION['username'] ?? null;
$type = $_SESSION['type'] ?? null;
$email = $_SESSION['email'] ?? null

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<body>
<div class="success">
    <h1>Hello Admin!</h1>
    <?php if ($username): ?>
        <h3>User: <?= $username ?></h3>
    <?php endif; ?>
    <?php if ($email): ?>
        <h3>Email: <?= $email ?></h3>
    <?php endif; ?>
</div>
</body>
</html>
