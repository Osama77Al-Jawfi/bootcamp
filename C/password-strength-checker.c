
#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdbool.h>

int main() {
    char password[11];
    int upper = 0, lower = 0, number = 0, symbol = 0;

    printf("Enter password: ");
    fgets(password, sizeof(password), stdin);

    password[strcspn(password, "\n")] = '\0';

    int length = strlen(password);

    for (int i = 0; i < length; i++) {
        if (isupper((unsigned char)password[i]))
            upper = 1;
        else if (islower((unsigned char)password[i]))
            lower = 1;
        else if (isdigit((unsigned char)password[i]))
            number = 1;
        else
            symbol = 1;
    }

    if (length >= 8 && upper && lower && number && symbol)
        printf("Password is Strong\n");
    else
        printf("Password is Weak\n");

    return 0;
}
