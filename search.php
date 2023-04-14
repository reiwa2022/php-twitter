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

// ツイッター認証ライブラリ用の名前空間をインポートします。
use Abraham\TwitterOAuth\TwitterOAuth;
// 検索フォームからポストされたワードを変数に代入します。
$searchWord = $_POST['searchWord'];

// ツイッター認証用のインスタンスを作成します。
$connection = new TwitterOAuth($consumer_key, $consumer_key_sercret, $access_token, $access_token_secret);

// ツイッターアカウント一覧のファイルを開きます。
($file = @fopen(FILE_PATH, 'rb')) or die('ファイルの読み込みに失敗しました');

// アカウントファイルを一行ごとに読み込みます。
while ($fline = fgets($file, 1024)) {
  // ツイッター情報を検索し配列に格納します。
  $contents[] = $connection->get('search/tweets', ['from' => $fline, 'count' => TWEETS_NUM, 'q' => $searchWord]);
}

// 検索ワードにヒットしなかったアカウントを除外します。
$filterResults = array_filter($contents, function ($ac) {
  // statuses配列が空のアカウントを除外します。(ヒットしたツイート情報はstatuses配列に格納)
  return !empty($ac->statuses);
  // return $ac->statuses !== '';
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

// ソート用の前処理。
// ツイート情報の配列からアカウント毎にツイート情報を取り出します。
foreach ($filterResults as $value) {
  // 一番新しいツイート日時を取得し日付ソート用にDateTimeクラスで処理します。
  $time[] = new DateTime($value->statuses[0]->created_at);
  // アカウントIDを取得します。
  $id[] = $value->statuses[0]->id;
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
