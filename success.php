<!DOCTYPE html>
<html lang="en" dir="ltr">
<body>
<div class="success">
    <h1>Hello</h1>
    <h3>User <?= $_POST['username'] ?? 'NULL'?></h3>
    <h3>Type <?= $_POST['type'] ?? 'NULL'?></h3>
    <h3>Email <?= $_POST['email'] ?? 'NULL'?></h3>
</div>
</body>
</html>
