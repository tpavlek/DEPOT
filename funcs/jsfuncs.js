var pageNum = 1;//((getUrlVars()['pageNum'])) + 1;
	//if (pageNum) pageNum = 1;
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
}

function userDeleteTopic(evt) {
	var tid = $(evt.target).parents().filter('ul').attr('id');
	$.ajax({
		url: "api.php?type=topic&method=userDeleteTopic",
		type: "POST",
		dataType: "json",
		success: hideTopic,
		data: {'tid': tid}
	});
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

function loadNextPageOfPosts(evt) {
	var tid = $('.topic').children('ul').attr('id');
	$.ajax({
		url: "api.php?type=post&method=loadNextPage",
		type: "GET",
		dataType: "json",
		success: concatNextPage,
		data: {'tid': tid, 'num': pageNum}
	});
}

function concatNextPage(data) {
	if (data['status'] == 0) {
		pageNum++;
		$('.topic').append(data['html']);
	}
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
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
		success: hidePost,
		data: {'pid': pid}
	});
}


function showDropDown(evt) { //TODO make this re-usable
	if (!$('#userControlDropDown').is(':visible')) {
		$('#userControlDropDown').show('fast');
	} else
		$('#userControlDropDown').hide('fast');
}

function errorPost(data) {
}
// $(evt.target).parents().filter('ul')
