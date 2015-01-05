<?php

function eh($string)
{
    if (!isset($string)) return;
    echo htmlspecialchars($string, ENT_QUOTES);
}
function readable_text($s)
{
    $s = htmlspecialchars($s, ENT_QUOTES);
    $s = nl2br($s);
    return $s;
}
function to_array($object)
{
	$new_array = array();
	foreach ($object as $key => $value) {
		$new_array[$key] = $value;
	}
	return $new_array;
}