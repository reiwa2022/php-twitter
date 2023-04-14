//
// スクリプト名: ツイートの検索ワードをハイライトし、URL文字列にリンクを貼ります(javascript)
// 説明: 検索結果画面で使います。
//

// アカウントごとに全ツイートを取得します。
let tweetContents = document.getElementsByClassName('tweets-content');
// data属性にセットされた検索ワードを取得します。
let searchWord = document.getElementById('sjs').dataset.searchword;

// searchWordで大文字小文字を区別せず複数にマッチする正規表現オブジェクトを作成します
let reg = new RegExp(searchWord, 'gi');
// アカウントごとにループ処理を行いツイートコンテンツを処理します。
for (let i = 0; i < tweetContents.length; i++) {
  // ツイートのテキストコンテンツを取得します。
  let tweetContent = tweetContents[i].textContent;
  // ツイートコンテンツについて正規表現オブジェクトにマッチしたものをハイライト用のタグで囲います。
  let searchWordReplace = tweetContent.replace(reg, (matchWord) => {
    return `<span class="highlight">${matchWord}</span>`;
  });
  // ハイライト用のタグで囲んだテキストコンテンツについてURL文字列にリンクを貼ります。
  tweetContents[i].innerHTML = searchWordReplace.replace(
    /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi,
    "<a href='$1' class='link-underline' target='_blank'>$1</a>"
  );
}
