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
//API V2を使用します。
$connection->setApiVersion('2');
// ツイッターアカウント一覧のファイルを開きます。
($file = @fopen(FILE_PATH, 'rb')) or dir('ファイルを開けませんでした');
// 文字列連結用に変数を初期化します。
$data = '';
// アカウントファイルを一行ごとに読み込み、カンマ区切りで$dataに追加します。
while ($fline = fgets($file, 1024)) {
  // アカウント一覧をカンマ区切りで連結します。
  $data .= trim($fline) . ',';
}
$data = rtrim($data, ','); // 最後の余分なカンマを削除します。

// ユーザID取得用のパラーメータを作成します。
$params = [
  'usernames' => $data, //カンマ区切りのアカウント一覧
  'tweet.fields' => 'author_id,created_at',
  'expansions' => 'pinned_tweet_id',
  'user.fields' => 'id,name,username,url,description,profile_image_url',
];
// TwitterAPIからユーザーIDを含むユーザー情報を取得します。
$user_data[] = $connection->get('users/by', $params);

// ユーザーIDでツイート情報(タイムライン)を取得します。
foreach ($user_data[0]->data as $item) {
  $user_id = trim($item->id);
  $params_ids = [
    'query' => "from:{$user_id}",
    'tweet.fields' => 'created_at',
    'expansions' => 'author_id',
    'max_results' => 10,
    'user.fields' => 'id,name,username,url,description,profile_image_url',
  ];
  $contents[] = $connection->get('tweets/search/recent', $params_ids);
}

// ソートの前処理。
// ツイート情報の配列からソート用にツイート日時、ユーザIDを取り出します
foreach ($contents as $value) {
  // 一番新しいツイート日時を取得し日付ソート用にDateTimeクラスで処理します。
  $time[] = new DateTime($value->data[0]->created_at);
  // アカウントIDを取得します。
  $id[] = $value->includes->users[0]->id;
}

// ツイート情報配列についてツイート日時とユーザID(ツイート日時が同じだった場合)をキーにソートします。
array_multisort($time, SORT_DESC, SORT_REGULAR, $id, SORT_DESC, SORT_NUMERIC, $contents);

// テンプレートエンジン用の処理
$s = new MySmarty();
// テンプレート変数に割り当てます。
$s->assign('contents', $contents);
// テンプレートファイルを画面に出力します。
$s->d();
