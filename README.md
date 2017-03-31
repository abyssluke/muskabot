# muskabot
ムスカBotのソースコードを公開します。

ムスカBotについては以下を参照してください。

* [制作者が解説する@muskabot(Twitterからのリンク用)](http://abyssluke.hatenadiary.com/entry/20090420/1240181359)
* [制作者が解説する@muskabot(基本編)](http://abyssluke.hatenadiary.com/entry/20090419/1240128522)
* [制作者が解説する@muskabot(Reply反応文字列編)](http://abyssluke.hatenadiary.com/entry/20090421/1240273283)

以下に記載されているライブラリ類以外のライセンスはMIT Licenseとします(twitterOAuthがMITなので合わせたとか言えない…)。

公開するとマズイ箇所(OAuthのトークンやシークレットなど)があるため実際に稼働していた時のものとは若干異なります。当該部分の削除などのみ行っているため、各種コメント部分、スクリプト作成時に生じた英語のスペルミスなどもそのままです。予めご了承ください。

## 各ファイルについて

### メインファイル
* launchex.php
    * ほぼ一定時間おきにbombtter_rawのタイムラインを読みに行き、最近爆発したものに対して何かtweetする。
    * 「ほぼ一定時間おき」なのはいわゆる擬似cron(Web cron)なため。
    * たまに適当なことをtweetすることもある。
* launchreply.php
    * mentionsを読みに行き、自分宛にreplyが来ていた場合パターンに応じてreply返しをする。
    * バルスだけはmentionでも反応する。
    * 管理者の特定replyにより動作を開始・停止させることができる。
* launchjust.php
    * 毎時00分にcronで呼び出されるスクリプト。時報をtweetする。
    * 日をまたいだ場合、月日・曜日によっては特別tweetとなる場合がある。
    * 特定のアニメの放送開始・終了時なども特別なtweetとなる場合がある。
* launchhf.php
    * 毎時30分にcronで呼び出されるスクリプト。内容はlaunchjust.phpとほぼ同一。
    * たまにプロフィールの場所を変更する。

### サブファイル
* launchex.DoFilter.inc.php
    * launchex.phpで利用するもの。特定単語の退避・復元処理、一人称置換、検閲処理などを行う。
* ReplyPattern.php
    * launchreply.phpで利用するもの。このファイルに定義されたパターンやユーザーに一致した場合、指定されたreplyを行う。
* muska-core/callback.php
    * トークンを取得するために使った簡単なスクリプト。
* blank.gif
    * ダミー画像。擬似cronで動作する時はこのファイルの内容を返していた。

### ライブラリ類
以下のライブラリ類の作者・出典についてはAUTHOR.md参照。

* muska-core/twitterOAuth.php,OAuth.php
    * twitterにOAuthでアクセスするためのライブラリ。
* MutexFile.php
    * 排他処理をファイルロックで実現するライブラリ。
    * どういうわけかlaunchreply.phpでしか使われていない謎。
