<?php
/*
 *      Block Latest Comments for Prestashop
 *      @package Block Latest Comments for Prestashop
 *      @author José A. Cidre Bardelás
 *      @copyright Copyright (C) 2011 José A. Cidre Bardelás. All rights reserved
 *      @license GNU/GPL v3 or later
 *      
 *      Contact me at informatico@cidrebardelas.com
 *      
 *      This file is part of Block Latest Comments for Prestashop.
 *      
 *          Block Latest Comments for Prestashop is free software: you can redistribute it and/or modify
 *          it under the terms of the GNU General Public License as published by
 *          the Free Software Foundation, either version 3 of the License, or
 *          (at your option) any later version.
 *      
 *          Block Latest Comments for Prestashop is distributed in the hope that it will be useful,
 *          but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *          GNU General Public License for more details.
 *      
 *          You should have received a copy of the GNU General Public License
 *          along with Block Latest Comments for Prestashop.  If not, see <http://www.gnu.org/licenses/>.
 */
if(!defined('_CAN_LOAD_FILES_')) 
		exit;

class BlockLastComments extends Module {

		public function __construct() {
				$this->name = 'blocklastcomments';
				$this->tab = 'front_office_features';
				$this->version = 0.5;
				$this->author = 'José A. Cidre Bardelás';
				$this->need_instance = 0;
				parent::__construct();
				$this->displayName = $this->l('Latest comments block');
				$this->description = $this->l('Displays a block with the latest published comments.');
		}

