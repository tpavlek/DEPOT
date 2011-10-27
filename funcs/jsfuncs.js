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

function adminDeleteTopic(evt) {
	var tid = $(evt.target).parents().filter('ul').attr('id');
	$.ajax({
		url: "api.php?type=topic&method=adminDeleteTopic",
		type: "POST",
		dataType: "json",
		success: hideTopic,
		data: {'tid': tid}
	});
	console.log('after ajax');
}

function hideTopic(data) {
	$('.topic').hide('fast');
}

function userDeleteTopic(evt) {
	var tid = $(evt.target).parents().filter('ul').attr('id');
	$.ajax({
		url: "api.php?type=post&method=userDeleteTopic",
		type: "POST",
		dataType: "json",
		success: hideTopic,
		data: {'tid': tid}
	});
}

function hidePost(data) {
	if (data['status'] == 0) {
		$('#' + data['pid']).hide('fast');
	}
}

/*function modifyDeletedPost(data) {
	if (data['status'] == 0) {
		$('#' + data['pid']).children('.message').hide('fast');
		$('#' + data['pid']).children('.message').html("[deleted]").show('fast');
	}
}*/

function userDeletePost(evt) {
	var pid = $(evt.target).parents().filter('ul').attr('id');
	$.ajax({
		url: "api.php?type=post&method=userDeletePost",
		type: "POST",
		dataType: "json",
		success: hidePost(data),
		error: errorPost,
		data: {'pid': pid}
	});
}

function errorPost(data) {
	console.log(data);
	console.log("error");
}
// $(evt.target).parents().filter('ul')
