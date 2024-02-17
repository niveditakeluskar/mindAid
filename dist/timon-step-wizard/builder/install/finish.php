
<!doctype html>
<html lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <title>Email Builder</title>
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" type="text/css" media="all" href="css/finish.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
  <div id="w">
    <div id="content">
      <?php
        if ($_GET['status']=='success') {
          echo '  <div class="notify successbox">
              <h1>Success!</h1>
              <span class="alerticon"><img src="images/check.png" alt="checkmark" /></span>
              <p>Thanks for installing \'Step Form Builder\'. Before starting using plugin please remove <strong>Install folder</strong> from your server.</p>
            </div>';
        }
       ?>
       <?php
         if ($_GET['status']!='success') {
           echo ' <div class="notify errorbox">
                  <h1>Error!</h1>
                  <span class="alerticon"><img src="images/error.png" alt="error" /></span>
                  <p>An error occured while configuration files. Please check your files and make sure that project folder has access for editing files(chmod) .  </p>
                </div>';
            }
           ?>
      <footer>
        If you have any questions,do not hesitate to ask, we will figure out how to help you! <a href="https://askerov.ticksy.com/submit/" >Visit Support Center</a>
      </footer>
    </div><!-- @end #content -->
  </div><!-- @end #w -->
</body>
</html>
