<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:stocks {{--limit=}} {{--page=}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сохранить склады в БД';

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
        $date = now()->format('Y-m-d');
        $limit = $this->option('limit') ?? 500;
        $page = $this->option('page') ?? 1;

        $key = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
        $url = 'http://109.73.206.144:6969/api/stocks';

            $response = Http::get($url, [
                'dateFrom' => $date,
                'page' => $page,
                'limit' => $limit,
                'key' => $key,
            ]);

            if ($response->failed()) {
                $this->error('Ошибка запроса: ' . $response->status());
                return;
            }

            $data = $response->json()['data'] ?? [];

            foreach ($data as $item) {
                Stock::updateOrCreate(
                    [
                        'nm_id' => $item['nm_id'],
                        'warehouse_name' => $item['warehouse_name'],
                        'date' => $item['date'],
                    ],
                    [
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'barcode' => $item['barcode'],
                        'quantity' => $item['quantity'],
                        'is_supply' => $item['is_supply'],
                        'is_realization' => $item['is_realization'],
                        'quantity_full' => $item['quantity_full'],
                        'in_way_to_client' => $item['in_way_to_client'],
                        'in_way_from_client' => $item['in_way_from_client'],
                        'subject' => $item['subject'],
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'sc_code' => $item['sc_code'],
                        'price' => $item['price'],
                        'discount' => $item['discount'],
                    ]
                );
            }

            $this->info("Загружена страница {$page}, сохранены " . count($data) . "записей");

        $this->info('Все данные складов успешно сохранены!');
    }
}
