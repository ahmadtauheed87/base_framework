<style>
    .main-div {
        padding: 25px;
        text-align: center;
        width: auto;
        height: auto;
    }
    .list-div {
        float: left;
        height: auto;
        width: 300px;
        border: 0.5px solid;
        padding: 15px;
    }
    .detail-div {
        text-align: center;
        height: auto;
        width: 30%;
    }
</style>
<div>
    <div class="main-div">
            <?php //foreach ($product_details as $product) { ?>
                <div class="detail-div">
                    <img src="<?php echo $product_details['image'];?>" height="100" width="100"/><br/>
                    <strong><h3>&#x20B9; <?php echo $product_details['price'];?></h3></strong>
                    <strong><h4><?php echo $product_details['title'];?></h4></strong><br/>
                    <strong>Details : </strong><?php echo $product_details['description']; ?><br/>
                    <button type="button" id="add_cart">Add to Cart</button>
                </div>
            <?php //} ?>
    </div>
</div>

<script type="text/javascript">
        
    $(document).ready(function() {
        $("#add_cart").click(function() {
            var product_id = <?php echo $product_details['id']?>;
            var product_title = "<?php echo $product_details['title']?>";
            var product_price = <?php echo $product_details['price']?>;
            $.ajax({
                type: "POST",
                data: {id: product_id, title: product_title, price: product_price},
                url: "<?php echo base_url() ?>frontend/products/update_cart_value/",
                success: function (data) {
                    $(".show_no").html(data);
                }
            });
        });
    });

</script>