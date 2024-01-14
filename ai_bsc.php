<?php
/**
Plugin Name: AI Ban Spam Comment
Tags:chatgpt,gpt,openai,ban spam comment
Requires at least: 5.0
Description:This plugin uses the OpenAI  gpt-4 and gpt-4-1106-preview models to analyze and filter comments made on your texts.
Tested up to: 6.4.2
Requires PHP: 7.2
Version: 1.1
Author: naimgr83
Stable tag: 1.1
License: GPL2
**/







if ( ! defined( 'ABSPATH' ) ) exit;
require_once __DIR__ . '/ai_bsc_function.php';

if ( ! class_exists( 'aibsc_bsc_controls' ) ) {

  class aibsc_bsc_controls{


   function __construct() {
          add_action( 'admin_init', [ $this, 'bsc_settings_save' ] );
          add_filter('pre_comment_approved', [$this, 'bsc_check_comment'], 99, 2);

     }

  function AI_Ban_Spam_Comment_admin_menu_option()
  {


  $aibsc_bsc_controls=new   aibsc_bsc_controls;
    add_menu_page('AI Ban Spam Comment','AI_Ban_Spam_Comment','manage_options','AI-Ban-Spam-Comment-admin-menu',array($aibsc_bsc_controls,'AI_Ban_Spam_Comment_scripts_page'),'dashicons-shield-alt',200);



  }

 

  
  function AI_Ban_Spam_Comment_scripts_page()
  {

    


    ?>




<?php


   
$abscai_api_key=esc_attr(get_option('abscai_api_key'));
$abscai_temperature=esc_attr(get_option('abscai_temperature'));
$abscai_max_tokens=esc_attr(get_option('abscai_max_tokens'));
$abscai_top_p=esc_attr(get_option('abscai_top_p'));
$abscai_frequency_penalty=esc_attr(get_option('abscai_frequency_penalty'));
$abscai_presence_penalty=esc_attr(get_option('abscai_presence_penalty'));
$absc_comm_status=esc_attr(get_option('absc_comm_status'));
$abscai_models=esc_attr(get_option('abscai_models'));


?>

     <div class="">
      <h2> AI BAN SPAM COMMENT SETTINGS</h2>
      <form method="post" action="">
   <?php wp_nonce_field("aibsc","aibsc"); ?>


         <div class="bsc_form_row">
        <label class="bsc_label">Models:</label>
             <select  name="abscai_models" class="regular-text">
                  <?php if(esc_attr(get_option('abscai_models'))=='gpt-4-1106-preview'){
    echo '<option value="gpt-4-1106-preview" selected>gpt-4-1106-preview</option>';
     echo '<option value="gpt-4">gpt-4</option>'; 
     
 
}
  
  else if(esc_attr(get_option('abscai_models'))=='gpt-4'){
    echo '<option value="gpt-4" selected>gpt-4</option>'; 
    echo '<option value="gpt-4-1106-preview" >gpt-4-1106-preview</option>'; 
  }?>
  </select>
       
    </div>                  
          
      <div class="bsc_form_row">
        <label class="bsc_label">Temperature:</label>
        <input type="text" class="regular-text"  name="temperature" value="<?php
        echo  esc_attr($abscai_temperature)  ;
        ?>">
       
    </div>

       <div class="bsc_form_row">
        <label class="bsc_label">API KEY:</label>
        <input type="text" class="regular-text"  name="api-key" value="<?php
        echo  esc_attr($abscai_api_key)  ;
        ?>">
        
    </div>

     <div class="bsc_form_row">
        <label class="bsc_label">Max Tokens:</label>
        <input type="text" class="regular-text"   name="max-tokens" value="<?php
        echo  esc_attr($abscai_max_tokens) ;
        ?>">
        
    </div>

       <div class="bsc_form_row">
        <label class="bsc_label">Presence Penalty:</label>
        <input type="text" class="regular-text"   name="presence-penalty" value="<?php
        echo  esc_attr($abscai_presence_penalty)  ;
        ?>">
       
    </div>

       <div class="bsc_form_row">
        <label class="bsc_label">Frequence Penalty:</label>
        <input type="text" class="regular-text"  name="frequency-penalty" value="<?php
        echo  esc_attr($abscai_frequency_penalty) ;
        ?>">
        
    </div>

          <div class="bsc_form_row">
        <label class="bsc_label">Top_P:</label>
        <input type="text" class="regular-text"  name="top-p" value="<?php
        echo  esc_attr($abscai_top_p) ;
        ?>">
        
    </div>

        <div class="bsc_form_row">
        <label class="bsc_label">Accepted Comment:</label>
       

         <select class="regular-text" name="bsc_accepted_comment">
                 <?php if(esc_attr(get_option('abscai_accepted_comment'))=='Pending'){
    echo '<option value="Pending" selected>Pending</option>';
     echo '<option value="Publish">Publish</option>'; 
     
 
}
  
  else if(esc_attr(get_option('abscai_accepted_comment'))=='Publish'){
    echo '<option value="Publish" selected>Publish</option>'; 
    echo '<option value="Pending" >Pending</option>'; 
  }
   
?>
  </select>
        
    </div>
         <div class="bsc_form_row">
        <label class="bsc_label">Rejected Comment:</label>
       

         <select class="regular-text" name="bsc_rejected_comment">
             <?php if(esc_attr(get_option('abscai_rejected_comment'))=='Trash'){
    echo '<option value="Trash" selected>Trash</option>';
     echo '<option value="Delete">Delete</option>'; 
     
 
}
  
  else if(esc_attr(get_option('abscai_rejected_comment'))=='Delete'){
    echo '<option value="Delete" selected>Delete</option>'; 
    echo '<option value="Trash" >Trash</option>'; 
  }
   
?>  </select>
        
    </div>
      </div>
    <div class="bsc_form_row">
        <label class="bsc_label">Only Positive Comment:</label>
        <input type="checkbox" class="regular-text"  name="absc_comm_status" value="1"<?php checked(  get_option( 'absc_comm_status' ) ); ?>><br/>
    </div>


     
  
<?php








?>

    <input type="submit" name="bsc_btn" class="button button-primary" value="Save Changes">
      </form>
    </div>


  

    <?php
  }



function bsc_settings_save(){


  
	if (isset($_POST['bsc_btn']) && is_user_logged_in() ) {
		



		if ( ! isset($_POST["aibsc"]) ||  ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["aibsc"])), "aibsc") ){

     exit();
 }

    else{


  $abscai_temperature=floatval(sanitize_text_field($_POST['temperature']));

   
   update_option('abscai_temperature',$abscai_temperature);



  


  $abscai_max_tokens=intval(sanitize_text_field($_POST['max-tokens']));
   
     

   update_option('abscai_max_tokens',$abscai_max_tokens);



 
     $abscai_presence_penalty=intval(sanitize_text_field($_POST['presence-penalty']));
    



   update_option('abscai_presence_penalty',$abscai_presence_penalty);
   
    $abscai_api_key=sanitize_text_field($_POST['api-key'] );

   update_option('abscai_api_key',$abscai_api_key);


     $abscai_frequency_penalty=intval(sanitize_text_field($_POST['frequency-penalty']));
     






   update_option('abscai_frequency_penalty',$abscai_frequency_penalty);
     $abscai_top_p=intval(sanitize_text_field($_POST['top-p']));

   



   update_option('abscai_top_p',$abscai_top_p);

   update_option('abscai_accepted_comment',sanitize_text_field($_POST['bsc_accepted_comment']));
   update_option('abscai_rejected_comment',sanitize_text_field($_POST['bsc_rejected_comment']));
   update_option('abscai_models',sanitize_text_field($_POST['abscai_models']));


       if (isset($_POST['absc_comm_status']) ) {
  
    if ($abscai_comm_status==0) {
     
    $abscai_comm_status=update_option('absc_comm_status','1');
    
   }

   
   
}
  else {
   
    $abscai_comm_status=update_option('absc_comm_status','0');
}

    }


	}








}









  function bsc_check_comment($approved, $commentdata){
  $abscai_api_key=esc_attr(get_option('abscai_api_key'));
    $abscai_temperature=esc_attr(get_option('abscai_temperature'));
$abscai_max_tokens=esc_attr(get_option('abscai_max_tokens'));
$abscai_top_p=esc_attr(get_option('abscai_top_p'));
$abscai_frequency_penalty=esc_attr(get_option('abscai_frequency_penalty'));
$abscai_presence_penalty=esc_attr(get_option('abscai_presence_penalty'));
$abscai_accepted_comment=esc_attr(get_option('abscai_accepted_comment'));
$abscai_rejected_comment=esc_attr(get_option('abscai_rejected_comment'));
$abscai_comm_status=esc_attr(get_option('absc_comm_status'));
$abscai_models=esc_attr(get_option('abscai_models'));
 $url = 'https://api.openai.com/v1/chat/completions';
    
    


   $current_url = sanitize_url($_SERVER['HTTP_REFERER']);
   $current_url_new = sanitize_url( $current_url, array( 'http', 'https' ) );
  $post_id = url_to_postid($current_url_new);


  
 $post_id->ID;
$content_post = get_post($post_id);
 $content = $content_post->post_content;

          $query="Can you tell me if the comment for the article below is related to this article?You can only answer yes or no?please reply in english";
         
$query_comment = $commentdata['comment_content'];
       

  


           
         


            $queryOne =  $content. ' question1:'.$query.' question:'.$query_comment.' Ans:';



          

              $messages = [
        [
          "role"    => "user",
          "content" => $queryOne,
            
        ],
        
      ];


      

  

     $send_arr = [
      
      "model" => $abscai_models,
     
     
      "messages"             => $messages,
      "temperature"       => (float)$abscai_temperature,
      "top_p"             => (int)$abscai_top_p,
      "frequency_penalty" => (int)$abscai_frequency_penalty,
      "presence_penalty"  => (int)$abscai_presence_penalty,
      
    ];


    $json_str = json_encode( $send_arr );
        
     







          

   

    $args = array(
      
      'headers'     => array(
        'Authorization' => 'Bearer ' . $abscai_api_key,
        'Content-Type'  => 'application/json'
      ),
      'body'        => $json_str,
      'cookies'     => array()
    );

    $response = wp_remote_post( $url, $args );

    $resArr = json_decode( wp_remote_retrieve_body( $response ));

   

 $resultOne = $resArr->choices[0]->message->content;




 
 $query="Are the comments positive or negative?You can only answer yes or no?";
         
$query_comment = $commentdata['comment_content'];
       

  


           
         


            $queryOne =  $content. ' question1:'.$query.' question:'.$query_comment.' Ans:';



          

              $messages = [
        [
          "role"    => "user",
          "content" => $queryOne,
            
        ],
        
      ];


      

  

     $send_arr = [
      
      "model" => $abscai_models,
     
      "messages"             => $messages,
      "temperature"       => (float)$abscai_temperature,
      "top_p"             => (int)$abscai_top_p,
      "frequency_penalty" => (int)$abscai_frequency_penalty,
      "presence_penalty"  => (int)$abscai_presence_penalty,
      
    ];


    $json_str = json_encode( $send_arr );
   
     







         

  

    $args = array(
      
      'headers'     => array(
        'Authorization' => 'Bearer ' . $abscai_api_key,
        'Content-Type'  => 'application/json'
      ),
      'body'        => $json_str,
      'cookies'     => array()
    );

    $response = wp_remote_post( $url, $args );

    $resArr = json_decode( wp_remote_retrieve_body( $response ));

   

 $resultTwo = $resArr->choices[0]->message->content;





   
    

     if ($abscai_comm_status==1) {
         

      if (stripos($resultOne, 'Yes') !== false && stripos($resultTwo, 'yes') !==false) {
    
    if ($abscai_accepted_comment=='Publish') {
   
     
      return 1;
    }
    else{
    return 0;
  }
}
else {


  if ($abscai_rejected_comment=='Trash') {
   
    return 'trash';
  }



else  if ($abscai_rejected_comment=='Delete')
{
 


 
  wp_safe_redirect($current_url_new);
   exit();



}

}
       }  



 if ($abscai_comm_status==0) {
  

  if (stripos($resultOne, 'Yes') !== false) {
    
    if ($abscai_accepted_comment=='Publish') {
   
     
      return 1;
    }
    else{
    return 0;
  }
}
else {


  if ($abscai_rejected_comment=='Trash') {
   
    return 'trash';
  }



else  if ($abscai_rejected_comment=='Delete')
{
 


 
  wp_safe_redirect($current_url_new);
   exit();



}

}
}

    }








    
  function bsc_css() {
  
 
  
  wp_enqueue_style( 'bsc-style', plugins_url( '/css/bsc_css.css', __FILE__ ), false, '1.0', 'all' );
  
  }

}
}

 $aibsc_bsc_controls=new   aibsc_bsc_controls;


 add_action('admin_menu',array($aibsc_bsc_controls,'AI_Ban_Spam_Comment_admin_menu_option'));

add_action( 'wp_enqueue_style', array($aibsc_bsc_controls,'bsc_css', 10 ));



    add_action('admin_head', array($aibsc_bsc_controls,'bsc_css'));
   
  
    $abscai_all_func =new   abscai_all_func;
 register_activation_hook( __FILE__, array($abscai_all_func, 'abscai_create_db') );
  register_deactivation_hook(__FILE__, array($abscai_all_func, 'abscai_delete_records'));
































