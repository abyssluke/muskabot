<?php
/**
    このスクリプトは、@muskabot稼働当時使用していたスクリプトを
    公開向けに一部修正したものです。
    実際に使用していたソースとは若干異なりますので、ご了承ください。
**/


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