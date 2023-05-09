<?php
//-------------------------------------------
// プログラム名:ツイッター情報検索プログラム
// 説明:検索フォームから入力された検索ワードで予め指定した複数のツイッターを検索します。
// 　　 ツイート日時でソート後画面に表示します。
//-------------------------------------------
// デフォルトタイムゾーンを設定します。
date_default_timezone_set('Asia/Tokyo');
// Composerのオートローダーを読み込みます。
require_once './vendor/autoload.php';
// 定数などのコモンファイルを読み込みます。
require_once 'common.php';
// Smarty拡張クラスを読み込みます。
require_once 'MySmarty.class.php';
// 例外処理のクラスを読み込みます。
require_once 'UtilException.class.php';

// 検索フォームからポストされたワードを変数に代入します。
$searchWord = $_POST['searchWord'];

// ツイッター認証ライブラリ用の名前空間をインポートします。
use Abraham\TwitterOAuth\TwitterOAuth;
// ツイッター認証用のインスタンスを作成します。
$connection = new TwitterOAuth($consumer_key, $consumer_key_sercret, $access_token, $access_token_secret);
//API V2を使用します。
$connection->setApiVersion('2');
// ツイッターアカウント一覧のファイルを開きます。
($file = @fopen(FILE_PATH, 'rb')) or dir('ファイルを開けませんでした');

$data = ''; // データを格納する変数
// アカウントファイルを一行ごとに読み込みます。
while ($fline = fgets($file, 1024)) {
  // 一行のデータを取得し、カンマで区切って$dataに追加します。
  $data .= trim($fline) . ',';
}
$data = rtrim($data, ','); // 最後の余分なカンマを削除します。

// ユーザID取得用のパラーメータを作成します。
$params = [
  'usernames' => $data,
  'tweet.fields' => 'author_id,created_at', // 今回は追加で投稿日時を指定
  'expansions' => 'pinned_tweet_id',
  'user.fields' => 'id,name,username,url,description,profile_image_url',
];
// ユーザーIDを含むユーザー情報を取得します。
$user_data[] = $connection->get('users/by', $params);

// ツイート検索用のパラメーターを作成し、検索ワードでヒットしたツイート情報を取得します。
foreach ($user_data[0]->data as $item) {
  $user_id = trim($item->id);
  $params_ids = [
    'query' => "from:{$user_id} {$searchWord}",
    'tweet.fields' => 'created_at',
    'expansions' => 'author_id',
    'max_results' => 10,
    'user.fields' => 'id,name,username,url,description,profile_image_url',
  ];
  $contents[] = $connection->get('tweets/search/recent', $params_ids);
}

// 検索ワードにヒットしなかったアカウントを除外します。
$filterResults = array_filter($contents, function ($ac) {
  // data配列が空のアカウントを除外します。(ヒットしたツイート情報はdata配列に格納)
  return !empty($ac->data);
});

// 検索結果のチェック処理
try {
  // 検索結果が空(0件)かチェックします。
  UtilException::checkContents($filterResults);
  // 検索結果が空だった場合
} catch (Exception $e) {
  // エラーメッセージを変数に格納します。
  $errorMsg = $e->getMessage();
  // テンプレートエンジン用の処理
  $s = new MySmarty();
  // テンプレート変数に割り当てます。
  $s->assign('errorMsg', $errorMsg);
  // エラー画面を表示します。
  $s->display('error.tpl');
  // 処理を終了します。
  exit();
}

// ソートの前処理。
// ツイート情報の配列からソート用にツイート日時、ユーザIDを取り出します。
foreach ($filterResults as $value) {
  // 一番新しいツイート日時を取得し日付ソート用にDateTimeクラスで処理します。
  $time[] = new DateTime($value->data[0]->created_at);
  // アカウントIDを取得します。
  $id[] = $value->includes->users[0]->id;
}

// ツイート情報配列についてツイート日時とアカウント(ツイート日時が同じだった場合)をキーにソートします。
array_multisort($time, SORT_DESC, SORT_REGULAR, $id, SORT_DESC, SORT_NUMERIC, $filterResults);

// テンプレートエンジン用の処理
$s = new MySmarty();
// テンプレート変数に割り当てます。
$s->assign('contents', $filterResults);
$s->assign('searchWord', $searchWord);
// テンプレートファイルを画面に出力します。
$s->d();
