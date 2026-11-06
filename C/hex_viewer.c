
#include <stdio.h>

int main(int argc, char *argv[])
{
    FILE *file;
    unsigned char buffer[16];
    size_t bytesRead;
    unsigned long offset = 0;

    if (argc < 2)
    {
        printf("Usage: %s <filename>\n", argv[0]);
        return 1;
    }

    file = fopen(argv[1], "rb");

    if (file == NULL)
    {
        printf("Error: Could not open the file.\n");
        return 1;
    }

    printf("Offset   Hexadecimal Values                          ASCII\n");
    printf("------------------------------------------------------------\n");

    while ((bytesRead = fread(buffer, 1, 16, file)) > 0)
    {
        printf("%08lX  ", offset);

        for (size_t i = 0; i < bytesRead; i++)
        {
            printf("%02X ", buffer[i]);
        }

        for (size_t i = bytesRead; i < 16; i++)
        {
            printf("   ");
        }

        printf(" ");

        for (size_t i = 0; i < bytesRead; i++)
        {
            if (buffer[i] >= 32 && buffer[i] <= 126)
            {
                printf("%c", buffer[i]);
            }
            else
            {
                printf(".");
            }
        }

        printf("\n");

        offset += bytesRead;
    }

    fclose(file);

    printf("\nFile reading completed successfully.\n");

    return 0;
}
