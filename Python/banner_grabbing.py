
import socket
import time


def grab_banner(target, port, timeout=3):

    try:
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as sock:

            sock.settimeout(timeout)
            sock.connect((target, port))

            if port in [80, 8080, 8000, 8888]:
                request = (
                    f"HEAD / HTTP/1.1\r\n"
                    f"Host: {target}\r\n"
                    f"Connection: close\r\n\r\n"
                )

                sock.sendall(request.encode())

            banner = sock.recv(1024)

            if banner:
                banner = banner.decode("utf-8", errors="ignore")

                lines = banner.splitlines()

                if lines:
                    return lines

    except socket.timeout:
        return None

    except OSError:
        return None

    return None


def main():

    print("========== BANNER GRABBING TOOL ==========")

    target = input("Enter Target IP: ").strip()

    ports_input = input("Enter Ports (example: 21,22,80,8080): ")

    try:
        socket.inet_aton(target)

        ports = [int(port.strip()) for port in ports_input.split(",")]

        if not ports or any(port < 1 or port > 65535 for port in ports):
            print("Invalid port number!")
            return

    except (OSError, ValueError):
        print("Invalid IP address or port number!")
        return

    print("\nStarting Banner Grabbing...")
    print("-" * 45)

    start_time = time.time()

    for port in ports:

        print(f"\n[*] Checking Port {port}...")

        banner = grab_banner(target, port)

        if banner:
            print(f"[+] Port {port} responded")
            print(f"[+] Banner: {banner[0]}")

            for line in banner[1:]:
                if line.lower().startswith(("server:", "x-powered-by:")):
                    print(f"[+] {line}")
        else:
            print(f"[-] No banner received from port {port}")

    elapsed_time = time.time() - start_time

    print("\n" + "-" * 45)
    print("Banner Grabbing Completed")
    print(f"Time Taken: {elapsed_time:.2f} seconds")
    print("=" * 45)


if __name__ == "__main__":
    main()
