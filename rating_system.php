<?php

/**
* PHP RATING SYSTEM v 1.0 BETA
* Mihai Ionut Vilcu (ionutvmi@gmail.com)
* Mar 2013
* master-land.net
*/


class rating_system {
	
	var $img_path; // the path to images folder
	var $db_path; // the path to a txt file where the ratings votes will be stored
	var $max_points; // max points per 1 vote
	var $unique; // The thing that makes a user unique, it can be an ip or userid or anything that works for you
	
	/**
	* the construct function
	* @param $max_points - max points per 1 vote
	* @param $db_path - the path to a txt file where the ratings votes will be stored
	*
	*/
	
	function __construct($unique,$max_points = 5, $db_path = './ratings.txt', $img_path = "./img"){
		$this->unique = $unique;
		$this->max_points = $max_points;
		$this->img_path = $img_path;
		if(fopen($db_path,"a+") && is_writable($db_path))
			$this->db_path = $db_path;
		else
			die("There is a problem withe the db file !");
	}
	
	/**
	* show_rating() - this will show the rating
	* @param $item - the item on witch you want to show the rating
	* @param $return - return or echo the data
	*/
	function show_rating($item, $return = 0){
		// we grab the data from the file
		$data = $this->grab_data();
		$total_score = 0; 
		$total_votes = 0;
		
		foreach($data as $d) {
			if($d[1] == $item){
				$total_score += $d[2];
				$total_votes++;
			}
		}
		
		if($total_score == 0)
			$rating = 0;
		else{
			$fromminrate = 1;
			$frommaxrate = $this->max_points;
			$tominrate = 0;
			$tomaxrate = 5;
			$rating = $tominrate + (
				 ((($total_score/$total_votes)-$fromminrate)
				 / ($frommaxrate-$fromminrate))
				 * ($tomaxrate-$tominrate));
			$rating = number_format(0.5 * ceil(2.0 * $rating),1); // get the next half-int 
		}
		$html = "<img src='".$this->img_path."/$rating.gif'>";
		
		if($return)
			return $html;
		else
			echo $html;
	}
	
	/**
	* set_rating() - This will add the new score to the db file
	* @param $item - the item you want to add the vote too
	* @param $score - the score you want to add
	*/
	function set_rating($item, $score){
		// we grab the data from the file
		$data = $this->grab_data();
		
		$score = (int)$score; // only int, you should modify this if you want to accept other values
		
		if($score > 0 && $score <= $this->max_points) {		
			
			// check if this person voted before on this item
			foreach($data as $d) {
				if($this->unique == $d[0] && $item == $d[1])
				return false;
			}
			
			
			// we add the new data to the array
			$data[] = array($this->unique, $item, $score);
			
			// we add it to the file
			file_put_contents($this->db_path,serialize($data));
			return true;
		}else {
			return false;
		}
	}
	/**
	* show_vote_options() - it will show a vote form
	* @param $page - the url where the vote data will be sent
	* @param $item - the item you want to add the vote
	* @param $return - return or echo the results
	*
	*/
	function show_vote_options($page, $item, $return = 0){
		
		$html = "<form action='$page' method='post'>
		<select name='score'>";
		for($i = 1; $i <= $this->max_points; $i++)
			$html .= "<option value='$i'>$i</option>";
		
		$html .= "</select>
		<input type='submit' value='vote'>
		<input type='hidden' name='item' value='$item'>
		</form>";
		
        // we grab the data from the file
		$data = $this->grab_data();

        $voted = 0;
        // check if this person voted before on this item
        foreach($data as $d) {
            if($this->unique == $d[0] && $item == $d[1])
            $voted = 1;
        }
        
        if($voted)
            $html = "";


		if($return)
			return $html;
		else
			echo $html;
	}
	
	function grab_data() {
		// we grab the data from the file
		$data = unserialize(file_get_contents($this->db_path));
		
		if(!is_array($data))
			$data = array();

		return $data;
	}
	
}
