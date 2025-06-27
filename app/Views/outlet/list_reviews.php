<?php // file: app/Views/outlet/list_reviews.php (DIPERBARUI) ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ulasan dari Customer</title>
    <style>
        /* ... (styling tetap sama) ... */
        .review-card strong { font-size: 1.1em; }
        .review-card .outlet-name { color: #6c757d; font-style: italic; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Ulasan dari Customer</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a></p>
        <hr>

        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <div>
                           <strong><?= esc($review['customer_name']) ?></strong><br>
                           <span class="outlet-name">untuk outlet: <?= esc($review['outlet_name']) ?></span> <!-- INFO NAMA OUTLET -->
                        </div>
                        <span class="rating"><?= esc($review['rating']) ?></span>
                    </div>
                    <p><i>"<?= esc($review['comment']) ?>"</i></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada ulasan yang diterima.</p>
        <?php endif; ?>
    </div>

</body>
</html>
