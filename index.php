<?php
require_once('./backend/myid.php');
?>

<!doctype html>

<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>Koryo Stream Ver4</title>
	<link rel="stylesheet" href="styles.css?Ver=4">
</head>

<body>
<script type="text/javascript" src="prototype.js"></script>
<script type="text/javascript" src="ajaxupdater.js?=Ver4"></script>
<div class="title">
	<img border="0" src="./twitterlogo.png">&thinsp;<span class="title"><?php echo TAGNAME ?></span>
</div>

<br>

<div class="container">
  <div class="photo">
        <div id="itemphoto"></div>
  </div>

  <div class="item1">
        <div id="item1"></div>
</div>

  <div class="item2">
        <div id="item2"></div>
</div>

  <div class="item3">
        <div id="item3"></div>
</div>

  <div class="item4">
	<div id="item4"></div>
</div>

</div>

</body>
</html>
