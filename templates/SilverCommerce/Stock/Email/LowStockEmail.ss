<% include \\SilverCommerce\\OrdersAdmin\\Email\\Includes\\EmailHead %>

<h1><%t SilverCommerce\Stock\Helpers\StockController.LowStockTitle 'Product {title} is low in stock' title=$Product.Title %></h1>

<p><%t SilverCommerce\Stock\Helpers\StockController.LowStockLevel '{title} has only {level} in stock' title=$Product.Title level=$Product.StockLevel %></p>

<% include \\SilverCommerce\\OrdersAdmin\\Email\\Includes\\EmailFooter %>
