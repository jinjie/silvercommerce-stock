<% include \\SilverCommerce\\OrdersAdmin\\Email\\Includes\\EmailHead %>

<h1><%t SilverCommerce\Stock\Helpers\StockController.OverSoldSubject "Product Over Sold"%></h1>

<p><%t SilverCommerce\Stock\Helpers\StockController.OverSoldTitle 'Product "{title}" is oversold' title=$Product.Title %></p>

<% include \\SilverCommerce\\OrdersAdmin\\Email\\Includes\\EmailFooter %>
