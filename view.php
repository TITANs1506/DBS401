<?php
include_once 'config/Database.php';
include_once 'class/Articles.php';
$database = new Database();
$db = $database->getConnection();

$article = new Articles($db);

$article->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $article->getArticles();

include('inc/header.php');

?>
<title>Hololive</title>
<link href="css/style.css" rel="stylesheet" id="bootstrap-css">

<?php include('inc/container.php'); ?>
<div class="container">
	<div class="header-right">
		<a class="btn btn-blog pull-right" href="./admin" style="color: white;">My account</a>
	</div>
	<?php
	while ($post = $result->fetch_assoc()) {
		$date = date_create($post['created']);
		$message = str_replace("\n\r", "<br><br>", $post['message']);
	?>
		<div class="col-md-10 blogShort">
			<h2><?php echo $post['title']; ?></h2>
			<em><strong>Published on</strong>: <?php echo date_format($date, "d F Y");	?></em> <br>
			<em><strong>Category:</strong> <?php echo $post['category']; ?></em>
			<br><br>
			<article>
				<p><?php echo $message; ?> </p>
			</article>
		</div>
	<?php } ?>

	<div class="col-md-12 gap10"></div>
	<?php include('inc/footer.php'); ?>