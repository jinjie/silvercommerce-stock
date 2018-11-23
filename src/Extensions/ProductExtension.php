<?php

namespace SilverCommerce\Stock\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\CheckboxField;
use SilverCommerce\Stock\Control\StockController;

class ProductExtension extends DataExtension
{
    private static $db = [
        'Stocked' => 'Boolean',
        'StockLevel' => 'Int',
        'LowStock' => 'Int',
        'AvailableOutOfStock' => 'Boolean'
    ];

    public function populateDefaults() 
    {
        $this->owner->AvailableOutOfStock = StockController::config()->products_available_nostock;
        parent::populateDefaults();
    }

    public function updateCMSFields(FieldList $fields) 
    {
        $fields->addFieldsToTab(
            'Root.Settings',
            [
                CheckboxField::create(
                    'Stocked'
                ),
                NumericField::create(
                    'StockLevel'
                ),
                NumericField::create(
                    'LowStock',
                    'Low Stock Level'
                )->setRightTitle('used to determine when to highlight the stock level as low'),
                CheckboxField::create(
                    'AvailableOutOfStock',
                    'This product is still available when out of stock'
                )
            ]
        );
    }

    /**
     * check if the stock level is below the low stock level
     *
     * @return boolean
     */
    public function isStockLow()
    {
        if ($this->owner->Stocked) {
            return $stock = $this->owner->StockLevel < $this->owner->LowStock ? true : false;
        }
        return false;
    }

    /**
     * check if the prodict is out of stock
     *
     * @return boolean
     */
    public function isStockOut()
    {
        if ($this->owner->Stocked) {
            return $stock = $this->owner->StockLevel < 1 ? true: false;
        }
        return false;
    }

    /**
     * Check if the product has enough stock
     *
     * @param integer $qty
     * @return boolean
     */    
    public function checkStockLevel($qty = 0) 
    {
        if ($this->owner->Stocked) {
            return $this->owner->StockLevel - $qty;
        } 
        return true;
    }
}