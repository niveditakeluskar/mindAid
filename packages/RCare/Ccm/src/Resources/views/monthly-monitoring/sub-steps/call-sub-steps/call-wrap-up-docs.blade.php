    <!DOCTYPE html>
    <html>
    <head>
 <style type="text/css">
            body {
                font-family:verdana;    
            }
            table, th {
                text-align:justify;
            }

            table,td {
                vertical-align: middle;
                text-align:justify;
            }

            .tbl {
                border-collapse: collapse;
                width: 100%;
            }

            .tbl td, .tbl th {
                border: 1px solid #ddd;
                text-align: left;
                padding: 6px;
            }

            .tbl tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            .tbl tr:hover {
                background-color: #ddd;
            }

            .tbl th {
                padding-top:2px;
                padding-bottom: 2px;
                text-align: center;
                background-color: #1474a0;
                color: white;
            }

            b {
                color:#000033;
            }

            h4 {
                background-color:#cdebf9;
                color:#000033;
                padding-top: 8px;
                padding-bottom: 8px;
                padding-left: 7px;
            }

            h3 {
                padding-top: 5px;
                padding-bottom: 5px;
                padding-left: 7px;
                color:white;
            }

            #cpid {
                float: right;
                color: white;
                font-size: 36px;
                font-family: -webkit-pictograph;   
                margin-top: -11px;
                margin-right: 10px;
            }
            
            .header {
                top: 0px;
            }

            .custom-separator {
                border-bottom: 1px solid #ebedf2;
                margin: 15px 0;
            }

            #footer { 
                position: fixed; 
                /* left: 0px;  */
                bottom: -180px; 
                /* right: 0px;  */
                height: 220px; 
                /* background-color:#1474a0; */
                margin: -45px -45px -0px -45px;
            }
            #footer .page:after { 
                content: counter(page, upper-roman); 
            }
        </style>
    </head>
    <body>
        <h4>Call Notes for Review and Approval</h4><br>
        <table id="callwrap-list" class="tbl">
            <thead>
                <tr><th width="5%" >srno.</th> 
                    <th width="20%" >Topic</th>
                    <th width="20%">CareManager Notes</th>
                    <th width="20%">Action Taken</th>
                </tr>
            </thead>
            <tbody>
                <?php $sr=0;
                   foreach ($data as $key => $value) { $sr++;?>
                    <tr>
                        <td><?php echo $sr; ?></td>
                     <td>{{$value->topic}}</td>
                      <td>{{$value->notes}}</td>
                       <td>{{$value->action_taken}}</td>
                   </tr>
                <?php } ?>
            </tbody> 
        </table>
    </body>
    </html>