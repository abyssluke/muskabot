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

/** ムスカbot メインプログラム
    Copyright(c) 2008 @abyssluke **/
// odie(true,true);
require_once 'launchex.DoFilter.inc.php';
require_once 'muska-core/twitterOAuth.php';
function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}
mt_srand(make_seed());

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 過去の日付

//動作停止ファイル存在する？
if(file_exists("/path/to/MuskaLockT.lock")) odie(true,true);

define("UA_PRM","Muskabot/0.2 (PHP/".phpversion().")");

$oauth_t = "(Token)";
$oauth_s = "(Secret)";

$twitter_update = "https://api.twitter.com/1/statuses/update.json";

$api_name	= "User Timeline(json)";
$twitter_api	= "http://api.twitter.com/1/statuses/user_timeline/bombtter_raw.json";

$getstime = 90; //取得タイマー
$lfadtime = 30; //ロックファイル削除タイマー

$stx = "Starting...\n";
if(strpos($_SERVER['HTTP_USER_AGENT'],"Wget") === 0) $stx .= "System: From wget(@abyssluke's Macintosh cron?)!\n";

$fct = filectime("bombtter_raw.json");
$lfct = date("Y/m/d G:i:s",$fct);

if($_GET['lock']=="off") odie(false,true,"Force Unlock.\n");

if(file_exists("./tmp/lock.lock")) {
	if(filectime("./tmp/lock.lock") + $lfadtime <= time()) {
		// unlink("./tmp/lock.lock");
		$stx .= "System:Unlocked!!\n";
	} else {
		odie(true,true);
	}
}

$fp = @fopen("./tmp/lock.lock" , "w");
fwrite($fp,"LOCK");
fclose($fp);

