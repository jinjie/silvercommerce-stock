<?php

namespace SilverCommerce\Stock\Extensions;

use ProductController;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\LiteralField;

class AddToCartFormExtension extends Extension
{
    public function updateAddToCartForm()
    {
        $fields = $this->owner->Fields();

        $product = Controller::curr();
        if ($product instanceof ProductController) {
            if ($product->Stocked) {
                if ($product->AvailableOutOfStock || !$product->isStockOut()) {
                    $fields->insertBefore(
                        'Quantity',
                        LiteralField::create(
                            'StockLevel',
                            '<p><small>'.$product->StockLevel.' items in stock.</small></p>'
                        )
                    );
                } else {
                    $fields->removeByName('Quantity');
                    $this->owner->setActions(FieldList::create());
                    $fields->push(
                        LiteralField::create(
                            'OutOfStock',
                            '<div class="alert alert-info"><p>Sorry, this item is out of stock</p></div>'
                        )
                    );
                }
            }
        }
    }
}
