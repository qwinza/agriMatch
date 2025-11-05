<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartNgrok extends Command
{
    protected $signature = 'ngrok:start {port=8000}';
    protected $description = 'Start ngrok tunnel for Laravel development';

    public function handle()
    {
        $port = $this->argument('port');

        $this->info("🚀 Starting ngrok tunnel on port {$port}...");
        $this->info("📝 Make sure Laravel is running on port {$port}");

        // Command untuk menjalankan ngrok
        $ngrokPath = $this->getNgrokPath();

        if (!$ngrokPath) {
            $this->error('❌ Ngrok not found. Please install ngrok first.');
            $this->info('📥 Download from: https://ngrok.com/download');
            $this->info('💡 Extract ngrok.exe to one of these locations:');
            $this->info('   - Project root (same folder as artisan)');
            $this->info('   - C:\\laragon\\bin\\ngrok\\');
            $this->info('   - Or add to PATH');
            return Command::FAILURE;
        }

        $this->info("✅ Ngrok found: {$ngrokPath}");
        $this->info("⏳ Starting ngrok tunnel...");

        $command = "\"{$ngrokPath}\" http {$port} --log=stdout";

        $this->info("🔧 Command: {$command}");
        $this->info("🌐 Your app will be available at: https://*.ngrok.io");
        $this->info("📞 Callback URL: https://*.ngrok.io/payment/callback");
        $this->info("🏁 Finish URL: https://*.ngrok.io/transactions/finish");
        $this->info("⏹️  Press Ctrl+C to stop ngrok");
        $this->info("");
        $this->warn("💡 If you see authentication error:");
        $this->warn("   - Make sure authtoken is configured");
        $this->warn("   - Run: ngrok authtoken YOUR_TOKEN");
        $this->warn("   - Or check: C:\\Users\\LOQ\\.ngrok2\\ngrok.yml");

        // Jalankan ngrok
        system($command);

        return Command::SUCCESS;
    }

    private function getNgrokPath()
    {
        // Cek di berbagai lokasi yang mungkin
        $possiblePaths = [
            'ngrok', // Jika ada di PATH
            'C:\laragon\bin\ngrok\ngrok.exe', // Windows dengan Laragon
            'C:\Program Files\ngrok\ngrok.exe', // Windows
            'C:\tools\ngrok\ngrok.exe', // Windows alternative
            base_path('ngrok.exe'), // Di root project
        ];

        foreach ($possiblePaths as $path) {
            if ($this->checkNgrokExists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function checkNgrokExists($path)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $command = "where \"{$path}\" 2>nul";
        } else {
            // Linux/Mac
            $command = "which \"{$path}\" 2>/dev/null";
        }

        $output = [];
        $result = null;
        exec($command, $output, $result);

        return $result === 0;
    }
}