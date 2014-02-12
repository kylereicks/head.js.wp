<script><?php
self::render_template('load-async-script', array('src' => $head_src, 'callback' => 'headInit'));
self::render_template('head-init', array('model' => $model, 'queue' => $model->queue));
?></script>
