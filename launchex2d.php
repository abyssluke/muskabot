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

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 過去の日付

//メンテ臨時
//odie(true,true);
//明日メンテ用。おきったーら解除！！！
//$mna = date("G");
//if($mna == 2 || $mna == 3 || $mna == 4) odie(true,true);

if(file_exists("/path/to/MuskaLockW.lock")) odie(true,true);

define("UA_PRM","Muskabot4WS/0.1beta (ServerDownChecker; PHP/".phpversion().")");

$twitter_u	= "muskabot";
$twitter_p	= "AnyPassword";
$twitter_update = "http://api.wassr.jp/statuses/update.json?source=MuskaBot-TwSDC%20For%20Wassr&status=";


$api_name	= "User Timeline(json)";
$twitter_api	= "http://twitter.com/statuses/user_timeline/muskabot.json";
//$twitter_api	= "http://doesntexist/statuses/user_timeline/muskabot.json";
$getstime = 60;
$lfadtime = 30;
$letsgocount = 5;
$a_act = "Twitter鯖落ちかも?のお知らせ";

$stx = "Starting...\n";
if(strpos($_SERVER['HTTP_USER_AGENT'],"Wget") === 0) $stx .= "System: From wget(@abyssluke's Macintosh cron?)!\n";

$fct = filectime("/path/to/serverdown.txt");
$lfct = date("Y/m/d G:i:s",$fct);

if($_GET['lock']=="off") odie(false,true,"Force Unlock.\n");

if(file_exists("./tmp/lock3.lock")) {
	if(filectime("./tmp/lock3.lock") + $lfadtime <= time()) {
		// unlink("./tmp/lock.lock");
		$stx .= "System:Unlocked!!\n";
	} else {
		odie(true,true);
	}
}

$fp = @fopen("./tmp/lock3.lock" , "w");
fwrite($fp,"LOCK");
fclose($fp);

