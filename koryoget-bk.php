//Twitterから特定のタグが付いたツイートを取得するやつ
//やっぱPDOでDBに書き込みたいよね(後ほど実装〜)

<?php
require_once('./lib/Phirehose.php');
require_once('./lib/OauthPhirehose.php');


class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {
    $data = json_decode($status, true);
    $photostatus = 0;
    $count = 0;
    $area = "a";
    $fontset = '<span class="norm">';
    $prmode = 0;
    $setdfw = 0;
    $setdf = 0;
    $prcount = 0;

    $rtcheck = array('RT','@');
    $tags = array('#koryosai2018','#koryosai');

    if (is_array($data) && isset($data['user']['screen_name'])) {
        $result = str_ireplace('/\r\n|\r|\n/', '', $data['text']);
	$result = preg_replace('/https?:\/\/[0-9a-z_,.:;&=+*%$#!?@()~\'\/-]+/i', '', $result);
        $result = str_replace($tags, '', $result);
	$result = htmlentities($result, ENT_QUOTES, 'UTF-8');

foreach ($rtcheck as $wordr)
{
    if (mb_strpos($result, $wordr, 0, "UTF-8") !== false){
        $retweetflag = 1;
    }else{
        $retweetflag = 0;
        }
}

	if($retweetflag == 1){
	       print "[除外]RT/@ツイートです。 対象: " . $data['user']['screen_name'] . ': ' . $result . "\n";
		}

	if($prmode == "1" && $retweetflag == "0"){
        $setdfw = '<span id="headset"><p>' . '<img src="' . $data['user']['profile_image_url'] . '" width="70">' . " " . $data['user']['name'] . "：" .  $result . "</p></span>";
        $setdf = fopen("head.html", "w");
        @fwrite($setdf, $setdfw);
        fclose($setdf);
	}

        if($retweetflag == "0"){

        $fpp = fopen("photocounter.txt", 'r');
        $photocount = fgets($fpp);
        fclose($fpp);
        $fp = fopen("counter.txt", 'r');
        $count = fgets($fp);
        fclose($fp);

        if(empty($data['extended_entities'])){

                if($count < 9){
                }else{
                        $count = 3;
                       }

               			 $writedata = '<p><img src="' . $data['user']['profile_image_url'] . '" align="top">' . $fontset . " "  . $data['user']['name'] . ": " . $result  . "</span></p>";
                                 $work = "id" . $count . ".html";
                                 $writer = fopen($work, "w");
                                 @fwrite($writer, $writedata);
                                 fclose($writer);
                                 $nowpro = $count;
                                 $count = $count + 1;
                                 $countdata = $count;
                                 $cowriter = fopen("counter.txt", "w");
                                 @fwrite($cowriter, $countdata);
                                 fclose($cowriter);

                                 print "[書込]DataID:" . $nowpro . "に書き込みました。 内容:";
                                 print $data['user']['screen_name'] . ': ' . $result . "\n";

        }elseif($data['extended_entities']['media'][0]['type'] == "photo"){
      if($photocount < 3){
        }else{
                $photocount = 1;
                }

      if($photocount == "2"){
                $area = "b";
                }
		$writedata =  '<img src="' . $data['extended_entities']['media'][0]['media_url'] . '">' . '<br><div id="photouser' . $area . '">' . '<p><img src="' . $data['user']['profile_image_url'] . '" align="top">' ." "  . $data['user']['name'] . ": " . $result  . "</div></p>";

        $work = "phid" . $photocount . ".html";
        $writer = fopen($work, "w");
        @fwrite($writer, $writedata);
        fclose($writer);
        $nowpro = $photocount;
        $photocount = $photocount + 1;
        $photocountdata = $photocount;
        $copwriter = fopen("photocounter.txt", "w");
        @fwrite($copwriter, $photocountdata);
        fclose($copwriter);

        print "[書込][Photo]DataID:" . $nowpro . "に書き込みました。 内容:";
        print $data['user']['screen_name'] . ': ' . $result . "\n";
        }
    }
    }
  }
}

define("TWITTER_CONSUMER_KEY", "XXXXXXXXXXXXXXXXXXX");
define("TWITTER_CONSUMER_SECRET", "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
define("OAUTH_TOKEN", "XXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
define("OAUTH_SECRET", "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->setTrack(array('#koryosai2017', '#koryosai'));
//$sc->setTrack(array('Hello','FGO'));
$sc->consume();
