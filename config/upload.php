<?php
/**
 * @文件上传配置
*/

return [

    'image_max' => 600,//KB

    'video_max' => 2048,//KB

    'msg' => '文件 {name} (<b>{size} KB</b>) 超过允许最大上传大小 <b>{maxSize} KB</b>. 请重新上传',

    'image_tips' => '<span style="color: red;"> 单图大小不超过600K </span>',

    'video_tips' => '<span style="color: red;"> 视频大小不超过2M,支持MP4,AVI格式 </span>',
];




?>
