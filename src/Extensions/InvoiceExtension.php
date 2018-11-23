<?php

namespace SilverCommerce\Stock\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\ValidationException;
use SilverCommerce\Stock\Control\StockController;

class InvoiceExtension extends DataExtension
{
    public function onBeforeWrite()
    {
        if ($this->owner->isChanged('Status')) {
            $statuses = $this->owner->config()->get("paid_statuses");
            $changed = $this->owner->getChangedFields()['Status'];
            $old = $changed['before'];
            $new = $changed['after'];
            if (!in_array($old, $statuses) && in_array($new, $statuses)) {
                $items = $this->owner->Items();
                foreach ($items as $item) {
                    $match = $item->FindStockItem();
                    if ($match && $match->Stocked) {
                        if ($item->checkStockLevel($item->Quantity) < 0) {
                            throw new ValidationException(_t(
                                "ShoppingCart.NotEnoughStock",
                                "There are not enough '{title}' in stock",
                                ['title' => $match->Title]
                            ));
                        }
                        StockController::reduceStock($match, $item->Quantity);
                    }
                }
            }
        }
    }
}