<?php 
 namespace Anagram
 {
   class Program 
   {
     public function main()
   {
	   try 
	   {
		  $file = 'test.txt';
		  $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		  foreach ($lines as $line)
		    $this->cluster($line);
	   }
	   catch(Exception $e){
	     echo "Couldn't read the text file";
		 echo "Caught exception: ", $e->getMessage(),"\n";
	   }
	 
	 }
	 
	 public function cluster($line)
	 {
	  $regexs = array();   //-To store the set of unique regular expressions
	  $a_group = array();  //-To store the set of anagrams 
      
	  echo "Initial input: ".$line."<br>";
	  $words = explode(",", $line);
	  usort($words, array('Anagram\Program','cmp')); //we sort by the input array by increasing string lenght to facilitate the search
      
	  //- Initial values of the regex set and the anagram set
	  $pattern = $words[0];  
      $regexs[$pattern] = $this -> regExp($pattern); 
	  $a_group[$pattern] =  $pattern;
	  
	    for ($i = 1; $i<count($words); $i++) {
		
            $found = 0;
			
			foreach ($regexs as $key =>$regex){	
			    
				  //Here, I prefered to use regular expression for linearity purposes in the complexity
				  
				  //We simply look through the existing set of regular expressions to see if the current value match one of those. If yes, we add it to the corresponding anagram set
				  if (preg_match($regex, $words[$i]) && (strlen($key) == strlen($words[$i])))
				        { $a_group[$key] .= ", ".$words[$i]; $found = 1; }
			}	
			
			 if($found == 0) { //If no match with existing set of regular expressions (thus with the existing set of anagrams), then add the current value's regex to the set of regexs and to the set of anagrams
			 	 $pattern = $words[$i];
			     $tmp = $this -> regExp($words[$i]); 
				 if(!in_array($tmp, $regexs)) $regexs[$pattern] = $tmp;
				 $a_group[$pattern] =  $pattern;
			 }

	    }		  
		//Display the results
	    foreach ($a_group as $k => $anagram_set)
		  echo "<u>Anagrams of ".$k."</u>: ".$anagram_set."<br>";
		echo"<br>";
	 }
	 
	 static function cmp($a, $b){
	   return strlen($b)- strlen($a);
	 }
	 
	 static function regExp($string){
	  $regchars = preg_split('//',$string, -1, PREG_SPLIT_NO_EMPTY); //we split the pattern to find its corresponding regex. N.B: differnt variations of parameters can be applied to preg_split
	  $regex = "/";
	  foreach($regchars as $char) $regex .= "(?=.*".$char.")";
	   return $regex."/";
	 }
   }
 }

?>
