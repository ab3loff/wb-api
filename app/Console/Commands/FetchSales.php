<?php

namespace App\Console\Commands;

use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:sales {{--from=}} {{--to=}} {{--limit=}} {{--page=}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сохранить продажи в БД';

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
        $baseUrl = 'http://109.73.206.144:6969/api/sales';

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
                Sale::updateOrCreate(
                    [
                        'g_number' => $item['g_number'],
                        'sale_id' => $item['sale_id']
                    ],
                    [
                        'date' => $item['date'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'total_price' => $item['total_price'],
                        'discount_percent' => $item['discount_percent'],
                        'is_supply' => $item['is_supply'],
                        'is_realization' => $item['is_realization'],
                        'promo_code_discount' => $item['promo_code_discount'],
                        'warehouse_name' => $item['warehouse_name'],
                        'country_name' => $item['country_name'],
                        'oblast_okrug_name' => $item['oblast_okrug_name'],
                        'region_name' => $item['region_name'],
                        'income_id' => $item['income_id'],
                        'odid' => $item['odid'],
                        'spp' => $item['spp'],
                        'for_pay' => $item['for_pay'],
                        'finished_price' => $item['finished_price'],
                        'price_with_disc' => $item['price_with_disc'],
                        'nm_id' => $item['nm_id'],
                        'subject' => $item['subject'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'is_storno' => $item['is_storno'],
                    ]
                );
            }

            $this->info("Принята страница {$page}, сохранены " . count($data) . " записей.");

        $this->info('Продажи приняты все.');
    }
}
