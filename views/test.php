<?php
require_once('obj/db.php');
$db =  DB::getInstance();
print_r($db->isInMatch(17, 0));
print_r($_SERVER);
?>

<script>

/*($.get({
  data: {channel: "FGTcortstar"},
  url: "http://api.justin.tv/api/stream/list.json",
  success: function(data) { console.log(data); }
});*/
</script>
