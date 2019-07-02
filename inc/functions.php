<?php 

function getFeed($feed_url, $feed_type){

	/*echo "Get Feed of Type: ".$feed_type."</br>"; 
	if($feed_type == "twitter"){
		echo "TRUE</br>";
	}*/

	$stories = array();
	
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($feed_url);  
	
	if($feed_type == "reddit"){ 
		$x = $xmlDoc->getElementsByTagName('entry'); 
	}else{
		$x = $xmlDoc->getElementsByTagName('item'); 
	}
	//print_r($x);
	//echo "</br>";

	$date = date('Y-m-d H:i:s');
	$four_hours_ago = strtotime($date .' -12 hours');  

	if($feed_type == "reddit"){
		$number_of_items = ($xmlDoc->getElementsByTagName('entry')->length)-1;
	}else{
		$number_of_items = ($xmlDoc->getElementsByTagName('item')->length)-1;
	}
	//echo $number_of_items." items</br>";

	for ($i=0; $i<=$number_of_items; $i++) {

		if($feed_type == "newspaper"){ 
			//echo $feed_type." a</br>";
			//print_r($x->item($i));
			$item_date = $x->item($i)->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue; 
			//echo $i."</br>".$item_date."<hr>";
		}elseif($feed_type == "twitter"){
			//echo $feed_type." b</br>";
			//print_r($x->item($i));
			$item_date = $x->item($i)->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue; 
			//echo $i."</br>".$item_date."<hr>";
		}elseif($feed_type == "reddit"){ 
			//echo $feed_type." c</br>";
			$item_date = $x->item($i)->getElementsByTagName('updated')->item(0)->childNodes->item(0)->nodeValue;

		}

		$item_date_conv = strtotime($item_date);  
 		$words_to_flag = array("austerity","oil","climate","recession","Corbyn","Maybot","poverty","riots","inflation");
 		$item_title = $x->item($i)->getElementsByTagName('title')
			->item(0)->childNodes->item(0)->nodeValue;

		if($item_date_conv > $four_hours_ago OR (in_array($item_title, $words_to_flag))){   
			array_push($stories, $item_title);
		}
	}

	return $stories;
} 

function getVibes($type){

	if($type == "newspapers"){

		$sources = array(
			"fox_news" => getFeed("http://feeds.foxnews.com/foxnews/politics","newspaper"),
			"daily_express_politics" => getFeed("https://feeds.feedburner.com/daily-express-politics?fmt=xml","newspaper"), 
			"sun_politics" => getFeed("https://www.thesun.co.uk/news/politics/feed/","newspaper"),
		 	"london_evening_standard" => getFeed("https://www.standard.co.uk/news/rss","newspaper"),
		 	"the_mirror" => getFeed("https://www.mirror.co.uk/?service=rss","newspaper"),,
		 	"the_telegraph" => getFeed("https://www.telegraph.co.uk/politics/rss.xml","newspaper"), 
		 	"sputnik_news" => getFeed("https://sputniknews.com/export/rss2/archive/index.xml","newspaper")
		);

	} else if($type == "twitter"){

		//echo "Get twitter </br>";
		$sources = array(
			"twitter_no10" => getFeed("https://twitrss.me/twitter_user_to_rss/?user=number10gov","twitter")
		);

	} else {

		$sources = array("reddit_the_uk" => getFeed("https://www.reddit.com/r/unitedkingdom/.rss","reddit"));
	}

	return $sources;

}

function getTab($tab_name){

	$words_to_ignore = array("a", "the", "it", "by", "he", "she", "over", "at", "as", "of", "for", "like", "to", "they", "that", "there", "them", "as", "was", "is");
	
	$newspapers_vibes = getVibes("newspapers");
	$twitters_vibes = getVibes("twitter");
	$reddits_vibes = getVibes("reddit");
	$sources = array_merge($newspapers_vibes, $twitters_vibes);

	echo "<h3>" . ucfirst($tab_name) . "</h3>";

	if($tab_name == "breaking") { 

		$breaking = array();
		$breaking_words = array();

		//print_r($sources);
		//echo "<hr>";

		foreach($newspapers_vibes as $source_base => $stories_base) {
		//echo $source_base."</br>"; 
		//print_r($stories_base);
		//echo "<hr>";

			foreach($stories_base as $story_one) { 
			//$sources["daily_express_politics"]
		
				//echo $story_one."</br>";

				$story_one = str_replace("'", "", $story_one);
				$story_one = str_replace("’", "", $story_one);
				$story_one = str_replace("‘", "", $story_one);
				$story_one = str_replace('"', "", $story_one);
				$words = preg_split('%[\s,]+%', $story_one);

				foreach($sources as $source => $stories) {

					//echo $source." and base: ".$source_base;
					//echo "</br>";
					//print_r($stories);

					if(($source != $source_base) && sizeof($stories)>0){
					//if(($source != "daily_express_politics") && sizeof($stories)>0){

						//echo "</br>PASS<hr>";

					    foreach($stories as $story_two) {

					    	$count = 0;
							foreach($words as $word) {

								if($word != ""){ 

									// /if(preg_match("/$keyword/i", $domain))
									if (strpos($story_two, $word) !== false && (!in_array($word, $words_to_ignore))) {
										$count++;
									} 
								}
							}
	 
							if($count > 0){ 
								$breaking_words[$story_one] ++;
							}
						}

					}
				}

			}
		}
 
 		arsort($breaking_words);
 		foreach($breaking_words as $title => $count) {
 			echo $title."</br>".$count."<hr>";
 		} 

	}
}
 
?> 