<?php

namespace Ayaseensd\IpChecker\Console;

use Illuminate\Console\Command;

class CheckIpAccess extends Command
{
    protected $signature = 'check:ip-access {ips* : IPs or IP ranges to check} {--port= : Use specific port for the requests, default is 80} {--udp : use udp protocol, default is tcp}';
    protected $description = 'Check if specified IP addresses or IP ranges are accessible using HTTP or HTTPS';

    public function handle()
    {
        $ipRanges = $this->argument('ips');
        $port = $this->option('port') ?? '80';
        $protocol = $this->option('udp') ? 'udp' : 'tcp';


        foreach ($ipRanges as $ipRange) {
            $ips = $this->getIpsFromRange($ipRange);


            foreach ($ips as $ip) {
                $this->info("Testing access to IP: {$ip} using {$port}");

                $isAccessible = $this->testIpAccess($ip, $port, $protocol);

                if ($isAccessible === true) {
                    $this->info("Successfully accessed IP: {$ip}");
                } else {
                    $this->error($isAccessible);
                }
            }
        }
    }

    protected function testIpAccess($ip, $port, $protocol)
    {
        $address = "$protocol://$ip:$port";
        $timeout = 10;
        $errno = null;
        $errstr = null;
        $connection = @stream_socket_client($address, $errno, $errstr, $timeout);

        if ($connection) {
            fclose($connection);
            return true;
        } else {
            return "IP $ip is not reachable on port $port. Error: $errstr ($errno)";
        }
    }

    protected function getIpsFromRange($ipRange)
    {
        if (strpos($ipRange, '/') !== false) {
            list($subnet, $mask) = explode('/', $ipRange);
            return $this->cidrToRange($subnet, $mask);
        }

        return [$ipRange];
    }

    protected function cidrToRange($subnet, $mask)
    {
        $ip = ip2long($subnet);

        $hostsCount = pow(2, (32 - $mask));

        $ips = [];

        for ($i = 0; $i < $hostsCount; $i++) {
            $ips[] = long2ip($ip + $i);
        }

        return $ips;
    }
}