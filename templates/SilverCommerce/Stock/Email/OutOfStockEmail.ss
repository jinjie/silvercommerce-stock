<% include \\SilverCommerce\\OrdersAdmin\\Email\\Includes\\EmailHead %>

<h1><%t SilverCommerce\Stock\Helpers\StockController.OutOfStockSubject "Product Out Of Stock"%></h1>

<p><%t SilverCommerce\Stock\Helpers\StockController.OutOfStockTitle 'Product "{title}" is out of stock' title=$Product.Title %></p>

<% include \\SilverCommerce\\OrdersAdmin\\Email\\Includes\\EmailFooter %>
