<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Mailgun Test Command
 *
 * This command is used for testing Mailgun email configuration in both
 * local development and production environments. It's useful for:
 * - Verifying Mailgun API credentials
 * - Testing email delivery functionality
 * - Troubleshooting email configuration issues
 * - Deployment verification
 *
 * Usage: php artisan mailgun:test your-email@example.com
 */
class TestMailgunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailgun:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mailgun API directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $domain = config('services.mailgun.domain');
        $apiKey = config('services.mailgun.secret');
        $endpoint = config('services.mailgun.endpoint', 'api.mailgun.net');

        $this->info("Testing Mailgun API with domain: {$domain}");
        $this->info("API Endpoint: {$endpoint}");
        $this->info("Target Email: {$email}");

        // Validate configuration
        if (!$domain || !$apiKey) {
            $this->error("Mailgun configuration is incomplete!");
            $this->error("Domain: " . ($domain ?: 'NOT SET'));
            $this->error("API Key: " . ($apiKey ? 'SET' : 'NOT SET'));
            return 1;
        }

        try {
            $this->info("Sending test email...");

            $response = Http::withBasicAuth('api', $apiKey)
                ->asForm()
                ->post("https://{$endpoint}/v3/{$domain}/messages", [
                    'from' => "Test <noreply@{$domain}>",
                    'to' => $email,
                    'subject' => 'Mailgun API Test - Task Management System',
                    'text' => 'This is a test email sent directly via Mailgun API from your Task Management System.',
                    'html' => '<h1>Mailgun Test</h1><p>This is a test email sent directly via Mailgun API from your <strong>Task Management System</strong>.</p><p>If you received this email, your Mailgun configuration is working correctly!</p>',
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $this->info("✅ Success! Email sent successfully!");
                $this->info("Message ID: " . ($responseData['id'] ?? 'N/A'));
                $this->info("Message: " . ($responseData['message'] ?? 'Email queued for delivery'));
                $this->newLine();
                $this->info("📧 Check your email inbox (including spam folder) for the test message.");
                return 0;
            } else {
                $this->error("❌ API request failed with status code: " . $response->status());
                $this->error("Error response: " . $response->body());

                // Provide helpful error messages
                if ($response->status() === 401) {
                    $this->error("This usually means your API key is incorrect.");
                } elseif ($response->status() === 400) {
                    $this->error("This usually means there's an issue with the request parameters or domain configuration.");
                }

                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception occurred: " . $e->getMessage());
            return 1;
        }
    }
}