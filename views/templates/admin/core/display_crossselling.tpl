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
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<style type="text/css">
    #prestaedit_crossselling a, #prestaedit_crossselling a:hover {
      color: #343943;
      text-decoration: none;
    }
    #prestaedit_crossselling .product_name {
      border-bottom: 1px solid #e4e7e9;
      margin-bottom: 10px;
      color: #343943;
      font-size: 17px;
      padding-bottom: 5px;
    }
    #prestaedit_crossselling .product_price {
      font-size: 14px;
    }
    #prestaedit_crossselling .product_description {
      font-size: 12px;
      margin-top: 10px;
      text-align: justify;
      font-style: italic;
      color: rgba(0,0,0,0.5);
      padding-left: 15px;
    }
    #prestaedit_crossselling i.icon-star, #prestaedit_crossselling i.icon-star-half-empty {
      color: #ffdc16;
    }
    #prestaedit_crossselling .carousel-indicators {
      position: relative;
      bottom: 0;
      padding-bottom: 10px;
    }
    #prestaedit_crossselling .carousel-indicators li {
      border-color: rgba(0,0,0,0.5);
    }
    #prestaedit_crossselling .carousel-indicators li.active {
      background-color: rgba(0,0,0,0.5);
      border-color: #ffffff;
    }
</style>

<div class="panel">
  <div class="panel-heading">
    <i class="icon-puzzle-piece"></i> {l s='Others modules by the same author' mod='passwordgenerator'}
  </div>
  <div class="form-wrapper">
    <div id="prestaedit_crossselling" class="carousel slide" data-ride="carousel">  
        <!-- Wrapper for carousel items -->
        <div class="carousel-inner">
            <div class="item active">
              {foreach from=$modules item=module name=prestaedit_crossselling}
                {if $module->name != $module_name}
                  {counter name='foreachIterationCards' assign='counter'}
                  <div class="col-lg-4 col-md-6 col-sm-12 large-4 medium-6 small-12 columns">
                      <div class="col-lg-2 text-center">
                        <img alt="{$module->displayName|escape:'htmlall':'UTF-8'}" height="57" width="57" src="{$module->img|escape:'htmlall':'UTF-8'}" style="display: inline-block;">
                      </div>
                      <div class="col-lg-10">
                        <div class="col-lg-12 product_name">
                          <a href="{$module->url|escape:'htmlall':'UTF-8'}" title="{$module->displayName|escape:'htmlall':'UTF-8'}">
                            {$module->displayName|escape:'htmlall':'UTF-8'}
                          </a>
                        </div>
                        <div class="col-lg-6">
                        {if $module->nbRates > 0}
                          {if $module->avgRate >= '0.0' && $module->avgRate < '0.5'}
                            <i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '0.5' && $module->avgRate < '1.0'}
                            <i class="icon-star-half-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '1.0' && $module->avgRate < '1.5'}
                            <i class="icon-star"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '1.5' && $module->avgRate < '2.0'}
                            <i class="icon-star"></i><i class="icon-star-half-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '2.0' && $module->avgRate < '2.5'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '2.5' && $module->avgRate < '3.0'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-half-empty"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '3.0' && $module->avgRate < '3.5'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '3.5' && $module->avgRate < '4.0'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-half-empty"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '4.0' && $module->avgRate < '4.5'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-empty"></i>
                          {/if}
                          {if $module->avgRate >= '4.5' && $module->avgRate < '5.0'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-half-empty"></i>
                          {/if}
                          {if $module->avgRate eq '5.0'}
                            <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                          {/if}
                          ({$module->nbRates|intval})
                        {/if}
                        </div>
                        <div class="col-lg-6 pull-right text-right product_price">
                          {displayPrice price=$module->price->EUR} {l s='HT' mod='passwordgenerator'}
                        </div>
                      </div>                      
                      <div class="col-lg-12 product_description">
                        {$module->description|escape:'htmlall':'UTF-8'}
                      </div>
                  </div>
                  {if $counter eq 3}
                    </div>
                    <div class="item">
                  {/if}
                {/if}
              {/foreach}
            </div>
        </div>
        <div class="col-lg-12">
          <!-- Carousel indicators -->
          {assign var='carousel_indicator' value=0}
          <ol class="carousel-indicators">
            <li data-target="#prestaedit_crossselling" data-slide-to="0" class="active"></li>
            {foreach from=$modules item=module name=prestaedit_crossselling}
              {if $module->name != $module_name}
                {counter assign='iterationsCounter'}
                {if $iterationsCounter eq 3}
                  {assign var='carousel_indicator' value=$carousel_indicator+1}
                  <li data-target="#prestaedit_crossselling" data-slide-to="{$carousel_indicator|intval}"></li>
                {/if}
              {/if}
            {/foreach}
          </ol>
        </div>
    </div>
  </div>
</div>