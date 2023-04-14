<?php
//-------------------------------------------
// プログラム名:ツイッター情報取得/表示プログラム
// 説明:ツイート情報を取得、ソート後、画面に表示します。
//-------------------------------------------

// デフォルトタイムゾーンを設定します。
date_default_timezone_set('Asia/Tokyo');
// Composerのオートローダーを読み込みます。
require_once './vendor/autoload.php';
// 定数などのコモンファイルを読み込みます。
require_once 'common.php';
// Smarty拡張クラスを読み込みます。
require_once 'MySmarty.class.php';

// ツイッター認証ライブラリ用の名前空間をインポートします。
use Abraham\TwitterOAuth\TwitterOAuth;

// ツイッター認証用のインスタンスを作成します。
$connection = new TwitterOAuth($consumer_key, $consumer_key_sercret, $access_token, $access_token_secret);

// ツイッターアカウント一覧のファイルを開きます。
($file = @fopen(FILE_PATH, 'rb')) or dir('ファイルを開けませんでした');
// アカウントファイルを一行ごとに読み込みます。
while ($fline = fgets($file, 1024)) {
  // ツイッター情報を取得し配列に格納します。
  $contents[] = $connection->get('statuses/user_timeline', ['screen_name' => $fline]);
}

// ソート用の前処理。
// ツイート情報の配列からアカウント毎にツイート情報を取り出します。
foreach ($contents as $value) {
  // 一番新しいツイート日時を取得し日付ソート用にDateTimeクラスで処理します。
  $time[] = new DateTime($value[0]->created_at);
  // アカウントIDを取得します。
  // $id[] = $value[0]->id . "\n";
  $id[] = $value[0]->id;
}

// ツイート情報配列についてツイート日時とアカウント(ツイート日時が同じだった場合)をキーにソートします。
array_multisort($time, SORT_DESC, SORT_REGULAR, $id, SORT_DESC, SORT_NUMERIC, $contents);

// テンプレートエンジン用の処理
$s = new MySmarty();
// テンプレート変数に割り当てます。
$s->assign('contents', $contents);
// テンプレートファイルを画面に出力します。
$s->d();

// print_r($contents);
