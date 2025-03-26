<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateVapidKeys extends Command
{
    protected $signature = 'custom:vapid-keys';
    protected $description = 'Generate VAPID keys manually';

    public function handle()
    {
        $this->info('Generating VAPID keys...');

        // Generate VAPID Keys
        $vapidKeys = $this->generateVapidKeys();

        $this->info('VAPID_PUBLIC_KEY=' . $vapidKeys['publicKey']);
        $this->info('VAPID_PRIVATE_KEY=' . $vapidKeys['privateKey']);

        $this->info('Add these keys to your .env file');

        return Command::SUCCESS;
    }

    private function generateVapidKeys()
    {
        // Generate a new private key
        $privateKey = openssl_pkey_new([
            'curve_name' => 'prime256v1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);

        // Get the private key details
        $details = openssl_pkey_get_details($privateKey);

        // Export the private key
        openssl_pkey_export($privateKey, $privateKeyPem);

        // Convert keys to the format expected by web-push
        $publicKey = $this->publicKeyToPEM($details['key']);

        return [
            'publicKey' => base64_encode($publicKey),
            'privateKey' => base64_encode($privateKeyPem),
        ];
    }

    private function publicKeyToPEM($key)
    {
        $key = base64_decode($key);
        return $key;
    }
}
