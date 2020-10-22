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
</style>
<div>
    <h2>Cart Details</h2>
    <div class="main-div">
        <table>
            <tr>
                <td>Product</td>
                <td>Name</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Action</td>
            </tr>
            <?php foreach ($cart_details as $cart_detail) { ?>
                <?php   $json = file_get_contents('https://fakestoreapi.com/products/'.$cart_detail['cart_product_id']);
                        $product_details = json_decode($json,TRUE); ?>
                <tr>
                    <td class="td-table"><img src="<?php echo $product_details['image'];?>" height="100" width="100"/></td>
                    <td class="td-table"><?php echo $cart_detail['cart_product_title']?></td>
                    <td class="td-table"><?php echo $cart_detail['cart_product_quantity']?></td>
                    <td class="td-table"><?php echo $cart_detail['cart_product_rate']?></td>
                    <td class="td-table"><button class="remove" data-totalrate="<?php echo $cart_detail['cart_product_rate']?>" data-quantity="<?php echo $cart_detail['cart_product_quantity']?>" rel="<?php echo $cart_detail['cart_id']?>" value="Remove">Remove</button></td>
                </tr>
            <?php } ?>
        </table>
        <div class="clearfix"></div>
        <button onclick="location.href='<?php echo base_url()?>frontend/products/checkout'">Checkout</button>
    </div>
</div>

<script type="text/javascript">
        
    $(document).ready(function() {
        
        $(".remove").click(function() {
            var id = $(this).attr('rel');
            var price = $(this).attr('data-totalrate');
            var quantity = $(this).attr('data-quantity')
            
            $.ajax({
                type: "POST",
                data: {id: id, price: price, quantity: quantity},
                url: "<?php echo base_url() ?>frontend/products/remove_cart_value/",
                success: function (data) {
                    $(".show_no").html(data);
                    location.reload();
                }
            });
        });
    });

</script>