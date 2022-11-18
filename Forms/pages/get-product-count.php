<?php
 $api_url = 'https://ce31bb05c0c659b3ff1b26bf0766060d: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@atacsportwear-com.myshopify.com';
 $count_obj_url = $api_url . '/admin/products/count.json';
 $count_content = @file_get_contents( $count_obj_url );
 $count_json = json_decode( $count_content, true );
 $count = $count_json['count'];
//  echo $count;
?>