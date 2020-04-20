<?php

namespace SilverCommerce\Stock\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\ValidationException;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use SilverCommerce\Stock\Control\StockController;

class LineItemExtension extends DataExtension
{
    public function onBeforeWrite()
    {
        $match = $this->owner->FindStockItem();
        $parent = $this->owner->Parent();
        if ($match && $match->Stocked && $parent instanceof Invoice && $parent->isPaid()) {
            $qty = 0;
            $old = 0;
            if (!$this->owner->ID) {
                $qty = $this->owner->Quantity;
            } elseif ($this->owner->isChanged('Quantity')) {
                $changed = $this->owner->getChangedFields()['Quantity'];
                $old = $changed['before'];
                $new = $changed['after'];
                $qty = $new - $old;
            }
            if ($qty > 0 && $this->owner->checkStockLevel($qty) < 0) {
                $this->owner->Quantity =  $old + $match->StockLevel;
                throw new ValidationException(_t(
                    "ShoppingCart.NotEnoughStock",
                    "There are not enough '{title}' in stock",
                    ['title' => $match->Title]
                ));
            }
            StockController::reduceStock($match, $qty);
        }
    }
}
