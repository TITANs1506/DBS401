<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($user->loggedIn()) {
	header("location: dashboard.php");
}

$loginMessage = '';
if (!empty($_POST["login"]) && $_POST["email"] != '' && $_POST["password"] != '') {
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];
	if ($user->login()) {
		echo "<script>alert('FLag: DBS{sql_1nj3ctj0n_succ3ss}');
		window.location.href='dashboard.php';
		</script>";
	} else {
		$loginMessage = 'Invalid login! Please try again.';
	}
}

include('inc/header.php');
?>
<title>Admin</title>
<?php include('inc/container.php'); ?>
<div class="container contact">
	<h2>Account</h2>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading" style="background:#00796B;color:white;">
				<div class="panel-title">Login and get the flag</div>
			</div>
			<div style="padding-top:30px" class="panel-body">
				<?php if ($loginMessage != '') { ?>
					<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $loginMessage; ?></div>
				<?php } ?>
				<form id="loginform" class="form-horizontal" role="form" method="POST" action="">
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" id="email" name="email" placeholder="email" style="background:white;" required>
					</div>
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password" placeholder="password" required>
					</div>
					<div style="margin-top:10px" class="form-group">
						<div class="col-sm-12 controls">
							<input type="submit" name="login" value="Login" class="btn btn-info">
						</div>
					</div>
					<div style="margin-top:10px" class="form-group">
						<div class="col-sm-12 controls">
							User: user@dbs.com<br>
							Pasword: No need password if you know SQL Injection xD<br>
							Hint: USER@BDS.COM==user@dbs.com
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php include('inc/footer.php'); ?>