<?php



  require_once ($_SERVER["DOCUMENT_ROOT"].'/youtube/google-api-php-client/src/Google_Client.php');
  require_once ($_SERVER["DOCUMENT_ROOT"].'/youtube/google-api-php-client/src/contrib/Google_YouTubeService.php');

  
  //My Key
  $DEVELOPER_KEY = 'AIzaSyAgsSNG7WFMxS6VZzF57z7gwJ-4x8TIzhQ';
  
  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);
  $_GET['maxResults'] = 50;
  $youtube = new Google_YoutubeService($client);

  // printme($youtube->activities);

  // try{


    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => '',
      'maxResults' => $_GET['maxResults'],
      'regionCode' => 'BR'
      // 'pageToken' => 'CAoQAA'
    ));



   

    while($searchResponse['nextPageToken'])
    {

        $response = $searchResponse['items'];
        
        processResponse($response);

        $nextPageToken = $searchResponse['nextPageToken'];
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
        'q' => '',
        'maxResults' => $_GET['maxResults'],
        'regionCode' => 'BR',
        'pageToken' => $nextPageToken
        // 'pageToken' => 'CAoQAA'
      ));
        
    }

    exit();




   
   

  

  //  } catch (Google_ServiceException $e) {
  //   echo $e->getMessage();
  // } catch (Google_Exception $e) {
  //   echo $e->getMessage();
  // }

function processResponse($searchResponse)
{
  foreach ($searchResponse as $searchResult) {

      if(!isset($searchResult['id']['videoId']))
      continue;

      //ADD CHANNEL
      $channel_mysql = array();
      $channel_mysql['channel_id'] = $searchResult['snippet']['channelId'];
      $channel_mysql['title'] = $searchResult['snippet']['channelTitle'];
      $channel_mysql['description'] = $searchResult['snippet']['description'];
      $channel_mysql['thumbnail'] = $searchResult['snippet']['thumbnails']['medium']['url'];
      $channel_mysql['keyword'] = 'brazil';
      saveChannel($channel_mysql);

      //ADD CHANNEL STATS
      $channel_stats = getChannelStats($searchResult['snippet']['channelId']);
      $channel_stats_mysql = array();
      $channel_stats_mysql['channel_id'] = $channel_mysql['channel_id'];
      $channel_stats_mysql['viewCount'] = $channel_stats->items[0]->statistics->viewCount;
      $channel_stats_mysql['commentCount'] = $channel_stats->items[0]->statistics->commentCount;
      $channel_stats_mysql['subscriberCount'] = $channel_stats->items[0]->statistics->subscriberCount;
      $channel_stats_mysql['videoCount'] = $channel_stats->items[0]->statistics->videoCount;
      saveChannelStats($channel_stats_mysql);
      

      //GET CHANNEL VIDEOS
      $channel_videos = getChannelVideos($channel_mysql['channel_id']);

      foreach ($channel_videos as $video) {
        $video = getVideo($video);

        //SAVE VIDEO 
        $video_mysql = array();
        $video_mysql['video_id'] = $video->items[0]->id;
        $video_mysql['channel_id'] = $channel_mysql['channel_id'];
        $video_mysql['title'] = $video->items[0]->snippet->title;
        $video_mysql['description'] = $video->items[0]->snippet->description;
        $video_mysql['published_at'] = $video->items[0]->snippet->publishedAt;
        $video_mysql['thumbnail'] = $video->items[0]->snippet->thumbnails->medium->url;
        $video_mysql['category'] = $video->items[0]->snippet->categoryId;
        saveVideo($video_mysql);

        //SAVE VIDEO STATS
        $video_stats_mysql = array();
        $video_stats_mysql['video_id'] = $video->items[0]->id;
        $video_stats_mysql['viewCount'] = $video->items[0]->statistics->viewCount;
        $video_stats_mysql['likeCount'] = $video->items[0]->statistics->likeCount;
        $video_stats_mysql['dislikeCount'] = $video->items[0]->statistics->dislikeCount;
        $video_stats_mysql['favoriteCount'] = $video->items[0]->statistics->favoriteCount;
        $video_stats_mysql['commentCount'] = $video->items[0]->statistics->commentCount;
        saveVideoStats($video_stats_mysql);

        

        $video_tags = $video->items[0]->snippet->tags;
        foreach ($video_tags as $tag) {
          saveTag($tag,$video_mysql['video_id'],$channel_mysql['channel_id']);
        }
      }

      
    }
}



function saveTag($tag,$video_id,$channel_id)
{
  
  // printme($tag);
  // printme($video_id);
  // exit();

  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "youtube";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 

  $sql = "SELECT * FROM tag where tag = '".$tag."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      // UPDATE AVAILABLE TAG
      while($row = $result->fetch_assoc()) 
      {
          $id = $row['id'];
          $count = $row['count'];
          $count++;
          $sql = "UPDATE tag SET count= ".$count." WHERE id=".$id;
      }
  } 
  else 
  {
      // ADD NEW TAG
      $sql = "INSERT INTO tag (tag, count)
        VALUES ('".$tag."',1)";
  }

  $conn->query($sql);
  if(isset($id))
    $last_id = $id;
  else
    $last_id = $conn->insert_id;



  $sql = "INSERT INTO video_tag (video_id, tag_id,channel_id)
        VALUES ('".$video_id."',".$last_id.",'".$channel_id."')";

  $conn->query($sql);
  $conn->close();
  

}





  function printme($x)
{
  echo '<pre>'.print_r($x,true).'</pre';
}

function getChannelStats($channel_id)
{
  //https://www.googleapis.com/youtube/v3/channels?part=statistics&id=channel_id&key=your_key
  $json = json_decode( file_get_contents("https://www.googleapis.com/youtube/v3/channels?part=statistics&id=".$channel_id."&key=AIzaSyAgsSNG7WFMxS6VZzF57z7gwJ-4x8TIzhQ") );
  
  return $json;
}

