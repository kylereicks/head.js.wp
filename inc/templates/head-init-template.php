var headInit = function(){
<?php
foreach($queue as $item){
  if($model->no_deps($item['handle'])){ ?>
  <?php self::render_template('head-load', array('model' => $model, 'item' => $item));
  }else{ ?>
  <?php self::render_template('head-ready', array('model' => $model, 'item' => $item));
  }
}
?>
};
