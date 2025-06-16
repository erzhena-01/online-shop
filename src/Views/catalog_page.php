<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
    }

    .nav-bar {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        background-color: #fff;
        padding: 15px 30px;
        border-bottom: 1px solid #ddd;
        border-radius: 0 0 8px 8px;
    }

    .nav-link {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #007bff;
    }

    .page-title {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin: 30px 0;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
    }

    .product-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background-color: #ff4757;
        color: #fff;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-brand {
        color: #555;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .product-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 20px;
        font-weight: bold;
        color: #27ae60;
        margin-bottom: 15px;
    }

    .add-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .amount-input {
        width: 60px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
    }

    .add-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 600px) {
        .nav-bar {
            flex-direction: column;
            align-items: flex-start;
        }

        .product-title {
            font-size: 16px;
        }

        .product-price {
            font-size: 18px;
        }
    }
</style>



<div class="container">
    <div class="nav-bar">
        <a href="/profile" class="nav-link">Мой профиль</a>
        <a href="/cart" class="nav-link cart-link">Корзина 🛒</a>
        <a href="/my-orders" class="nav-link">Мои заказы</a>
        <a href="/logout" class="nav-link">Выйти</a>
    </div>

    <h3 class="page-title">Каталог товаров</h3>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="card-header">Хит продаж</div>
                <img src="<?php echo $product->getImageUrl(); ?>" alt="<?php echo $product->getName(); ?>" class="product-image">
                <div class="card-body">
                    <p class="product-brand"><?php echo $product->getName(); ?></p>
                    <h5 class="product-title"><?php echo $product->getDescription(); ?></h5>
                    <div class="product-price"><?php echo $product->getPrice(); ?> ₽</div>

                    <div class="quantity-controls">
                        <form method="post" action="/cart/decrease" class="add-form">
                            <input type="hidden" name="productId" value="<?php echo $product->getId(); ?>">
                            <button type="submit" class="quantity-button">-</button>
                        </form>

                        <input type="number" name="amount" value="<?php echo $product->getAmount(); ?>" min="1" class="amount-input" readonly>

                        <form method="post" action="/cart/increase" class="add-form">
                            <input type="hidden" name="productId" value="<?php echo $product->getId(); ?>">
                            <button type="submit" class="quantity-button">+</button>
                        </form>
                    </div>

                </div>
        <?php endforeach; ?>
    </div>
</div>