		public function install() {
				if(parent::install() == false OR $this->registerHook('home') == false OR $this->registerHook('header') == false OR Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_NBR', 5) == false) 
						return false;
				return true;
		}

		public function getContent() {
				$output = '<h2>'.$this->displayName.'</h2>';
				if(Tools::isSubmit('submitBlockLastComments')) {
						if(!$commentNbr = Tools::getValue('commentNbr') OR empty($commentNbr)) 
								$output .= '<div class="alert error">'.$this->l('Please fill in the "latest comments displayed" field.').'</div>';
						elseif((int)($commentNbr) == 0) 
								$output .= '<div class="alert error">'.$this->l('Invalid number.').'</div>';
						else {
								Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_DISPLAY', (int)(Tools::getValue('always_display')));
								Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_DTITLE', (int)(Tools::getValue('display_title')));
								Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_DNOTITLE', (int)(Tools::getValue('display_notitle')));
								Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_NBR', (int)($commentNbr));
								Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_TITMAXCHAR', (!(int)(Tools::getValue('commenttitlecharMax')) || (int)(Tools::getValue('commenttitlecharMax')) >= 4 ? (int)(Tools::getValue('commenttitlecharMax')) : 4));
								Configuration::updateValue('PS_BLOCK_LASTCOMMENTS_MAXCHAR', (!(int)(Tools::getValue('commentcharMax')) || (int)(Tools::getValue('commentcharMax')) >= 4 ? (int)(Tools::getValue('commentcharMax')) : 4));
								$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';
						}
				}
				return $output.$this->displayForm();
		}

		public function displayForm() {
				$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Latest comments displayed').'</label>
					<div class="margin-form">
						<input type="text" name="commentNbr" value="'.(int)(Configuration::get('PS_BLOCK_LASTCOMMENTS_NBR')).'" />
						<p class="clear">'.$this->l('Set the number of latest comments to be displayed in this block. Default value is 5').'</p>
					</div>
				<label>'.$this->l('Always display block').'</label>
					<div class="margin-form">
						<input type="radio" name="always_display" id="display_on" value="1" '.(Tools::getValue('always_display', Configuration::get('PS_BLOCK_LASTCOMMENTS_DISPLAY')) ? 'checked="checked" ' : '').'/>
						<label class="t" for="display_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
						<input type="radio" name="always_display" id="display_off" value="0" '.(!Tools::getValue('always_display', Configuration::get('PS_BLOCK_LASTCOMMENTS_DISPLAY')) ? 'checked="checked" ' : '').'/>
						<label class="t" for="display_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
						<p class="clear">'.$this->l('Show the block even if no comments are available.').'</p>
					</div>
				<label>'.$this->l('Display comments title').'</label>
					<div class="margin-form">
						<input type="radio" name="display_title" id="display_title_on" value="1" '.(Tools::getValue('display_title', Configuration::get('PS_BLOCK_LASTCOMMENTS_DTITLE')) ? 'checked="checked" ' : '').'/>
						<label class="t" for="display_title_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
						<input type="radio" name="display_title" id="display_title_off" value="0" '.(!Tools::getValue('display_title', Configuration::get('PS_BLOCK_LASTCOMMENTS_DTITLE')) ? 'checked="checked" ' : '').'/>
						<label class="t" for="display_title_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
						<p class="clear">'.$this->l('Show comments title if available.').'</p>
					</div>
				<label>'.$this->l('Display \'no title\' message if comments title not available').'</label>
					<div class="margin-form">
						<input type="radio" name="display_notitle" id="display_notitle_on" value="1" '.(Tools::getValue('display_notitle', Configuration::get('PS_BLOCK_LASTCOMMENTS_DNOTITLE')) ? 'checked="checked" ' : '').'/>
						<label class="t" for="display_notitle_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
						<input type="radio" name="display_notitle" id="display_notitle_off" value="0" '.(!Tools::getValue('display_notitle', Configuration::get('PS_BLOCK_LASTCOMMENTS_DNOTITLE')) ? 'checked="checked" ' : '').'/>
						<label class="t" for="display_notitle_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
						<p class="clear">'.$this->l('Show \'no title\' message if above setting is enabled and comments title isn\'t available.').'</p>
					</div>
				<label>'.$this->l('Max characters in title').'</label>
					<div class="margin-form">
						<input type="text" name="commenttitlecharMax" size="4" value="'.(int)(Configuration::get('PS_BLOCK_LASTCOMMENTS_TITMAXCHAR')).'" />
						<p class="clear">'.$this->l('Set the max number of characters for titles (including three dots at end, i.e min value will be 4). If you set this to 0, no title trim will be applied').'</p>
					</div>
				<label>'.$this->l('Max characters in comment').'</label>
					<div class="margin-form">
						<input type="text" name="commentcharMax" size="4" value="'.(int)(Configuration::get('PS_BLOCK_LASTCOMMENTS_MAXCHAR')).'" />
						<p class="clear">'.$this->l('Set the max number of characters for comments (including three dots at end, i.e min value will be 4). If you set this to 0, no comment trim will be applied').'</p>
					</div>
					<center><input type="submit" name="submitBlockLastComments" value="'.$this->l('Save').'" class="button" /></center>
				</fieldset>
			</form>';
				return $output;
		}

		public function hookHome($params) {
				global $smarty, $cookie;
				$validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');
				$lastComments = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT pc.`id_product_comment`, pc.`id_product`, IF(c.id_customer, CONCAT(c.`firstname`, \' \',  LEFT(c.`lastname`, 1)), pc.customer_name) AS customer_name, pc.`title`, pc.`content`, pc.`grade`, pc.`date_add`, pl.`name`, pl.`link_rewrite`, p.`id_category_default`, p.`ean13`
		FROM `'._DB_PREFIX_.'product_comment` AS pc
		LEFT JOIN `'._DB_PREFIX_.'customer` AS c ON (c.`id_customer` = pc.`id_customer`) 
		LEFT JOIN `'._DB_PREFIX_.'product_lang` AS pl ON (pl.`id_product` = pc.`id_product`) 
		LEFT JOIN `'._DB_PREFIX_.'product` AS p ON (p.`id_product` = pc.`id_product`) 
		WHERE pc.`validate` = '.(int)($validate).'
		AND pl.`id_lang` = '.(int)($cookie->id_lang).'
		ORDER BY pc.`date_add` DESC LIMIT 0,'.(int)(Configuration::get('PS_BLOCK_LASTCOMMENTS_NBR')));
				if(!$lastComments AND !Configuration::get('PS_BLOCK_LASTCOMMENTS_DISPLAY')) 
						return;
				for($i = 0;$i < count($lastComments);$i++) {
						$link = new Link();
						$lastComments[$i]['category'] = Category::getLinkRewrite((int) $lastComments[$i]['id_category_default'], (int)($cookie->id_lang));
						$lastComments[$i]['link'] = $link->getProductLink((int) $lastComments[$i]['id_product'], $lastComments[$i]['link_rewrite'], $lastComments[$i]['category'], $lastComments[$i]['ean13']);
				}
				$smarty->assign(array('last_comments'=>$lastComments, 'display_title'=>(int) Configuration::get('PS_BLOCK_LASTCOMMENTS_DTITLE'), 'display_notitle'=>(int) Configuration::get('PS_BLOCK_LASTCOMMENTS_DNOTITLE'), 'maxchar_title'=>(int) Configuration::get('PS_BLOCK_LASTCOMMENTS_TITMAXCHAR'), 'maxchar_comment'=>(int) Configuration::get('PS_BLOCK_LASTCOMMENTS_MAXCHAR')));
				return $this->display(__FILE__, 'blocklastcomments.tpl');
		}

		public function hookRightColumn($params) {
				return $this->hookHome($params);
		}

		public function hookLeftColumn($params) {
				return $this->hookHome($params);
		}

		public function hookHeader($params) {
				Tools::addCSS(($this->_path).'blocklastcomments.css', 'all');
		}
}
