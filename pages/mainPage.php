<div class="options"><form method="GET">
I want to see:
<select name="display">
<option value="crayons">Crayons</option>
<option value="blag">Blog Posts</option>
</select>
<input type="checkbox" name="remember" value="1">
<input type="submit" value="Show me!" />
</form>
</div>
<?php
if (isset($_GET['display']))
        $display = $_GET['display'];
elseif (isset($_COOKIE['mainpage']))
    $display = $_COOKIE['mainpage'];
else
    $display = "blag";

switch ($display) {
    case "blag": echo "<script src=\"http://feeds.feedburner.com/DepotWarehouseBlag?format=sigpro\" type=\"text/javascript\" ></script>"; break;
    case "crayons": echo "<script src=\"http://feeds.feedburner.com/CrayonArt?format=sigpro\" type=\"text/javascript\" ></script>"; break;
}
?>