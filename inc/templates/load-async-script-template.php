(function(window, document){
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = '<?php echo $src; ?>';
  if(script.addEventListener){
    script.addEventListener('load', function(){<?php echo $callback; ?>();}, false);
  }else if(script.attachEvent){
    script.attachEvent('onload', function(){<?php echo $callback; ?>();});
  }
  document.getElementsByTagName('head')[0].appendChild(script);
}(this, document));
