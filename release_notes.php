<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8"/>
<?php
   $release_tag = $_REQUEST['tag']; // e.g. '20120709';
?>

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
        padding-top: 18px;
      font-size: 1.5em;
    }

    h2 {
        font-size: 1.5em;
    }

.label {
  background-color: #588A00;
    color: white;
 padding: 4px;
  border-radius: 5px;
  margin-right: 4px;
}

.description {
    background: #EEE;
    padding: 10px;
    border: 1px solid #AAA;
	}
</style>
</head>
<body>
<h2>Release Notes for the week of <?php echo date('Y-m-d'); ?></h2>

<?php

include('pivotaltracker_rest.php');

$pivotal = new PivotalTracker();
$pivotal->authenticate();

$total_points = 0;

$projects = $pivotal->projects_get(539573);

$finished_stories = $pivotal->stories_get_by_filter(539573, 'state:finished');
$next_week_stories = $pivotal->stories_get_by_filter(539573, 'label:new frontend ');
$live_stories = $pivotal->stories_get_by_filter(539573, 'label:' . $release_tag . ' includedone:true');
?>

<?php
    $staging_points = print_stories($live_stories);
    $total_points += $staging_points;
?>
<br/><br/>

<!-- <h2>Stories going live next week</h2>
<?php
    $test_points = print_stories($next_week_stories);
//    $total_points += $test_points;
?>
<br/><br/>


<h2>Stories marked 'finished' this week</h2>
<?php
    $finished_points = print_stories($finished_stories);
//    $total_points += $finished_points;
?>
<br/><br/> -->


<!--h2>Total points: <?php // echo $total_points; ?></h2-->

<?php


function print_stories($stories) {
    $points = 0;
    echo "<ul>";
    foreach ($stories AS $story) {
        if ($story['story_type'] == 'feature') {
            $points += $story['estimate'];
        }
	//	debug($story);
        // Story Name
        echo '<li class="story_name">';
        echo "<a href='{$story['url']}'>{$story['name']}</a>";
        echo '</li>';

        echo "<ul>";

        // Story requested by
        echo "<li>Requested by: " . $story['requested_by'] . "</li>";

        // Story labels
	if (!empty($story['labels'])) {
	  $labels = explode(',', $story['labels']);
	    echo "<li>";
	    foreach ($labels AS $label) {
	      echo "<span class='label'>$label</span>";
	    }
	    echo "<li>";
	  }

	// Description
	if (!empty($story['description'])) {
	  echo "<li>";
	  echo "<div class='description'>" . nl2br($story['description']) . '</div></li>';
	}
        echo "</ul>";
    }
    echo "<li><h2>Number of points: $points</h2></li>";
    echo "</ul>";

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
?>
</body>
</html>