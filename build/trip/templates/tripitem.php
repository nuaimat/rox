<?php

$words = new MOD_words();

// show the first image if there is a photoset assigned to the trip
if (isset($trip->gallery_id_foreign) && $trip->gallery_id_foreign) {
    $gallery = new Gallery;
    $d = $gallery->getLatestGalleryItem($trip->gallery_id_foreign);
    if ($d) {
        $galleryitem = '<a href="gallery/show/image/'.$d.'"><img src="gallery/thumbimg?id='.$d.'" alt="image" style="margin:10px;" class="framed" /></a>';
    } else {
        $galleryitem = '';
    }
	$gallerylink = '<a href="gallery/show/galleries/'.$trip->gallery_id_foreign.'">'.$words->get('TripShowPhotoset').'</a>';
} else {
    $galleryitem = '';
	$gallerylink = '';
}
?>

<h3 class="trip_title"><?php echo $trip->trip_name; ?></h3>
<div class="trip_author">by <a href="user/<?php echo $trip->handle; ?>"><?php echo $trip->handle; ?></a>

<?php
// show flags for a trip that is about a specific country !?
if ($trip->fk_countrycode) {
	echo ' <a href="country/'.$trip->fk_countrycode.'"><img src="images/icons/flags/'.strtolower($trip->fk_countrycode).'.png" alt="" /></a>';
}
?>
        <a href="blog/<?php echo $trip->handle; ?>" title="Read blog by <?php echo $trip->handle; ?>"><img src="images/icons/blog.gif" alt="" /></a>
        <a href="trip/show/<?php echo $trip->handle; ?>" title="Show trips by <?php echo $trip->handle; ?>"><img src="images/icons/world.gif" alt="" /></a>
         &mdash; 
		<a href="trip/<?php echo $trip->trip_id; ?>"><?php echo $words->get('TripDetails'); ?></a> 
        <?=$gallerylink ?></div>

<div class="floatbox">
<?php if ($galleryitem) {
   echo '<div class="gallery-item" style="float:left">'.$galleryitem.'</div>';
}
?>

<div class="float_left">
<?php
if (isset($trip->trip_descr) && $trip->trip_descr) {
	echo '<p>'.$trip->trip_descr.'</p>';
}

if (isset($trip->trip_text) && $trip->trip_text) {
	echo '<p>'.$trip->trip_text.'</p>';
}




if (isset($trip_data[$trip->trip_id])) {
    $counter = 0;
	echo '<ul>';
	foreach ($trip_data[$trip->trip_id] as $blogid => $blog) {
        echo '<li style="line-height: 2.2em"><span style="background-color: #666; font-size: 14px; text-align: center; padding: 5px 10px 5px 10px; color: #fff; font-weight: bold; ) no-repeat top left;">';
        echo ++$counter;
		if ($blog->name) {
			echo ' / ';
			echo $blog->name;
		}
        echo '</span> ';
		echo '<a href="blog/'.$trip->handle.'/'.$blogid.'">'.$blog->blog_title.'</a>';

		if ($blog->blog_start) {
			echo ', ';
			echo $blog->blog_start;
		}
		echo '</li>';
			
	}
	echo '</ul>';
}



?>
</div>
</div>