<html>
<head>
<style>
@page {
    size: A4 landscape;
    margin: 1.25cm 2cm 1.5cm 2cm;
}
p { font-size:16px; margin-top:0; padding-top:0 }
table { font-size:14.3px }
h1 { font-size:18.5px; text-align:center;  }
h2 { font-size:16px; text-align:left; margin-bottom:0; padding-bottom:0 }
</style>
</head> 
<body>
  
First Name: <b>
{{empty($patient[0]->fname) ? '' : $patient[0]->fname}} {{empty($patient[0]->lname) ? '' : $patient[0]->lname}} 
</b><br>
Last Name: <b>
{{date("m-d-Y", strtotime($patient[0]->dob))}} ({{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}})
</b><br>

<br>
<!-- Your html -->
</body>
</html>