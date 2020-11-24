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
                            $product->renderWIth('SilverCommerce\Stock\Includes\InStockNotice')
                        )
                    );
                } else {
                    $fields->removeByName('Quantity');
                    $this->owner->setActions(FieldList::create());
                    $fields->push(
                        LiteralField::create(
                            'OutOfStock',
                            $product->renderWIth('SilverCommerce\Stock\Includes\OutOfStockNotice')
                        )
                    );
                }
            }
        }
    }
}
