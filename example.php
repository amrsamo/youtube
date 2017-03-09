<?php

  
    $HTTP_HOST = $_SERVER['HTTP_HOST'];

    if($HTTP_HOST == 'localhost')
    {
        //Development
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "youtube";
    }
    else
    {
        //Production
        $servername = "localhost";
        $username = "root";
        $password = ".?R](%B=<NE,6'g";
        $dbname = "youtube";
    }


    $GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($GLOBALS['conn']->connect_error) {
        die("Connection failed: " . $GLOBALS['conn']->connect_error);
    } 


  require_once ($_SERVER["DOCUMENT_ROOT"].'/youtube/google-api-php-client/src/Google_Client.php');
  require_once ($_SERVER["DOCUMENT_ROOT"].'/youtube/google-api-php-client/src/contrib/Google_YouTubeService.php');

  
  //My Key
  $DEVELOPER_KEY = 'AIzaSyAgsSNG7WFMxS6VZzF57z7gwJ-4x8TIzhQ';
  
  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);
  $_GET['maxResults'] = 50;
  $youtube = new Google_YoutubeService($client);

  $hashtags = '#love #TagsForLikes #TagsForLikesApp #TFLers #tweegram #photooftheday #20likes #amazing #smile #follow4follow #like4like #look #instalike #igers #picoftheday #food #instadaily #instafollow #followme #girl #iphoneonly #instagood #bestoftheday #instacool #instago #all_shots #follow #webstagram #colorful #style #swag #fun #instagramers #TagsForLikes #TagsForLikesApp #food #smile #pretty #followme #nature #lol #dog #hair #onedirection #sunset #swag #throwbackthursday #instagood #beach #statigram #friends #hot #funny #blue #life #art #instahub #photo #cool #pink #bestoftheday #clouds #amazing #TagsForLikes #TagsForLikesApp #followme #all_shots #textgram #family #instago #igaddict #awesome #girls #instagood #my #bored #baby #music #red #green #water #harrystyles #bestoftheday #black #party #white #yum #flower #2012 #night #instalove #niallhoran #jj_forum #nature #TagsForLikes #TagsForLikesApp #sky #sun #summer #beach #beautiful #pretty #sunset #sunrise #blue #flowers #night #tree #twilight #clouds #beauty #light #cloudporn #photooftheday #love #green #skylovers #dusk #weather #day #red #iphonesia #mothernature #beach #sun #nature #water #TagsForLikes #TagsForLikesApp #TFLers #ocean #lake #instagood #photooftheday #beautiful #sky #clouds #cloudporn #fun #pretty #sand #reflection #amazing #beauty #beautiful #shore #waterfoam #seashore #waves #wave #sunset #sunrise #sun #TagsForLikes #TagsForLikesApp #TFLers #pretty #beautiful #red #orange #pink #sky #skyporn #cloudporn #nature #clouds #horizon #photooftheday #instagood #gorgeous #warm #view #night #morning #silhouette #instasky #all_sunsets #flowers #flower #TagsForLikes #petal #petals #nature #beautiful #love #pretty #plants #blossom #sopretty #spring #summer #flowerstagram #flowersofinstagram #flowerstyles_gf #flowerslovers #flowerporn #botanical #floral #florals #insta_pick_blossom #flowermagic #instablooms #bloom #blooms #botanical #floweroftheday #love #TagsForLikes #TagsForLikesApp #photooftheday #me #instamood #cute #igers #picoftheday #girl #guy #beautiful #fashion #instagramers #follow #smile #pretty #followme #friends #hair #swag #photo #life #funny #cool #hot #bored #portrait #baby #girls #iphonesia #selfie #selfienation #selfies #TagsForLikes #TFLers #TagsForLikesApp #me #love #pretty #handsome #instagood #instaselfie #selfietime #face #shamelessselefie #life #hair #portrait #igers #fun #followme #instalove #smile #igdaily #eyes #follow #girl #girls #love #TagsForLikes #TFLers #me #cute #picoftheday #beautiful #photooftheday #instagood #fun #smile #pretty #follow #followme #hair #friends #lady #swag #hot #cool #kik #fashion #igers #instagramers #style #sweet #eyes #beauty #guys #guy #boy #TagsForLikes #TFLers #boys #love #me #cute #handsome #picoftheday #photooftheday #instagood #fun #smile #dude #follow #followme #swag #hot #cool #kik #igers #instagramers #eyes #love #couple #cute #adorable #TagsForLikes #TagsForLikesApp #kiss #kisses #hugs #romance #forever #girlfriend #boyfriend #gf #bf #bff #together #photooftheday #happy #me #girl #boy #beautiful #instagood #instalove #loveher #lovehim #pretty #fun #smile #xoxo #friend #friends #fun #TagsForLikes #TagsForLikesApp #funny #love #instagood #igers #friendship #party #chill #happy #cute #photooftheday #live #forever #smile #bff #bf #gf #best #bestfriend #lovethem #bestfriends #goodfriends #besties #awesome #memories #goodtimes #goodtime #travel #traveling #TagsForLikes #TFLers #vacation #visiting #instatravel #instago #instagood #trip #holiday #photooftheday #fun #travelling #tourism #tourist #instapassport #instatraveling #mytravelgram #travelgram #travelingram #igtravel #cars #car #ride #drive #TagsForLikes #driver #sportscar #vehicle #vehicles #street #road #freeway #highway #sportscars #exotic #exoticcar #exoticcars #speed #tire #tires #spoiler #muffler #race #racing #wheel #wheels #rim #rims #engine #horsepower';
  
  $hashtags = explode('#',$hashtags);
  unset($hashtags[0]);
  foreach ($hashtags as $key => $value) {
     $hashtags[$key] = trim($value);
  }
  

  $index =file_get_contents("index.txt");
  $index = intval($index);
  

  if($index == count($hashtags))
  {
      $index = 1;
  }

  $new_index = $index+1;
  file_put_contents('index.txt',$new_index);


  $q = $hashtags[$index];

    //$pageToken = loadToken();

    
    // if($pageToken)
    // {
    //     $searchResponse = $youtube->search->listSearch('id,snippet', array(
    //       'q' => '',
    //       'maxResults' => $_GET['maxResults'],
    //       'regionCode' => 'BR',
    //       'pageToken' => $pageToken
    //     ));
    // }
    // else
    // {
    //     $searchResponse = $youtube->search->listSearch('id,snippet', array(
    //     'q' => '',
    //     'maxResults' => $_GET['maxResults'],
    //     'regionCode' => 'BR'
    //     // 'pageToken' => 'CAoQAA'
    //   ));
    // }
     
     $searchResponse = $youtube->search->listSearch('id,snippet', array(
          'q' => $q,
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'BR'
          // 'pageToken' => $pageToken
        ));


   
     // printme($searchResponse);exit();
    while($searchResponse['nextPageToken'])
    {

        $response = $searchResponse['items'];

        $nextPageToken = $searchResponse['nextPageToken'];
        // saveToken($nextPageToken);


        processResponse($response);
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
        'q' => '',
        'maxResults' => $_GET['maxResults'],
        'regionCode' => 'BR',
        'pageToken' => $nextPageToken
        // 'pageToken' => 'CAoQAA'
      ));
        
    }

    exit();



    function loadToken()
  {
    $sql = "SELECT * FROM token order by id desc limit 1";
    $result = $GLOBALS['conn']->query($sql);

    if ($result->num_rows > 0) 
    {
        // UPDATE AVAILABLE TAG
        while($row = $result->fetch_assoc()) 
        {
            return $row['token'];
        }
    }
    else
    {
      return false;
    }
  }

   function saveToken($nextPageToken)
   {
     $sql = "INSERT INTO token (token)
        VALUES ('".$nextPageToken."')";

      $GLOBALS['conn']->query($sql);
   }
   

  

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
  
 

  $sql = "SELECT * FROM tag where tag = '".$tag."'";
  $result = $GLOBALS['conn']->query($sql);

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

  $GLOBALS['conn']->query($sql);
  if(isset($id))
    $last_id = $id;
  else
    $last_id = $GLOBALS['conn']->insert_id;



  $sql = "INSERT INTO video_tag (video_id, tag_id,channel_id)
        VALUES ('".$video_id."',".$last_id.",'".$channel_id."')";

  $GLOBALS['conn']->query($sql);
  

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
  

  $sql = "SELECT * FROM channel where channel_id = '".$data['channel_id']."'";
  $result = $GLOBALS['conn']->query($sql);

  if ($result->num_rows > 0) {
      return;
  } 

  $sql = "INSERT INTO channel (channel_id, title, description,thumbnail,keyword)
    VALUES ('".$data['channel_id']."',
            '".$data['title']."', 
            '".$data['description']."',
            '".$data['thumbnail']."',
            '".$data['keyword']."' )";


  if ($GLOBALS['conn']->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
  }

}


