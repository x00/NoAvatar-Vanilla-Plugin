<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['NoAvatar'] = array(
   'Name' => 'NoAvatar',
   'Description' => 'Prevents uploading of profile picture or Avatar, and prevent any avatars from displaying.',
   'Version' => '0.1.1b',
   'Author' => "Paul Thomas",
   'RequiredApplications' => array('Vanilla' => '2.0.18'),
   'MobileFriendly' => TRUE,
   'AuthorEmail' => 'dt01pqt_pt@yahoo.com',
   'AuthorUrl' => 'http://vanillaforums.org/profile/x00'
);



class NoAvatarPlugin extends Gdn_Plugin {
  
  public function ProfileController_AfterAddSideMenu_Handler($Sender,&$Args) {
    $Links = &$Args["SideMenu"]->Items["Options"]["Links"];
    unset($Links["/profile/picture"]);
    unset($Links["/profile/thumbnail"]);
    foreach($Links As $LinkI => $LinkV){
      if(stripos($LinkI,'/profile/removepicture')!==FALSE){
        unset($Links[$LinkI]);
        break;
      }
    }
    //remove "Add a Profile Picture" link
    unset($Sender->Assets["Panel"]["UserPhotoModule"]);

  }
  public function ProfileController_BeforeUserInfo_Handler($Sender, &$Args){
    //remove "Add a Profile Picture" link in mobile
    if(IsMobile())
      echo '<style type="text/css">.Photo{ display:none;}</style>';
  }
  
  public function Base_BeforeLoadRoutes_Handler($Sender, &$Args){
    //block access
    $BlockDestinations = array(
      '/profile\/picture(\/.*)?$/',
      '/profile\/thumbnail(\/.*)?$/',
      '/profile\/removepicture(\/.*)?$/'
    );
    foreach($BlockDestinations As $BlockDestination){
      if(preg_match($BlockDestination, Gdn::Request()->Path())){
        Gdn::Dispatcher()->Dispatch('Default404');
        exit;
      }
    }
  }
  
}

if(!function_exists('UserPhoto')){
  function UserPhoto(){
    return '';
  }
}
