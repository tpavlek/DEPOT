<?php
/* Copyright by spl1nes.com & Dennis Eichhorn
 * eMail: coyle.maguire@googlemail.com
 * Website: http://spl1nes.com
 *
 * Version: 2.4
 */

class BattleNetParser {
	private $URL;
	private $UserURL;
	private $GlobalURL;
	private $MainSource;
	private $LeagueOne;
	private $History;
	private $User;

	public function __construct( $ProfileURL) { 
			$this->URL = $ProfileURL;
		if( strpos( $ProfileURL, "http://sea" ) === false ) {
			$this->UserURL = substr( $this->URL, 20 );
			$this->GlobalURL = substr( $this->URL, 0, 20 );
		} else {
			$this->UserURL = substr( $this->URL, 21 );
			$this->GlobalURL = substr( $this->URL, 0, 21 );
		}
		
		$this->MainSource = preg_replace( "/\r|\n/s", "", $this->get_url( $this->URL ) );
		$this->User = $this->string_delim( $this->string_delim( $this->MainSource, "<a href=\"".$this->UserURL, "</li>", 0 ), "\">", "</a>", 0 );
	}
	
	// Buffering
	private function buffer1() {
		$Link1 = $this->get_1v1_league_link();	
		if( strpos( $Link1, '#' ) !== false ) {
			$this->LeagueOne = preg_replace( "/\r|\n/s", "", substr( $this->get_url( $this->URL.$Link1 ), 5000, -10000 ) );
		} else {
			$this->LeagueOne = -1;
		}
	}

	// Looking for text between two offsets
	private function string_delim( $MyString, $FirstString, $SecondString, $c ) {
		$FirstPosition = 0;
		
		for( $i = 0; $i <= $c; $i++ ) {
			$FirstPosition = strpos( $MyString, $FirstString, $FirstPosition+1 );
		}
		
		if( $FirstPosition === false ) {
			return -1;
		} else {
			$SecondPosition = strpos( $MyString, $SecondString, $FirstPosition+strlen( $FirstString ) );
			
			if( $SecondPosition === false ) {
				return -1;
			} else {
				return trim( substr( $MyString, $FirstPosition+strlen( $FirstString ), $SecondPosition-$FirstPosition-strlen( $FirstString ) ) );
			}
		}
	}
	
	// Cheking availability
	public function is_available() {
		if( $this->User && $this->User != "" && $this->User != -1 ) {
			return true;
		} else {
			return false;
		}
	}
	
	// Loeading url ( allow_url_fopen )
	private function get_url( $myURL ) {
		$File = fopen( $myURL, "r" );
		$Content = "";
		
		if( $File === false )
			return -1;

		while( !feof( $File ) ) {
			$Content .= fgets( $File, 4096 );
		}
		
		fclose( $File );
		
		return preg_replace( "/\r|\n/s", "", $Content );
	}
	
	// Creating positive text part
	private function get_ext( $MyText, $FirstString, $c ) {
		$FirstPosition = strpos( $MyText, $FirstString );
		
		if( $FirstPosition === false ) {
			return -1;
		} else {
			return substr( $MyText, $FirstPosition+strlen( $FirstString ), $c );
		}
	}
	
	// Creating negative text part
	private function get_ext_neg( $MyText, $FirstString, $c ) {
		$FirstPosition = strpos( $MyText, $FirstString );
		
		if( $FirstPosition === false ) {
			return -1;
		} else {
			return substr( $MyText, $FirstPosition-$c, $c );
		}
	}
	
	public function get_user() {
		return $this->User;
	}
	
	public function get_race() {
		return $this->string_delim( $this->MainSource, "<a href=\"ladder/\" class=\"race-", "\">", 0 );
	}
	
	public function get_achievements() {
		return $this->string_delim( $this->MainSource, "<h3>", "</h3>", 0 );
	}
	
	public function get_overall_league_wins() {
		return $this->string_delim( $this->MainSource, "</h4>\t\t\t\t\t\t\t<h2>", "</h2>", 0 );
	}
	
	public function get_custom_games() {
		return $this->string_delim( $this->MainSource, "<li>\t\t\t\t\t\t\t\t\t<span>", "</span>", 0 );
	}
		
	// Returns 
	// ResultSet[0]: 0 = nothing; bronze; silver; gold; platinum; diamond; master; grandmaster
	// ResultSet[1]: 1 = top 100%; 2 = top 50%; 3 = top 25%; 4 = top 8%;
	public function get_1v1_league() {
		$ResultSet = array();
		
		$temp = $this->get_ext_neg( $this->MainSource, "<div id=\"best-team-1\"", 110 );
	
		$ResultSet[0] = $this->string_delim( $temp, "<span class=\"badge badge-", " badge-", 0 );
		$ResultSet[1] = $this->string_delim( $temp, "<span class=\"badge badge-".$ResultSet[0]." badge-medium-", "\">", 0 );

		return  $ResultSet;
	}
	
	public function get_1v1_games() {
		$source = $this->get_ext( $this->MainSource, "<div class=\"division\">1v1</div>", 300 );
		$temp = $this->string_delim( $source, "<span class=\"totals\">", "</span>", 0 );
		
		if( trim( $temp ) != "" && $temp != -1 ) {
		
			if( strpos( $temp, ".") !== false ) {
				$pieces = explode(".", $temp);
				$temp = $pieces[0].$pieces[1];
			}
			
			preg_match( "/\d+/", $temp, $ret );
			
			if( $ret[0] == "" ) {
				return "0";
			} else {
				return $ret[0];
			}
		} else {
			return "0";
		}
	}
	
