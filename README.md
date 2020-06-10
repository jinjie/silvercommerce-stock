# SilverCommerce stock tracking

Simple module for tracking stock levels for SilverCommerce products

## Install

Install this module using composer:

    composer require silvercommerce/stock

## Usage

Once installed this module adds some more fields to a `CatalogueProduct` (which can be changed
via a Product's "Settings" tab):

`Stocked`: Is this product stocked (should SilverCommerce track stock levels against it)?

`StockLevel`: What is the current stock level for the current product.

`LowStock`: What is the low stock level for this product? If the stock level gets below this level,
the catalogue admin gridfield will display a notification.

`AvailableOutOfStock`: Can this product be purchased when out of stock?

## Email Notifications

This module allows email notifications to be sent when stock levels get low, are out of stock or
are understocked (less than 0).

To enable email notifications, you have to add the email addresses you want to recieve notifications
to the `StockController` via config.yml, eg:

    SilverCommerce\Stock\Helpers\StockController:
        send_alerts_to:
            - email1@domain.com
            - email2@domain.com
