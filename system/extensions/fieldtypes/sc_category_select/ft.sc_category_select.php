<?php
 
if ( ! defined('EXT')) exit('Invalid file request');

/**
 * Category Select Class
 * @package   Category Select
 * @author    Andrew Gunstone <andrew@thirststudios.com>
 * @copyright 2010 Andrew Gunstone
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */
 
class Sc_category_select extends Fieldframe_Fieldtype {
 	
	var $info = array(
		'name'             => 'SC Category Select',
		'version'          => '1.1.3',
		'desc'             => 'Creates a select menu from a selected EE category',
		'docs_url'         => 'http://sassafrasconsulting.com.au/software/category-select',
		'versions_xml_url' => 'http://sassafrasconsulting.com.au/versions.xml'
	);

	var $hooks = array(
		'submit_new_entry_absolute_end'
	);
	
	var $cache = array();

	/**
	 * On saving an entry, delete all categories for this post, then add in categories
	 * from all SC Category Select fields
	 *
	 * @param  int  $entry_id      The entry id
	 * @return nothing
	 */
	function submit_new_entry_absolute_end($entry_id)
	{
		global $DB;

		if (isset($this->cache['cat_del']) AND $this->cache['cat_del'] == 'true')
		{
			$DB->query("DELETE FROM exp_category_posts WHERE entry_id = $entry_id");
		}
		if (isset($this->cache['cat_id']) AND $this->cache['cat_id'] != '')
		{
			$sql = '';
			$cat_ids = explode(',', trim($this->cache['cat_id'],','));
			$cat_ids = array_unique($cat_ids);
			foreach ($cat_ids AS $id):
				$sql .= "($id, $entry_id),";
			endforeach;
			$DB->query("INSERT INTO exp_category_posts (cat_id, entry_id) VALUES ".trim($sql,','));				
		}
	}
 
	/**
	 * Display Field
	 *
	 * @param  string  $field_name      The field's name
	 * @param  mixed   $field_data      The field's current value
	 * @param  array   $field_settings  The field's settings
	 * @return string  The field's HTML
	 */
	function display_field($field_name, $field_data, $field_settings)
	{
	 	global $DSP, $DB;

		$r = $DSP->input_select_header($field_name);
		$r .= $DSP->input_select_option('', '--');
			
		$group_id = (!isset($field_settings['options'])) ? 0 : $field_settings['options'];
		$r .= $this->_input_select_options(0,0,$group_id,$field_data);

		$r .= $DSP->input_select_footer();
		return $r;
	}

	/**
	 * Display Cell
	 *
	 * @param  string  $cell_name      The cell's name
	 * @param  mixed   $cell_data      The cell's current value
	 * @param  array   $cell_settings  The cell's settings
	 * @return string  The cell's HTML
	 * @author Brandon Kelly <me@brandon-kelly.com>
	 */
	function display_cell($cell_name, $cell_data, $cell_settings)
	{
		return $this->display_field($cell_name, $cell_data, $cell_settings);
	}
	
	/**
	 * Display Field Settings
	 * 
	 * @param  array  $field_settings  The field's settings
	 * @return array  Settings HTML (cell1, cell2, rows)
	 */
	function display_field_settings($field_settings)
	{
		global $DSP, $LANG;
		
		$options = (!isset($field_settings['options'])) ? 0 : $field_settings['options'];

		$cell = $DSP->qdiv('defaultBold', $LANG->line('select_category_group'))
		       . $this->_select_category($options);

		return array('cell1' => '', 'cell2' => $cell);
	}
	
	/**
	 * Display Field Settings
	 * 
	 * @param  array  $cell_settings  The cell's settings
	 * @return string  Settings HTML
	 */
	function display_cell_settings($cell_settings)
	{
		global $DSP, $LANG;

		$options = (!isset($cell_settings['options'])) ? 0 : $cell_settings['options'];
		
		$r = '<label class="itemWrapper">'
		   . $DSP->qdiv('defaultBold', $LANG->line('select_category_group'))
		   . $this->_select_category($options)
   		   . '</label>';

		return $r;
	}
	/**
	 * Save Field
	 *
	 * @param  mixed   $field_data      The field's data
	 * @param  array   $field_settings  The field's settings
	 * @param  int     $entry_id	    The entry id
	 * @return string  Modified $field_data
	 */
	function save_field($field_data, $field_settings)
	{
		$this->cache['cat_del'] = 'true';
		$this->cache['cat_id'] .= ",".$field_data;
		$this->cache['cat_id'] = trim($this->cache['cat_id'],",");
		return trim($field_data);
	}

	/**
	 * Save Cell
	 *
	 * @param  mixed   $cell_data      The cell's data
	 * @param  array   $cell_settings  The cell's settings
	 * @return string  Modified $cell_data
	 */
	function save_cell($cell_data, $cell_settings)
	{
		return $this->save_field($cell_data, $cell_settings);
	}

	/**
	 * Save Site Settings
	 *
	 * @param  array  $field_settings  The site settings post data
	 * @return array  The modified $site_settings
	 */
	function save_field_settings($field_settings)
	{
		$field_settings['options'] = implode(",", $field_settings["options"]);

		return $field_settings;
	}

