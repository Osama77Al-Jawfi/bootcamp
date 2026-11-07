
#include <stdio.h>
#include <string.h>

int main(int argc, char *argv[])
{
    FILE *file;
    char line[512];
    char keyword[50];
    int lineNumber = 0;
    int alertCount = 0;

    if (argc < 3)
    {
        printf("Usage: %s <logfile> <keyword>\n", argv[0]);
        return 1;
    }

    strcpy(keyword, argv[2]);

    file = fopen(argv[1], "r");

    if (file == NULL)
    {
        printf("Error: Could not open the log file.\n");
        return 1;
    }

    printf("\nSearching for keyword: %s\n", keyword);
    printf("--------------------------------------------\n");

    while (fgets(line, sizeof(line), file) != NULL)
    {
        lineNumber++;

        if (strstr(line, keyword) != NULL)
        {
            alertCount++;

            printf("Alert #%d\n", alertCount);
            printf("Line Number: %d\n", lineNumber);
            printf("Log Entry: %s", line);

            if (strchr(line, '\n') == NULL)
            {
                printf("\n");
            }

            printf("--------------------------------------------\n");
        }
    }

    printf("\n========== Analysis Report ==========\n");
    printf("Keyword Searched: %s\n", keyword);
    printf("Total Alerts Found: %d\n", alertCount);
    printf("Total Lines Checked: %d\n", lineNumber);
    printf("=====================================\n");

    fclose(file);

    return 0;
}
