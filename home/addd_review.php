<!DOCTYPE html>
<html lang="en">

<body>
<?php include('header.php'); ?>

<div class="review-container">
    <h1>Add a Review</h1>

    <?php if ($product): ?>
        <form method="POST" action="">
            <label for="review">Your Review:</label><br>
            <textarea name="review" id="review" rows="5" cols="50" placeholder="Write your review here..." required></textarea>
            <br><br>
            <button type="submit">Submit Review</button>
        </form>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
</body>
</html>
