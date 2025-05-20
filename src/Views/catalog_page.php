<div class="container">
    <div class="nav-bar">
        <a href="/profile" class="nav-link">Мой профиль</a>
        <a href="/cart" class="nav-link cart-link">Корзина 🛒</a>
        <a href="/logout" class="nav-link">Выйти</a>
    </div>

    <h3 class="page-title">Каталог товаров</h3>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="card-header">Хит продаж</div>
                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                <div class="card-body">
                    <p class="product-brand"><?php echo $product['name']; ?></p>
                    <h5 class="product-title"><?php echo $product['description']; ?></h5>
                    <div class="product-price"><?php echo $product['price']; ?> ₽</div>

                    <form method="post" action="/add-product" class="add-form">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="amount" value="1" min="1" class="amount-input">
                        <button type="submit" class="add-button">Добавить в корзину</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    body {
        font-family: sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .nav-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .nav-link {
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }

    .cart-link {
        color: #007bff;
    }

    .page-title {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
    }

    .product-card {
        background-color: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: box-shadow 0.2s ease;
    }

    .product-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .card-header {
        font-size: 12px;
        color: gray;
        margin-bottom: 10px;
    }

    .product-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .product-brand {
        font-size: 13px;
        color: #888;
        margin: 5px 0;
    }

    .product-title {
        font-size: 16px;
        margin: 5px 0;
        color: #333;
    }

    .product-price {
        font-size: 18px;
        font-weight: bold;
        margin: 10px 0;
    }

    .add-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .amount-input {
        width: 60px;
        padding: 4px;
    }

    .add-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }

    .add-button:hover {
        background-color: #0056b3;
    }
</style>
