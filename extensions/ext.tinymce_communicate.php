<?php
if (!defined('EXT')) exit('Invalid file request');

class TinyMCE_Communicate
{
	var $name			= 'TinyMCE Communicate';
	var $description	= "Adds TinyMCE WYSIWYG editing to ExpressionEngine's 'Communicate' tab. (Works with and without LG TinyMce)";
	var $version 		= '0.2';
	var $settings_exist = 'y';
	var $docs_url		= 'http://github.com/aaronrussell/ee-tinymce-communicate';

	function TinyMCE_Communicate($settings='')
	{
		if($settings == '') {
			$settings = array();
			$settings['Use_LG_TinyMCE'] = "yes";
		}
		if(!isset($settings['LG_settings']) && $settings['Use_LG_TinyMCE'] == "yes") {
			$settings['LG_settings'] = $this->_get_lg_settings();
		}
		$this->settings = $settings;
	}

	function _get_lg_settings( $force_refresh = FALSE, $return_all = FALSE )
	{
		global $DB, $REGX, $PREFS;
		$query = $DB->query("SELECT settings FROM exp_extensions WHERE enabled = 'y' AND class = 'Lg_tinymce' LIMIT 1");
		$settings = $REGX->array_stripslashes(unserialize($query->row['settings']));
		return $settings[$PREFS->ini('site_id')];
	}

	// --------------------------------
	//  Settings
	// --------------------------------  
	function settings()
	{
		$settings = array();

		$settings['Use_LG_TinyMCE']			= array('r', array('yes' => "yes", 'no' => "no"), 'yes');;
		$settings['TinyMCE_Script_URL']		= '';
		$settings['TinyMCE_Config_URL']		= '';
		$settings['TinyMCE_Class_Selector']	= '';

		if($settings['Use_LG_TinyMCE'] == "yes") {
			$settings['LG_settings'] = $this->_get_lg_settings();
		}

		return $settings;
	}
	// END settings

	function activate_extension()
	{
		global $DB;
 
		$settings =	array();
		$hooks[] = array(			
			'extension_id'	=> '',
			'class'			=> __CLASS__,
			'method'		=> 'show_full_control_panel_end',
			'hook'			=> 'show_full_control_panel_end',
			'settings'		=> serialize($settings),
			'priority'		=> 10,
			'version'		=> $this->version,
			'enabled'		=> 'y'
		);
 
		foreach($hooks as $hook)
		{
			 $DB->query($DB->insert_string('exp_extensions', $hook));
		}
	}

	function disable_extension()
	{
		global $DB;
		$DB->query("DELETE FROM exp_extensions WHERE class = '" . __CLASS__ . "'");
	}

	function show_full_control_panel_end($html)
	{
		global $EXT;

		$html = ($EXT->last_call !== FALSE) ? $EXT->last_call : $html;
 
		if(isset($_GET['C']) && $_GET['C'] == 'communicate')
		{
			if($settings['Use_LG_TinyMCE'] == "yes") {
				// work with LG module
				$replace = '<script type="text/javascript" src="' . trim($this->settings['LG_settings']['script_path']) . '"></script>' . NL;
				$settings_parts = implode("\n\t\t", preg_split("/(\r\n|\n|\r)/", trim($this->settings['LG_settings']['script_config'])));
				$replace .= '
					<script type="text/javascript">
					//<![CDATA[
						tinyMCE.init({'.$settings_parts.'});
					//]]>
					</script>';
				$html = str_replace('</head>', $replace.'</head>', $html);
				$html = $this->str_replace_once("class='textarea'", "class='textarea lg_mceEditor'", $html);
			} else if(strlen(trim($this->settings['TinyMCE_Script_URL'])) > 0 && strlen (trim($this->settings['TinyMCE_Config_URL'])) > 0 && strlen(trim($this->settings['TinyMCE_Class_Selector'])) > 0) {
				// work without LG module and with eg. http://wiseupstudio.com/expressionengine/extensions/ 'Universal Editor' or 'TinyBrowser + TinyMCE'
				$replace = '<script type="text/javascript" src="' . trim($this->settings['TinyMCE_Script_URL']) . '"></script>' . NL;
				$replace .= '<script type="text/javascript" src="' . trim($this->settings['TinyMCE_Config_URL']) . '"></script>' . NL;
				$html = str_replace('</head>', $replace.'</head>', $html);
				$html = $this->str_replace_once("class='textarea'", "class='textarea ".$this->settings['TinyMCE_Class_Selector']."'", $html);
			}
		}
		return $html;
	}

	function str_replace_once($needle , $replace , $haystack)
	{
		$pos = strpos($haystack, $needle);
		if ($pos === false) {return $haystack;}
		return substr_replace($haystack, $replace, $pos, strlen($needle));
	}
}
?>
