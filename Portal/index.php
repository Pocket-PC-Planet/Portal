<!--
_____________________________   __________.____                          __
\______   \______   \_   ___ \  \______   \    |   _____    ____   _____/  |_
 |     ___/|     ___/    \  \/   |     ___/    |   \__  \  /    \_/ __ \   __\
 |    |    |    |   \     \____  |    |   |    |___ / __ \|   |  \  ___/|  |
 |____|    |____|    \______  /  |____|   |_______ (____  /___|  /\___  >__|
                            \/                    \/    \/     \/     \/
Copyright (c) Pocket PC Planet 2021. Do not copy or replicate this site.
-->
<?php
include('mysqlconnect.php');
include('head.html');

//get search terms from URL
$searchTxt = isset($_GET['q']) ? $_GET['q'] : '';

if ($searchTxt != '') {
  $search_string = "SELECT * FROM `posts` WHERE";
  $display_words = "";

  //format the keywords for the query
  $keywords = explode(',', $searchTxt);
  foreach ($keywords as $word) {
    $search_string .= "`name` LIKE '%" . $word . "%' OR `content` LIKE '%" . $word . "%' OR `category` LIKE '%" . $word . "%' OR `type` LIKE '%" . $word . "%' OR ";
    $display_words .= $word . ' ';
  }

  $search_string = substr($search_string, 0, strlen($search_string) - 4);
  $display_words = substr($display_words, 0, strlen($display_words) - 1);

  //connect to db
  try {
    $conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    $query = mysqli_query($conn, $search_string);
    $result_count = mysqli_num_rows($query);

    //display keywords
    echo '<div class="right"><b><u>' . number_format($result_count) . '</u></b> result(s) found in the PPC Planet archive</div>';
    echo 'Your search for <i>"' . $display_words . '"</i>';

    if ($result_count > 0) {
      //if items are found in the PPC Planet archive...
      echo "<h3>PPC Planet Archive Results:</h3>";
      //loop through results
      while ($row = mysqli_fetch_assoc($query)) {
        //echo name, description, and category. link to archive listing.
        echo "<hr><div class='result'><h2><a href='https://archive.ppcplanet.org/?q=" . $row["name"] . "' target='_blank'>" . $row["name"] . "</a></h2><p>" . $row["content"] . "</p><p>Category:&nbsp;<i>" . $row['category'] . "</i></p></div>";
      }
    }

    //if query isn't blank, list other sources as well...
    if ($searchTxt != "") {
      echo "<hr><h3>Other Sources:</h3><hr><div class='result'><h2><a href='https://archive.org/search.php?query=" . $searchTxt . "' target='_blank'>Search the Internet Archive for '" . $searchTxt . "'</a></h2><p>The Internet Archive is a great place to find old software for Pocket PCs. In fact, PPC Planet actually uses it to host the files in its archive!</p></div><hr><div class='result'><h2><a href='https://web.archive.org/web/*/" . $searchTxt . "' target='_blank'>Search the WayBack Machine for '" . $searchTxt . "'</a></h2><p>This is the website portion of the Internet Archive. If you are looking for an old file or site, you might be able to find it here.</p></div><hr><div class='result'><h2><a href='https://google.com/search?q=site:oldhandhelds.com " . $searchTxt . "' target='_blank'>Search OldHandhelds for '" . $searchTxt . "'</a></h2><p>OldHandhelds hosts tons of software for Pocket PCs and similar devices.</p></div><hr><div class='result'><h2><a href='https://google.com/search?q=site:mariomasta64.me " . $searchTxt . "' target='_blank'>Search MarioMasta64's archive for '" . $searchTxt . "'</a></h2><p>Another place that hosts Pocket PC software.</p></div><hr><div class='result'><h2><a href='https://google.com/search?q=\"" . $searchTxt . "\"' target='_blank'>Search Google for '" . $searchTxt . "'</a></h2><p>If all else has failed, perhaps Google can help you find the software you are looking for.</p></div><hr><br><p style='text-align: center'>&copy; PPC Planet Team 2021</p><br>";
    }
  } catch (PDOException $exception) {
    //if there is an error with the connection, stop the script and display the error
    exit('Failed to connect to database!');
  }
}
?>
