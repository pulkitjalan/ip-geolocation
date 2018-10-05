<?php

namespace PulkitJalan\GeoIP\Console;

use Illuminate\Console\Command;
use PulkitJalan\GeoIP\GeoIPUpdater;

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
        $result = $this->geoIPUpdater->update();

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
