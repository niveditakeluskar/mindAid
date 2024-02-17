<?php


try {



  //config.php
  $dirConfig=$_POST['site_directory'].'/config.php';
  $content_config=file_get_contents($dirConfig);

  $content_config.='define("SITE_URL", "'.$_POST['site_url'].'/");';
  $content_config.='define("SITE_DIRECTORY", "'.$_POST['site_directory'].'/");';
  $content_config.='define("ELEMENTS_DIRECTORY", SITE_DIRECTORY."/elements.json");';

  $content_config.='define("UPLOADS_DIRECTORY", SITE_DIRECTORY."/uploads/");';
  $content_config.='define("UPLOADS_URL", SITE_URL."/uploads/");';

  $content_config.='define("EXPORTS_DIRECTORY", SITE_DIRECTORY."/exports/");';
  $content_config.='define("EXPORTS_URL", SITE_URL."/exports/");';





  file_put_contents($dirConfig,$content_config);


  echo 'ok';

} catch (Exception $e) {
  echo 'error : '.$e->getMessage();
}

 ?>
