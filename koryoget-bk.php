<?php
require_once('./lib/Phirehose.php');
require_once('./lib/OauthPhirehose.php');


class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {
    $data = json_decode($status, true);
    $spamflag = 0;
    $photostatus = 0;
    $bannedusers = 0;
    $count = 0;
    $area = "a";
    $secret = 0;
    $fontset = '<span class="norm">';
    $prmode = 0;
    $setdfw = 0;
    $setdf = 0;
    $prcount = 0;
    $atumori = '';

    $rtcheck = array('RT','@');
    $nguser = array('交換','まとめ','ニュース','攻略');
    $deleteword = array('#koryosai2017','#koryosai');
    $cmd = array('#cmdsecret','#cmdred','#cmdgreen','#cmdblue','#cmdpr');

    if (is_array($data) && isset($data['user']['screen_name'])) {
        $result = str_ireplace('/\r\n|\r|\n/', '', $data['text']);
	$result = preg_replace('/https?:\/\/[0-9a-z_,.:;&=+*%$#!?@()~\'\/-]+/i', '', $result);
        $result = str_replace($deleteword, '', $result);

	 if (strstr($result, '#cmdsecret')) {
                $secret = 1;
        }
         if (strstr($result, '#cmdred')) {
                $fontset = '<span class="red">';
        }
         if (strstr($result, '#cmdgreen')) {
                $fontset = '<span class="green">';
        }
         if (strstr($result, '#cmdblue')) {
                $fontset = '<span class="blue">';
        }
         if (strstr($result, '#cmdpr')) {
                $prmode = 1;
        }

        $result = str_replace($cmd, '', $result);
	$result = htmlentities($result, ENT_QUOTES, 'UTF-8');


foreach ($nguser as $wordu)
{
    if (mb_strpos($data['user']['name'], $wordu, 0, "UTF-8") !== false){
        $bannedusers = 1;
    }else{
        $bannedusers = 0;
        }
}

foreach ($nguser as $wordg)
{
    if (mb_strpos($data['user']['screen_name'], $wordg, 0, "UTF-8") !== false){
        $bannedusers = 1;
    }else{
        $bannedusers = 0;
        }
}

foreach ($rtcheck as $wordr)
{
    if (mb_strpos($result, $wordr, 0, "UTF-8") !== false){
        $retweetflag = 1;
    }else{
        $retweetflag = 0;
        }
}

 	if($bannedusers == 1){
        print "[除外]このユーザーはBANしています。 対象ID: " . $data['user']['screen_name'];
        }elseif($spamflag == 1){
        print "[除外]NGワードが含まれているかスパムです。 対象: " . $data['user']['screen_name'] . ': ' . $result . "\n";
        }elseif($retweetflag == 1){
       print "[除外]RT/@ツイートです。 対象: " . $data['user']['screen_name'] . ': ' . $result . "\n";
	}

	if($prmode == "1" && $retweetflag == "0" && $bannedusers == "0" && $spamflag == "0"){
        $setdfw = '<span id="headset"><p>' . '<img src="' . $data['user']['profile_image_url'] . '" width="70">' . " " . $data['user']['name'] . "：" .  $result . "</p></span>";
        $setdf = fopen("head.html", "w");
        @fwrite($setdf, $setdfw);
        fclose($setdf);
	}

        if($retweetflag == "0" && $bannedusers == "0" && $spamflag == "0"){

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

			if($secret == '1'){
					 $writedata = '<p>' . $fontset  . $result . $atumori . "</span></p>";
					}else{
                                 	 $writedata = '<p><img src="' . $data['user']['profile_image_url'] . '" align="top">' . $fontset . " "  . $data['user']['name'] . ": " . $result . $atumori . "</span></p>";
					}

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
		$writedata =  '<img src="' . $data['extended_entities']['media'][0]['media_url'] . '">' . '<br><div id="photouser' . $area . '">' . '<p><img src="' . $data['user']['profile_image_url'] . '" align="top">' ." "  . $data['user']['name'] . ": " . $result . $atumori . "</div></p>";

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
