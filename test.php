<html>
<script type="text/javascript" src="includes/jquery.js"></script>
<body>
<?php
$arr = array('arr1' => array('desu', 'two'), 'arr2' => array('key' => 'value'));
$other = array('key2' => 'val2');
print_r(array_merge($arr['arr2'], $other));
?>
</body>
</html>

