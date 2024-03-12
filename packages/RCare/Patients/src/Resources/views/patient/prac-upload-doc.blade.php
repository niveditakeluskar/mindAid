<html>
    <?php
        $content = pg_unescape_bytea($documents[0]->doc_content);
        print_r($content);die;
    ?> 
    
    <?php
    header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="service.pdf"');
echo base64_decode($documents[0]->doc_content);


echo Pdf::getText('sample.pdf');

       
    ?>
</html>