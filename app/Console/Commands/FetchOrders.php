<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:orders {{--from=}} {{--to=}} {{--limit=}} {{--page=}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сохранить заказы в БД';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateFrom = $this->option('from') ?? now()->subDays(7)->format('Y-m-d');
        $dateTo = $this->option('to') ?? now()->format('Y-m-d');
        $limit = $this->option('limit') ?? 500;
        $page = $this->option('page') ?? 1;

        $key = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
        $baseUrl = 'http://109.73.206.144:6969/api/orders';

            $response = Http::get($baseUrl, [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit,
                'key' => $key,
            ]);

            if ($response->failed()) {
                $this->error('Ошибка получения: ' . $response->status());
                return;
            }

            $data = $response->json()['data'] ?? [];
            foreach ($data as $item) {
                Order::updateOrCreate(
                    [
                        'warehouse_name' => $item['warehouse_name'],
                        'nm_id' => $item['nm_id'],
                        'date' => $item['date']
                    ],
                    [
                        'g_number' => $item['g_number'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'total_price' => $item['total_price'],
                        'discount_percent' => $item['discount_percent'],
                        'oblast' => $item['oblast'],
                        'income_id' => $item['income_id'],
                        'odid' => $item['odid'],
                        'subject' => $item['subject'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'is_cancel' => $item['is_cancel'],
                        'cancel_dt' => $item['cancel_dt'],
                    ]
                );
            }

            $this->info("Принята страница {$page}, сохранены " . count($data) . " записей.");

        $this->info('Заказы приняты все');
    }
}
