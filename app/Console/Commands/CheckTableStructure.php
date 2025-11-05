<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'db:check-structure {table=transaksis}';
    protected $description = 'Check database table structure';

    public function handle()
    {
        $table = $this->argument('table');

        try {
            $columns = DB::select("SHOW COLUMNS FROM $table");

            $this->info("=== Structure of table: $table ===");
            $this->info("=====================================");

            foreach ($columns as $column) {
                $this->line("🔹 {$column->Field}: {$column->Type} | Default: {$column->Default} | Null: {$column->Null}");
            }

            // Cek data sample
            $this->info("\n=== Sample Data (5 records) ===");
            $sampleData = DB::table($table)->select('id', 'status')->limit(5)->get();
            foreach ($sampleData as $data) {
                $this->line("ID: {$data->id} | Status: {$data->status}");
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}