<html>

<head>
  <meta charset="utf=8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Twitter情報表示サイト(勉強用)</title>
  <link rel="stylesheet" href="css/destyle.css">
  <link rel="stylesheet" href="css/twitter_app.css">
  <script defer type="text/javascript" id="sjs" data-searchword="{$searchWord}" src="js/word-highlight-url-link.js">
  </script>
</head>

<body>
  <div class="body-area">
    <div class="header-area">
      <div class="title-search-area">
        <h1 class="main-title">Twitter情報表示サイト(勉強用)</h1>
        <div class="search-area">
          <form method="post" action="search.php">
            <label for="search-box">ツイート検索</label><br>
            <input type="search" id="search-box" name="searchWord" placeholder="検索ワード" required>
            <button type="submit">検索</button>
          </form>
        </div>
      </div>
      <div class="author-area">
        <p>連絡先</p>
        <p>保泉</p>
        <address>
          <p>ho***ay@gmail.com</p>
          <p>080-88**-23**</p>
        </address>
      </div>
    </div>
    <div class="twitter-container-area">
      {foreach from=$contents item=content}
        <div class="twitter-container-one">
          <div class="twitterーcontainer-top">
            <div class="twitter-icon">
              <a href="https://twitter.com/{$content->includes->users[0]->username}" target="_blank">
                <img src="{$content->includes->users[0]->profile_image_url}" alt="ツイッターアイコン">
              </a>
            </div>
            <div class="twitter-top-right">
              <div class="twitter-name">
                <a href="https://twitter.com/{$content->includes->users[0]->username}" target="_blank">
                  {$content->includes->users[0]->name}
                </a>
              </div>
            </div>
            <div class="tweeter-description-area">
              {$content->includes->users[0]->description}
            </div>
          </div>
          <div class="tweet-container-bottom">
            {foreach from=$content->data item=elem}
              <p class="tweets-content">{$elem->text}</p>
              <p class="tweet-time">{$elem->created_at|date_format:"Y年m月d日 H:i:s"}</p>
              <hr class="tweet-hr">
            {/foreach}
          </div>
        </div>
      {/foreach}
    </div>

    <p class="back-p"><a href="JavaScript:history.back()" class="link-underline">戻る</a></p>
    <hr class="copylight-hr">
    <div id="footter-area">
      <div class="admin-link">
        <p>管理者画面</p>
      </div>
      <div class="copylight">
        <p><small>&copy; Hozumi 2022</small></p>
      </div>
    </div>
  </div>
</body>

</html>