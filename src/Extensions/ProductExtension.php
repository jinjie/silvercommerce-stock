<?php

namespace SilverCommerce\Stock\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;

class ProductExtension extends DataExtension
{
    private static $db = [
        'Stocked' => 'Boolean',
        'StockLevel' => 'Int',
        'LowStock' => 'Int'
    ];

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
                )->setRightTitle('used to determine when to highlight the stock level as low')
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