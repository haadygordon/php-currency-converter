<?php
    $config = require __DIR__ . '/../config.php';
    require __DIR__ . '/../classes/ExchangeRateAPI.php';
    require __DIR__ . '/../classes/DB.php';

    $rateService = new ExchangeRateAPI(
        $config->exchange_api_url,
        $config->exchange_api_key
    );

    // Logger
    $logger = new DB(
        $config->db_host,
        $config->db_user,
        $config->db_pass,
        $config->db_name
    );

    $errors = [];
    $result = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $from   = strtoupper(trim($_POST['from']   ?? ''));
        $to     = strtoupper(trim($_POST['to']     ?? ''));
        $amount = (float) ($_POST['amount'] ?? 0);

        if (!$from || !$to || $amount <= 0) {
            $errors[] = 'Please enter valid currencies and amount.';
        } else {
            try {
                $rate = $rateService->getRate($from, $to);
                $result = $amount * $rate;
                // Log it
                $logger->logConversion($from, $to, $amount, $result);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
    }
?>

<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>PHP Currency Converter</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <h1>Currency Converter</h1>
            <?php if ($errors): ?>
                <div class="errors">
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <input name="amount" 
                    type="number" step="0.01" 
                    placeholder="Amount" 
                    value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" required>
                <input name="from" 
                    type="text" maxlength="3" size="3" 
                    placeholder="USD" 
                    value="<?= htmlspecialchars($_POST['from'] ?? '') ?>" required>
                <input name="to" 
                    type="text" maxlength="3" size="3" 
                    placeholder="EUR" 
                    value="<?= htmlspecialchars($_POST['to'] ?? '') ?>" required>
                <button type="submit">Convert</button>
            </form>

            <?php if ($result !== null): ?>
                <div class="result">
                <p>
                    <?= number_format($amount, 2) ?> <?= $from ?> =
                    <?= number_format($result, 2) ?> <?= $to ?>
                </p>
                </div>
            <?php endif; ?>
        </body>
    </html>