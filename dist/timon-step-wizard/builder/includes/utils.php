<?php
  include_once './config.php';


  function createHtmlandZipFile($html='',$jsScript='')
  {

    $todayh = getdate();
    $filename= "timon-form-".$todayh[seconds].$todayh[minutes].$todayh[hours].$todayh[mday]. $todayh[mon].$todayh[year];

    $newHtmlFilename=EXPORTS_DIRECTORY.$filename.'.html';
    $zipFilename=EXPORTS_DIRECTORY.$filename.'.zip';
    $zipFileUrl=EXPORTS_URL.$filename.'.zip';
    $htmlFileUrl=EXPORTS_URL.$filename.'.html';

    //read email template
    $templateContent=file_get_contents("template.html",true);

    //create new document
    $new_content =$html;

    //view in browser link
    //$new_content=str_replace('#view_web',$htmlFileUrl,$new_content);


    $content=str_replace('[form-body]',$new_content,$templateContent);
    $content=str_replace('[form-script]',$jsScript,$content);
    $fp = fopen($newHtmlFilename,"wb");
    fwrite($fp,$content);
    fclose($fp);

    //create zip document
    $zip = new ZipArchive();

    $zip->open($zipFilename, ZipArchive::CREATE);
    $zip -> addEmptyDir('css');
    $zip -> addEmptyDir('js');
    $zip->addFile(EXPORTS_DIRECTORY.'/css/tsf-wizard.bundle.min.css', 'css/tsf-wizard.bundle.min.css');
    $zip->addFile(EXPORTS_DIRECTORY.'/js/tsf-wizard.bundle.min.js', 'js/tsf-wizard.bundle.min.js');
    $zip->addFile($newHtmlFilename, 'index.html');
    $zip->close();
    //remove html file
    //unlink($newHtmlFilename);

    $response=array();
    $response['code']=0;
    $response['url']=$zipFileUrl;
    $response['preview_url']=$htmlFileUrl;
    $response['html']=$new_content;

    return $response;
  }
?>