function getVideo($video)
{ 
      
      $json = json_decode( file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$video."&key=AIzaSyAgsSNG7WFMxS6VZzF57z7gwJ-4x8TIzhQ&part=snippet,contentDetails,statistics,status") );
      return $json;
      // $ch = curl_init();
      // $url =  'https://www.youtube.com/watch?v='.$video;
      // $youtube = "http://www.youtube.com/oembed?url=" . $url. "&format=json";
      // $curl = curl_init($youtube);
      // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      // $return = curl_exec($curl);
      // curl_close($curl);
      // $result = json_decode($return, true);
      // return $result;
}


function getChannelVideos($channel_id)
{
  $baseUrl = 'https://www.googleapis.com/youtube/v3/';
  // https://developers.google.com/youtube/v3/getting-started
  $apiKey = 'AIzaSyAgsSNG7WFMxS6VZzF57z7gwJ-4x8TIzhQ';
  // If you don't know the channel ID see below
  $channelId = $channel_id;
   
  $params = [
      'id'=> $channelId,
      'part'=> 'contentDetails',
      'key'=> $apiKey
  ];
  $url = $baseUrl . 'channels?' . http_build_query($params);
  $json = json_decode(file_get_contents($url), true);
   
  $playlist = $json['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
   
  $params = [
      'part'=> 'snippet',
      'playlistId' => $playlist,
      'maxResults'=> '5',
      'key'=> $apiKey
  ];
  $url = $baseUrl . 'playlistItems?' . http_build_query($params);
  // printme($url);
  // exit();
  $json = json_decode(file_get_contents($url), true);
   
  $videos = [];
  foreach($json['items'] as $video)
      $videos[] = $video['snippet']['resourceId']['videoId'];
   
  // while(isset($json['nextPageToken'])){
  //     $nextUrl = $url . '&pageToken=' . $json['nextPageToken'];
  //     $json = json_decode(file_get_contents($nextUrl), true);
  //     foreach($json['items'] as $video)
  //         $videos[] = $video['snippet']['resourceId']['videoId'];
  // }
  return $videos;
}


function saveChannel($data)
{
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "youtube";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM channel where channel_id = '".$data['channel_id']."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      return;
  } 

  $sql = "INSERT INTO channel (channel_id, title, description,thumbnail,keyword)
    VALUES ('".$data['channel_id']."',
            '".$data['title']."', 
            '".$data['description']."',
            '".$data['thumbnail']."',
            '".$data['keyword']."' )";


  if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}


function saveChannelStats($data)
{
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "youtube";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 


  $sql = "SELECT * FROM channel_stats where channel_id = '".$data['channel_id']."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      return;
  } 

  $sql = "INSERT INTO channel_stats (channel_id, viewCount, commentCount,subscriberCount,videoCount)
    VALUES ('".$data['channel_id']."',
            '".$data['viewCount']."', 
            '".$data['commentCount']."',
            '".$data['subscriberCount']."',
            '".$data['videoCount']."' )";


  if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}


function saveVideoStats($data)
{
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "youtube";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 


  $sql = "SELECT * FROM video_stats where video_id = '".$data['video_id']."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      return;
  } 
  
  $sql = "INSERT INTO video_stats (video_id, viewCount, likeCount,dislikeCount,favoriteCount,commentCount)
    VALUES ('".$data['video_id']."',
            '".$data['viewCount']."', 
            '".$data['likeCount']."',
            '".$data['dislikeCount']."',
            '".$data['favoriteCount']."',
            '".$data['commentCount']."')";


  if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}

function saveVideo($data)
{ 

  
  $mails = saveMails($data['description'],$data['channel_id']);
  $urls  = saveUrls($data['description'],$data['channel_id']);

  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "youtube";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 


  $sql = "SELECT * FROM video where video_id = '".$data['video_id']."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      return;
  } 
  
  $sql = "INSERT INTO video (video_id, channel_id, title,description,published_at,thumbnail,category)
    VALUES ('".$data['video_id']."',
            '".$data['channel_id']."', 
            '".$data['title']."',
            '".$data['description']."',
            '".$data['published_at']."',
            '".$data['thumbnail']."',
            '".$data['category']."' )";


  if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();

  
}




function saveMails($string,$channel_id)
{   
    $mails = array();
    $pattern = '/[A-Za-z0-9_-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/';
    preg_match_all($pattern, $string, $matches);
    $matches = $matches[0];
    if(is_array($matches))
    {
        foreach ($matches as $match) {
            $mails[] = $match;
        }
    }

    if(!empty($mails))
    {
      foreach ($mails as $mail) {
        

        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "youtube";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "SELECT * FROM channel_mail where channel_id = '".$channel_id."' and mail='".$mail."' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            continue;
        } 

         $sql = "INSERT INTO channel_mail (channel_id, mail)
          VALUES ('".$channel_id."',
                  '".$mail."'
                 )";


        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


      }
    }
    
}


function saveUrls($string,$channel_id)
{   
    $urls = array();
    $pattern = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
    preg_match_all($pattern, $string, $matches);
    $matches = $matches[0];
    if(is_array($matches))
    {
        foreach ($matches as $match) {
            $urls[] = $match;
        }
    }

    if(!empty($urls))
    {
      foreach ($urls as $url) {
        

        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "youtube";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "SELECT * FROM channel_url where channel_id = '".$channel_id."' and url='".$url."' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            continue;
        } 

         $sql = "INSERT INTO channel_url (channel_id, url)
          VALUES ('".$channel_id."',
                  '".$url."'
                 )";


        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


      }
    }
}
?>
