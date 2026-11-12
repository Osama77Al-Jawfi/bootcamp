
import hashlib
import time
import os


def calculate_file_hash(file_path):

    sha256 = hashlib.sha256()

    with open(file_path, "rb") as file:

        while True:

            chunk = file.read(4096)

            if not chunk:
                break

            sha256.update(chunk)

    return sha256.hexdigest()



def monitor_file(file_path):

    if not os.path.isfile(file_path):
        print("[ERROR] File does not exist!")
        return

    baseline_hash = calculate_file_hash(file_path)

    print("\n========== File Integrity Monitor ==========")
    print("[INFO] Monitoring started successfully.")
    print("[INFO] File:", file_path)
    print("[INFO] Baseline SHA-256:", baseline_hash)
    print("[INFO] Checking interval: 3 seconds")
    print("[INFO] Press CTRL + C to stop monitoring.")
    print("=============================================\n")

    try:

        while True:

            time.sleep(3)

            if not os.path.isfile(file_path):

                print("[CRITICAL ALERT] File has been deleted!")
                continue

            current_hash = calculate_file_hash(file_path)

            if current_hash != baseline_hash:

                print("\n[CRITICAL ALERT] FILE MODIFIED!")
                print("[WARNING] File integrity has been compromised!")
                print("Original Hash:", baseline_hash)
                print("Current Hash :", current_hash)
                print("---------------------------------------------")

            else:

                print("OK File integrity verified. No changes detected.")

    except KeyboardInterrupt:

        print("\n[INFO] Monitoring stopped by user.")


def main():

    file_path = input("Enter file path to monitor: ")

    monitor_file(file_path)


if __name__ == "__main__":
    main()
