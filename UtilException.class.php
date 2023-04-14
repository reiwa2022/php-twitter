<?php
//-------------------------------------------
// プログラム名:配列件数チェック処理
// 説明:配列が空の場合、例外をスローします。
//-------------------------------------------
require_once 'common.php';
class UtilException
{
  public static function checkContents(array $arr)
  {
    // 配列が空の場合。例外をスローします。
    if (empty($arr)) {
      throw new Exception(ERR_MSG);
    }
  }
}
