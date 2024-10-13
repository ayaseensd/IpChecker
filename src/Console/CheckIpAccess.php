<?php

namespace Ayaseensd\IpChecker\Console;

use Illuminate\Console\Command;

class CheckIpAccess extends Command
{
    protected $signature = 'check:ip-access {ips* : IPs or IP ranges to check} {--https : Use HTTPS instead of HTTP for the requests}';
    protected $description = 'Check if specified IP addresses or IP ranges are accessible using HTTP or HTTPS';

    public function handle()
    {
        $ipRanges = $this->argument('ips');
        $useHttps = $this->option('https');
        $protocol = $useHttps ? 'https' : 'http';

        foreach ($ipRanges as $ipRange) {
            $ips = $this->getIpsFromRange($ipRange);

            foreach ($ips as $ip) {
                $this->info("Testing access to IP: {$ip} using {$protocol}");

                $isAccessible = $this->testIpAccess($ip, $protocol);

                if ($isAccessible) {
                    $this->info("Successfully accessed IP: {$ip}");
                } else {
                    $this->error("Failed to access IP: {$ip}");
                }
            }
        }
    }

    protected function testIpAccess($ip, $protocol)
    {
        $ch = curl_init("{$protocol}://{$ip}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $isAccessible = $httpCode >= 200 && $httpCode < 300;

        curl_close($ch);

        return $isAccessible;
    }

    protected function getIpsFromRange($ipRange)
    {
        if (strpos($ipRange, '/') !== false) {
            list($subnet, $mask) = explode('/', $ipRange);
            return [$subnet];
        }
        return [$ipRange];
    }
}