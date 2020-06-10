<div class="dashboard-commerce-panel dashboard-top-products">
    <table>
        <thead>
            <tr>
                <th><%t SilverCommerce.StockID "StockID" %></th>
                <th><%t SilverCommerce.Title "Title" %></th>
                <th><%t SilverCommerce.QTY "QTY" %></th>
            </tr>
        </thead>
        <tbody>
            <% loop Products %>
                <tr>
                    <td>$StockID</td>
                    <td>$Title</td>
                    <td>$StockLevel</td>
                <tr>
            <% end_loop %>
        </tbody>
    </table>
</div>