<?php

$date = Rb_Date::getInstance()->toSql();

return  Array(
		'category_id'	=>	0,
		'title'			=> 	null,
		'published'		=>	1,
		'cover_media'	=>	null,
		'teaser'		=>	null,
		'description'	=>	null,
		
		'publish_up'	=>	$date,
		'publish_down'	=>	'0000-00-00 00:00:00',
		
		'ordering'		=>	0,
		'featured'		=>	0,

		'hits'			=>	0,
		'meta_data'		=>	'{}' 		//"{'title':'','descriprion':'','keyword':''}"
);
