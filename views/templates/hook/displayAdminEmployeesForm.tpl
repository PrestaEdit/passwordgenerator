{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @version  Release: $Revision: 17018 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
	function gencode(size)
	{
		$("input[name=passwd]").val('');
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for (var i = 1; i <= size; ++i)
			$("input[name=passwd]").val($("input[name=passwd]").val() + chars.charAt(Math.floor(Math.random() * chars.length)));
		$("input[name=passwd]").next().next().text($("input[name=passwd]").val());
	}
	{literal}
	$( document ).ready(function() {
		if (!$("input[name=sendMail").length) {
    		$("input[name=passwd]").next().append('&nbsp; <a href="javascript:gencode(8);" class="button">{/literal}{l s="Generate code" mod="passwordgenerator"}{literal}</a>');
    		$("input[name=passwd]").next().next().after('<input type="checkbox" value="1" name="sendMail" /> {/literal}{l s="Send by mail" mod="passwordgenerator"}{literal}');
    	}
	});
	{/literal}
</script>