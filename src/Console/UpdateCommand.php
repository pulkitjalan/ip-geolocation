<?php

namespace PulkitJalan\IPGeolocation\Console;

use Illuminate\Console\Command;
use PulkitJalan\IPGeolocation\IPGeolocationUpdater;
use PulkitJalan\IPGeolocation\Exceptions\InvalidDatabaseException;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

class UpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ip-geolocation:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ip geolocation database files to the latest version';

    /**
     * @var \PulkitJalan\IPGeolocation\IPGeolocationUpdater
     */
    protected $updater;

    /**
     * Create a new console command instance.
     */
    public function __construct(array $config)
    {
        parent::__construct();

        $this->updater = new IPGeolocationUpdater($config);
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $result = $this->updater->update();
        } catch (InvalidDatabaseException $e) {
            $this->error('Database update config not setup properly');

            return static::FAILURE;
        } catch (InvalidCredentialsException $e) {
            $this->error('The license key is required to update');

            return static::FAILURE;
        }

        if (! $result) {
            $this->error('Update failed!');

            return static::FAILURE;
        }

        $this->info('New update file ('.$result.') installed.');

        return static::SUCCESS;
    }
}
