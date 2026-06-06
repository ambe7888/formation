<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$categories = Illuminate\Support\Facades\DB::table('categories')->get();
echo "CATEGORIES:\n";
foreach ($categories as $c) {
    echo "$c->id | $c->name | $c->slug | $c->sort_order\n";
}
$trainings = Illuminate\Support\Facades\DB::table('trainings')->select('category','category_id')->distinct()->get();
echo "TRAINING CATEGORIES:\n";
foreach ($trainings as $t) {
    echo (($t->category_id === null) ? 'NULL' : $t->category_id) . ' | ' . (($t->category === null) ? 'NULL' : $t->category) . "\n";
}
