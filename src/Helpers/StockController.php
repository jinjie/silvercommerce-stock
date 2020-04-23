<?php

namespace SilverCommerce\Stock\Helpers;

use SilverCommerce\CatalogueAdmin\Model\CatalogueProduct;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class StockController
{
    use Injectable, Configurable;

    /**
     * set whether or not products can be purchased when they have no stock
     * default to false
     *
     * @var boolean
     */
    private static $products_available_nostock = false;

    /**
     * Send email alerts when stock levels are low
     *
     * @var boolean
     */
    protected $email_alerts = false;

    /**
     * Handle the reduction of of a products stock level
     *
     * @param CatalogueProduct $item
     * @param Int $quantity
     * @return void
     */
    public static function reduceStock($item, $quantity)
    {
        $alerts = self::config()->email_alerts;
        $item->StockLevel -= $quantity;
        $item->write();

        if ($item->StockLevel < 0 && $alerts == true) {
            self::alertOversold($item);
        }

        if ($item->StockLevel < $item->LowStock && $alerts == true) {
            self::alertLowStock($item);
        }
    }

    /** ### TO DO ### **/ #################################################################
    public static function alertOversold($item)
    {
        // send email alerting stocklevel mismatch
        return null;
    }

    public static function alertLowStock($item)
    {
        // send email alerting low stock level
        return null;
    }
}
