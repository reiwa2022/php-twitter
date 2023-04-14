<?php
// -------------------------------------------
// プログラム名:プログラム共通定数ファイル
// 説明:各プログラムで使う定数を定義します
// -------------------------------------------

// Consumer keyの値を格納
$consumer_key = getenv('CONSUMER_KEY');
// Consumer secretの値を格納
$consumer_key_sercret = getenv('CONSUMER_SECRET');
// Access Tokenの値を格納
$access_token = getenv('ACCESS_TOKEN_KEY');
// Access Token Secretの値を格納
$access_token_secret = getenv('ACCESS_TOKEN_SECRET');

//　ツイート情報のツイート数
const TWEETS_NUM = 5;
// ツイッターアカウント一覧ファイル
const FILE_PATH = './list/account.txt';
// ツイッターURL
const TWITTER_URL = 'https://twitter.com/';
// エラーメッセージ
const ERR_MSG = '該当するツイートがありません';
