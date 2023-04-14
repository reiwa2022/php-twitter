<?php
//-------------------------------------------
// プログラム名:Smarty拡張クラス
// 説明:Smartyクラスを継承し初期値等の設定を行う
//-------------------------------------------
require_once './vendor/autoload.php';

class MySmarty extends Smarty
{
  // コンストラクターをオーバライドします。
  public function __construct()
  {
    // Smartyクラスをコンストラクタを呼び出します。
    parent::__construct();
    // テンプレートファイル格納用のフォルダを設定します。
    $this->template_dir = './templates';
    // テンプレートファイルコンパイル用のフォルダを設定します。
    $this->compile_dir = './templates_c';
    // 適用可能な文字ををすべてhtmlエスケープ処理します。(php→テンプレート出力時)
    $this->default_modifiers = ['escape: "htmlall"'];
  }
  // Smartyクラスのdisplayメソッドを呼び出します。
  public function d()
  {
    // phpファイルと同名のテンプレートファイルを画面に出力します。
    parent::display(basename($_SERVER['PHP_SELF'], '.php') . '.tpl');
  }
}
