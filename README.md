
# IP Checker

## Overview

The `Ayaseensd\IpChecker` package provides a Laravel console command that allows you to check the accessibility of specified IP addresses or IP ranges using TCP or UDP protocols. You can also specify the port to use for the connection tests.

## Installation

To install the package, add it to your project via composer:

```bash
composer require ayaseensd/ipchecker
```

Once installed, register the service provider by adding it to the `config/app.php` file (if needed):

```php
'providers' => [
    Ayaseensd\IpChecker\IpCheckerServiceProvider::class,
]
```

## Usage

The package provides a console command `check:ip-access`, which can be used to test access to IP addresses or ranges.

### Command Syntax

```bash
php artisan check:ip-access {ips* : IPs or IP ranges to check} {--port= : Use specific port for the requests, default is 80} {--udp : Use UDP protocol, default is TCP}
```

### Example Usages

1. **Check a single IP (TCP on default port 80):**

   ```bash
   php artisan check:ip-access 192.168.1.1
   ```

2. **Check a single IP using a specific port (e.g., 8080):**

   ```bash
   php artisan check:ip-access 192.168.1.1 --port=8080
   ```

3. **Check multiple IP addresses (default port 80):**

   ```bash
   php artisan check:ip-access 192.168.1.1 192.168.1.2
   ```

4. **Check a range of IPs (CIDR notation):**

   ```bash
   php artisan check:ip-access 192.168.1.0/24
   ```

5. **Check using UDP instead of TCP:**

   ```bash
   php artisan check:ip-access 192.168.1.1 --udp
   ```

### Command Options

- `ips*`: IP addresses or IP ranges in CIDR notation to check (e.g., `192.168.1.0/24`).
- `--port`: Specifies the port to use for the connection test. Defaults to port 80.
- `--udp`: If set, the package will use the UDP protocol for testing, otherwise, TCP will be used by default.

## How it Works

1. **IP Range Support:**
   - The package supports individual IP addresses and IP ranges in CIDR notation. If a range is specified, it is expanded into individual IP addresses for testing.
   
2. **Protocol Selection:**
   - You can choose either the TCP or UDP protocol for connection testing by using the `--udp` flag. By default, TCP is used.

3. **Port Selection:**
   - By default, the connection tests use port 80. You can specify a different port using the `--port` option.

4. **Access Testing:**
   - The package uses the `stream_socket_client` function to attempt a connection to the specified IPs. If the connection is successful, it reports success; otherwise, it returns the error message.

## Output

- For each IP address tested, the command will display the result:
  - If the IP is accessible, a success message will be shown.
  - If the IP is not accessible, an error message with details will be displayed.

### Example Output

```bash
Testing access to IP: 192.168.1.1 using 80
Successfully accessed IP: 192.168.1.1
Testing access to IP: 192.168.1.2 using 80
IP 192.168.1.2 is not reachable on port 80. Error: Connection timed out (110)
```

## License

This package is open-source and licensed under the MIT License.