<html>
<script type="text/javascript" src="includes/jquery.js"></script>
<body>
<script typ="text/javascript">
	function loadNewPage(evt) {
		$.ajax({
			url: "http://lemur/?page=forum",
			dataType: "html",
			success: gotPage,
		});
	}
	
	function gotPage(data) {
		console.log(data);
		$('body').html(data);
	}
	</script>
<?php

?>

<button onclick=loadNewPage(event)>HELLO</button>
</body>
</html>

