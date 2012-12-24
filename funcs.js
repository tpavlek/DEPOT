function newReply() {
  var tid = getURLParameter("tid");
  var mysubject = $('#replySubject').val();
  var mymessage = $('#replyMessage').val();
  var myurl = "api.php?type=forum&method=reply&tid=" + tid;
  $.ajax({
    type: "POST",
    dataType:"json",
    url: myurl,
    data: {subject:mysubject , message: mymessage},
    success: function(data) {
      //todo ajax loading
      console.log(data);
    }
  });
}

function newTopic() {
  var fid= getURLParameter("fid");
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "api.php?type=forum&method=newTopic&fid=" + fid,
    data: {subject: $('#topicSubject').val(), message: $('#topicMessage').val()},
    success: function(data) {
      //ajax
      console.log(data);
    },
    error: function(jqXHR) {
      console.log(jqXHR);
    }
  });
}
function getLatestPosts() {
  var pids = [];
  $('#posts').find('div.post').each(function() { pids.push(this.id); });
  console.log(pids);
  /*
  $.ajax({
    type:"GET",
    url: "api.php?type=forum&method=getLatestPosts&tid=12&pid=" + pid,
    success: loadLatestPosts(data),
  });*/
}

function getURLParameter(name) {
      return decodeURI(
                  (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
                      );
}

function logout() {
  $.get('api.php?type=sess&method=logout', function() {
    location.reload();
  });
}
function openPopupWindow(openid) {
  $('#registerButton').html('Authenticating');
  window.open('/openid/begin.php?openid_identifier='+encodeURIComponent(openid), 'openid_popup', 'width=790,height=580');
}

function openGoogleWindow() {
    openPopupWindow('https://www.google.com/accounts/o8/id');
    } 

function handleOpenIDResponse(obj) {
  console.log("hello");
 
  if ($('#registerPopup').css('display') == 'none') {
    console.log('login');
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "api.php?type=sess&method=loginUser",
      data: {email: obj.email},
      success: function(data) {
        location.reload();
        //TOOD fail case
      }
    });
  } else if (obj.success) {
    $.ajax({
      type: "POST",
      dataType:"json",
      url: "api.php?type=register&method=registerUser",
      data: {username: $('#inputUsername').val(), email: obj.email},
      error: function(jqXHR, textStatus, errorThrown) { console.log(errorThrown) },
      success: function(data) {
        if (data.status == 1) {
          $('#inputUsernameControlGroup').addClass('error');
          $('#registerButton').html('Resubmit');
          $('#usernameError').show().html(data.message);
          $('#registerButton').click(function() {
            $('#inputUsernameControlGroup').removeClass('error');
            $('#registerButton').html('Authenticating');
            $('#usernameError').hide();
          });
        } else {
          $('#registerPopup').modal('hide');
          location.reload();
        } 
      }
    }); 
  } else {
    $('#registerButton').html('failed');
  }
}

