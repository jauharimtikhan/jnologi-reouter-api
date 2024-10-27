# MikroTik API Command List

This document provides an overview of MikroTik API commands, their functions, and a brief description of each command.

## System Commands

- **`/system/identity/set name=[name]`**  
  Sets the name of the MikroTik device.

- **`/system/reboot`**  
  Reboots the MikroTik router.

- **`/system/shutdown`**  
  Shuts down the MikroTik router safely.

- **`/system/clock/set time=[HH:MM:SS] date=[YYYY-MM-DD]`**  
  Sets the system's date and time.

- **`/system/resource/print`**  
  Displays system resources, including CPU, memory usage, and uptime.

## Interface Commands

- **`/interface/print`**  
  Lists all network interfaces along with their current statuses.

- **`/interface/enable [ID/name]`**  
  Enables a specified network interface.

- **`/interface/disable [ID/name]`**  
  Disables a specified network interface.

- **`/interface/vlan/add name=[vlan_name] vlan-id=[id] interface=[interface_name]`**  
  Adds a VLAN to a specified interface.

## IP Addressing

- **`/ip/address/add address=[IP/mask] interface=[interface_name]`**  
  Assigns an IP address to a specified interface.

- **`/ip/address/print`**  
  Displays a list of all IP addresses assigned to the router’s interfaces.

- **`/ip/dhcp-client/add interface=[interface_name]`**  
  Configures a DHCP client on an interface to obtain an IP address dynamically.

## Routing

- **`/ip/route/add dst-address=[destination_IP/mask] gateway=[gateway_IP]`**  
  Adds a static route to the routing table.

- **`/ip/route/print`**  
  Lists all routes in the routing table.

- **`/ip/route/remove [ID]`**  
  Removes a specified route from the routing table.

## Firewall

- **`/ip/firewall/filter/add chain=[input/forward/output] action=[accept/drop/reject] src-address=[IP/mask]`**  
  Adds a firewall rule to filter traffic based on source address and action.

- **`/ip/firewall/nat/add chain=[srcnat/dstnat] action=[masquerade/accept/drop]`**  
  Creates a NAT rule for source or destination NAT.

- **`/ip/firewall/mangle/add chain=[chain] action=[mark-connection] new-connection-mark=[mark_name]`**  
  Adds a mangle rule to mark connections for traffic management.

## Bandwidth Management

- **`/queue/simple/add name=[queue_name] target=[IP/mask] max-limit=[rx/tx limit]`**  
  Adds a simple queue to limit bandwidth for a target IP address.

- **`/queue/simple/print`**  
  Displays a list of all simple queues.

- **`/queue/simple/remove [ID]`**  
  Removes a specific simple queue.

## Wireless

- **`/interface/wireless/set [ID] ssid=[SSID]`**  
  Sets the SSID for a wireless interface.

- **`/interface/wireless/security-profiles/add name=[profile_name] mode=[authentication_mode]`**  
  Adds a wireless security profile with the specified authentication mode.

## User Management

- **`/user/add name=[username] group=[group_name] password=[password]`**  
  Adds a new user with specified username, group, and password.

- **`/user/remove [username]`**  
  Removes a specified user from the system.

- **`/user/print`**  
  Lists all users configured on the MikroTik device.

## Hotspot

- **`/ip/hotspot/add name=[hotspot_name] interface=[interface_name]`**  
  Creates a hotspot on a specified interface.

- **`/ip/hotspot/user/add name=[user_name] password=[password]`**  
  Adds a user to the hotspot.

- **`/ip/hotspot/active/print`**  
  Lists all currently active hotspot users.

## Monitoring and Tools

- **`/tool/ping address=[target_IP]`**  
  Sends ICMP ping requests to a specified IP address.

- **`/tool/traceroute address=[target_IP]`**  
  Performs a traceroute to a specified IP address.

- **`/interface/monitor-traffic interface=[interface_name]`**  
  Monitors real-time traffic on a specified interface.

## Backup and Restore

- **`/system/backup/save name=[backup_name]`**  
  Creates a backup of the current configuration.

- **`/system/backup/load name=[backup_name]`**  
  Restores a backup configuration.

- **`/export file=[filename]`**  
  Exports the current configuration to a specified file.

- **`/import file=[filename]`**  
  Imports a configuration from a specified file.

---

This list covers the essential MikroTik API commands for managing, configuring, and monitoring the router. Each command provides a basic building block for controlling router functions and managing network configurations.
