<?php

namespace Webkul\RestApi\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'krayin-rest-api:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish L5SwaggerServiceProvider provider, view and config files.';

    /**
     * Install and configure bagisto rest api.
     */
    public function handle()
    {
        $this->warn('🔧 Step 1: Publishing L5Swagger Provider File...');

        $result = shell_exec('php artisan vendor:publish --tag=krayin-rest-api-swagger');

        if ($result) {
            $this->info('📄 Provider file published successfully! 🚀');
        } else {
            $this->error('❌ Failed to publish the provider file. Please check the error logs.');
        }
        
        $this->warn('🔧 Step 2: Generating L5-Swagger Docs for Admin...');

        $result = shell_exec('php artisan l5-swagger:generate --all');

        if ($result) {
            $this->info('📚 Swagger documentation generated successfully! 📘');
        } else {
            $this->error('❌ Failed to generate Swagger documentation. Please check the error logs.');
        }
        
        $this->comment('-----------------------------');

        $this->comment('🎉 Success! Krayin REST API has been configured successfully. Enjoy your newly documented API! 🚀✨');
    }
}
