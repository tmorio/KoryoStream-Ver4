//Twitterから特定のタグが付いたツイートを取得するやつ
//やっぱPDOでDBに書き込みたいよね(後ほど実装〜)

<?php
require_once('./lib/Phirehose.php');
require_once('./lib/OauthPhirehose.php');


class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {
    //JSONデコード
    $data = json_decode($status, true);

    if (is_array($data) && isset($data['user']['screen_name'])) {
        //改行コード変換
        $result = str_ireplace('/\r\n|\r|\n/', '', $data['text']);
        //URL削除
        $result = preg_replace('/https?:\/\/[0-9a-z_,.:;&=+*%$#!?@()~\'\/-]+/i', '', $result);
        //ツイートからハッシュタグを除去
        $result = str_replace($tags, '', $result);
        $result = htmlentities($result, ENT_QUOTES, 'UTF-8');

        print "[書込]内容:";
        print $data['user']['screen_name'] . ': ' . $result . "\n";
        }
     }
}

//APIキーの設定
define("TWITTER_CONSUMER_KEY", "XXXXXXXXXXXXXXXXXXX");
define("TWITTER_CONSUMER_SECRET", "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
define("OAUTH_TOKEN", "XXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
define("OAUTH_SECRET", "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
//検索ターゲット指定
$sc->setTrack(array('#koryosai2018', '#koryosai'));
//$sc->setTrack(array('Hello','FGO'));$sc->consume();
