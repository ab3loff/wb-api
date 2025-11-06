<?php

namespace App\Console\Commands;

use App\Models\Income;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:incomes {{--from=}} {{--to=}} {{--limit=}} {{--page=}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сохранить доходы в БД';

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
        $baseUrl = 'http://109.73.206.144:6969/api/incomes';

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
                Income::updateOrCreate(
                    [
                        'income_id' => $item['income_id'],
                        'barcode' => $item['barcode'],
                        'nm_id' => $item['nm_id'],
                        'date' => $item['date'],
                    ],
                    [
                        'number' => $item['number'],
                        'last_change_date' => $item['last_change_date'],
                        'supplier_article' => $item['supplier_article'],
                        'tech_size' => $item['tech_size'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total_price'],
                        'date_close' => $item['date_close'],
                        'warehouse_name' => $item['warehouse_name'],
                    ]
                );
            }

            $this->info("Принята страница {$page}, сохранены " . count($data) . " записей.");


        $this->info('Доходы приняты все.');
    }
}
