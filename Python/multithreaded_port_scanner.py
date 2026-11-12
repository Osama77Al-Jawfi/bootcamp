
import socket
import time
import ipaddress
from concurrent.futures import ThreadPoolExecutor, as_completed


def scan_port(target_ip, port, timeout=1.0):

    try:
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as sock:
            sock.settimeout(timeout)

            result = sock.connect_ex((target_ip, port))

            if result == 0:
                return port, True

    except OSError:
        pass

    return port, False


def port_scanner(target_ip, start_port=1, end_port=1024,
                 max_workers=50, timeout=1.0):

    open_ports = []

    print("\n========== PORT SCANNER ==========")
    print(f"Target IP: {target_ip}")
    print(f"Port Range: {start_port} - {end_port}")
    print(f"Max Workers: {max_workers}")
    print("----------------------------------")

    start_time = time.time()

    with ThreadPoolExecutor(max_workers=max_workers) as executor:

        futures = {
            executor.submit(scan_port, target_ip, port, timeout): port
            for port in range(start_port, end_port + 1)
        }

        for future in as_completed(futures):

            port, is_open = future.result()

            if is_open:
                open_ports.append(port)

                try:
                    service = socket.getservbyport(port, "tcp")
                except OSError:
                    service = "Unknown"

                print(f"[OPEN] Port {port} - {service}")

    end_time = time.time()
    elapsed_time = end_time - start_time

    open_ports.sort()

    print("----------------------------------")
    print("Scan Completed!")

    if open_ports:
        print("Open Ports:", open_ports)
    else:
        print("No open ports found.")

    print(f"Total Open Ports: {len(open_ports)}")
    print(f"Time Taken: {elapsed_time:.2f} seconds")
    print("==================================")

    return open_ports


def main():

    print("Welcome to Multithreaded Port Scanner")

    target = input("Enter Target IP: ").strip()

    try:
        target_ip = str(ipaddress.ip_address(target))

        start_port = int(input("Enter Starting Port (default 1): ") or "1")
        end_port = int(input("Enter Ending Port (default 1024): ") or "1024")

        if not (1 <= start_port <= 65535):
            print("Error: Starting port must be between 1 and 65535.")
            return

        if not (1 <= end_port <= 65535):
            print("Error: Ending port must be between 1 and 65535.")
            return

        if start_port > end_port:
            print("Error: Starting port cannot be greater than ending port.")
            return

        port_scanner(
            target_ip,
            start_port,
            end_port,
            max_workers=50,
            timeout=1.0
        )

    except ValueError:
        print("Error: Invalid IP address or port number.")


if __name__ == "__main__":
    main()
