<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            body {
                font-family:verdana;    
                width: 100%;
            }
            table, th {
                text-align:justify;
            }

            table,td {
                vertical-align: middle;
                text-align:justify;
                font-size: 14px;
            }

            .tbl {
                border-collapse: collapse;
                width: 100%;
            }

            .tbl td, .tbl th {
                border: 1px solid #ddd;
                text-align: left;
                padding: 6px;
                font-size: 90%;

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
        <title>CARE PLAN</title>
    </head>
    <body>
        <?php
            if(!empty($patient[0]->id))  {
        ?>
                @include('Ccm::print-care-plan.print-care-plan-pdf-header')
                @include('Ccm::print-care-plan.print-care-plan-pdf-body-problem')
                @include('Ccm::print-care-plan.print-care-plan-pdf-body-care-team')
        <?php
            } else {
                echo "Patient details not found.";
            }
        ?>
    </body>
</html>