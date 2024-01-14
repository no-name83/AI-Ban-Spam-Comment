<?php

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'abscai_all_func' ) ) {

  class abscai_all_func{
  
      function abscai_create_db()
{
   $abscai_api_key=update_option('abscai_api_key','');
 $abscai_temperature=update_option('abscai_temperature','0.6');
 $absc_max_tokens=update_option('abscai_max_tokens','150');

$abscai_top_p=update_option('abscai_top_p','1');

$abscai_frequency_penalty=update_option('abscai_frequency_penalty','1');
$abscai_presence_penalty=update_option('abscai_presence_penalty','1');
$abscai_accepted_comment=update_option('abscai_accepted_comment','Pending');
$abscai_rejected_comment=update_option('abscai_rejected_comment','Trash');
$abscai_comm_status=update_option('absc_comm_status','0');
$abscai_models=update_option('abscai_models','gpt-4');
}
  

     
        
                function   abscai_delete_records(){


         $absc_api_key=delete_option('abscai_api_key');
 $abscai_temperature=delete_option('abscai_temperature');
 $abscai_max_tokens=delete_option('abscai_max_tokens','150');

$abscai_top_p=delete_option('abscai_top_p','1');

$abscai_frequency_penalty=delete_option('abscai_frequency_penalty');
$abscai_presence_penalty=delete_option('abscai_presence_penalty');
$abscai_accepted_comment=delete_option('abscai_accepted_comment');
$abscai_rejected_comment=delete_option('abscai_rejected_comment');
$abscai_comm_status=delete_option('absc_comm_status');
$abscai_models=delete_option('abscai_models');

}
    

 

   


         }
}

  //}



?>