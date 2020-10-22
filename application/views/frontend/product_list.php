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
    .td-table {
        width: 25%;
        padding: 25px;
        text-align: center;
        border: 1px solid;
    }
</style>
<div>
    <div class="main-div">
        <table>
            <?php $count = 0;
                foreach ($products as $product) { 
                    if(($count % 4) == 0) {
                    ?>
                    <tr>
                <?php } ?>
                    <td class="td-table">
                        <a href="<?php echo base_url()."frontend/products/product_detail/".$product['id']?>">
                            <img src="<?php echo $product['image'];?>" height="100" width="100"/><br/>
                            <span><?php echo $product['title'];?></span><br/>
                            <span>&#x20B9; <?php echo $product['price'];?></span>
                        </a>
                    </td>
            <?php  $count++;
            if(($count % 4) == 0) { ?>
            </tr>
            <?php }
        } ?>
        </table>
    </div>
</div>