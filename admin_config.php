<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

require_once("./vendor/autoload.php");
use ActiveCampaign;
// e107::lan('activecampaign',true);


class activecampaign_adminArea extends e_admin_dispatcher
{

	protected $modes = array(	
	
		'main'	=> array(
			'controller' 	=> 'activecampaign_ui',
			'path' 			=> null,
			'ui' 			=> 'activecampaign_form_ui',
			'uipath' 		=> null
		),
		

	);	
	
	
	protected $adminMenu = array(
			
		'main/prefs' 		=> array('caption'=> LAN_PREFS, 'perm' => 'P'),	

		// 'main/div0'      => array('divider'=> true),
		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P'),
		
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = 'Active Campaign';
}




				
class activecampaign_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Active Campaign';
		protected $pluginName		= 'activecampaign';
	//	protected $eventName		= 'activecampaign-'; // remove comment to enable event triggers in admin. 		
		protected $table			= '';
		protected $pid				= '';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
		protected $batchExport     = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
	//	protected $listOrder		= ' DESC';
	
		protected $fields 		= array (
		);		
		
		protected $fieldpref = array();
		

	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
			'active'		=> array('title'=> 'Active', 'tab'=>0, 'type'=>'boolean', 'data' => 'str', 'help'=>'Enable to activate', 'writeParms' => array()),
			'api_url'		=> array('title'=> 'API Url', 'tab'=>0, 'type'=>'url', 'data' => 'str', 'help'=>'', 'writeParms' => array('size'=>'xxlarge', 'placeholder'=>'eg. https://account-name.api-us1.com')),
			'api_key'		=> array('title'=> 'API Key', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'help'=>'', 'writeParms' => array('size'=>'xxlarge', 'placeholder'=>'eg. 75ecc7608b621faaeb6533b6e5f2386c7d955dd6b5e41aff9397432d28d643de316bc049')),
			'caption'       => array('title'=> LAN_TITLE, 'tab'=>0, 'type'=>'text', 'data'=>'str'),
			'description'   => array('title'=> LAN_DESCRIPTION, 'tab'=>0, 'type'=>'text', 'data'=>'str'),
			'tags'          => array('title'=> "Tags", 'tab'=>0, 'type'=>'tags', 'data'=>'str', 'help'=>'Optional. Active Campaign tags to add to the user when they subscribe.'),
		);

	
		public function init()
		{


	
		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data)
		{
			return $new_data;
		}
	
		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something		
		}		
		
		
		// ------- Customize Update --------
		
		public function beforeUpdate($new_data, $old_data, $id)
		{
			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something	
		}
		
		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something		
		}		
		
		// left-panel help menu area. (replaces e_help.php used in old plugins)
		public function renderHelp()
		{
			$caption = LAN_HELP;
			$text = '<p>On the active campaign website, go to <span class="text-nowrap"><strong>Settings > Developer</strong></span> to obtain your API URL and KEY. </p>Then, using the menu-manager enable the activecampaign menu on our website. ';

			return array('caption'=>$caption,'text'=> $text);

		}

		public function afterPrefsSave()
		{
			$pref = e107::pref('activecampaign');

			if(!empty($pref['api_url']) && !empty($pref['api_key']))
			{
				$ac = new ActiveCampaign($pref['api_url'], $pref['api_key']);
				$result = (int) $ac->credentials_test();

				if($result)
				{
					e107::getMessage()->addSuccess("API Connection Successful. You're all set!");
				}
				else
				{
					e107::getMessage()->addError("API Connection Failed. Invalid Credentials?");
				}
			}


			return null;

		}
			
	/*	
		// optional - a custom page.  
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;
			
		}
		
	
		
		
	*/
			
}
				


class activecampaign_form_ui extends e_admin_form_ui
{

}		
		
		
new activecampaign_adminArea();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;