	/**
	 * Save Cell Settings
	 *
	 * @param  array  $cell_settings  The site settings post data
	 * @return array  The modified $site_settings
	 */
	function save_cell_settings($cell_settings)
	{
		return $this->save_field_settings($cell_settings);
	}
	
	/**
	 * List all select options
	 * 
	 * @param  int  $parent_id
	 * @param  int  $level
	 * @param  int  $group_id
	 * @return string  A list of available categories for the category group
	 */
	function _input_select_options($parent_id,$level,$group_id,$field_data)
	{
		global $DSP, $DB;
				
		// fetch all categories
		$categories = $DB->query("SELECT cat_id, cat_name, parent_id, group_id, (SELECT COUNT(cat_id) FROM exp_categories WHERE parent_id = tblCat.cat_id) AS children FROM exp_categories tblCat WHERE parent_id = $parent_id  AND group_id IN ($group_id) ORDER BY group_id, cat_order");
		$r = '';
		$level_label = '';
		$current_group = 0;
		if ($level > 0)
		{
			$counter=0;
			while($counter < $level)
			{
				$level_label .= "&nbsp&nbsp&nbsp&nbsp;&nbsp&nbsp;";
				$counter++;
			} 
		}
		foreach ($categories->result as $cat):
			if ($current_group == 0) $current_group = $cat['group_id'];
			if ($current_group != $cat['group_id'])
			{
				$r .= $DSP->input_select_option('', 
											'---');
				$current_group = $cat['group_id'];
			}
			$r .= $DSP->input_select_option($cat['cat_id'], 
											$level_label.$cat['cat_name'], 
											$field_data == $cat['cat_id']);
			if ($cat['children'] > 0)
			{
				$xLevel = $level+1;
				$r .= $this->_input_select_options($cat['cat_id'],$xLevel,$group_id,$field_data);
			}
		endforeach;
		return $r;
	}

	/**
	 * All category groups
	 * 
	 * @param  int  $current_option
	 * @return string  A list of available category groups
	 */
	function _select_category($options)
	{
		global $DB, $PREFS;

		$site_id = $PREFS->ini('site_id');
		$options = explode(",", $options);
		$block = "<div class='itemWrapper'><select name=\"options[]\" multiple=\"multiple\" style=\"width:45%\" >";
		
		$selected = (in_array(0, $options)) ? " selected=\"true\"" : "";
		$block .= "<option value=\"0\"$selected>None</option>";

		$dls = $DB->query("SELECT group_id, group_name FROM exp_category_groups WHERE site_id = $site_id ORDER BY group_name ASC");
		foreach($dls->result as $dl):
			$selected = (in_array($dl['group_id'], $options)) ? " selected=\"true\"" : "";
			$block .= "<option value=\"{$dl['group_id']}\"$selected>{$dl['group_name']}</option>";
		endforeach;
		
		$block .= "</select></div></div>";
		
		return $block;
	}

	/**
	 * Show Heading Tag
	 *
	 * @param  array   $params          Name/value pairs from the opening tag
	 * @param  string  $tagdata         Chunk of tagdata between field tag pairs
	 * @param  string  $field_data      Currently saved field value
	 * @param  array   $field_settings  The field's settings
	 * @return string  relationship references
	 */
	function heading($params, $tagdata, $field_data, $field_settings)
	{
		return $this->_get_category_data($field_data,'cat_name');
	} 

	/**
	 * Show Description Tag
	 *
	 * @param  array   $params          Name/value pairs from the opening tag
	 * @param  string  $tagdata         Chunk of tagdata between field tag pairs
	 * @param  string  $field_data      Currently saved field value
	 * @param  array   $field_settings  The field's settings
	 * @return string  relationship references
	 */
	function description($params, $tagdata, $field_data, $field_settings)
	{
		return $this->_get_category_data($field_data,'cat_description');
	} 

	/**
	 * Show URL Title Tag
	 *
	 * @param  array   $params          Name/value pairs from the opening tag
	 * @param  string  $tagdata         Chunk of tagdata between field tag pairs
	 * @param  string  $field_data      Currently saved field value
	 * @param  array   $field_settings  The field's settings
	 * @return string  relationship references
	 */
	function url_title($params, $tagdata, $field_data, $field_settings)
	{
		return $this->_get_category_data($field_data,'cat_url_title');
	} 

	/**
	 * Get Category Data
	 *
	 * @param  string  $field_data      Currently saved field value
	 * @param  str     $col  The relevant category table column
	 * @return string  relationship references
	 */
	function _get_category_data($field_data,$col)
	{
		global $DB, $PREFS;
		$r = '';
		$query = $DB->query("SELECT $col FROM exp_categories WHERE cat_id = $field_data LIMIT 1");
		if (isset($query->row[$col]) AND trim($query->row[$col]) != '')
			$r = $query->row[$col];
		return $r;
	}

}


/* End of file ft.sc_category_select.php */
/* Location: ./system/extensions/fieldtypes/sc_category_select/ft.sc_category_select.php */