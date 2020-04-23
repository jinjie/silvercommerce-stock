<?php

namespace SilverCommerce\Stock\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverCommerce\Stock\Control\StockController;

class CatalogueProductExtension extends DataExtension
{
    private static $db = [
        'Stocked' => 'Boolean',
        'StockLevel' => 'Int',
        'LowStock' => 'Int',
        'AvailableOutOfStock' => 'Boolean'
    ];

    private static $field_labels = [
        'Stocked' => 'Track Stock?',
        'StockLevel' => 'Current Stock',
        'LowStock' => 'Low Stock Limit',
        'AvailableOutOfStock' => 'Can still be sold when out of stock?'
    ];

    public function populateDefaults()
    {
        $this->owner->AvailableOutOfStock = StockController::config()->products_available_nostock;
        parent::populateDefaults();
    }

    public function updateSummaryFields(&$fields)
    {
        $fields['Stocked'] = $this->getOwner()->fieldLabel('Stocked');
        $fields['StockLevel'] = $this->getOwner()->fieldLabel('StockLevel');
    }

    public function updateExportFields(&$fields)
    {
        $fields['Stocked'] = 'Stocked';
        $fields['StockLevel'] = 'StockLevel';
        $fields['LowStock'] = 'LowStock';
        $fields['AvailableOutOfStock'] = 'AvailableOutOfStock';
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Settings',
            [
                $fields->dataFieldByName('Stocked'),
                $fields->dataFieldByName('StockLevel'),
                $fields->dataFieldByName('LowStock'),
                $fields->dataFieldByName('AvailableOutOfStock')
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
