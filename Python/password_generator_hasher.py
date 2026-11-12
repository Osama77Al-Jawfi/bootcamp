
import secrets
import string
import hashlib


def generate_password(length):

    if length < 8:
        raise ValueError("Password length must be at least 8.")

    uppercase = string.ascii_uppercase
    lowercase = string.ascii_lowercase
    digits = string.digits
    symbols = string.punctuation

    password = [
        secrets.choice(uppercase),
        secrets.choice(lowercase),
        secrets.choice(digits),
        secrets.choice(symbols)
    ]

    all_characters = uppercase + lowercase + digits + symbols

    for _ in range(length - 4):
        password.append(secrets.choice(all_characters))

    secrets.SystemRandom().shuffle(password)

    return ''.join(password)


def calculate_hash(text):

    encoded_text = text.encode('utf-8')

    hash_object = hashlib.sha256(encoded_text)

    return hash_object.hexdigest()


def main():

    while True:

        print("\n========== Security Tool ==========")
        print("1. Generate Password")
        print("2. Calculate SHA-256 Hash")
        print("3. Exit")
        print("===================================")

        choice = input("Choose an option: ")

        if choice == "1":

            try:
                length = int(input("Enter password length: "))

                password = generate_password(length)
                password_hash = calculate_hash(password)

                print("\n========== Generated Password ==========")
                print("Password:", password)
                print("SHA-256:", password_hash)
                print("========================================")

            except ValueError as error:
                print("Error:", error)

        elif choice == "2":

            text = input("Enter text to hash: ")

            if not text:
                print("Error: Text cannot be empty.")
                continue

            result = calculate_hash(text)

            print("\n========== SHA-256 Result ==========")
            print("Original Text:", text)
            print("SHA-256 Hash:", result)
            print("====================================")

        elif choice == "3":

            print("Exiting the program. Goodbye!")
            break

        else:
            print("Invalid choice. Please try again.")


if __name__ == "__main__":
    main()
