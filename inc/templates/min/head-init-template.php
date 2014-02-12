var headInit=function(){<?php
foreach($queue as $item){
  if($model->no_deps($item['handle'])){
    self::render_template('head-load', array('model' => $model, 'item' => $item));
  }else{
    self::render_template('head-ready', array('model' => $model, 'item' => $item));
  }
}
?>};
