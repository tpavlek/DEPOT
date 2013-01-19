
<div class="row">
<div class="span8">
<h3>[FGT] Presents: C-Weekly</h3>
</div>
<div class="span4">
<a class="btn btn-info pull-right" 
href="http://www.z33k.com/games/starcraft2/tournaments/9978-fgt-hosts-c-weekly-1/brackets">Brackets</a>
</div>
</div>
<div class="row">
  <div class="span8">
<object type="application/x-shockwave-flash" height="400" width="630" 
id="live_embed_player_flash" 
data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=cortstar" 
bgcolor="#000000"><param name="allowFullScreen" value="true" /><param 
name="allowScriptAccess" value="always" /><param name="allowNetworking" 
value="all" /><param name="movie" 
value="http://www.twitch.tv/widgets/live_embed_player.swf" /><param 
name="flashvars" 
value="hostname=www.twitch.tv&channel=ebonwumon&auto_play=true&start_volume=25" 
/></object>
  </div>
  <div class="span4">
    <div class="well">
<iframe frameborder="0" scrolling="no" id="chat_embed" 
src="http://twitch.tv/chat/embed?channel=ebonwumon&amp;popout_chat=true" 
height="400" width="350"></iframe>
    </div>
  </div>
</div>
<div class="row">
<div class="span4"></div>
<div class="span4">
<button id="mailFormShow" class="btn btn-success btn-large btn-block">Subscribe to FGT Mail</button>
</div>
</div>
<div class="row">
<div class="span2"></div>
<div class="span8">
<div id='mailchimp-form' style="display:none;">
<iframe height="500" width="600" name="mail-form" src="http://eepurl.com/ucHgD" scrolling="no" frameborder="0">
</iframe>
</div>
</div>
</div>
<script>
$('#mailFormShow').click(function() {
  if (!($('#mailchimp-form').is(':visible'))) {
    $('#mailchimp-form').show('fast');
    $('#mailFormShow').html('Hide subscription form');
  } else {
    $('#mailchimp-form').hide('fast');
    $('#mailFormShow').html('Subscribe to FGT Mail');
  }
});
</script>

