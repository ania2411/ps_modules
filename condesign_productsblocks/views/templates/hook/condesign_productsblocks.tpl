{**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{if $blocks}
  <section class="con-pb">
    <div class="row">
      {foreach from=$blocks item=$block}
        <div class="col-lg-4">
          <div class="con-pb__block">
            <div class="con-pb__block-header">
              <div class="con-pb__block-category">{$block.category_name|truncate:15:'...'}</div>
            </div>
            <div class="con-pb__products">
              {foreach from=$block.products item="product"}
                <div>
                  {include file="module:condesign_productsblocks/views/templates/hook/_product.tpl" product=$product}
                </div>
              {/foreach}
            </div>
            <div class="con-pb__btn-wrapper">
              <a class="btn con-pb__btn" href="{$block.link}">{l s='more from this category' d='Modules.Condesignproductsblocks.Productblocks'}</a>
            </div>
          </div>
        </div>
      {/foreach}
    </div>
  </section>
{/if}

