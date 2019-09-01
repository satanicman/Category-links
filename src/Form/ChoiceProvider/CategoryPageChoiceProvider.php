<?php
/**
 * 2007-2018 PrestaShop.
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\LinkList\Form\ChoiceProvider;

use Category;
use Doctrine\DBAL\Connection;

/**
 * Class CMSPageChoiceProvider.
 */
final class CategoryPageChoiceProvider extends AbstractDatabaseChoiceProvider
{
    /**
     * @return array
     */
    public function getChoices()
    {
        $tree = Category::getRootCategory()->recurseLiteCategTree(0, 0, null, null, 'sitemap');
        return $this->buildTree([$tree]);
    }

    public function buildTree(array $categories, int $level = 0) {
        if (empty($categories)) {
            return null;
        }

        $result = [];
        foreach ($categories as $category) {
            preg_match('/\d+$/', $category['id'], $id);
            $result[str_repeat(' - ', $level * 2) . $category['label']] = (int)$id[0];
            if (!empty($category['children'])) {
                $children = $this->buildTree($category['children'] , $level + 1);

                if ($children !== null) {
                    $result += $children;
                }
            }
        }

        return $result;
    }
}
