<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NgrokSetup extends Command
{
    protected $signature = 'ngrok:setup {authtoken?}';
    protected $description = 'Setup ngrok authtoken';

    public function handle()
    {
        $authtoken = $this->argument('authtoken');

        if (!$authtoken) {
            $this->info('🔐 Ngrok Authtoken Setup');
            $this->info('========================');
            $this->info('1. Visit: https://dashboard.ngrok.com/get-started/your-authtoken');
            $this->info('2. Login/Signup to ngrok');
            $this->info('3. Copy your authtoken');
            $this->info('4. Run: php artisan ngrok:setup YOUR_AUTHTOKEN');
            $this->info('');

            $authtoken = $this->ask('Enter your ngrok authtoken:');
        }

        if (empty($authtoken)) {
            $this->error('❌ Authtoken cannot be empty');
            return Command::FAILURE;
        }

        // Setup authtoken menggunakan ngrok command
        $ngrokPath = $this->getNgrokPath();

        if (!$ngrokPath) {
            $this->error('❌ Ngrok not found. Please install ngrok first.');
            return Command::FAILURE;
        }

        $command = "\"{$ngrokPath}\" authtoken \"{$authtoken}\"";
        $output = [];
        $result = null;

        exec($command, $output, $result);

        if ($result === 0) {
            $this->info('✅ Authtoken configured successfully!');
            $this->info('💡 Now you can run: php artisan ngrok:start');
        } else {
            $this->error('❌ Failed to configure authtoken');
            $this->info('💡 Try running manually: ngrok authtoken ' . $authtoken);
        }

        return $result === 0 ? Command::SUCCESS : Command::FAILURE;
    }

    private function getNgrokPath()
    {
        $possiblePaths = [
            'ngrok',
            'C:\laragon\bin\ngrok\ngrok.exe',
            'C:\Program Files\ngrok\ngrok.exe',
            base_path('ngrok.exe'),
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
            $command = "where \"{$path}\" 2>nul";
        } else {
            $command = "which \"{$path}\" 2>/dev/null";
        }

        $output = [];
        $result = null;
        exec($command, $output, $result);

        return $result === 0;
    }
}