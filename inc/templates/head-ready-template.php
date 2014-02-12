head.ready([<?php
$max = count($item['deps']);
foreach($item['deps'] as $index => $dep){
  echo "'" . $dep . "'";
  if($index + 1 < $max){
    echo ',';
  }
}
?>
], function(){
    <?php
      self::render_template('head-load', array('model' => $model, 'item' => $item));
    ?>
  });
