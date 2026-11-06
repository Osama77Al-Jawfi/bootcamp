
#include <stdio.h>
#include <string.h>
#include <ctype.h>

int checkPassword(char password[])
{
    int length = strlen(password);

    int has_upper = 0;
    int has_lower = 0;
    int has_digit = 0;
    int has_spe_char = 0;
    int score = 0;

    if (length >= 8)
    {
        score++;
    }

    for (int i = 0; i < length; i++)
    {
        unsigned char ch = (unsigned char)password[i];

        if (isupper(ch))
        {
            has_upper = 1;
        }
        else if (islower(ch))
        {
            has_lower = 1;
        }
        else if (isdigit(ch))
        {
            has_digit = 1;
        }
        else
        {
            has_spe_char = 1;
        }
    }

    if (has_upper)
    {
        score++;
    }
    if (has_lower)
    {
        score++;
    }
    if (has_digit)
    {
        score++;
    }
    if (has_spe_char)
    {
        score++;
    }

    return score;
}

int main()
{
    char password[100];
    int score;

    printf("================================\n");
    printf("     PASSWORD STRENGTH CHECKER\n");
    printf("================================\n");

    printf("Enter your password: ");

    if (scanf("%99s", password) != 1)
    {
        printf("Error: Could not read password \n");
        return 1;
    }

    score = checkPassword(password);

    printf("\n---------- PASSWORD REPORT ----------\n");

    printf("Password length: %zu characters\n", strlen(password));
    printf("Password score: %d/5\n", score);

    if (score <= 2)
    {
        printf("Password strength: WEAK\n");
        printf("Recommendation: Use a longer password with different character types.\n");
    }
    else if (score <= 4)
    {
        printf("Password strength: ACCEPTABLE\n");
        printf("Recommendation: Add more character types to improve your password.\n");
    }
    else
    {
        printf("Password strength: STRONG\n");
        printf("Your password meets all the selected criteria.\n");
    }


    return 0;
}
