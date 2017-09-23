<?php
 include  "simple_html_dom.php";
 date_default_timezone_set("GMT");
$now   = date("D, d M Y H:i:s T");
$ASIN  = $url = $img = $title = $bio = $name = "";
 
$head = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$head .= '<rss version="2.0">';
$head .= '<channel>';
$head .= '<title>Amazon </title>';
$head .= '<link>http://www.amazon.com</link>';
$head .= '<description>Amazon RSS Feed</description>';
 
$url = "http://www.amazon.com/Best-Sellers-Kindle-Store/zgbs/digital-text/";
 $rssfeed='';
$text = file_get_html($url);
foreach ($text->find("div.zg_item_compact") as $class) {
  foreach ($class->find('strong.price') as $price) {
    if ($price->plaintext == "Free") {
            $rssfeed .= '<item>';
            foreach ($class->find("div.zg_title a") as $book) {				
              preg_match("/\/dp\/(.*)\/ref/", $book->href, $matches);                
              $ASIN  = trim($matches[1]);
              $url   = "http://www.amazon.com/dp/" . $ASIN . "/?tag=publisherapi-20";
              $img   = "http://images.amazon.com/images/P/" . $ASIN . ".01.LZZZZZZZ.jpg";
              $title = htmlentities(trim($book->plaintext));                
              $rssfeed .= '<title>' . $title . '</title>';
              $rssfeed .= '<link>' . $url . '</link>';
              $rssfeed .= '<guid isPermaLink="true">' . $url . '</guid>';
              $rssfeed .= '<description>';
            }
            foreach ($class->find("div.zg_byline a") as $author) {
                $bio  = "http://www.amazon.com" . $author->href . "/?tag=publisherapi-20";
                $name = htmlentities(trim($author->plaintext));
                $rssfeed .= 'By <a href="' . $authorURL . '">' . $name . '</a>';
            }
            $rssfeed .= '</description>';
            $rssfeed .= '<pubDate>' . $now . '</pubDate>';
            $rssfeed .= '</item>';
        }
    }
}
$footer  = '</channel></rss>';
$rssfeed = $head . $rssfeed . $footer;
$fh      = fopen("amazon.rss", "w");
fwrite($fh, $rssfeed);
fclose($fh);
?>
