<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRsaKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rsa:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate RSA public and private keys in storage/app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $privateKeyPath = storage_path('app/private_key.pem');
        $publicKeyPath = storage_path('app/public_key.pem');

        if (file_exists($privateKeyPath) && file_exists($publicKeyPath)) {
            $this->info('RSA keys already exist.');
            return;
        }

        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);
        
        if (!$res) {
            $this->error('Failed to generate RSA keys. Make sure OpenSSL is properly configured in PHP.');
            return;
        }

        openssl_pkey_export($res, $privKey);
        $pubKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($privateKeyPath, $privKey);
        file_put_contents($publicKeyPath, $pubKey);

        $this->info('RSA keys generated successfully.');
    }
}
