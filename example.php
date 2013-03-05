<?php

// simple example on how you can integrate it in your system

include "rating_system.php";


$rating = new rating_system($_SERVER['REMOTE_ADDR'], 10);

// just some dump data

for($i = 0; $i < 10; $i++){
	echo "<b>This is item $i </b><br/>";
	$rating->show_vote_options("vote.php", $i);
	echo "<br/> Current rating for item $i is ";
	$rating->show_rating($i);
	echo "<hr>";

}