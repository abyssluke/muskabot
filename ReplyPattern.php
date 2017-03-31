<?php
/**
    このスクリプトは、@muskabot稼働当時使用していたスクリプトを
    公開向けに一部修正したものです。
    実際に使用していたソースとは若干異なりますので、ご了承ください。
**/

$inpost = $nowpost["text"];
$inuser = $nowpost["user"]["screen_name"];
$inuserid = $nowpost["user"]["id"];
$replyis = "@".$inuser." ";
$is_bal = false; //in_reply_(ry を付けないかのフラグ
$goaway = false; //POST許可フラグ
$ce = false; //特別POST許可フラグ
$rtdetect = false; //RT文字列が含まれる場合

$replytable = array(
	"乱数" => "見ろ! 数字がゴミのようだ! →".mt_rand(),

	"頭" => "少し、頭冷やそうかね?",
	"逃" => "ははは、どこに行こうと言うのかね",
	"読" => "読める…読めるぞ!!",
	"ムスカ" => "私のことを呼んだかね?",
	"大佐" => "私のことを呼んだかね?",
	"ありがとう" => "どういたしまして、".$replyis."。",
	"鳩山" => "鳩山由紀夫とやらは正直心底うんざりさせられる。",
	"小沢" => "小沢一郎とやらは正直心底うんざりさせられる。",
	"ちんこ" => "は何を言っているのかね?",
	"名前は" => "私の名はロムスカ・パロ・ウル・ラピュタ。ラピュタ王の末裔だ。",
	"何者" => "私の名はロムスカ・パロ・ウル・ラピュタ。ラピュタ王の末裔だ。",
	"王家" => "私の一族は王家なのだ。",
	"核兵器" => "ラピュタの力の事かね?",
	"死ね" => "死ねぇ!!",
	"氏ね" => "氏ねぇ!!",
	"待って" => "3分間待ってやる!",
	"まって" => "3分間待ってやる!",
	"教えて" => "教えて欲しければ少女が持っている石を渡せ!",
	"クッキング" => "ムスカ大佐 恐怖の3分間待ってやるクッキングだ!",
	"料理" => "ムスカ大佐 恐怖の3分間待ってやるクッキングだ!",
	"まいん" => "はぴはぴはっぴーだと思わんかね?",
	"魔神剣" => "ははは、どこを狙っているのかね?",
	
	"石は隠した" => "、娘の命と引き替えだ、石のありかを言え!",
	"天気" => "明日の天気はゴミのようだ!",
	"ハガレン" => "私はロイ・マスタング大佐とやらではない!",
	"ごみ" => "その通り! ゴミのようだ!",
	"ゴミ" => "その通り! ゴミのようだ!",
	"よみがえらせるな" => "ラピュタの力こそ人類の夢だからだ!",
	"よみがえるな" => "ラピュタの力こそ人類の夢だからだ!",
	"蘇るな" => "ラピュタの力こそ人類の夢だからだ!",
	"蘇らないで" => "ラピュタの力こそ人類の夢だからだ!",
	
	
	"何の真似だ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"うるさい" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"うるせえ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"うるせぇ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"うるせー" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"ウザい" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"うざい" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"ウザイ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"だまれ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"黙れ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"黙りな" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"バカ" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	"馬鹿" => "言葉を慎みたまえ。君はラピュタ王の前にいるのだ。",
	
	"@muskabot 爆発しろ" => "ん？",
	"ムスカ爆発しろ" => "ん？",
	"大佐爆発しろ" => "ん？",
	"@muskabot爆発しろ" => "ん？",
	"ムスカbot爆発しろ" => "ん？",
	"ムスカボット爆発しろ" => "ん？",
	"ムスカ大佐爆発しろ" => "ん？",
	"ラピュタ王爆発しろ" => "ん？",
	"ラピュタ爆発しろ" => "ラピュタは亡びぬ。何度でもよみがえるさ。",
	
	"nullpo" => "ガッ",
	"nurupo" => "ガッ",
	"ぬるぽ" => "ガッ",
	"ヌルポ" => "ガッ",
);
	//"カウントダウン" => "私はゴミのようなクリスマスまで不定期的にカウントダウンしていますよ、".$replyis."。",
	
foreach($replytable as $match => $replyvalue){
	if(strpos($inpost,$match) !== false) {
		$gomim = $replyis.$replyvalue;
		$goaway=true;
	}
}



