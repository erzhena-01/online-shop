
<style>

.product-container {
max-width: 800px;
margin: 30px auto;
padding: 20px;
font-family: 'Segoe UI', sans-serif;
background-color: #ffffff;
border-radius: 10px;
box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.product-info {
display: flex;
gap: 20px;
margin-bottom: 30px;
align-items: flex-start;
}

.product-image {
width: 200px;
height: auto;
border-radius: 8px;
border: 1px solid #ddd;
object-fit: cover;
}

.product-details h2 {
margin: 0 0 10px;
color: #2c3e50;
}

.product-details .price {
font-size: 20px;
color: #27ae60;
font-weight: bold;
}

.review-form {
margin-bottom: 30px;
}

.review-form textarea {
width: 100%;
padding: 10px;
font-size: 14px;
border-radius: 6px;
border: 1px solid #ccc;
resize: vertical;
}

.review-form button {
margin-top: 10px;
background-color: #4a90e2;
color: white;
border: none;
padding: 12px;
border-radius: 6px;
cursor: pointer;
font-weight: bold;
}

.review-form button:hover {
background-color: #357ABD;
}

.star-rating {
display: flex;
flex-direction: row-reverse;
justify-content: flex-start;
gap: 5px;
margin: 10px 0;
}

.star-rating input[type="radio"] {
display: none;
}

.star-rating label {
font-size: 24px;
color: #ccc;
cursor: pointer;
transition: color 0.2s;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
color: #f39c12;
}

.star-display span {
font-size: 20px;
color: #ccc;
}

.star-display .filled {
color: #f39c12;
}
</style>



<div class="product-container">
    <div class="product-info">
        <img src="<?php echo $product->getImageUrl(); ?>" alt="Фото товара" class="product-image">
        <div class="product-details">
            <h2><?php echo ($product->getName()); ?></h2>
            <p><?php echo ($product->getDescription()); ?></p>
            <p class="price"><?php echo $product->getPrice(); ?> ₽</p>
        </div>
    </div>

    <h3>Оставить отзыв</h3>
    <form method="post" action="/add-review" class="review-form">
        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">

        <label for="rating">Оценка:</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                <label for="star<?php echo $i; ?>">★</label>
            <?php endfor; ?>
        </div>

        <label for="comment">Комментарий:</label>
        <textarea name="comment" rows="4" required></textarea>

        <button type="submit">Отправить отзыв</button>
    </form>

    <h3>Отзывы</h3>
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <p><strong><?php echo $review->getAuthor(); ?></strong> (<?php echo $review->getCreatedAt(); ?>)</p>
                <div class="star-display">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="<?php echo $i <= $review->getRating() ? 'filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
                <p><?php echo nl2br($review->getComment()); ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Отзывов пока нет.</p>
    <?php endif; ?>
</div>
