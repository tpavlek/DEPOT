<?php
 echo "hello";
?>

<script>
$.getJSON("https://api.twitch.tv/kraken/chat/ebonwumon?callback=hi&jsonp=?");

function hi(json) {
  console.log(json);
}
/*($.get({
  data: {channel: "FGTcortstar"},
  url: "http://api.justin.tv/api/stream/list.json",
  success: function(data) { console.log(data); }
});*/
</script>
