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

//Do filter

function DoFilter($xxe_object,$xxe_oneman) {
	
	//自室(以降の一人称置換で対象になるようにする)
	$xxe_object = str_replace("自室","自分の部屋",$xxe_object);
	
	//コードへ置換
	$xxe_object = str_replace("私鉄","mお９CM、A8gnoi3.Aojg48。",$xxe_object);
	$xxe_object = str_replace("リュシータ","Xj84jS、Am509。A!んふｚ",$xxe_object);
	$xxe_object = str_replace("カフェオレ","じmxciCj.。mCjh！",$xxe_object);
	$xxe_object = str_replace("オレゴン","Cj6ｍん８ｒ５。KMF9。",$xxe_object);
	$xxe_object = str_replace("オレオレ詐欺","ｃｄ、buxjあｍ９。",$xxe_object);
	$xxe_object = str_replace("オラオラ詐欺","ｃえ、buxjあｍ１０。",$xxe_object);
	$xxe_object = str_replace("パチンコ","CK045k.2=Al3l.C<t。ｓlc",$xxe_object);
	$xxe_object = str_replace("ぱちんこ","zCK048k.2=Al3l.C<t！。ｓlc",$xxe_object);
	$xxe_object = str_replace("ウルトラマンコスモス","!C93X_X.3-;Xｖ５;lA;;Dl-pc=C",$xxe_object);
	//一人称置換
	$oneman = array("自分","私","俺","僕","おいら","オラ","オレ");
	$xxe_object = str_replace($oneman,$xxe_oneman,$xxe_object);

	//俺が名指しで爆発された場合の置換。
	$xxe_object = str_replace("@abyssluke","私の居場所を作った人",$xxe_object);

	//ここはムスカらしく
	$xxe_object = str_replace("パズー","小僧",$xxe_object);
	$xxe_object = str_replace("シータ","リュシータ",$xxe_object);

	//自主規制ｗ
	$xxe_object = str_replace("ちんちん","<censored>",$xxe_object);
	$xxe_object = str_replace("ちωちω","<censored>",$xxe_object);
	$xxe_object = str_replace("チンコ","<censored>",$xxe_object);
	$xxe_object = str_replace("ちんこ","<censored>",$xxe_object);
	$xxe_object = str_replace("ちωこ","<censored>",$xxe_object);
	$xxe_object = str_replace("おっぱい","<censored>",$xxe_object);
	$xxe_object = str_replace("オッパイ","<censored>",$xxe_object);
	$xxe_object = str_replace("まんこ","<censored>",$xxe_object);
	$xxe_object = str_replace("まωこ","<censored>",$xxe_object);
	$xxe_object = str_replace("マンコ","<censored>",$xxe_object);
	$xxe_object = str_replace("オナホ","<censored>",$xxe_object);
	$xxe_object = str_replace("ペニス","<censored>",$xxe_object);
	$xxe_object = str_replace("ぺにす","<censored>",$xxe_object);
	$xxe_object = str_replace("sex","<censored>",$xxe_object);
	$xxe_object = str_replace("Sex","<censored>",$xxe_object);
	$xxe_object = str_replace("SEX","<censored>",$xxe_object);
	$xxe_object = str_replace("オナニ","<censored>",$xxe_object);
    $xxe_object = str_replace("ちんぽ","<censored>",$xxe_object);
    $xxe_object = str_replace("チンポ","<censored>",$xxe_object);
    $xxe_object = str_replace("フェラチオ","<censored>",$xxe_object);
    $xxe_object = str_replace("フェラ","<censored>",$xxe_object);
    $xxe_object = str_replace("アナル","<censored>",$xxe_object);
    $xxe_object = str_replace("TENGA","<censored>",$xxe_object);
    $xxe_object = str_replace("ぱんつ","<censored>",$xxe_object);
    $xxe_object = str_replace("射精","<censored>",$xxe_object);
    $xxe_object = str_replace("精液","<censored>",$xxe_object);
    $xxe_object = str_replace("白濁液","<censored>",$xxe_object);
    $xxe_object = str_replace("乱交","<censored>",$xxe_object);
    $xxe_object = str_replace("強姦","<censored>",$xxe_object);
    $xxe_object = str_replace("股間","<censored>",$xxe_object);
    $xxe_object = str_replace("自慰","<censored>",$xxe_object);
    $xxe_object = str_replace("セックス","<censored>",$xxe_object);
    $xxe_object = str_replace("巨乳","<censored>",$xxe_object);
    
	//@＠
	$xxe_object = str_replace("@","＠ ",$xxe_object);
	
	//コードを戻す
	$xxe_object = str_replace("Xj84jS、Am509。A!んふｚ","リュシータ",$xxe_object);
	$xxe_object = str_replace("mお９CM、A8gnoi3.Aojg48。","私鉄",$xxe_object);
	$xxe_object = str_replace("じmxciCj.。mCjh！","カフェオレ",$xxe_object);
	$xxe_object = str_replace("Cj6ｍん８ｒ５。KMF9。","オレゴン",$xxe_object);
	$xxe_object = str_replace("ｃｄ、buxjあｍ９。","オレオレ詐欺",$xxe_object);
	$xxe_object = str_replace("ｃえ、buxjあｍ１０。","オラオラ詐欺",$xxe_object);
	$xxe_object = str_replace("CK045k.2=Al3l.C<t。ｓlc","パチンコ",$xxe_object);
	$xxe_object = str_replace("zCK048k.2=Al3l.C<t！。ｓlc","ぱちんこ",$xxe_object);
	$xxe_object = str_replace("!C93X_X.3-;Xｖ５;lA;;Dl-pc=C","ウルトラマンコスモス",$xxe_object);
	//日付置換
	$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
	$tomorrow2  = mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"));
	$yesterday  = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$yesterday2  = mktime(0, 0, 0, date("m")  , date("d")-2, date("Y"));
	
	$xxe_object = str_replace("おととい","おととい(" . date("Y/m/d",$yesterday2) . ")",$xxe_object);
	$xxe_object = str_replace("一昨日","一_昨_日(" . date("Y/m/d",$yesterday2) . ")",$xxe_object);
	$xxe_object = str_replace("昨日", "昨日(" . date("Y/m/d",$yesterday) . ")",$xxe_object);
	$xxe_object = str_replace("今日", "今日(" . date("Y/m/d") . ")" ,$xxe_object);
	$xxe_object = str_replace("明日", "明日(" . date("Y/m/d",$tomorrow) . ")",$xxe_object);
	$xxe_object = str_replace("明後日", "明後日(" . date("Y/m/d",$tomorrow2) . ")" ,$xxe_object);
	$xxe_object = str_replace("一_昨_日","一昨日",$xxe_object);
	
	//結果をメインに返す
	return $xxe_object;
}
?>