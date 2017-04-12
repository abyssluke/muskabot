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

//muskabot 00分

//メンテ臨時
//die();
//明日メンテ用。おきったーら解除！！！
//$mna = date("G");
//if($mna == 2 || $mna == 3) $dono = true;

define("UA_PRM","Muskabot/0.1 (Just-Time; PHP/".phpversion().")");
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
$statmsg = "Starting Just-Time Program...\n";

$weekstr = array("日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日");
$nowweekday = $weekstr[$weks];
$is_daystart = false;

if($hour == 0 && $minute == 0) $is_daystart = true;


//ハガレンタイム用
//if($hour == 17 && $minute == 0 && $weks == 0) $geass = true;
//if($hour == 2 && $minute == 0 && $weks == 5) $kon = true;
if($hour == 9 && $minute == 0 && $weks == 0) $precure = true;
if($hour == 5 && $minute == 0) $morningchun = true;
if($hour == 12 && $minute == 0) $hiruchun = true;

//イベント判定
if($day == 24 && $month == 12 && $is_daystart) $dummymode = 6;
if($day == 25 && $month == 12 && $is_daystart) $dummymode = 7;
if($day == 1 && $month == 1 && $is_daystart) $dummymode = 8;
if($day == 1 && $month == 4 && $is_daystart) {
 $dummymode = 9;
}

if($day == 20 && $month == 11 && $year == 2009 && $hour == 21 && $minute == 0) $dummymode = 10;
if($day == 21 && $month == 11 && $year == 2009 && $is_daystart) $dummymode = 11;
if($day == 28 && $month == 2 && $year == 2011 && $is_daystart) {
 $txmp = file_get_contents("http://www3.example.com/iconchangerscript.php");
}
$c1 = "時間"; //Case 1

