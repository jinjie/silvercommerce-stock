<?php

namespace SilverCommerce\Stock\Helpers;

use SilverStripe\Core\Extensible;
use SilverStripe\Control\Email\Email;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverCommerce\CatalogueAdmin\Model\CatalogueProduct;

class StockController
{
    use Injectable, Configurable, Extensible;

    /**
     * Default setting for if products are out of stock
     *
     * @var boolean
     */
    private static $products_available_nostock = false;

    /**
     * List of addresses to send email alerts to when stock levels are low
     *
     * @var array
     */
    private static $send_alerts_to = [];

    /**
     * Handle the reduction of of a products stock level
     *
     * @param CatalogueProduct $item
     * @param Int $quantity
     *
     * @return void
     */
    public static function reduceStock(CatalogueProduct $item, $quantity)
    {
        $item->StockLevel -= $quantity;
        $item->write();

        // if alerts are disabled, finish
        if (!self::sendAlertEmail()) {
            return;
        }

        if ($item->isStockOver()) {
            self::alertOversold($item);
        }

        if ($item->isStockOut()) {
            self::alertOutOfStock($item);
        }

        if ($item->isStockLow()) {
            self::alertLowStock($item);
        }
    }

    /**
     * Should we send stock alert emails to users?
     *
     * @return boolean
     */
    public static function sendAlertEmail()
    {
        $alerts = self::config()->send_alerts_to;

        return count($alerts) > 0;
    }

    /**
     * Send an oversold alert email when negative stock is set
     *
     * @param CatalogueProduct $item
     *
     * @return boolean
     */
    public static function alertOversold(CatalogueProduct $item)
    {
        return self::sendEmails(
            'SilverCommerce\\Stock\\Email\\OverSoldEmail',
            _t(__CLASS__ . '.OverSoldSubject', "Product Over Sold"),
            $item
        );
    }

    /**
     * Send an out of stock alert email when stock is set to 0
     *
     * @param CatalogueProduct $item
     *
     * @return boolean
     */
    public static function alertOutOfStock(CatalogueProduct $item)
    {
        return self::sendEmails(
            'SilverCommerce\\Stock\\Email\\OutOfStockEmail',
            _t(__CLASS__ . '.OutOfStockSubject', "Product Out Of Stock"),
            $item
        );
    }

    /**
     * Send a low stock email when stock threshhold is reached
     *
     * @param CatalogueProduct $item
     *
     * @return boolean
     */
    public static function alertLowStock(CatalogueProduct $item)
    {
        return self::sendEmails(
            'SilverCommerce\\Stock\\Email\\LowStockEmail',
            _t(__CLASS__ . '.LowStockSubject', "Product Low In Stock"),
            $item
        );
    }

    /**
     * Send alert emails to the configured email addresses
     *
     * @param string $template  Name of the email template to use
     * @param string $subject   The email subject to use
     * @param CatallogueProduct The current product with low stock
     *
     * @return boolean
     */
    protected static function sendEmails(string $template, string $subject, CatalogueProduct $item)
    {
        $emails = self::config()->send_alerts_to;
        $success = true;

        foreach ($emails as $address) {
            $email = Email::create()
                ->setHTMLTemplate($template)
                ->setData(['Product' => $item])
                ->setTo($address)
                ->setSubject($subject);
    
            $sent = $email->send();

            if (!$sent && $success) {
                $success = false;
            }
        }

        return $success;
    }
}