//ユーザー差別(主にbot)
if($inuser == "joymanbot") {
	$stx .= "Type: JoymanBot\n";
	$gomim = ". @joymanbot 君のネタには心底うんざりさせられる。";
	$goaway=true;
	$is_bal=true;
}
if($inuser == "shigin") {
	$stx .= "Type: shigin\n";
	$gomim = ". @shigin 君は何を言っているのかね?";
	$goaway=true;
	$is_bal=true;
}
if($inuser == "meitantei_bot") {
	$stx .= "Type: meitantei_bot\n";
	$gomim = ". @meitantei_bot 君の推理は当てにならない。もうちょっと勉強したまえ。";
	$goaway=true;
	$is_bal=true;
}
if($inuser == "matayoshi") {
	$stx .= "Type: matayoshi\n";
	$gomim = ". @matayoshi 君は何を言っているのかね? 変な妄想はやめたまえ。";
	$goaway = true;
	$is_bal = true;
}
if($inuser == "linux_cafe") {
	$stx .= "Type: linux_cafe\n";
	$gomim = ". @linux_cafe あいにく、リナカフェと言われる所に行くことはできないのだ。";
	$goaway = true;
	$is_bal = true;
}
if($inuser == "LinaCafe") {
	$stx .= "Type: linacafe\n";
	$gomim = ". @LinaCafe 私はリナカフェではなくラピュタにいるのだ。";
	$goaway = true;
	$is_bal = true;
}
if($inuser == "tetsuwo_bot") {
	$stx .= "Type: tetsuwo_bot\n";
	$gomim = ". @tetsuwo_bot 今すぐ変身だ!";
	$goaway = true;
	$is_bal = true;
}
if($inuser == "patchouli__") {
	$stx .= "Type: patchouli__\n";
	$gomim = ". @patchouli__ 悪口言った人を一緒に何とかするかね?";
	$goaway = true;
	$is_bal = true;
}

if(strpos($inpost,"RT @") === 0 || strpos($inpost,"ReTweet @") === 0 || strpos($inpost,"RT:") === 0) {
	$stx .= "Notice: Ah... It's ReTweet. If not Barus, will be ignored.\n";
	$rtdetect = true;
	$goaway = false;
}
if(strpos($inpost,"ゴミのようだ!") !== FALSE) {
	$stx .= "Notice: Trash Loop!? ignored!\n";
	$goaway = false;
}

$notreply = mt_rand(0,24);
if($notreply == 12) {
	$stx .= "Notice: Random Reply Stopper. (expect Barus/follow/remove)\n";
	$goaway = false;
}

if(stripos($inpost,"@muskabot followして") === 0 && stripos($inpost,"followしてこないで") === FALSE) {
 $stx .= "Type: follow me\n";
 $stx .= "Info: Going following ".$replyis."(".$inuserid.")\n";
 $to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest("https://api.twitter.com/1/friendships/create/".$inuserid.".json", "", 'POST');
		$gomim = $replyis."[R2FR-F]多分followしましたよ、".$replyis."。";

  $goaway = true;
}
if(stripos($inpost,"@muskabot removeして") === 0) {
 $stx .= "Type: remove me\n";
 $stx .= "Info: Going removing ".$replyis."(".$inuserid.")\n";
  $to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest("https://api.twitter.com/1/friendships/destroy/".$inuserid.".json", "", 'POST');
		$gomim = $replyis."[R2FR-R]多分removeしましたよ、".$replyis."。";

  $goaway = true;
}
if(stripos($inpost,"@muskabot unfollowして") === 0) {
 $stx .= "Type: remove me(unfollow)\n";
 $stx .= "Info: Going removing ".$replyis."(".$inuserid.")\n";
  $to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_t,$oauth_s);
$content = $to->OAuthRequest("https://api.twitter.com/1/friendships/destroy/".$inuserid.".json", "", 'POST');
		$gomim = $replyis."[R2FR-R]多分unfollowしましたよ、".$replyis."。";

  $goaway = true;
}

//最高順位 バルス ここから上にパターンを置くこと!!
$spac = array("　.","　　.","　　　.","　　　　.","　　　　　.","　　　　　　.","　　　　　　　.","　　　　　　　　.","　　　　　　　　　.","　　　　　　　　　　.","　　　　　　　　　　　."," ."," ..","　.　","　　..",".　.　","　　　　..","!","!!","!!!","!!!!","!!!!!","!!!!!!","！","！！","！！","！！","！！！");
$addspac = mt_rand(0,15);
if(strpos($inpost,"バルス") !== FALSE) { 
	$stx .= "Target Me: Hit!\n";
	$gomim = "あぁ…目が…目がぁぁ!!".$spac[$addspac];
	$is_bal=true;
	$goaway=true;
}
if(strpos($inpost,"ばるす") !== FALSE) {
	$stx .= "Target Me: Hit!\n";
	$gomim = "あぁ…目が…目がぁぁ!!".$spac[$addspac];
	$is_bal=true;
	$goaway=true;
}

if($inuser == "muskabot") {
	$stx .= "Notice: Hey, Reply to me? IGNORE!!\n";
	$goaway = false;
}

if($inuser == "abyssluke" && $inpost == "@muskabot Reply停止") {
	$stx .= "Type: command Reply停止\n";
	@touch("/path/to/replystop.lock");
	$gomim = "@abyssluke Reply確認動作を止めましたよ、閣下。";
	$ce = true;
	$goaway=true;
}
if($inuser == "abyssluke" && $inpost == "@muskabot Reply開始") {
	$stx .= "Type: command Reply開始\n";
	@unlink("/path/to/replystop.lock");
	$gomim = "@abyssluke Reply確認動作を開始しましたよ、閣下。";
	$goaway=true;
}

?>