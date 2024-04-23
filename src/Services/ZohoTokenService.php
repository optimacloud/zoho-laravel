<?php
namespace Optimacloud\Zoho\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ZohoTokenService
{
    protected $localPath;
    protected $disk;

    public function __construct()
    {
        $this->localPath = storage_path(config('zoho.token_persistence_path'));
        $this->disk = config('zoho.disk', 'local');
    }

    public function updateLocalToken()
    {
        try {
            //Fetch from S3 if remote disk is enabled in config
            if ($this->disk !== 'local') {
                $this->fetchFromStorage();
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error("Failed to ensure local token: " . $e->getMessage());

        }
    }

    public function updateRemoteToken()
    {
        try {
            //Fetch from S3 if remote disk is enabled in config
            if ($this->disk !== 'local') {
                $this->putToRemoteStorage();
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error("Putting token to ".$this->disk." failed: " . $e->getMessage());

        }

    }
    protected function putToRemoteStorage()
    {
        try {
            $contents = file_get_contents($this->localPath);
            Storage::disk($this->disk)->put($this->getRemotePath(), $contents);
        } catch (\Exception $e) {
            Log::error("Error putting token to remote storage: " . $e->getMessage());
        }
    }

    protected function fetchFromStorage()
    {
        try {
            $contents = Storage::disk($this->disk)->get($this->getRemotePath());
            file_put_contents($this->localPath, $contents);
        } catch (\Exception $e) {
            Log::error("Error fetching token from storage: " . $e->getMessage());
            //throw new \RuntimeException("Could not fetch the token from storage.", 0, $e);
        }
    }

    protected function getRemotePath()
    {
        // Assuming the path on the remote disk is the same as the local filename
        return config('zoho.token_persistence_path');
    }
}
