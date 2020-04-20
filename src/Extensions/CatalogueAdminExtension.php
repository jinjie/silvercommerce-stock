<?php

namespace SilverCommerce\Stock\Extensions;

use Product;
use SilverStripe\Core\Extension;
use SilverStripe\GridFieldAddOns\GridFieldRecordHighlighter;

class CatalogueAdminExtension extends Extension
{
    public function updateEditForm($form)
    {
        $fields = $form->Fields();
        $grid = $fields
        ->fieldByName($this->sanitiseClassName($this->owner->modelClass));
        if ($this->owner->modelClass == Product::class && $grid) {
            $config = $grid->getConfig();
            $alerts = [
                'isStockOut' => [
                    'comparator' => 'equal',
                    'patterns' => [
                        true => [
                            'status' => 'alert',
                            'message' => 'Out of stock'
                        ]
                    ]
                ],
                'isStockLow' => [
                    'comparator' => 'equal',
                    'patterns' => [
                        true => [
                            'status' => 'info',
                            'message' => 'Low stock level'
                        ]
                    ]
                ]
            ];
            $config->addComponent(new GridFieldRecordHighlighter($alerts));
        }
    }
    /**
     * Sanitise a model class' name for inclusion in a link
     *
     * @param string $class
     * @return string
     */
    protected function sanitiseClassName($class)
    {
        return str_replace('\\', '-', $class);
    }
}
