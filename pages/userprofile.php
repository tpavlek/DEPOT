<?php
include("common.php");
?>
<div id="listbox">
<?php
if (!isset($_GET['uid'])) {
$query = "SELECT id, username, join_date, postcount, points from user ";

if(isset($_COOKIE['block']))
$query = $query . "where block = " . $_COOKIE['block']; 
if (isset($_GET['sort'])) {
	switch($_GET['sort']) {
	case 'username': $query = $query . " order by username"; break;
	case 'joined': $query = $query . " order by join_date"; break;
	case 'postcount': $query = $query . " order by postcount DESC"; break;
	default: $query = $query . " order by points DESC";
	}
} else {
$query = $query . " order by points DESC";
}



$users = $pdo->query($query);
echo "<table cellspacing=\"0\"><th><a href='?page=userprofile&sort=username'>Username</a></th>
<th><a href='?page=userprofile&sort=joined'>Joined on</a></th>
<th><a href='?page=userprofile&sort=postcount'>Post Count</a></th>
<th><a href='?page=userprofile&sort=points'>Points</a></th>";
foreach ($users as $row)
	echo "<tr><td><a href=\"?page=userprofile&uid=".$row['id']."\">" 
	. $row['username']. "</td><td>" . $row['join_date'] . "</td><td>" 
	. $row['postcount'] . "</td><td>" . $row['points'] . "</td></tr>";
}

else {
    $query = "SELECT username, join_date, postcount, rank, points from user where id =".$_GET['uid'];
    $display = $pdo->query($query);
    $data = $display->fetch();
    echo "<span class=\"big\">".$data['username']."</span><br><i>".$data['rank']."</i><br><br>";
    echo "<span class=\"big\">Posts:".$data['postcount']."</span><br><br>";
    echo "<span class='big'>Points:" . $data['points'] . "</span><br>";
	}
?>
</table>
</div>