switch($dummymode) {
	case 0:
		if($geass) {
			$gomim = "時計を見ろ! ハガレンタイム({$hour}時{$minute}分)がゴミのようだ!";
		}elseif($kon) {
			$gomim = "時計を見ろ! 大正野球娘。タイム({$hour}時{$minute}分)がゴミのようだ!";
		}elseif($precure) {
			$gomim = "時計を見ろ! 終わったプリキュアタイム({$hour}時{$minute}分)がゴミのようだ!";
		}elseif($morningchun){
			$gomim = "時計を見ろ! 朝チュン({$hour}時{$minute}分)がゴミのようだ!";
		}elseif($hiruchun){
			$gomim = "時計を見ろ! 昼ぽっぽー({$hour}時{$minute}分)がゴミのようだ!";
		}else{
		$gomim = "時計を見ろ! {$hour}時{$minute}分がゴミのようだ!";
		}
		if($is_daystart) $gomim = "カレンダーを見ろ! {$nowweekday}がゴミのようだ!";
		break;
	case 1:
		if($geass) $c1 = "ハガレンタイム";
		if($kon) $c1 = "大正野球娘。タイム";
		if($precure) $c1 = "プリキュアタイム終了";
		if($morningchun) $c1 = "朝チュン";
		if($hiruchun) $c1 = "昼ぽっぽー";
		$gomim = "{$c1}({$hour}時{$minute}分)だ! 答えを聞こう!";
		if($is_daystart) $gomim = "{$nowweekday}だ! 答えを聞こう!";
		break;
	case 2:
		if($geass) {
			$gomim = "最高のハガレンタイム({$hour}時{$minute}分)だと思わんかね?";
		}elseif($kon) {
			$gomim = "最高の大正野球娘。タイム({$hour}時{$minute}分)だと思わんかね?";
		}elseif($precure) {
			$gomim = "最高だったプリキュアタイム({$hour}時{$minute}分)だと思わんかね?";
		}elseif($morningchun){
			$gomim = "最高の朝チュン({$hour}時{$minute}分)だと思わんかね?";
		}elseif($hiruchun){
			$gomim = "最高の昼ぽっぽー({$hour}時{$minute}分)だと思わんかね?";
		}else{
		$gomim = "最高の{$hour}時{$minute}分だと思わんかね?";
		}
		if($is_daystart) $gomim = "最高の{$nowweekday}だと思わんかね?";
		break;
	case 3:
		if($geass) {
			$gomim = "私はムスカ大佐だ。 ハガレンタイム({$hour}時{$minute}分)だ!";
		}elseif($kon) {
			$gomim = "私はムスカ大佐だ。 大正野球娘。タイム({$hour}時{$minute}分)だ!";
		}elseif($precure) {
			$gomim = "私はムスカ大佐だ。 プリキュアタイム終了({$hour}時{$minute}分)だ!";
		}elseif($morningchun){
			$gomim = "私はムスカ大佐だ。 朝チュン({$hour}時{$minute}分)だ!";
		}elseif($hiruchun){
			$gomim = "私はムスカ大佐だ。 昼ぽっぽー({$hour}時{$minute}分)だ!";
		}else{
			$gomim = "私はムスカ大佐だ。 {$hour}時{$minute}分だ!";
		}
		if($is_daystart) $gomim = "私はムスカ大佐だ。 {$nowweekday}だ!";
		break;
	case 4:
		if($geass) {
			$gomim = "将軍に伝えろ。 ハガレンタイム({$hour}時{$minute}分)になったと。";
		}elseif($kon){
			$gomim = "将軍に伝えろ。 大正野球娘。タイム({$hour}時{$minute}分)になったと。";
		}elseif($precure) {
			$gomim = "将軍に伝えろ。 プリキュアタイムが終わった({$hour}時{$minute}分)と。";
		}elseif($morningchun){
			$gomim = "将軍に伝えろ。 朝チュン({$hour}時{$minute}分)だと。";
		}elseif($hiruchun){
			$gomim = "将軍に伝えろ。 昼ぽっぽー({$hour}時{$minute}分)だと。";
		}else{
		$gomim = "将軍に伝えろ。 {$hour}時{$minute}分になったと。";
		}
		if($is_daystart) $gomim = "将軍に伝えろ。 {$nowweekday}になったと。";
		break;
	case 5:
		$gomim = "{$hour}時{$minute}分ですよ、閣下。";
		if($minute == 0) $gomim = "{$hour}時ですよ、閣下。";
		if($geass) $gomim = "ハガレンタイム({$hour}時)ですよ、閣下。";
		if($kon) $gomim = "大正野球娘。タイム({$hour}時)ですよ、閣下。";
		if($precure) $gomim = "プリキュアタイムが終わりました({$hour}時{$minute}分)よ、閣下。";
		if($morningchun) $gomim = "朝チュン({$hour}時)ですよ、閣下。";
		if($hiruchun) $gomim = "昼ぽっぽー({$hour}時)ですよ、閣下。";
		if($is_daystart) $gomim = "{$nowweekday}ですよ、閣下。";
		break;
		
	//イベント用
	case 6:
		$gomim = "見ろ! クリスマスイブ(12/24)がゴミのようだ!　";
		break;
	case 7:
		$gomim = "クリスマス(12/25)なんて存在しませんよ、閣下。";
		break;
	case 8:
		$gomim = "すばらしい新年だと思わんかね? 見ろ! 今年がゴミのようだ!(今年もよろしくお願いします) ".$year."年元旦";
		break;
	case 9:
		$gomim = "すばらしくない!最低の4/1だと思うかね? 見るな! 4/1がゴミではないようだ!";
		break;
	case 10:
		$gomim = "時間(21時)だ!ぜひ日本テレビ系列で放送される金曜ロードショーで私の活躍を見たまえ! http://bit.ly/1120lpt (明日の0時までreply処理を除く運転を停止します)";
		break;
	case 11:
		$gomim = "すばらしい! 生きてる…生きてるぞ!! …私は亡びぬ、何度でも蘇るさ。 (通常運転再開。0時です)";
		break;
}
$statmsg .= "No.$dummymode\n";

$supermode = mt_rand(0,1);

//0時/月曜日用(高確率処理)
if($is_daystart && $weks == 1 && $supermode == 1) {
 $gomim = "カレンダーを見ろ! 月曜日がゴミのようだ!";
 $statmsg .= "Super Mode(Monday)\n";
}
//0時/日曜日用(高確率処理)
if($is_daystart && $weks == 0 && $supermode == 1) {
 $gomim = "最高の日曜日だと思わんかね?";
 $statmsg .= "Super Mode(Sunday)\n";
}
//$gomim = "@muskabot ".$gomim;
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
		if($curl_status !== 200) { 
		// if($curl_errcode == 28) //echo "<br />どうやらconnectがタイムアウトで失敗した。鯖落ちの予感？<br />";
		}
		curl_close($curl_twitterx);
*/
}

		//$fpzz = @fopen("bombtter_raw.json" , "w");
		//fwrite($fpzz,"Muska:<$gomim> at ".time());
//echo "処理しました。<br />";
//echo "<br />jsonから配列に変換したときの中身<br />";
//echo "<pre>";
//print_r($jsjs);
//echo "</pre>";

//echo "</body></html>";
$statmsg .= "Okay:$gomim\n";
$pfix = date("Ymd");
$cex = @fopen("/path/to/muskabot".$pfix.".log" , "a");
$lockstatus = flock($cex,LOCK_EX);
if($lockstatus === FALSE) { sleep(1); $lockstatus = flock($cex,LOCK_EX); }
fwrite($cex,"[".date("Y/m/d G:i:s")."]\n$statmsg\n");
fclose($cex);

echo "CRON OK";
?>