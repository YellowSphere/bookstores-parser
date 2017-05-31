                                                                                                              <?php

include('simple_html_dom.php'); /* Needed for file_get_html */




function get_title($subdiv) {
   
  $title = NULL; 

  foreach ($subdiv->find('div') as $div) {
    if ($div->class == "a-section a-spacing-mini") {

      foreach ($div->find('img') as $img) {
        $title = $img->alt;  
      }
    }
   
  }

  if ($title == NULL) {
      return $subdiv->innertext;
  }

  return $title;
}






function get_rating($div) {
   
  $rating = NULL; 

  foreach ($div->find('div') as $subdiv) {
    if ($subdiv->class == "a-icon-row a-spacing-none") {

      foreach ($subdiv->find('a') as $a) {
        if ($a->class == "a-link-normal") {
          $rating = $a->title;  
        }
      }
             
    }
  }
  
  if ($rating == NULL) {
    return "5 out of 5 stars";
  }
    
  return $rating;
}



function parse_bestsellers($html) {

  $fp = fopen('csv/amazon.csv', 'w+');

  foreach ($html->find('div') as $div) {
    if ($div->class == "a-section a-spacing-none p13n-asin") {

      $url = NULL;
      foreach ($div->find('a') as $subdiv) {
        if ($subdiv->class == "a-link-normal") {
          $url = $subdiv->href;
                  
        }
      }

      $price = NULL;
      foreach ($div->find('span') as $span) {
        if ($span->class == "p13n-sc-price") {
          $price = $span->plaintext;
          break;

        } 

      }
     
      if ($price == NULL) {
        $price = "$0.00 with trial";
      }

      $title = get_title($div);
      $rating = get_rating($div);
      $new_url = "https://www.amazon.com/" . $url;
      
      $data = array($title, $price, $rating, $new_url);
      
      fputcsv($fp, $data);

      print("Book:\n");
      print("  Title: " . $title . "\n");
      print("  Price: " . $price . "\n");
      print("  Rating: " . $rating . "\n");
      print("  URL: " . $new_url . "\n\n");
    }
  }
  fclose($fp);
}

$html = file_get_html("http://amzn.to/2rrg2bR"); 

parse_bestsellers($html);

?>