function saveChannelStats($data)
{


  $sql = "SELECT * FROM channel_stats where channel_id = '".$data['channel_id']."'";
  $result = $GLOBALS['conn']->query($sql);

  if ($result->num_rows > 0) {
      return;
  } 

  $sql = "INSERT INTO channel_stats (channel_id, viewCount, commentCount,subscriberCount,videoCount)
    VALUES ('".$data['channel_id']."',
            '".$data['viewCount']."', 
            '".$data['commentCount']."',
            '".$data['subscriberCount']."',
            '".$data['videoCount']."' )";


  if ($GLOBALS['conn']->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
  }

}


function saveVideoStats($data)
{
  


  $sql = "SELECT * FROM video_stats where video_id = '".$data['video_id']."'";
  $result = $GLOBALS['conn']->query($sql);

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


  if ($GLOBALS['conn']->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
  }

}

function saveVideo($data)
{ 

  
  $mails = saveMails($data['description'],$data['channel_id']);
  $urls  = saveUrls($data['description'],$data['channel_id']);




  $sql = "SELECT * FROM video where video_id = '".$data['video_id']."'";
  $result = $GLOBALS['conn']->query($sql);

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


  if ($GLOBALS['conn']->query($sql) === TRUE) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
  }

  
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
        

        
        
        $sql = "SELECT * FROM channel_mail where channel_id = '".$channel_id."' and mail='".$mail."' ";
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0) {
            continue;
        } 

         $sql = "INSERT INTO channel_mail (channel_id, mail)
          VALUES ('".$channel_id."',
                  '".$mail."'
                 )";


        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
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
        


        $sql = "SELECT * FROM channel_url where channel_id = '".$channel_id."' and url='".$url."' ";
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0) {
            continue;
        } 

         $sql = "INSERT INTO channel_url (channel_id, url)
          VALUES ('".$channel_id."',
                  '".$url."'
                 )";


        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
        }


      }
    }
}
?>
