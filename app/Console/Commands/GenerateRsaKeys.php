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

        // Try to find openssl.cnf for XAMPP/Windows
        $cnfPaths = [
            'C:\\xampp\\php\\extras\\ssl\\openssl.cnf',
            'C:\\xampp\\apache\\conf\\openssl.cnf',
            'C:\\xampp\\apache\\bin\\openssl.cnf'
        ];
        
        foreach ($cnfPaths as $cnfPath) {
            if (file_exists($cnfPath)) {
                $config['config'] = $cnfPath;
                break;
            }
        }

        $res = openssl_pkey_new($config);
        
        if (!$res) {
            // Fallback to CLI if possible
            $this->warn('openssl_pkey_new failed. Trying fallback to CLI openssl...');
            $opensslBin = 'openssl';
            if (file_exists('C:\\xampp\\apache\\bin\\openssl.exe')) {
                $opensslBin = 'C:\\xampp\\apache\\bin\\openssl.exe';
            } elseif (file_exists('C:\\Program Files\\Git\\usr\\bin\\openssl.exe')) {
                $opensslBin = '"C:\\Program Files\\Git\\usr\\bin\\openssl.exe"';
            }
            
            exec($opensslBin . ' genrsa -out "' . $privateKeyPath . '" 2048 2>&1', $out, $ret);
            if ($ret === 0) {
                exec($opensslBin . ' rsa -in "' . $privateKeyPath . '" -pubout -out "' . $publicKeyPath . '" 2>&1', $out2, $ret2);
                if ($ret2 === 0) {
                    $this->info('RSA keys generated successfully via OpenSSL CLI.');
                    return;
                }
            }

            $this->error('Failed to generate RSA keys. Make sure OpenSSL is properly configured in PHP or CLI is available.');
            // Print openssl errors
            while ($msg = openssl_error_string()) {
                $this->error($msg);
            }
            return;
        }

        openssl_pkey_export($res, $privKey, null, $config);
        $pubKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($privateKeyPath, $privKey);
        file_put_contents($publicKeyPath, $pubKey);

        $this->info('RSA keys generated successfully.');
    }
}
