function adminDeletePost(evt) {
	var pid = $(evt.target).parents().filter('ul').attr('id');
	$.ajax({
		url: "api.php?type=post&method=adminDeletePost",
		type: "POST",
		dataType: "json",
		success: hidePost,
		error: errorPost,
		data: {'pid': pid}
	});

}

function hidePost(data) {
	if (data['status'] == 0) {
		$('#' + data['pid']).hide('fast');
	}
}

function modifyDeletedPost(data) {
	if (data['status'] == 0) {
		$('#' + data['pid']).children('.message').html("[deleted]");
	}
}

function userDeletePost(evt) {
	var pid = $(evt.target).parents().filter('ul').attr('id');
	$.ajax({
		url: "api.php?type=post&method=userDeletePost",
		type: "POST",
		dataType: "json",
		success: modifyDeletedPost,
		error: errorPost,
		data: {'pid': pid}
	});
}

function errorPost(data) {
	console.log(data);
	console.log("error");
}
// $(evt.target).parents().filter('ul')
