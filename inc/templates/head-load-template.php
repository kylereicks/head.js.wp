var headInit = function(){
  head.load([<?php
  $max = count($queue);
  foreach($queue as $index => $item){
    self::render_template('resource', array('item' => $item));
    if($index + 1 < $max){
      echo ',';
    }
    $model->done($item['handle']);
  }
  ?>]);
};
