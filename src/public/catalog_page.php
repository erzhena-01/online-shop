<div class="container">
    <a href="/profile">Мой профиль</a>
    <a href="/cart" style="float: right;">Корзина 🛒</a>

    <h3>Catalog</h3>
    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        Hit!
                    </div>
                    <img class="card-img-top" src="<?php echo $product['image_url']; ?>" alt="Card image">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product['name'];?></p>
                        <a href="#"><h5 class="card-title"><?php echo $product['description'];?></h5></a>
                        <div class="card-footer">
                            <?php echo $product['price'];?>
                        </div>
                        <form method="post" action="/add_product" style="margin-top: 10px;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="amount" value="1" min="1" style="width: 60px;">
                            <button type="submit">Добавить в корзину</button>
                        </form>

                    </div>
                </a>
            </div>


        <?php endforeach;?>




        <style>
            body {
                font-style: sans-serif;
            }

            a {
                text-decoration: none;
            }

            a:hover {
                text-decoration: none;
            }

            h3 {
                line-height: 3em;
            }

            .card {
                max-width: 16rem;
            }

            .card:hover {
                box-shadow: 1px 2px 10px lightgray;
                transition: 0.2s;
            }

            .card-header {
                font-size: 13px;
                color: gray;
                background-color: white;
            }

            .text-muted {
                font-size: 11px;
            }

            .card-footer{
                font-weight: bold;
                font-size: 18px;
                background-color: white;
            }
        </style>