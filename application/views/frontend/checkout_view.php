<style>
    .main-div {
        padding: 25px;
        text-align: center;
        width: auto;
        height: auto;
    }
    .td-table {
        width: 25%;
        padding: 25px;
        text-align: center;
        border: 1px solid;
    }
    .payment-mode {
        float: left;
        padding: 20px;
    }
</style>
<div>
    <h2>Confirm Order</h2>
    <div class="main-div">
        <table>
            <tr>
                <td>Product</td>
                <td>Quantity</td>
                <td>Rate</td>
            </tr>
            <?php $total = 0;
                foreach ($cart_details as $cart_detail) { ?>
                <?php   $json = file_get_contents('https://fakestoreapi.com/products/'.$cart_detail['cart_product_id']);
                        $product_details = json_decode($json,TRUE); ?>
                <tr>
                    <td class="td-table"><img src="<?php echo $product_details['image'];?>" height="100" width="100"/></td>
                    <td class="td-table"><?php echo $cart_detail['cart_product_quantity']?></td>
                    <td class="td-table"><?php echo $cart_detail['cart_product_rate']?></td>
                </tr>
            <?php $total = $total + $cart_detail['cart_product_rate']; } ?>
            <tr>
                <td colspan=2><strong>Total</strong></td>
                <td><strong><?php echo $total; ?></strong></td>
            </tr>
        </table>
        
        <div class="payment-mode">
            <label>Payment Method</label></br>
            <input type="radio" checked /> Cash on Delivery
        </div>
    </div>
    <div class="clearfix"></div>
    <button onclick="location.href='<?php echo base_url()?>frontend/products/thankyou'">Confirm Order</button>
</div>