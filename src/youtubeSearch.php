<?php

require_once __DIR__ . '/../lib/vendor/autoload.php';

define("API_KEY", "AIzaSyBcQHyxgAiHlu7qpSzGtbHoin-yWDGBj1A");


if (!empty($_GET['keyword'])) {
  $key = isset($_GET['keyword']) ? $_GET['keyword'] : null;
  //認証
  $client = new Google_Client();
  $client->setApplicationName("My Application");
  $client->setDeveloperKey(API_KEY);

  $youtube = new Google_Service_YouTube($client);



  $keyword = $key;
  $params['q'] = $keyword;
  $params['type'] = 'video';
  $params['maxResults'] = 10;


  $videos = [];

  try {
    $searchRes = $youtube->search->listSearch('snippet', $params);

    // $ss = function ($sr) use (&$videos) {
    //   return $videos[] = $sr;
    // };
    // array_map($ss, $searchRes['items']);

    array_map(
      function ($searchRes) use (&$videos) {
        $videos[] = $searchRes;
      },
      $searchRes['items']
    );
  } catch (Google_Service_Exception $e) {
    echo htmlspecialchars($e->getMessage());
    exit;
  } catch (Google_Exception $e) {
    echo htmlspecialchars($e->getMessage());
    exit;
  }
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <style type="text/css">
    .mg {
      margin: 20px 30px;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>サーチ</title>
</head>

<body>

  <div class="mg">
    <form action="" method="get">
      <input type="text" name="keyword">
      <input type="submit" value="検索">
    </form>
  </div>



  <?php
  if (!empty($videos)) {
    echo '<table>';
    foreach ($videos as $v) {
      echo '<tr>';
      echo '<td><img src="' . $v['snippet']['thumbnails']['default']['url'] . '" /></td>';
      // echo '<td>title</td>';
      echo '<td>' . $v['snippet']['title'] . '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }
  ?>



</body>

</html>