	public function get_1v1_wins() {
		$source = $this->get_ext( $this->MainSource, "<div class=\"division\">1v1</div>", 700);
		$temp = $this->string_delim( $source, "<span class=\"totals\">", "</span>", 1 );
		
		if( trim( $temp ) != "" && $temp != -1 ) {
			if( strpos( $temp, ".") !== false ) {
				$pieces = explode(".", $temp);
				$temp = $pieces[0].$pieces[1];
			}
			
			preg_match( "/\d+/", $temp, $ret );
			
			if( $ret[0] == "" ) {
				return "0";
			} else {
				return $ret[0];
			}
		} else {
			return "0";
		}
	}
	
	// Returns
	// Url - X-Coordinate - Y-Coordinate
	// Imagesize 90px
	public function get_user_pic() {
		$ResultSet = array();
	
		$temp = $this->string_delim( $this->MainSource, "<span class=\"icon-frame \"\t\tstyle=\"background: url('", "\">", 0 );
		$Offset = strpos( $temp, "')");
		
		$ResultSet[0] = $this->GlobalURL.substr( $temp, 0, $Offset );
		
		$temp = substr( $temp, $Offset+strlen( $Offset )+1, 13 );
		$Delimed = explode( " ", $temp );
		
		if( trim( $temp ) != "" && $temp != -1 ) {
			preg_match( "/\d+/", $Delimed[0], $ret1 );
			preg_match( "/\d+/", $Delimed[1], $ret2 );
			
			if( $ret1[0] == "" || $ret2[0] == "" ) {
				$ResultSet[1] = -1;
				$ResultSet[2] = -1;
			} else {
				$ResultSet[1] = $ret1[0];
				$ResultSet[2] = $ret2[0];
			}
		} else {
			$ResultSet[1] = -1;
			$ResultSet[2] = -1;
		}
		
		return $ResultSet;
	}

	private function get_1v1_league_link() {
		return $this->string_delim( $this->MainSource, "1\">\t\t\t<a href=\"".$this->UserURL, "\">", 0 );
	}
	
	public function get_1v1_bonuspool() {
			if( !$this->LeagueOne || $this->LeagueOne == "" ) {
			$this->buffer1();
		}
	
		$temp = $this->string_delim( $this->get_ext( $this->LeagueOne, "<span id=\"bonus-pool\">", 100 ), "<span>", "</span>", 0 );
		
		if( $temp != "" && $temp != -1 ) {
			return $temp;
		} else {
			return "0";
		}
	}
	
	public function get_1v1_rank() {
		$source = $this->get_ext( $this->MainSource, "<div id=\"best-team-1\" style=\"display: none\">", 300);
		$temp = $this->string_delim( $source, "</strong>", "</div>", 1 );

		if( $temp != "" && $temp != -1 ) {
			preg_match( "/\d+/", $temp, $ret );
			return $ret[0];
		} else {
			return "0";
		}
	}
	
	public function get_1v1_league_points() {
		if( !$this->LeagueOne || $this->LeagueOne == "" ) {
			$this->buffer1();
		}
	
		$temp = $this->string_delim( $this->get_ext( $this->LeagueOne, "<div class=\"tooltip-title\">".$this->User."</div>", 350 ), "<td class=\"align-center\">", "</td>", 0 );
		if( $temp != "" && $temp != -1 ) {
			preg_match( "/\d+/", $temp, $ret );
			return $ret[0];
		} else {
			return "0";
		}
		
		return $temp;
	}

	// Returns
	// Map - Result - Points - Date
	// filter = solo/twos/threes/fours/co_op/custom/ffa
	public function get_history( $filter ) {
		$history = array();
		$history[] = array();
		$history[0][0] = -1;
		
		if( !$this->History || $this->History == "" ) {
			$Link5 = "matches#filter=".$filter;
			$new_url = $this->URL.$Link5;
			$this->History = preg_replace( "/\r|\n/s", "", substr( $this->get_url( $new_url ), 5000, -10000 ) );
		}
								   
		if( strpos($this->History, "<tr class=\"match-row ".$filter."\">") === false || !$filter || $filter == "" ) {
			return $history;
		} else {
			$ToParse = $this->string_delim($this->History, "<table class=\"data-table\">", "<div id=\"footer\">", 0 );
			$i = 0;
			$temp = "0";
			
			do {
				$temp = $this->string_delim($ToParse, "<tr class=\"match-row ".$filter."\">", "</tr>", $i );
				if( $temp != -1 ) {
					$history[$i][0] = $this->string_delim($temp, "<td>", "</td>", 0 );
					$history[$i][1] = $this->string_delim($temp, "<span class=\"match-", "\">", 0 );
					if($history[$i][1] == "loss" && ($filter == "solo" || $filter == "twos" || $filter == "threes" || $filter == "fours" ) ) {
						$history[$i][2] = $this->string_delim($temp, "(<span class=\"text-red\">", "</span>)", 0 );
						if($history[$i][2] == -1 ) {
							$history[$i][2] = "0";
						}
					} else if( $history[$i][1] == "win" && ($filter == "solo" || $filter == "twos" || $filter == "threes" || $filter == "fours" ) ) {
						$history[$i][2] = $this->string_delim($temp, "(<span class=\"text-green\">", "</span>)", 0 );
						if($history[$i][2] == -1 ) {
							$history[$i][2] = "0";
						}
					} else {
						$history[$i][2] = "0";
					}
				
					$history[$i][3] = $this->string_delim($temp, "<td class=\"align-right\">\t\t\t\t\t\t\t\t\t", "\t\t\t\t\t\t\t\t</td>", 0 );
					$i++;
				}
			} while( $temp != -1 );
			
			return $history;
		}
	}
}
?>
