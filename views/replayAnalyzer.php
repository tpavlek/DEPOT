<?php
require_once('obj/Map.php');
if (isset($_GET['parse'])) {
  require_once('sc2replay/mpqfile.php');
  print_r($_FILES);
  $mpqfile = new mpqfile($_FILES['replayUpload']['tmp_name']);
  $replay = $mpqfile->parseReplay();
  foreach ($replay->getPlayers() as $player) {
    ?>
      <button class='btn <?php echo (isset($player['won'])) ? "btn-success" : "btn-danger"; ?>'> <?php echo $player['name']; ?></button>
    <?php
  }
  $map = new Map($replay->getMapName());
  ?>
    <img src='<?php echo $map->getPath(); ?>' class='img-polaroid' width=200>
<?php
} else {
?>
<h2>Upload SC2Replay For Analysis</h2>
<form action='index.php?page=replayAnalyzer.php&parse=1' method="POST" enctype="multipart/form-data">
<input name="replayUpload" type='file' />
<input type='submit' class='btn btn-primary' value="Upload" />
</form>

<?php
}
?>
