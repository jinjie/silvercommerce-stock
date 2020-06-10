<?php

namespace SilverCommerce\Stock\Dashboard;

use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Injector\Injector;
use UncleCheese\Dashboard\DashboardPanel;
use UncleCheese\Dashboard\DashboardPanelAction;
use SilverCommerce\CatalogueAdmin\Admin\CatalogueAdmin;
use SilverCommerce\CatalogueAdmin\Model\CatalogueProduct;

if (!class_exists(DashboardPanel::class)) {
    return;
}

/**
 * Add low stock dashboard panel, if dashboard module is installed
 */
class LowStockPanel extends DashboardPanel
{
    private static $table_name = 'DashboardLowStockPanel';

    private static $db = array (
        'Count' => 'Int'
    );

	private static $defaults = array (
		'Count' => "5",
		'PanelSize' => "small"
	);

    private static $icon = "silvercommerce/dashboard: client/dist/images/warning.png";

    public function getLabel()
    {
        return _t(__CLASS__ . '.LowStock', 'Low Stock');
    }

    public function getDescription()
    {
        return _t(__CLASS__ . '.LowStockDescription', 'List of low stock products.');
    }

    public function PanelHolder()
    {
        Requirements::css("silvercommerce/dashboard: client/dist/css/dashboard.css");
        return parent::PanelHolder();
    }

    /**
     * Get a link to the catalogue admin
     *
     * @return string
     */
    public function CatalogueLink()
    {
        return Injector::inst()->create(CatalogueAdmin::class)->Link();
    }

    public function getConfiguration()
    {
        $fields = parent::getConfiguration();

        $fields->push(TextField::create(
            "Count",
            "Number of products to show"
        ));

        return $fields;
    }

    /**
     * Add view all button to actions
     *
     * @return ArrayList
     */
    public function getSecondaryActions()
    {
		$actions = parent::getSecondaryActions();
		$actions->push(DashboardPanelAction::create(
            $this->CatalogueLink(),
            _t("SilverCommerce.ViewAll", "View All")
        ));
			
		return $actions;
	}

    /**
     * Get a list of products to render in the template
     *
     * @return DataList
     */
    public function Products()
    {
        $count = ($this->Count) ? $this->Count : 7;
        $products = CatalogueProduct::get();
        
        return $products->filterByCallback(function($item, $list) {
                return $item->isStockLow();
            })
            ->sort("StockLevel", "ASC")
            ->limit($count);
    }
}