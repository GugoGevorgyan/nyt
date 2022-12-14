<?php

declare(strict_types=1);

namespace ServiceEntity\GeoIP\Services;

use Exception;
use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;
use PharData;

class MaxMindDatabase extends AbstractService
{
    /**
     * Service reader instance.
     *
     * @var Reader
     */
    protected $reader;

    /**
     * The "booting" method of the service.
     *
     * @return void
     * @throws InvalidDatabaseException
     */
    public function boot()
    {
        $path = $this->config('database_path');

        // Copy test database for now
        if (false === is_file($path)) {
            @mkdir(dirname($path));

            copy(__DIR__.'/../../../var/geoip.mmdb', $path);
        }

        $this->reader = new Reader($path, $this->config('locales', ['en']));
    }

    /**
     * {@inheritdoc}
     */
    public function locate($ip)
    {
        $record = $this->reader->city($ip);

        return $this->hydrate([
            'ip' => $ip,
            'iso_code' => $record->country->isoCode,
            'country' => $record->country->name,
            'city' => $record->city->name,
            'state' => $record->mostSpecificSubdivision->isoCode,
            'state_name' => $record->mostSpecificSubdivision->name,
            'postal_code' => $record->postal->code,
            'lat' => $record->location->latitude,
            'lon' => $record->location->longitude,
            'timezone' => $record->location->timeZone,
            'continent' => $record->continent->code,
        ]);
    }

    /**
     * Update function for service.
     *
     * @return string
     * @throws Exception
     */
    public function update()
    {
        if (false === $this->config('database_path', false)) {
            throw new Exception('Database path not set in config file.');
        }

        $this->withTemporaryDirectory(function ($directory) {
            $tarFile = sprintf('%s/maxmind.tar.gz', $directory);

            file_put_contents($tarFile, fopen($this->config('update_url'), 'rb'));

            $archive = new PharData($tarFile);

            $file = $this->findDatabaseFile($archive);

            $relativePath = "{$archive->getFilename()}/{$file->getFilename()}";

            $archive->extractTo($directory, $relativePath);

            file_put_contents($this->config('database_path'), fopen("{$directory}/{$relativePath}", 'rb'));
        });

        return "Database file ({$this->config('database_path')}) updated.";
    }

    /**
     * Provide a temporary directory to perform operations in and and ensure
     * it is removed afterwards.
     *
     * @param  callable  $callback
     *
     * @return void
     */
    protected function withTemporaryDirectory(callable $callback)
    {
        $directory = tempnam(sys_get_temp_dir(), 'maxmind');

        if (file_exists($directory)) {
            unlink($directory);
        }

        mkdir($directory);

        try {
            $callback($directory);
        } finally {
            $this->deleteDirectory($directory);
        }
    }

    /**
     * Recursively delete the given directory.
     *
     * @param  string  $directory
     *
     * @return mixed
     */
    protected function deleteDirectory(string $directory)
    {
        if (!file_exists($directory)) {
            return true;
        }

        if (!is_dir($directory)) {
            return unlink($directory);
        }

        foreach (scandir($directory) as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }

            if (!$this->deleteDirectory($directory.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }
        }

        return rmdir($directory);
    }

    /**
     * Recursively search the given archive to find the .mmdb file.
     *
     * @param  PharData  $archive
     *
     * @return mixed
     * @throws Exception
     */
    protected function findDatabaseFile($archive)
    {
        foreach ($archive as $file) {
            if ($file->isDir()) {
                return $this->findDatabaseFile(new PharData($file->getPathName()));
            }

            if ('mmdb' === pathinfo($file->getPathName(), PATHINFO_EXTENSION)) {
                return $file;
            }
        }

        throw new Exception('Database file could not be found within archive.');
    }
}
