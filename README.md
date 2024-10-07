<h1 align="center">Mikrotik PHP API Package</h1>

![Mikrotik API](https://img.shields.io/badge/Mikrotik-RouterOS-blue) ![PHP](https://img.shields.io/badge/PHP-%3E%3D%208.2-orange) ![License](https://img.shields.io/badge/License-MIT-green)

## 🚀 Introduction

The **Mikrotik PHP API** package provides a convenient and easy-to-use interface to communicate with your MikroTik RouterOS device using PHP. This package is designed to simplify the process of automating your Mikrotik router, including managing interfaces, configuring IPs, monitoring traffic, and more.

Whether you're building network monitoring tools or automating network configuration, this package helps you interact with MikroTik API programmatically.

## ✨ Features

- 📡 **Easy RouterOS API Integration**: Simple API wrapper to interact with RouterOS.
- 🛠️ **Manage Configuration**: Create, modify, and delete Mikrotik configurations (IP, firewall, interfaces, etc.).
- 📊 **Monitor and Analyze Traffic**: Retrieve real-time data such as traffic stats, resource usage, and more.
- 🔐 **Secure Connection**: Secure communication with the router using Mikrotik API.
- ⚡ **Fast and Lightweight**: Efficient data exchange with minimal overhead.

## 🛠️ Installation

Install the package via Composer:

```bash
composer require jauhar/router-api
```

Alternatively, download the package and include the class manually.

## 🚀 Quick Start

### 1. **Establishing Connection**

To begin, you'll need to establish a connection to your Mikrotik RouterOS device using the API.

```php
<?php

require('vendor/autoload.php'); // Include via Composer

use Jnologi\RouterApi\Core;

$api = Core::config('{ip_router}', '{username}', '{password}');

$response = $api->query('/interface/print');
print_r($response);

?>
```

### 2. **Retrieving Interface Information By Parameter**

Retrieve a list of all interfaces from the router with query parameter :

```php
<?php

$response = $api->where('/interface/print', [
    '?name' => 'ether1'
]);

print_r($response);
?>
```

## 📚 API Reference

### Methods Overview

- `getById($host, $username, $password, $port = 8728)`  
  Connect to the Mikrotik RouterOS device.
- `query($command, $paramType = true)`  
  Send a command to the router. Use `false` for sending multiple parts of the same command.
- `where()`  
  Read the response from RouterOS.
- `disconnect()`  
  Terminate the connection with the RouterOS API.

### Common Commands

- `/interface/print`  
  Retrieve the list of interfaces.
- `/ip/address/add`  
  Add a new IP address to an interface.

- `/ip/address/remove`  
  Remove an IP address from the router.

### Example Usage

- **Get traffic stats**:

  ```php
  $api->where('/interface/monitor-traffic', [
    '?interface' => 'ether1',
    '?once' => true
  ]);
  ```

## 🛡️ Security

Ensure that the Mikrotik API service is securely enabled and that your API connection is not exposed to public networks. It is recommended to use **SSH** or a VPN when working with the API.

## 🧪 Testing

You can test the API using a local development environment or any PHP web server. Make sure that you have:

- Enabled the Mikrotik API service on your router.
- Correct firewall rules to allow access to the API port.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any bugs or features.

1. Fork the repository.
2. Create a new branch for your feature or fix.
3. Submit a pull request and describe the changes.

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## 💡 Need Help?

If you encounter any issues, feel free to reach out by creating an issue on the GitHub repository or by visiting [Mikrotik Forums](https://forum.mikrotik.com/).

---

### Made with ❤️ by Jauhar Imtikhan

---

Enjoy building your network automation tools with Mikrotik PHP API!

---
