{*
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
*}

<!-- BEGIN MODULE Block latest comments -->
<div id="last-comments_block" class="block comments_block">
	<h4>{l s='Latest comments' mod='blocklastcomments'}</h4>
	<div class="block_content">
	{if $last_comments !== false}
		<dl class="comments">
		{foreach from=$last_comments item=lastcomment name=myLoop}
			<dt class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}"><a href="{$lastcomment.link}" title="{$lastcomment.name|escape:html:'UTF-8'}">{$lastcomment.name|strip_tags|escape:html:'UTF-8'}</a></dt>
			<dd class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}">{if $display_title && $lastcomment.title}{if $maxchar_title}{$lastcomment.title|escape:html:'UTF-8'|truncate:$maxchar_title:'...'}{else}{$lastcomment.title|escape:html:'UTF-8'}{/if}{elseif $display_title && $display_notitle}{l s='No title'  mod='blocklastcomments'}{/if}<p>{if $maxchar_comment}{$lastcomment.content|escape:html:'UTF-8'|truncate:$maxchar_comment:'...'}{else}{$lastcomment.content|escape:html:'UTF-8'}{/if}</p><span class="customer">{if $lastcomment.customer_name}({$lastcomment.customer_name|escape:html:'UTF-8'}.){/if}</span></dd>
		{/foreach}
		</dl>
	{else}
		<p>{l s='No published comments at this time' mod='blocklastcomments'}</p>
	{/if}
	</div>
</div>
<!-- END MODULE Block latest comments -->
