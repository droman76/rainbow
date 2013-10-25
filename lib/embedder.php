<?php

class Video {

}

function getVideo($body,$width,$height) {


$video = new Video();
$url = $body;
$frame = '';
 
// YouTube url?
if(preg_match("/youtu.be\/[a-z1-9.-_]+/", $url)) {
    preg_match("/youtu.be\/([a-z1-9.-_]+)/", $url, $matches);
    if(isset($matches[1])) {
        $url = 'https://www.youtube.com/embed/'.$matches[1];
        $frame = "<iframe width=\"$width\" src=\"$url\" frameborder=\"0\" allowfullscreen></iframe>";
        $youtube = simplexml_load_file('https://gdata.youtube.com/feeds/api/videos/'.$matches[1].'?v=1');
        $video->title =(string) $youtube->title;
        $video->description = (string) $youtube->content;
        $video->frame = $frame;
        $video->url = $url;
    }
}
else if(preg_match("/youtube.com(.+)v=([^&]+)/", $url)) {
    preg_match("/v=([^&]+)/", $url, $matches);
    if(isset($matches[1])) {
        $url = 'https://www.youtube.com/embed/'.$matches[1];
         $frame = "<iframe width=\"$width\" height=\"$height\" src=\"$url\" frameborder=\"0\" allowfullscreen></iframe>";
         $youtube = simplexml_load_file('https://gdata.youtube.com/feeds/api/videos/'.$matches[1].'?v=1');
         $video->title =(string) $youtube->title;
         $video->description = (string) $youtube->content;
         $video->frame = $frame;
         $video->url = $url;
    }
   
}
// Google video
else if(preg_match("/video.google.com(.+)docid=([^&]+)/", $url)) {
    preg_match("/docid=([^&]+)/", $url, $matches);
    if(isset($matches[1])) {
        $url = 'https://video.google.com/googleplayer.swf?docId='.$matches[1].'&hl=en';
    }
}
// Vimeo video
else if(preg_match("/vimeo.com\/[1-9.-_]+/", $url)) {
    preg_match("/vimeo.com\/([1-9.-_]+)/", $url, $matches);
    if(isset($matches[1])) {
        $url = 'https://player.vimeo.com/video/'.$matches[1];
    }
}
 
return $video;
}
?>