<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan dari Customer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Menggunakan dasar styling yang sama dengan halaman lain untuk konsistensi */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(to bottom, #eef2f9, #ffffff);
            margin: 0;
            padding: 20px 0;
            min-height: 100vh;
            color: #2c3e50;
        }
        .container { 
            max-width: 900px; 
            margin: 20px auto;
            padding: 0 20px;
        }
        .header { 
            display: flex; 
            flex-wrap: wrap; /* Agar rapi di mobile */
            justify-content: space-between; 
            align-items: center; 
            gap: 10px;
            margin-bottom: 20px; 
        }
        h1 { 
            color: #2c3e50; 
            font-size: 28px;
            margin: 0;
        }
        .header-nav a { 
            text-decoration: none; 
            color: #007bff; 
            font-weight: 500; 
        }
        .header-nav a:hover { 
            text-decoration: underline; 
        }
        .info-box {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 40px 20px;
            text-align: center;
            margin-top: 25px;
        }

        /* Styling khusus untuk Kartu Ulasan */
        .review-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-top: 25px;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            gap: 15px;
        }
        .review-header strong {
            font-size: 1.1em;
            font-weight: 600;
            color: #34495e;
        }
        .review-header .outlet-name {
            font-size: 0.9em;
            color: #6c757d;
        }
        .review-card .rating {
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: #fff3cd;
            color: #856404;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0; /* Mencegah rating mengecil */
        }
        .review-card .rating svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
        .review-card .comment-body {
            margin: 0;
            line-height: 1.6;
            color: #495057;
            padding-left: 15px;
            border-left: 3px solid #e9ecef;
            font-style: italic;
        }
         .review-card .review-date {
            text-align: right;
            font-size: 0.8em;
            color: #adb5bd;
            margin-top: 15px;
        }

        /* Penyesuaian untuk mobile */
        @media screen and (max-width: 768px) {
            h1 {
                font-size: 24px;
            }
            .review-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Ulasan dari Customer</h1>
            <nav class="header-nav">
                 <a href="/dashboard">Kembali ke Dashboard</a>
            </nav>
        </div>
        
        <div class="content-wrapper">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <strong><?= esc($review['customer_name']) ?></strong><br>
                                <span class="outlet-name">untuk outlet: <?= esc($review['outlet_name']) ?></span>
                            </div>
                            <div class="rating">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span><?= esc(number_format($review['rating'], 1)) ?></span>
                            </div>
                        </div>
                        <div class="comment-body">
                            <p>"<?= nl2br(esc($review['comment'])) ?>"</p>
                        </div>
                        <?php if(isset($review['created_at'])): ?>
                            <p class="review-date">Diulas pada: <?= date('d F Y', strtotime($review['created_at'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="info-box">
                    <p>Belum ada ulasan yang diterima untuk outlet Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>