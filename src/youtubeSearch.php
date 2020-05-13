<?php

require_once __DIR__ . '/../lib/vendor/autoload.php';

define("API_KEY", "AIzaSyBcQHyxgAiHlu7qpSzGtbHoin-yWDGBj1A");

//認証
$client = new Google_Client();
$client->setApplicationName("My Application");
$client->setDeveloperKey(API_KEY);

$youtube = new Google_Service_YouTube($client);


$keyword = "マコなり";
$params['q'] = $keyword;
$params['type'] = 'video';
$params['maxResults'] = 2;


$videos = [];
try {

  $searchRes = $youtube->search->listSearch('snippet', $params);

  array_map(function ($searchRes) use (&$videos) {
      $videos[] = $searchRes;
    }, $searchRes['items']
  );

  // $i = 0;
  // foreach($searchRes['items'] as $v){
  //   $videos[$i] = $v[$i];
  //   $i++;
  // }

  // echo '<pre>';
  // var_dump($videos);
  // echo '</pre>';
  // exit;


} catch (Google_Service_Exception $e) {
  echo htmlspecialchars($e->getMessage());
  exit;
} catch (Google_Exception $e) {
  echo htmlspecialchars($e->getMessage());
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>サーチ</title>
</head>

<body>
  <table>
    <?php
    if ($videos) {
      foreach ($videos as $v) {
        echo '<tr>';
        echo '<td><img src="'. $v['snippet']['thumbnails']['default']['url'] .'" /></td>';
        // echo '<td>title</td>';
        echo '<td>' . $v['snippet']['title'] . '</td>';
        echo '</tr>';
      }
    }
    ?>


  </table>

</body>

</html>