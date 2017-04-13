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

require_once 'launchex.DoFilter.inc.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 過去の日付
//header("Content-Type: text/html;charset=UTF-8");
//メンテ臨時
//odie(true,true);
if(file_exists("/path/to/MuskaLockW.lock")) odie(true,true);

define("UA_PRM","Muskabot4WS/0.1beta (PHP/".phpversion().")");

$twitter_u	= "muskabot";
$twitter_p	= "AnyPassword";
$twitter_update = "http://api.wassr.jp/statuses/update.json?source=MuskaBot%20For%20Wassr&status=";


$api_name	= "User Timeline(json)";
$twitter_api	= "http://api.wassr.jp/statuses/user_timeline.json?id=kyubotter";

$getstime = 60;
$lfadtime = 30;
$stx = "Starting...\n";
if(strpos($_SERVER['HTTP_USER_AGENT'],"Wget") === 0) $stx .= "System: From wget(@abyssluke's Macintosh cron?)!\n";

$fct = filectime("bombtter_raw2.json");
$lfct = date("Y/m/d G:i:s",$fct);

if($_GET['lock']=="off") odie(false,true,"Force Unlock.\n");

if(file_exists("./tmp/lock2.lock")) {
	if(filectime("./tmp/lock2.lock") + $lfadtime <= time()) {
		// unlink("./tmp/lock2.lock");
		$stx .= "System:Unlocked!!\n";
	} else {
		odie(true,true);
	}
}

$fp = @fopen("./tmp/lock2.lock" , "w");
fwrite($fp,"LOCK");
fclose($fp);
$onde = 0;

if($fct+$getstime <= time() || $_GET['force_']=="1" || $onde == 1) {
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
		//if($curl_errcode == 28) echo "<br />どうやらconnectがタイムアウトで失敗した。鯖落ちの予感？<br />";
		}
//		//echo "完了しました。<br />curlを閉じます...";
		curl_close($curl_twitter);
//		//echo "完了しました。<br />jsonを配列形式でデコードします...";
////echo "$curl_result\n";
$jsjs = json_decode($curl_result,true);
////echo "完了しました。<br /><br />";
if(!is_array($jsjs)) { $stx .= "Error:json_decode return not array! Exiting...\n"; odie(false,true,$stx); } else {

//$nono = mt_rand(0,9);
//echo "配列No{$nono}。<br />";
$dummymode = mt_rand(0,199);

$olddate = file_get_contents("bombtter_raw2.json");
if($dummymode !== 5 && $dummymode !== 35 && $jsjs[0]["epoch"] == $olddate) { $stx .= "System:No update!\n"; odie(false,false,$stx); }


$stx .= "System:Post No ".$jsjs[0]["id"]."\n";
//echo "<br />パースしてみるよ。<br />";
//echo "explode...<br /><pre>";
$explaz = $jsjs[0]["text"];
//print_r($expla);
//echo "</pre>爆破フラグ:";
//echo "$explaz<br />";
//関係ないやつをkill
if(preg_match("/僕が.*?の要求により/i",$explaz) || preg_match("/僕がbombtterの代わりに.*?を/i",$explaz)) $okflag=true;

if(!$okflag){ $stx .= "System: not related explode. ignored.\n"; odie(false,false,$stx);}

$explaz = str_replace(". @","@",$explaz);
//多段処理

$expla2 = str_replace("僕がbombtterの代わりに","",$explaz);
$expla2 = preg_replace("/僕が.*?の要求により/i","",$expla2);

$expla = explode("を爆発させました。",$expla2);
$expla = explode("を爆破しました。",$expla[0]);
//print_r($expla);
//echo "<br />";
//if(strpos($expla[0],"1,") === 0) { $stx .= "Exploded target:".$expla[2]."\n"; $expld=true; }else{ $stx .= "Not exploded target:".$expla[2]."\n"; $expld = false; }
}
//echo "爆破物:".$expla[0]."<br />";

//echo "ムスカが投稿するテストをするようです。<br />";
//$stx .= "Exploded object:".$expla[2]."\n";

$chusun = rand(0,7);

	
	if($chusun == 4) { $expla[0] = DoFilter($expla[0],"わっさっさーな人"); $gomim = "旧約聖書にある、".$expla[0]."を滅ぼした@kyubotterだよ。Twitterでは@bombtterとも伝えられているがね。"; } else {
	$expla[0] = DoFilter($expla[0],"人");
	$gomim = "見ろ! ".$expla[0]."がゴミのようだ!";
	}
	
	
	if(strpos($expla[0],"ムスカ") === FALSE) { 
		//dummy
	}else{
		$stx .= "Target Me: Hit!\n";
		$gomim = "あぁ…目が…目がぁぁ!!";
	}
	if(strpos($expla[0],"@muskabot") === FALSE) {
		//dummy
	}else{
		$stx .= "Target Me: Hit!\n";
		$gomim = "あぁ…目が…目がぁぁ!!";
	}


if($dummymode == 5) { $stx .= "System: 1/100 post\n"; $gomim = "最高のワッサーだと思わんかね?"; $donotupdate = true; }
if($dummymode == 35) { $stx .= "System: 1/100 post(Type2)\n"; $gomim = "私は爆発した物をゴミとして見ることができるんです。あなたとは違うんですよ、閣下。"; }
//echo "$gomim <br />";
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

		if(!$dupd && isset($jsjs)){
		$fpzz = @fopen("bombtter_raw2.json" , "w");
		fwrite($fpzz,$jsjs[0]["epoch"]);
		fclose($fpzz);
		$statmsg .= "Updated:bombtter_raw2.json => ".$jsjs[0]["epoch"]."\n";
		}
if(!$xmode) { //壊れたらあれなので。。
$pfix = date("Ymd");
$cex = @fopen("/path/to/ws-muskabot".$pfix.".log" , "a");
$lockstatus = flock($cex,LOCK_EX);
if($lockstatus === FALSE) { sleep(1); $lockstatus = flock($cex,LOCK_EX); }
fwrite($cex,"[".date("Y/m/d G:i:s")."]\n$statmsg\n");
fclose($cex);
if(substr(sprintf('%o', fileperms("/path/to/ws-muskabot".$pfix.".log")), -4) !== "0606") chmod("/path/to/ws-muskabot".$pfix.".log", 0606);
}

if(file_exists("./tmp/lock2.lock") && !$xmode) unlink("./tmp/lock2.lock");

header("Content-Type: image/gif");
@readfile("/path/to/blank.gif") or die("1px image load error");

exit;
}

?>