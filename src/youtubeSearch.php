<?php

require_once __DIR__ . '/../lib/vendor/autoload.php';

define("API_KEY", "AIzaSyBcQHyxgAiHlu7qpSzGtbHoin-yWDGBj1A");


if (!empty($_GET['keyword'])) {
  $key = isset($_GET['keyword']) ? $_GET['keyword'] : null;
  //認証
  $client = new Google_Client();
  $client->setApplicationName("My Application");
  $client->setDeveloperKey(API_KEY);
  
  $result = serach_youtube($client, $key);
  
  $videos = '';
  if($result['res']){
    $videos = $result['body'];
  }else{
    $err = $result['body'];
  }
}



  function serach_youtube($client, $key) {
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
        },$searchRes['items']
      );
      return ['res'=>true,'body'=>$videos];
    
    } catch (Google_Service_Exception $e) {
      return ['res' => false,'body' => htmlspecialchars($e->getMessage())];
    } catch (Google_Exception $e) {
      return ['res' => false,'body' => htmlspecialchars($e->getMessage())];
    }
  }




?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>サーチ</title>
  <style type="text/css">
    .mg {
      margin: 20px 30px;
    }
  </style>
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
  }elseif(isset($err)){
    echo 'エラー理由：';
    echo $err;
  }
  ?>



</body>

</html>