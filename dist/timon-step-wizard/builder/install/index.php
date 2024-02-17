<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Email Builder</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script  src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
  </head>
  <body>
    <div class="head">
    <h1>Install Step Form Builder</h1>
  </div>

<div class="container">
    <div class="row">
        <div class="board">
            <ul class="nav nav-tabs">
                <!-- <div class="liner"></div> -->
                <li rel-index="0" class="active">
                    <a href="#step-1" class="btn" aria-controls="step-1" role="tab" data-toggle="tab">
                        <span><i class="fa fa-info"></i></span>
                    </a>
                </li>

            </ul>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="step-1">

                <div class="col-md-12">
                    <h3>Site information</h3>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Site URL</label>
                            <input id="site_url" type="text" required="required" class="form-control" placeholder="Enter site url"  />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Site directory</label>
                            <input id="site_directory"  type="text" required="required" class="form-control" placeholder="Enter site directory"  />
                        </div>
                    </div>



                    <button id="step-1-next" class="btn btn-lg btn-primary nextBtn pull-right">Next</button>
                </div>
            </div>
        


        </div>
    </div>
</div>
  </body>
</html>
