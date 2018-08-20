<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';

use Dotenv\Dotenv;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Plan;

if ($argc < 3) {
    echo <<<HELP
Usage:
    php import.php <products_csv_file.csv> <plans_csv_file.csv>\n
HELP;

    exit(0);
}

(new Dotenv(__DIR__))->load();

Stripe::setApiKey(getenv('STRIPE_SECRET'));

$productsCsvFile = $argv[1];
$plansCsvFile = $argv[2];

$productFields = [
    'id' => 'id',
    'Name' => 'name',
    'Type' => 'type',
    'Statement Descriptor' => 'statement_descriptor',
    'Unit Label' => 'unit_label',
];

$planFields = [
    'Plan ID' => 'id',
    'Product ID' => 'product',
    'Nickname' => 'nickname',
    'Amount' => 'amount',
    'Currency' => 'currency',
    'Interval' => 'interval',
    'Interval Count' => 'interval_count',
    'Usage Type' => 'usage_type',
    'Aggregate Usage' => 'aggregate_usage',
    'Billing Scheme' => 'billing_scheme',
    'Trial Period Days' => 'trial_period_days',
];

echo 'Importing products...' . PHP_EOL;
foreach (readCsv($productsCsvFile) as $product) {
    $data = mapCsvToApiFields($productFields, $product);
    $data && Product::create($data);
}

echo 'Importing plans...' . PHP_EOL;
foreach (readCsv($plansCsvFile) as $plan) {
    $data = mapCsvToApiFields($planFields, $plan);
    $data && Plan::create($data);
}
