<?php
include("new.php");
if (isset($_GET["adventure"])) {
   $requestedadventure = $_GET["adventure"];
   if (strpos($_GET["adventure"], " ")) die("Invalid parameter passed.");



                if (strcmp($requestedadventure, $defaultadventure) == 0)

                        $usedefcat = true;

                else

                        $usedefcat = false;

        } else {
                $requestedadventure = $defaultadventure;

                $usedefcat = true;

        }
$i=0;
include("$requestedadventure/caps.txt");
$adventure=preg_replace('/_/',' ',$requestedadventure);
?>
<html>
<head>
		<title>TEKAEF</title>
        <link rel="stylesheet" type="text/css" href="style.css">
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" />	
</head>
	<body>
	
<?php
include("includes/header.php");
?>
<h1>The Epic Kitchen Adventures of Ebon and Friends</h1>
<?php
echo"
    <h2>Adventure: $adventure</h2>
";
$dir = glob($requestedadventure . '/*.jpg');
foreach($dir as $file) {
	echo "<img class=\"pics\" src=\"$file\" alt=\"$cap
\"><br><br><div class=\"caption\">$cap[$i]</div><br>";
        $i++;
}
echo"<p><br><br><a 
href=\"Old.php\">Old Releases</a> 
<br><a href=\"mailto:ebonwumon@depotwarehouse.net\">ebonwumon@depotwarehouse.net</a></p>";
?>
</body>
</html>
