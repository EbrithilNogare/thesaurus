<?php





echo crypt("123456789", '$1$jeuzbfrs$tYNFy3vyfhn6h/lftFYMH0');
if (hash_equals($hashed_password, crypt($user_input, $hashed_password))) {
	echo "Password verified!";
 }