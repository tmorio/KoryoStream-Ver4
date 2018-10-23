<?php

require_once('./backend/myid.php');

//文字コード設定(絵文字対策のためにUTF8MB4)
$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
//DB接続
try {
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch (PDOException $e) {
	 exit('DB Connect error.'.$e->getMessage());
	}

        $id = (int)$_GET['id'];
	if($id <= 5){
		$sql = "SELECT * FROM Data WHERE id = " . $id;
		$stmt = $pdo->query($sql);
		while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$username = $row["USER_NAME"];
                	$userid = $row["USER_ID"];
			$iconurl = $row["USER_ICON"];
			$text = $row["TEXT"];
			$media = $row["MEDIA"];
		}
	}

	$html = '<div class="areasub">' . '<img src="' . $iconurl . '">&nbsp;' . $username ." @" .$userid . '</div><div class="textareapink"><div class="marquee"><div class="marquee-innera">' . $text . '</div></div></div>';

	switch($id){
	case 1:
		echo '<div class="bigarea"><img src="' . $media . '" class="bigpic"></div><div class="area"><img src=" ' . $iconurl . '">&nbsp;' . $username . ' @' . $userid . '</div><br><div class="textarea"><div class="marquee"><div class="marquee-inner">' . $text . '</div></div></div>';
		break;
	case 2:
		echo $html;
		break;
	case 3:
		echo '<div class="areasub">' . '<img src="' . $iconurl . '">&nbsp;' . $username ." @" .$userid . '</div><div class="textareagreen"><div class="marquee"><div class="marquee-innera">' . $text . '</div></div></div>';
		break;
	case 4:
		echo '<div class="areasub">' . '<img src="' . $iconurl . '">&nbsp;' . $username ." @" .$userid . '</div><div class="textareablue"><div class="marquee"><div class="marquee-innera">' . $text . '</div></div></div>';
		break;
	case 5:
		echo '<div class="areasub">' . '<img src="' . $iconurl . '">&nbsp;' . $username ." @" .$userid . '</div><div class="textareayellow"><div class="marquee"><div class="marquee-innera">' . $text . '</div></div></div>';
		break;
	default:
		echo 'IDがセットされていません';
		break;
	}
