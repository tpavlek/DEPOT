<html>
    <head>
    <title>TEKAEF</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <h1>The Past Epic Kitchen Adventures of Ebon and Friends</h1>
<?php
$adventuredirs=glob('*',GLOB_ONLYDIR);
foreach ($adventuredirs as $folder) {
 $imgs = glob($folder . '/*.jpg');
 $img = empty($imgs) ? '' : '<img class="old" src="'.$imgs[0].'" /><br><br>';
 echo '<center><div class="olddiv"><a href="index.php?adventure=' . $folder . '">' . $img . str_replace('_',' ',$folder) . '</a></div></center><br><br>';
}
?>
