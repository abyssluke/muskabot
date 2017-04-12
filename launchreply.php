<?php
/*

MIT License

Copyright (c) 2017 @abyssluke 

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

/*
    このスクリプトは、@muskabot稼働当時使用していたスクリプトを
    公開向けに一部修正したものです。
    実際に使用していたソースとは若干異なりますので、ご了承ください。
*/

//require_once 'launchex.DoFilter.inc.php';
//require_once 'Services/TinyURL.php';
//odie(true,true);

require_once "MutexFile.php";
require_once "muska-core/twitterOAuth.php";
$mutex = new MutexFile("/path/to/reply.lock");

usleep(mt_rand(50000,400000));
//$tinyurl = new Services_TinyURL();

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 過去の日付

//メンテ臨時
//odie(true,true);
//明日メンテ用。おきったーら解除！！！
//$mna = date("G");
//if($mna == 2 || $mna == 3 || $mna == 4) odie(true,true);


define("UA_PRM","Muskabot/0.2 (Rp; PHP/".phpversion().")");

$oauth_t = "(Token)";
$oauth_s = "(Secret)";
$twitter_update = "https://api.twitter.com/1/statuses/update.json";


$api_name	= "Reply Timeline(json)";
$twitter_api	= "https://api.twitter.com/1/statuses/mentions.json";

$getstime = 120;
$lfadtime = 30;
$stx = "Starting...\n";
if(strpos($_SERVER['HTTP_USER_AGENT'],"Wget") === 0) $stx .= "System: From wget(@abyssluke's Macintosh cron?)!\n";

$fct = filectime("replies.json");
$lfct = date("Y/m/d G:i:s",$fct);

if($_GET['lock']=="off") odie(false,true,"Force Unlock.\n");

if(!$mutex->lock()) odie(true,true);
/*
if(file_exists("./tmp/lock.lock")) {
	if(filectime("./tmp/lock.lock") + $lfadtime <= time()) {
		// unlink("./tmp/lock.lock");
		$stx .= "System:Unlocked!!\n";
	} else {
		odie(true,true);
	}
}
*/
$fp = @fopen("./tmp/lock.lock" , "w");
fwrite($fp,"LOCK");
fclose($fp);

if($fct+$getstime <= time() || $_GET['force_']=="1") {
//echo "curlを準備します...";


$olddate = file_get_contents("replies.json");
$lastid = $olddate;
 $stx .= "System:Getting timeline...\n";
//		$curl_twitter = curl_init();
$to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest($twitter_api, array('since_id' => $olddate), 'GET');

//		echo "完了しました。<br />パラメータを設定しています...";
//		curl_setopt($curl_twitter, CURLOPT_URL, $twitter_api."?since_id=".$olddate);


//		if($to->lastStatusCode !== 200) { 
//	        	$stx .= "Warning:Status isn't 200\n";
	 	//if($curl_errcode == 28) echo "<br />どうやらconnectがタイムアウトで失敗した。鯖落ちの予感？<br />";
//		}

 $jsjs = json_decode($content,true);
////echo "完了しました。<br /><br />";
 if(!is_array($jsjs)) { $stx .= "Error:json_decode return not array! Exiting...\n"; odie(false,true,$stx); } else {

  //$nono = mt_rand(0,9);
  //echo "配列No{$nono}。<br />";

  //$dummymode = mt_rand(0,199);

  if(empty($jsjs)) { $stx .= "System: No update!\n"; odie(false,false,$stx); }
  if(!trim($jsjs[0]["created_at"])) { $stx .= "Error: 'created_at' data is null! Exiting...\n"; odie(false,true,$stx); }
}
$jsjs = array_reverse($jsjs);
foreach($jsjs as $nowpost) {
$gomim = "";
$ce = false;
$goaway = false;
$is_bal = false;
$stx .= "System:Post No ".$nowpost["id_str"]."\n";
$lastid = $nowpost["id_str"];
 
//echo "爆破物:".$expla[2]."<br />";

//echo "ムスカが投稿するテストをするようです。<br />";
$stx .= "Reply:".$nowpost["text"]."\n";


require("ReplyPattern.php");

if(file_exists("/path/to/replystop.lock")) $goaway=false;
if($goaway || $ce){

$message = urlencode($gomim);
$twitter_message = $twitter_update . $message;
//echo "$twitter_message<br />";
$tadd = "";
$reqdd = null;
$reqdd = array("status" => $gomim);
if(!$is_bal) $reqdd["in_reply_to_status_id"] = $nowpost['id_str'];
$to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest($twitter_update, $reqdd, 'POST');

//		if($to->lastStatusCode !== 200) { 
//	        	$stx .= "Warning:Status isn't 200\n";
	 	//if($curl_errcode == 28) echo "<br />どうやらconnectがタイムアウトで失敗した。鯖落ちの予感？<br />";
//		}

//echo "処理しました。<br />";
//echo "<br />jsonから配列に変換したときの中身<br />";
//echo "<pre>";
//print_r($jsjs);
//echo "</pre>";
$stx .= "Okay:$gomim\n";
}else{ $stx .= "System: No problem ;-)\n"; }

}

}else{ $stx .= "System: Do not update.\n"; $donotupdate = true; }
//echo "</body></html>";
odie(false,$donotupdate,$stx);

function odie($xmode = false,$dupd = false,$statmsg = "No Prm."){
global $jsjs,$lastid;
		if(!$dupd && isset($jsjs)){
		$fpzz = @fopen("replies.json" , "w");
		fwrite($fpzz,$lastid);
		fclose($fpzz);
		$statmsg .= "Updated:replies.json => ".$lastid."\n";
		}
if(!$xmode) { //壊れたらあれなので。。
$pfix = date("Ymd");
$cex = @fopen("/path/to/muskabot".$pfix.".log" , "a");
$lockstatus = flock($cex,LOCK_EX);
if($lockstatus === FALSE) { sleep(1); $lockstatus = flock($cex,LOCK_EX); }
fwrite($cex,"[".date("Y/m/d G:i:s")."]\n$statmsg\n");
fclose($cex);
// if(substr(sprintf('%o', fileperms("/path/to/muskabot".$pfix.".log")), -4) !== "0606") chmod("/path/to/muskabot".$pfix.".log", 0606);
}

if(file_exists("./tmp/lock.lock") && !$xmode) unlink("./tmp/lock.lock");

header("Content-Type: image/gif");
@readfile("/path/to/blank.gif") or die("1px image load error");
exit;
}

?>