if($fct+$getstime <= time() || $_GET['force_']=="1") {
//echo "curlを準備します...";
$stx .= "System:Getting timeline...\n";
		$curl_twitter = curl_init();

//		echo "完了しました。<br />パラメータを設定しています...";
		curl_setopt($curl_twitter, CURLOPT_URL, $twitter_api);
		//curl_setopt($curl_twitter, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl_twitter, CURLOPT_HEADER, false);
		//curl_setopt($curl_twitter, CURLOPT_USERPWD, "$twitter_user:$twitter_pass");
		//curl_setopt($curl_twitter, CURLOPT_POST, true);
		curl_setopt($curl_twitter, CURLOPT_USERAGENT, UA_PRM);
		curl_setopt($curl_twitter, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_twitter, CURLOPT_TIMEOUT, 15);
		//curl_setopt($curl_twitter, CURLOPT_FILE, $fpzz);
//		echo "完了しました。<br />{$api_name}を取得しています...";
		$curl_result = curl_exec($curl_twitter);
		$curl_status = curl_getinfo($curl_twitter,CURLINFO_HTTP_CODE);
		$curl_errcode = curl_errno($curl_twitter);
		$curl_errmsg = curl_error($curl_twitter);
		if($curl_status !== 200) { 
		$stx .= "Warning:Status isn't 200\n";
		$cantflag = true;
		switch($curl_errcode){
		 case 6:
		  $a_typ = "名前解決ができない";
		  $a_act = "もしかしてこのプログラムが動いているレンタル鯖とかDNSとかがおかしいかも?";
		  break;
		 case 7:
		 case 28:
		  $a_typ = "サーバーに繋がらない";
		  break;
		 default:
		  $a_typ = "サーバーエラーを返す";
		  break;
		}
		}
//		//echo "完了しました。<br />curlを閉じます...";
		curl_close($curl_twitter);
//		//echo "完了しました。<br />jsonを配列形式でデコードします...";
////echo "$curl_result\n";
if(!$cantflag){
 $jsjs = json_decode($curl_result,true);
 ////echo "完了しました。<br /><br />";
 if(!is_array($jsjs)) { $cantflag = true; $a_typ = "JSONがパースできない"; } else {

 //くじらメンテナンスの時はjsonとかが真っ白になるはずなので…
 if(!trim($jsjs[0]["text"])) { $cantflag = true; $a_typ = "JSONが真っ白(メンテ?)な"; }

 }
}
$xfaa = file_get_contents("/path/to/serverdown.txt");

if($cantflag) {
 $xfaa++;
 }else{
 if($xfaa >= $letsgocount) { $xfab = $xfaa; $goaway = true; }
 $xfaa = 0;
}
//Post Once
if($xfaa == $letsgocount) $goaway = true;

$cex = @fopen("/path/to/serverdown.txt" , "w");
$lockstatus = flock($cex,LOCK_EX);
if($lockstatus === FALSE) { sleep(1); $lockstatus = flock($cex,LOCK_EX); }
fwrite($cex,$xfaa);
fclose($cex);
$bakadome = rand(0,15);
if($goaway) {
 if($xfaa == 0) {
  $gomim = "Twitterが復旧したようですよ、閣下。(復旧までの間{$xfab}回(約1分おきにチェック)、エラーが観測されました)";
 }else{
  $gomim = "見ろ! {$a_typ}Twitterがゴミのようだ!({$a_act})";
  if($bakadome == 5) $gomim = "バカどもにはちょうどいい目くらましだ(Twitterまだ落ちてます Count:{$xfaa})";
 }


$message = urlencode($gomim);
$twitter_message = $twitter_update . $message;
//echo "$twitter_message<br />";

$curl_twitterx = curl_init();
		curl_setopt($curl_twitterx, CURLOPT_URL, $twitter_message);
		curl_setopt($curl_twitterx, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl_twitterx, CURLOPT_HEADER, false);
		curl_setopt($curl_twitterx, CURLOPT_USERPWD, $twitter_u.":".$twitter_p);
		curl_setopt($curl_twitterx, CURLOPT_POST, true);
		curl_setopt($curl_twitterx, CURLOPT_USERAGENT, UA_PRM);
		curl_setopt($curl_twitterx, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_twitterx, CURLOPT_TIMEOUT, 15);
		$curl_result = curl_exec($curl_twitterx);
		$curl_status = curl_getinfo($curl_twitterx,CURLINFO_HTTP_CODE);
		$curl_errcode = curl_errno($curl_twitterx);
		$curl_errmsg = curl_error($curl_twitterx);
		if($curl_status !== 200) { 
		// if($curl_errcode == 28) //echo "<br />どうやらconnectがタイムアウトで失敗した。鯖落ちの予感？<br />";
		}
		curl_close($curl_twitterx);
		
		$twitter_update = "http://api.wassr.jp/channel_message/update.json?name_en=twitter&body=";
		$twitter_message = $twitter_update . $message;
$curl_twitterx = curl_init();
		curl_setopt($curl_twitterx, CURLOPT_URL, $twitter_message);
		curl_setopt($curl_twitterx, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl_twitterx, CURLOPT_HEADER, false);
		curl_setopt($curl_twitterx, CURLOPT_USERPWD, $twitter_u.":".$twitter_p);
		curl_setopt($curl_twitterx, CURLOPT_POST, true);
		curl_setopt($curl_twitterx, CURLOPT_USERAGENT, UA_PRM);
		curl_setopt($curl_twitterx, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_twitterx, CURLOPT_TIMEOUT, 15);
		$curl_result = curl_exec($curl_twitterx);
		$curl_status = curl_getinfo($curl_twitterx,CURLINFO_HTTP_CODE);
		$curl_errcode = curl_errno($curl_twitterx);
		$curl_errmsg = curl_error($curl_twitterx);
		if($curl_status !== 200) { 
		// if($curl_errcode == 28) //echo "<br />どうやらconnectがタイムアウトで失敗した。鯖落ちの予感？<br />";
		}
		curl_close($curl_twitterx);
}
//echo "処理しました。<br />";
//echo "<br />jsonから配列に変換したときの中身<br />";
//echo "<pre>";
//print_r($jsjs);
//echo "</pre>";
$stx .= "Okay:$gomim\n";
}else{ $stx .= "System: Do not update.\n"; $donotupdate = true; }
//echo "</body></html>";
odie(false,$donotupdate,$stx);

function odie($xmode = false,$dupd = false,$statmsg = "No Prm."){
global $jsjs;
/*
		if(!$dupd && isset($jsjs)){
		$fpzz = @fopen("bombtter_raw.json" , "w");
		fwrite($fpzz,$jsjs[0]["created_at"]);
		fclose($fpzz);
		$statmsg .= "Updated:bombtter_raw.json => ".$jsjs[0]["created_at"]."\n";
		}
if(!$xmode) { //壊れたらあれなので。。
$pfix = date("Ymd");
$cex = @fopen("/path/to/muskabot".$pfix.".log" , "a");
$lockstatus = flock($cex,LOCK_EX);
if($lockstatus === FALSE) { sleep(1); $lockstatus = flock($cex,LOCK_EX); }
fwrite($cex,"[".date("Y/m/d G:i:s")."]\n$statmsg\n");
fclose($cex);
if(substr(sprintf('%o', fileperms("/path/to/muskabot".$pfix.".log")), -4) !== "0606") chmod("/path/to/muskabot".$pfix.".log", 0606);
}
*/
if(file_exists("./tmp/lock3.lock") && !$xmode) unlink("./tmp/lock3.lock");

header("Content-Type: image/gif");
@readfile("/path/to/blank.gif") or die("1px image load error");
exit;
}

?>