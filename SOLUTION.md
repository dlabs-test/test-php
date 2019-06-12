# SOLUTION

## Estimation

Estimated: 6 hours

Spent: 3 hours development + 1 hour reading and writing the solution

## Solution

### Test cases

```gherkin
Scenario: I execute the Yearly Views report command
WHEN I execute the Yearly Views report command
THEN I expect to se a prompt for the desired year of the report

GIVEN that I executed the Yearly Views report command
WHEN I don't provide a desired year
THEN I expect to see a monthly breakdown of the total views per profile for the current year

GIVEN that I executed the Yearly Views report command
WHEN I don't provide a valid year
THEN I expect to se a validation error and a prompt for a new year entry

GIVEN that I executed the Yearly Views report command
WHEN I do provide a valid year
THEN I expect to see a monthly breakdown of the total views per profile for the provided year

GIVEN that there is no historical data and no profiles available
WHEN I view the Yearly Views report
THEN I expect to see a notification that there is no data

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see the report year in the report
```

### Product expansion

The product has the potential for expansion. Some of the additional functionalities and refactoring that I could implement into the product include:

- add additional parameters that the user can enter to modify the report output (eg. sorting, searching,...) ~ 30min
- additional refactoring of the code to further separate concerns ~ 1h
- create a graphical web interface to improve the user experience ~ 5h
- implement the ability to export the report (eg. CSV, Excel, PDF) ~ 2h
