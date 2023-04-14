<script type="text/javascript">
  alert("test");
  const timeTrans = createTime => {
    var date = new Date((Date.parse(createTime)) + ((new Date().getTimezoneOffset() + (9 * 60)) * 60 * 1000));
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = (date.getHours() < 10) ? "0" + date.getHours() : date.getHours();
    var min = (date.getMinutes() < 10) ? "0" + date.getMinutes() : date.getMinutes();
    var sec = (date.getSeconds() < 10) ? "0" + date.getSeconds() : date.getSeconds();

    return `${year}-${month}-${day} ${hour}:${min}:${sec}`;
  }
  var tweetTime = timeTrans({$created_at});
  alert("tweetTime");
</script>
{$created_at}