if($fct+$getstime <= time() || $_GET['force_']=="1") {


//おまじない。これかけないとキャッシュ地獄。
$olddate = file_get_contents("bombtter_raw.json");
$rqdate = strtotime($olddate);
$rqdate2 = $rqdate - mt_rand(0,720);
$rcdate = date("r",$rqdate2);

$a_year = date("Y");
$a_day = date("j");
$a_month = date("n");
$a_hour = date("H");

if($a_year == 2009 && $a_month == 11 && $a_day == 20 && $a_hour >= 21) {
$stx .= "Notice: Muska is Laputa now. Do not processing.\n";
$jackerz = $olddate;
odie(false,false,$stx);
}


$olddate = urlencode($rcdate);
$stx .= "System:Getting timeline...\n";
/*
$curl_twitter = curl_init();

curl_setopt($curl_twitter, CURLOPT_URL, $twitter_api."?since=".$olddate);
curl_setopt($curl_twitter, CURLOPT_HEADER, false);
curl_setopt($curl_twitter, CURLOPT_USERAGENT, UA_PRM);
curl_setopt($curl_twitter, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl_twitter, CURLOPT_TIMEOUT, 15);

$curl_result = curl_exec($curl_twitter);
$curl_status = curl_getinfo($curl_twitter,CURLINFO_HTTP_CODE);
$curl_errcode = curl_errno($curl_twitter);
$curl_errmsg = curl_error($curl_twitter);

if($curl_status !== 200) $stx .= "Warning:Status isn't 200\n";

curl_close($curl_twitter);
*/
$curl_result = @file_get_contents($twitter_api."?since=".$olddate);

$jsjs = json_decode($curl_result,true);

if(!is_array($jsjs)) { 
 $stx .= "Error:json_decode return not array! Exiting...\n";
 odie(false,true,$stx); 
} else {

$dummymode = mt_rand(0,240);

if(!trim($jsjs[0]["created_at"])) { $stx .= "Error: 'created_at' data is null! Exiting...\n"; $jackerz=$olddate; odie(false,true,$stx); }
$olddate = file_get_contents("bombtter_raw.json");
if($dummymode !== 5 && $dummymode !== 35 && $dummymode !== 72 && $dummymode !== 28 && $jsjs[0]["created_at"] == $olddate) { $stx .= "System:No update!\n"; odie(false,false,$stx); }
$stx .= "System:Post No ".$jsjs[0]["id"]."\n";
$expla = explode("|",$jsjs[0]["text"]);

if(strpos($expla[0],"1,") === 0) { $stx .= "Exploded target:".$expla[2]."\n"; $expld=true; }else{ $stx .= "Not exploded target:".$expla[2]."\n"; $expld = false; }
}
	$spac = array("","　","　　","　　　","　　　　","　　　　　");
$addspac = mt_rand(0,5);

if(strpos($expla[2],"大佑" !== FALSE)) { $stx .= "Explode stopper; from @22mayko\n";$expld = false; $sp1 = true; }

if($expld) { 

	$chusun = mt_rand(0,7);

	$tempura = $expla[1];

	$stx .= "System: Post URL:{$tempura}\n";
	$tampura = explode("/",$tempura);
	$tompura = "＠ ".$tampura[0];
	$stx .= "System: Exploder is $tompura\n";
	if(strpos($expla[2],"ロムスカ") !== FALSE) $expla[2] = "ムスカ";
	
	if(strpos($expla[2],"ラピュタ") !== FALSE || strpos($expla[2],"らぴゅた") !== FALSE || strpos(strtolower($expla[2]),"laputa") !== FALSE || strpos(strtolower($expla[2]),"raputa") !== FALSE || strpos(strtolower($expla[2]),"rapyuta") !== FALSE ) { $expla[2] = "ラピュタ"; $chusun = 4; $stx .= "System: Forced Type III:Reason: Laputa\n"; }
	
	if(strpos(strtolower($expla[2]),"sadako_") !== FALSE || strpos($expla[2],"貞子") !== FALSE) { $expla[2] = "貞子"; $chusun = 0; $stx .= "System: Forced Type I:Reason: @sadako_\n"; }
	if(strpos($expla[2],"ソドムとゴモラ") !== FALSE) { $expla[2] = "ソドムとゴモラ"; $chusun=2; $stx .= "System: Forced Type II:Reason: Sodom and Gomola\n"; }
	if($chusun == 2 || $chusun == 3) {
		$stx .= "System: Type II:Bible\n";
		$flagg = mt_rand(0,1);
		$expla[2] = DoFilter($expla[2],"自分自身");
		if($flagg == 1) {
			$gomim = "旧約聖書にある、 ".$expla[2]." を滅ぼした ".$tompura." だよ。";
		}else{
			$gomim = "旧約聖書にある、 ".$expla[2]." を滅ぼした Twitter-erの誰か だよ。";
		}
		if($expla[2] == "ソドムとゴモラ") $gomim = "旧約聖書にある、ソドムとゴモラを滅ぼした天の火だよ。 ラーマヤーナではインドラの矢とも伝えているがね。";
	}elseif($chusun == 4){ 
		$stx .= "System: Type III:Rebirth\n";

		$expla[2] = DoFilter($expla[2],$tompura);
		if(strpos($expla[2],"@") === 0) $expla[2] = ". " .$expla[2];
		if($expla[2] == "ラピュタ") { $gomim = "ラピュタは亡びぬ。 何度でもよみがえるさ。"; } else { 
		$gomim = $expla[2] . " は滅びぬ。 何度でもよみがえるさ。"; }
	}elseif($chusun == 5) {
		$stx .= "System: Type IV:Exploded Report\n";
		$expla[2] = DoFilter($expla[2],$tompura);
		$flagg = mt_rand(0,1);
		if(strpos($expla[2],$tompura) !== false) $flagg = 0;
		if($flagg == 1) {
			$gomim = ". ".$tompura." に伝えろ。 予定通り ".$expla[2]." を爆破したと。";
		}else{
			$gomim = "将軍に伝えろ。 予定通り ".$expla[2]." を爆破したと。";
		}

	}elseif($chusun == 6) {
		$stx .= "System: Type V:Broken Curcit\n";
		$expla[2] = DoFilter($expla[2],$tompura);
		$flagg = mt_rand(0,2);
		if(strpos($expla[2],$tompura) !== false) $flagg = 0;
		if($flagg == 1) {
			$gomim = "私はムスカ大佐だ。 ".$tompura." により ".$expla[2]." が破壊された。";
		}elseif($flagg == 2){
			$gomim = "私はムスカ大佐だ。 ロボットにより " .$expla[2]." が破壊された。";
		}else{
		    $gomim = "私はムスカ大佐だ。 誰かにより" .$expla[2]." が破壊された。";
		}
    }else{
		$flagg = mt_rand(0,2);
		if($flagg == 1) {
			$expla[2] = DoFilter($expla[2],$tompura);
		}else{
			$expla[2] = DoFilter($expla[2],"人");
		}
		$gomim = "見ろ! ".$expla[2]." がゴミのようだ! ";
		if(strpos($expla[2],"ゴミ") !== FALSE && strpos($expla[2],"マスゴミ") === FALSE) $gomim = "見ろ! ".$expla[2]." が人のようだ!";
	}

	if($tampura[0] == "buzztter") {
	 $stx .= "System: Type Ex3:buzztter-filter\n";
	 $gomim = "すばらしい @buzztter だと思わんかね？";
	}
	if($expla[2] == "だうよのミゴ") { 
	 $stx .= "System: Type Ex1:Circle-Word-Gomi\n";
	 $gomim = "見ろ! だうよのミゴがゴミのようだ !ろ見";
	}
	if($expla[2] == "はじけるレモンの香り") {
	 $stx .= "System: Type Ex2:Pretty Cure - Lemon\n";
	 $gomim = "すばらしい! 最高のレモンの香りだと思わんかね?";
	}
	if(strpos($expla[2],"打線") !== FALSE) { 
		$stx .= "System: Type Ex4:Baseball?\n";
		$gomim = "すばらしい! 最強の ".$expla[2]." だと思わんかね?";
	}
	if(strpos($expla[2],"ムスカ") !== FALSE || strpos($expla[2],"muskabot") !== FALSE) { 
		$stx .= "Target Me: Hit!\n";
		$gomim = "あぁ…目が…目がぁぁ!!";
	}
} else {
    $chusun = mt_rand(0,1);
	$tempura = $expla[1];
    if($sp1) $chusun = 0;
	$stx .= "System: Post URL:{$tempura}\n";
	$tampura = explode("/",$tempura);
	$tompura = "@".$tampura[0];
	$stx .= "System: Exploder is $tompura\n";
	if($chusun){
		$gomim = "私は ".$expla[2]."をゴミとしては見ていないですよ、閣下。";
		if(strpos($expla[2],"ムスカ") !== FALSE || strpos($expla[2],"muskabot") !== FALSE) {
 			$stx .= "Target Me: Hit!\n";
			$gomim = "私は 自分をゴミとしては見ていないですよ、閣下。";
		}
	}else{
		$stx .= "System: Type III(Alt.):Rebirth\n";
		$expla[2] = DoFilter($expla[2],$tompura);
		if(strpos($expla[2],"@") === 0) $expla[2] = ". " .$expla[2];
		if($expla[2] == "ラピュタ") { $gomim = "ラピュタは亡びぬ。 何度でもよみがえるさ。"; } else { 
		$gomim = $expla[2] . " は滅びぬ。 何度でもよみがえるさ。"; }
	}
}

$gomim = $gomim.$spac[$addspac];
$dummymode2 = mt_rand(0,2);
if($dummymode == 5) { 

$stx .= "System: 1/100 post\n"; $gomim = "最高のTwitterだと思わんかね?";
//企画用エリア
//if($dummymode2 = 2) {
//$stx .="System: Hlw.";
//$gomim = "見ろ! カボチャがゴミのようだ! Trick or Treat! ハッハッハッ!";
//}

$donotupdate = true; 

}
if($dummymode == 28 || $dummymode == 72) { 

$stx .= "System: 1/100 post(Type3)\n"; 
$citycode = mt_rand(1,142);
$xml = simplexml_load_file("http://weather.livedoor.com/forecast/webservice/rest/v1?city=".$citycode."&day=tomorrow");

$pref = $xml->location["pref"];
$area = $xml->location["area"];
$city = $xml->location["city"];
$weather = $xml->telop;
$gomim = $pref."(".$area.") ".$city."の明日の天気は".$weather."だ! (by livedoor 天気予報)";


$donotupdate = true; 

}
/*
if($dummymode == 72) {
$stx .= "System: 1/100 post(Type-Ex1)\n"; $gomim = "11月20日(金) 21時から日本テレビ系列で私やリュシータ、小僧らが登場する。ぜひ見たまえ。 http://bit.ly/1120lpt";
//企画用エリア
//if($dummymode2 = 2) {
//$stx .="System: Hlw.";
//$gomim = "見ろ! カボチャがゴミのようだ! Trick or Treat! ハッハッハッ!";
//}

$donotupdate = true; 
}
*/
if($dummymode == 35) { 
$stx .= "System: 1/100 post(Type2)\n";
$nowmonth = date("n");
$nextmonth = $nowmonth + 1;
$nextyear = $a_year;
if($nextmonth == 13) { $nextyear++; $nextmonth = 1; }

$xmastime = mktime(0,0,0,$nextmonth,1,$nextyear);
$remainxmas = $xmastime - time();
$remainxmasmin = round($remainxmas/60,3);
$remainxmashour = round(($remainxmas/60)/60,3);
$remainxmasday = round((($remainxmas/60)/60)/24,3);

$gomim = $nextmonth."月まであと".$remainxmasmin."分(≒".$remainxmashour."時間≒".$remainxmasday."日)ですよ、閣下。";
//$gomim = "私は爆発した物をゴミとして見ることができるんです。あなたとは違うんですよ、閣下。"; 

//企画用エリア
//if($dummymode2 = 2) {
//$stx .="System: Hlw.";
//$gomim = "将軍に伝えろ。予定通りお菓子をあげると。";
//}

$donotupdate = true;
}

//$gomim = "@muskabot ".$gomim;
$message = $gomim;

$to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest($twitter_update, array('status' => $message), 'POST');
/*
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
*/
//if($to->lastStatusCode !== 200) $stx .= "Warning: Failed post to Twitter.\n";
// curl_close($curl_twitterx);

$stx .= "Okay:$gomim\n";

}else{
$stx .= "System: Do not update.\n";
$donotupdate = true;
}

odie(false,$donotupdate,$stx);

function odie($xmode = false,$dupd = false,$statmsg = "No Prm."){
global $jsjs,$jackerz;
if(!$jackerz) { $jackerz = $jsjs[0]["created_at"]; }
		if(!$dupd && isset($jsjs)){
		$fpzz = @fopen("bombtter_raw.json" , "w");
		fwrite($fpzz,$jackerz);
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
mt_srand(make_seed());
if(mt_rand(0,1) == 1) {
$sakurareply = @fopen("http://www3.example.com/launchreply.php", 'r');
$tmptmptmp = @fread($sakurareply, 8192);
@fclose($sakurareply);
}
$ti = date("G");
//if(mt_rand(0,8) == 4 && ( $ti < 5 || $ti > 20)) {
//$sakurareply2 = @fopen("http://www3.example.com/launchtl.php", 'r');
//$tmptmptmp = @fread($sakurareply2, 8192);
//@fclose($sakurareply2);
//}
if(file_exists("./tmp/lock.lock") && !$xmode) unlink("./tmp/lock.lock");

header("Content-Type: image/gif");
@readfile("/path/to/blank.gif") or die("1px image load error");
exit;
}

?>