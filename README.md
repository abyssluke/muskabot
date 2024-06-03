# muskabot
> ムスカ「見たまえ、このスパゲッティソースを」 

※実際の劇中やbotでは発言していません

***

以前@abysslukeが運用していたムスカBot(@muskabot)のソースコードを公開します。

ムスカBotについては以下を参照してください。

* [かつてあったムスカbotについて](https://scrapbox.io/abyssluke/%E3%81%8B%E3%81%A4%E3%81%A6%E3%81%82%E3%81%A3%E3%81%9F%E3%83%A0%E3%82%B9%E3%82%ABbot%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6)

以下に記載されているライブラリ類以外のライセンスはMIT Licenseとします(twitterOAuthがMITなので合わせたとか言えない…)。

公開するとマズイ箇所(OAuthのトークンやシークレットなど)があるため実際に稼働していた時のものとは若干異なります。当該部分の削除などのみ行っているため、各種コメント部分、スクリプト作成時に生じた英語のスペルミスなどもそのままです。予めご了承ください。

**注意: ムスカbotは、株式会社スタジオジブリ、株式会社徳間書店、東宝株式会社、宮崎駿監督などの企業・個人とは一切関係ありません。単なるファンメイドのbotです。**

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

### おまけ
* launchex2.php
    * Wassr版ムスカbotのソースコード。
* launchex2d.php
    * twitterがよく落ちていた時代、twitterからmuskabotのタイムラインが取得できない場合にtwitterのダウンとみなしてその旨をWassrのmuskabotとtwitterチャンネルに投稿するbot。

### ライブラリ類
以下のライブラリ類の作者・出典についてはAUTHOR.md参照。

* muska-core/twitterOAuth.php,OAuth.php
    * twitterにOAuthでアクセスするためのライブラリ。
* MutexFile.php
    * 排他処理をファイルロックで実現するライブラリ。
    * どういうわけかlaunchreply.phpでしか使われていない謎。
