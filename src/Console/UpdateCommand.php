<?php

namespace PulkitJalan\GeoIP\Console;

use Illuminate\Console\Command;
use PulkitJalan\GeoIP\GeoIPUpdater;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class UpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'geoip:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update geoip database files to the latest version';

    /**
     * @var \PulkitJalan\GeoIP\GeoIPUpdater
     */
    protected $geoIPUpdater;

    /**
     * Create a new console command instance.
     *
     * @param Config $config
     */
    public function __construct(array $config)
    {
        parent::__construct();

        $this->geoIPUpdater = new GeoIPUpdater($config);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $result = $this->geoIPUpdater->update();
        } catch (InvalidDatabaseException $e) {
            $this->error('Database update config not setup properly');

            return;
        } catch (InvalidCredentialsException $e) {
            $this->error('The license key is required to update');

            return;
        }

        if (! $result) {
            $this->error('Update failed!');

            return;
        }

        $this->info('New update file ('.$result.') installed.');
    }

    /**
     * Compatibility with old versions of Laravel.
     */
    public function fire()
    {
        $this->handle();
    }
}
