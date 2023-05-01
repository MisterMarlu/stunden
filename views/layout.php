<?php
/**
 * @var string $body_content
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/public/css/style.css" rel="stylesheet">
    <script src="/public/js/main.js" defer type="module"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="/public/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/favicon-16x16.png">
    <link rel="manifest" href="/public/site.webmanifest">
    <link rel="mask-icon" href="/public/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>

    <title>Stunden</title>
</head>
<body>
<div class="container mx-auto py-2 px-3">
    <?php echo $body_content; ?>
</div>
</body>
</html>
