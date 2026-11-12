
import re
from collections import Counter


patterns = {
    "SQL Injection": re.compile(
        r"(SELECT\s+.*FROM|UNION\s+SELECT|OR\s+['\"]?1['\"]?\s*=\s*['\"]?1)",
        re.IGNORECASE
    ),

    "Path Traversal": re.compile(
        r"(\.\./|\.\.\\|/etc/passwd|/etc/shadow)",
        re.IGNORECASE
    ),

    "XSS": re.compile(
        r"(<script|javascript:|onerror\s*=|onload\s*=)",
        re.IGNORECASE
    ),

    "Command Injection": re.compile(
        r"(;\s*(cat|whoami|id|ls)|\|\s*(cat|whoami|id)|&&\s*(cat|whoami|id))",
        re.IGNORECASE
    )
}


def analyze_logs(file_name):

    alerts = []
    attack_count = Counter()

    try:
        with open(file_name, "r", encoding="utf-8", errors="ignore") as file:

            for line_number, line in enumerate(file, start=1):

                ip_match = re.search(
                    r"(\d{1,3}\.){3}\d{1,3}",
                    line
                )

                source_ip = ip_match.group() if ip_match else "Unknown"

                for attack, pattern in patterns.items():

                    if pattern.search(line):

                        alert = {
                            "type": attack,
                            "line": line_number,
                            "ip": source_ip,
                            "log": line.strip()
                        }

                        alerts.append(alert)
                        attack_count[attack] += 1

                        print("\n" + "=" * 50)
                        print("[!] SECURITY ALERT")
                        print(f"Attack Type : {attack}")
                        print(f"Source IP   : {source_ip}")
                        print(f"Line Number : {line_number}")
                        print(f"Log Entry   : {line.strip()}")
                        print("=" * 50)

    except FileNotFoundError:
        print("Error: Log file not found!")
        return

    except OSError as error:
        print(f"Error reading file: {error}")
        return

    print("\n========== ANALYSIS REPORT ==========")
    print(f"Total Alerts Found: {len(alerts)}")
    print(f"Total Attack Types: {len(attack_count)}")

    if attack_count:
        print("\nAttack Summary:")

        for attack, count in attack_count.items():
            print(f"{attack}: {count}")

    else:
        print("No suspicious activity detected.")

    print("=" * 40)


if __name__ == "__main__":

    print("Automated Security Log Analyzer")
    print("-" * 40)

    file_name = input("Enter Log File Path: ").strip()

    analyze_logs(file_name)
