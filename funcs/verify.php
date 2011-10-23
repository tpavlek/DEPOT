<?php
function verifyString($word, $min=3, $max=30) {
    $blacklist=array("<space>,<!doctype>,<a>,<abbr>,<acronym>,<address>,<applet$
<basefont>,<bdo>,<big>,<blockquote>,<body>,<br>,<button>,<caption>,<center>,<ci$
<del>,<dfn>,<dir>,<div>,<dl>,<dt>,<em>,<fieldset>,<font>,<form>");
    $hasLetter=false;
    foreach ($blacklist as $bad)
        $newword = str_replace($bad,chr(17),$word);
    foreach (str_split($newword) as $bad) {
        if (ord($bad) > 32 && ord($bad) < 127) {
            $hasLetter=true;
     }
     }
    if ($hasLetter == true)
      return true;
		elseif (strlen($word) > $max)
			return false;
    else
        return false;
    }
?>
