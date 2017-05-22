                                                                                                              <?php

setlocale(LC_ALL, 'russia');

require_once('simple_html_dom.php'); /* Needed for file_get_html */

function get_rating($div) {
  foreach ($div->find('div') as $subdiv) {
    $matches = NULL;
    preg_match('/bStars inline m([0-9]+).*/', $subdiv->class, $matches);
    if ($matches) {
      return (int) $matches[1];
    }
  }
}

function parse_bestsellers($html) {
  foreach ($html->find('div') as $div) {
    if ($div->class == "bOneTile inline jsUpdateLink mRuble") {
      $subdivs = $div->find('div');
      $title = NULL;
      foreach ($subdivs as $subdiv) {
        if ($subdiv->class == "eOneTile_ItemName") {
          $title = $subdiv->plaintext;
        }
      }

      $price = NULL;
      foreach ($div->find('span') as $span) {
        if ($span->class == "eOzonPrice_main") {
          $price = $span->innertext;
          break;
        }
      }

      $rating = get_rating($div);

      print("Book:\n");
      print("  Title: " . $title . "\n");
      print("  Price: " . $price . "\n");
      print("  Rating: ");
      for ($idx = 0; $idx < $rating; ++$idx) {
        print('*');
      }
      print("\n");
      print("  URL:    " . 'https://www.ozon.ru/' . $div->attr['data-href'] . "\n");

    }
  }
}

$html = file_get_html("https://www.ozon.ru/context/best_books/");


parse_bestsellers($html);
?>
