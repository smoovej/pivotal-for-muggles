<head>
	<meta content='text/html; charset=utf-8' http-equiv='Content-Type' />	

	<style type="text/css">
	* {
	    font-family: helveticaneue;
	}
	    li {
	        padding: 5px;
	        list-style-type: none;
	    }

	    a {
	        text-decoration: none;
	    }

	    .story_name {
	        padding: 20px;
			font-size: 1.5em;
	    }
	
		.metadata {
			padding-left: 40px;
		}

	    h2 {
	        font-size: 1.5em;
	    }
	
		.description {
			background: #EEE;
			padding: 10px 10px 10px 40px;
			border: 1px solid #AAA;
		}
	</style>
</head>
<?php

include('pivotaltracker_rest.php');

$tags = array("cameron / priority high", "cameron / june", "cameron / 1a");

$pivotal = new PivotalTracker();
$pivotal->authenticate();

$total_points = 0;

$stories  = array();

foreach ($tags AS $tag) {
	$search = urlencode('label:"' . $tag . '"');
	$s = $pivotal->stories_get_by_filter(539573, $search);
	if (array_key_exists('id',$s)) {
		$stories[] = $s;
	} else {
		$stories = $stories + $s;
	}
}
// debug($stories);
?>



<h2>Cameron's Iceboxed Stories</h2>

<?php
    $total_points = print_stories($stories);
?>
<br/><br/>

<?php


function print_stories($stories) {
    $points = 0;

    foreach ($stories AS $story) {
        // Story Name
        echo '<div class="story_name">';
        echo "<a href='{$story['url']}'>{$story['name']}</a>";
        echo '</div>';
		echo "<br/>";
		
		// Cost
		if ($story['story_type'] == 'feature' && $story['estimate'] >= 0) {
            $points += $story['estimate'];
			//echo "<div>Cost: " . $story['estimate'] . "</div>";
        }

        // Story labels
        echo "<div class='metadata'>Labels: " . str_replace(',', ', ', $story['labels']) . "</div>";
		echo "<br/>";
        // Story requested by
        // echo "<div class='metadata'>Requested by: " . $story['requested_by'] . "</div>";
        // 		echo "<br/>";
		// Description
		echo "<blockquote class='description'>" . nl2br($story['description']) . "</blockquote>";
		echo "<br/><br/>";
    }
    //echo "<li>Total Number of points: $points</li>";


    return $points;
}

/**
 * Prints out debug information about given variable.
 *
 * Only runs if debug level is greater than zero.
 *
 * @param boolean $var Variable to show debug information for.
 * @param boolean $showHtml If set to true, the method prints the debug data in a screen-friendly way.
 * @param boolean $showFrom If set to true, the method prints from where the function was called.
 * @link http://book.cakephp.org/view/1190/Basic-Debugging
 * @link http://book.cakephp.org/view/1128/debug
 */
function debug($var = false, $showHtml = false, $showFrom = true) {
    if ($showFrom) {
        $calledFrom = debug_backtrace();
        echo '<strong>' . $calledFrom[0]['file'] . '</strong>';
        echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
    }
    echo "\n<pre class=\"cake-debug\">\n";

    $var = print_r($var, true);
    if ($showHtml) {
        $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
    }
    echo $var . "\n</pre>\n";
}