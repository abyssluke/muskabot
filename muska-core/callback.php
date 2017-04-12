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

require("twitterOAuth.php");
session_start();

$req_token = $_SESSION['req_token'];
$oauth_token = $req_token['oauth_token'];
$oauth_token_secret = $req_token['oauth_token_secret'];
$to = new TwitterOAuth("(AppToken)", "(AppSecret)",$oauth_token,$oauth_token_secret);
$acc_token = $to->getAccessToken();

$oauth_token = $acc_token['oauth_token'];
$oauth_token_secret = $acc_token['oauth_token_secret'];

echo "T:$oauth_token<br />S:$oauth_token_secret";
exit;
?>