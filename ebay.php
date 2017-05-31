                                                                                                              <?php

include('simple_html_dom.php'); /* Needed for file_get_html */

function get_rating($li) {
   
  $count = NULL; 

  foreach ($li->find('i') as $subdiv) {
    if ($subdiv->class == "star-ratings__star star-ratings__star--full") {
       $count++;
    }
  }

  if ($count == NULL) {
    return "No rating";
  }

  return $count;
}


function parse_bestsellers($html) {

  $fp = fopen('csv/ebay.csv', 'w+');

  foreach ($html->find('li') as $li) {
    if ($li->class == "sresult lvresult clearfix li shic") {
      $subdivs = $li->find('a');
      $title = NULL;

      foreach ($subdivs as $subdiv) {
        if ($subdiv->class == "vip") {
          $title = $subdiv->plaintext;

        }
      }

      $price = NULL;
      foreach ($li->find('span') as $span) {
        if ($span->class == "bold") {
          $price = $span->plaintext;
          break;
        }
      }
        
      $url = NULL;
      foreach ($li->find('a') as $a) {
        $url = $a->href;
      }

      $rating = get_rating($li);

      $data = array($title, $price, $rating, $new_url);
      
      fputcsv($fp, $data);

      print("Book:\n");
      print("  Title: " . $title . "\n");
      print("  Price: " . $price . "\n");
      print("  Rating: " . $rating . "\n");
      print("  URL: " . $url . "\n");

    }
  }
  fclose($fp);
}

$html = file_get_html("http://ebay.to/2pZRgjt"); 

parse_bestsellers($html);

?>
