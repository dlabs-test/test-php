SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 5.5 hours


Solution
--------
Comments on your solution

Issues:
- first year has wrong month offset
- profiles are not in alphabetical order

Improvements:
- move output logic to service class,
- use Symphony equal of Laravel Validator to validate entries,
- use Model to pull data from DB as objects,
- use models for easier creation of monthly/yearly stats with inheritance
- do processing in batches
- process bar for command
- multiple commands, allowing selecting desired output (which user, date range)
- predefine output row arrays, so there is no need of verifying if output is set
- check month position in a year, so we can set first year array correctly

Test cases

Feature: What profiles to display
- Given profile was created in 2018
- Given user has no entries in 2017
- Then: Profile should be skipped when generating output for 2017


Feature: default month count value
- Given profile had no views for specific month
- Then: this month entry should have default value set to 'n/a', so we don't have to set it manually afterwards
