<?php

namespace SilverCommerce\Stock\Control;

use SilverStripe\GraphQL\Controller;
use SilverCommerce\CatalogueAdmin\Model\CatalogueProduct;

class StockController extends Controller
{
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
    public function reduceStock($item, $quantity) 
    {
        $item->StockLevel -= $quantity;
        if ($item->StockLevel < 0) {
            $item->StockLevel = 0;
            $this->alertOversold($item);
        }
        $item->write();

        if ($item->StockLevel < $$item->LowStock && $this->email_alerts) {
            $this->alertLowStock($item);
        }
    }

    public function alertOversold($item) 
    {
        // send email alerting stocklevel mismatch
    }

    public function alertLowStock($item) 
    {
        // send email alerting low stock level
    }
}