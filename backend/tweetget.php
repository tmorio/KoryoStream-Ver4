<?php
require_once('./lib/Phirehose.php');
require_once('./lib/OauthPhirehose.php');

//各種キーの設定
define('TWITTER_CONSUMER_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('TWITTER_CONSUMER_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('OAUTH_TOKEN', 'xxxxxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('OAUTH_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('DB_HOST', 'localhost');
define('DB_NAME', 'hogehoge');
define('DB_ID', 'hogehoge');
define('DB_PASS', 'hogehogehoge');

class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {
    //文字コード設定(絵文字対策のためにUTF8MB4)
    $strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
    //DB接続試行
    try {
         $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
         $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
         echo $e->getMessage();
         exit;
    }

    //JSONデコード
    $data = json_decode($status, true);
    //タグ指定
    $tags = array('#koryosai2018','#koryosai','#morikapusantest');

    if (is_array($data) && isset($data['user']['screen_name'])) {
           //URL除去
           $result = preg_replace("(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)", '', $data['text']);
           //改行除去
           $result = preg_replace(array('/\r\n/','/\r/','/\n/'), '', $result);
           //ハッシュタグ除去
           $result = str_ireplace($tags, '', $result);

           //取得結果出力
           print "[取得]内容:";
           print $data['user']['screen_name'] . ': ' . $result . "\n";

           $username = $data['user']['name'];
           $userid = $data['user']['screen_name'];
           $iconurl = $data['user']['profile_image_url_https'];
           $strcontent = $result;

	         //ID位置取得
           $fp = fopen("counter.txt", 'r');
           $pointer = fgets($fp);
           fclose($fp);

           //後で変更すべし
           $mediaurl = "";

           //DRBUG
           print "DB挿入:" . $username . "," . $userid . "," . $iconurl . "," . $strcontent . "," . $mediaurl . "\n";

           //実行するSQL文設定
           $query = "UPDATE Data SET USER_NAME = :username, USER_ID = :userid, USER_ICON = :usericon, TEXT = :text, MEDIA = :media WHERE id = :id";
           //SQL実行準備
           $stmt = $dbh->prepare($query);
           //各キーに文章代入
           $params = array(':username' => $username, ':userid' => $userid, ':usericon' => $iconurl, ':text' => $strcontent, ':media' => $mediaurl, ':id' => $pointer);
           //INSERT実行
           $stmt->execute($params);


	   if($pointer >= 5){
		$pointer = 1;
	   }else{
		$pointer = $pointer + 1;
	   }

	   $cowriter = fopen("counter.txt", "w");
           @fwrite($cowriter, $pointer);
           fclose($cowriter);
    }
  }
}


$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
//検索ターゲット指定
$sc->setTrack(array('#koryosai2018','hello'));   //取得したいタグを入れる
//$sc->setTrack(array('Hello','FGO'));
$sc->consume();