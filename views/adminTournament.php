<?php
if (!$page->permissions(array("admin"))) { ?> 
  <script>
    window.location = "index.php"; 
    return;
  </script> 
<?php }
if (!isset($tourn_id)) {
  //CREATE TOURNAMENT

}

if (isset($tourn_id)) {

}
?>

