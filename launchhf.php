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

//muskabot 30分

//メンテ臨時
//die();
//明日メンテ用。おきったーら解除！！！
//$mna = date("G");
//if($mna == 2 || $mna == 3) $dono = true;

define("UA_PRM","Muskabot/0.1 (Half-Time; PHP/".phpversion().")");
require_once("/path/to/muska-core/twitterOAuth.php");

$oauth_t = "(Token)";
$oauth_s = "(Secret)";
$twitter_update = "https://api.twitter.com/1/statuses/update.json";


$year = date("Y");
$day = date("j");
$month = date("n");
$hour = date("H");
$minute = date("i");
$weks = date("w");
$dummymode = mt_rand(0,5);
$statmsg = "Starting Half-Time Program...\n";
$c1 = "時間";

//ハガレンタイム用
//if($hour == 17 && $minute == 30 && $weks == 0) $geass = true;
//if($hour == 2 && $minute == 30 && $weks == 5) $kon = true;
if($hour == 8 && $minute == 30 && $weks == 0) $precure = true;

if($hour == 23 && $minute == 30 && $year == 2009 && $month == 11 && $day == 20) $dummymode == 10;

switch($dummymode) {
	case 0:
		if($geass) {
			$gomim = "時計を見ろ! 終わったハガレンタイム({$hour}時{$minute}分)がゴミのようだ!";
		}elseif($kon) {
			$gomim = "時計を見ろ! 終わった大正野球娘。タイム({$hour}時{$minute}分)がゴミのようだ!";
		}elseif($precure){
			$gomim = "時計を見ろ! プリキュアタイム({$hour}時{$minute}分)がゴミのようだ!";
		}else{
		$gomim = "時計を見ろ! {$hour}時{$minute}分がゴミのようだ!";
		}
		break;
	case 1:
		if($geass) $c1 = "ハガレンタイム終了";
		if($kon) $c1 = "大正野球娘。タイム終了";
		if($precure) $c1 = "プリキュアタイム";
		$gomim = "{$c1}({$hour}時{$minute}分)だ! 答えを聞こう!";
		break;
	case 2:
		if($geass) {
			$gomim = "最高だったハガレンタイム({$hour}時{$minute}分)だと思わんかね?";
		}elseif($kon) {
			$gomim = "最高だった大正野球娘。タイム({$hour}時{$minute}分)だと思わんかね?";
		}elseif($precure) {
			$gomim = "最高のプリキュアタイム({$hour}時{$minute}分)だと思わんかね?";
		}else{
		$gomim = "最高の{$hour}時{$minute}分だと思わんかね?";
		}
		break;
	case 3:
		if($geass) {
			$gomim = "私はムスカ大佐だ。 ハガレンタイム終了({$hour}時{$minute}分)だ!";
		}elseif($kon) {
			$gomim = "私はムスカ大佐だ。 大正野球娘。タイム終了({$hour}時{$minute}分)だ!";
		}elseif($precure) {
			$gomim = "私はムスカ大佐だ。 プリキュアタイム({$hour}時{$minute}分)だ!";
		}else{
		$gomim = "私はムスカ大佐だ。 {$hour}時{$minute}分だ!";
		}
		break;
	case 4:
		if($geass) {
			$gomim = "将軍に伝えろ。 ハガレンタイムが終わった({$hour}時{$minute}分)と。";
		}elseif($kon) {
			$gomim = "将軍に伝えろ。 大正野球娘。タイムが終わった({$hour}時{$minute}分)と。";
		}elseif($precure){
			$gomim = "将軍に伝えろ。 プリキュアタイム({$hour}時{$minute}分)になったと。";
		}else{
		$gomim = "将軍に伝えろ。 {$hour}時{$minute}分になったと。";
		}
		break;
	case 5:
		$gomim = "{$hour}時{$minute}分ですよ、閣下。";
		if($minute == 0) $gomim = "{$hour}時ですよ、閣下。";
		if($geass) $gomim = "ハガレンタイムが終わりました({$hour}時{$minute}分)よ、閣下。";
		if($kon) $gomim = "大正野球娘。タイムが終わりました({$hour}時{$minute}分)よ、閣下。";
		if($precure) $gomim = "プリキュアタイム({$hour}時)ですよ、閣下。";
		break;
	case 10:
		$gomim = "あ、あぁ…目がぁぁ、あぁぁぁぁ、ぁぁぁ!(23時30分をお知らせします)";
		break;

}
// $gomim = "@muskabot ".$gomim;
$statmsg .= "No.$dummymode\n";
$message = urlencode($gomim);
$twitter_message = $twitter_update . $message;
//echo "$twitter_message<br />";

if(!$dono) {
$to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest($twitter_update, array('status' => $gomim), 'POST');
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
}
$statmsg .= "Okay:$gomim\n";

$locax = array("ゴリアテ","ラピュタ(黒い半球体の中)","要塞","ラピュタ","地上");

$lafiz = mt_rand(0,4);
if($lafiz == 1) {
	$sele = mt_rand(0,4);
	//$message2 = urlencode($locax[$sele]);
	$to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest("https://api.twitter.com/1/account/update_location.json", array('location' => $locax[$sele]), 'POST');

//if($to->lastStatusCode !== 200) $stx .= "Warning: Failed change location in Twitter.\n";


	$statmsg .= "Location Updated: ". $locax[$sele] . "\n";
}

$pfix = date("Ymd");
$cex = @fopen("/path/to/muskabot".$pfix.".log" , "a");
$lockstatus = flock($cex,LOCK_EX);
if($lockstatus === FALSE) { sleep(1); $lockstatus = flock($cex,LOCK_EX); }
fwrite($cex,"[".date("Y/m/d G:i:s")."]\n$statmsg\n");
fclose($cex);

echo "CRON OK";
?>