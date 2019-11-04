SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: x hours


Solution
--------
5 test cases:
1. Provided you have data available for years X, Y and Z run command report:profiles:yearly <X, Y or Z> and check if you see:
      - header of the table titled "Profile" leading X, Y or Z (it depends for which year the command was executed) and 12 header columns with month names
      - "Body rows" for each of the "Profiles". 1st column is name of the profile, other columns are views per month in year for which the command was run.
      - If there is no data for specific profile, for specific month of year, "n/a" is displayed.
2. Run command without arguments. Expected message: 
    'Please provide the year argument for which this report should be created. Data is available for years:'
    After the message a list of years(1 year per row) for which data is available is displayed.
3. Run command with argument that is not number. Expected message:
    '<year> argument should be a positive number.'
4. Run command with argument of year for which no data is available. Expected message:
    'Please make sure you have some data available for report creation.'
5. Have data for multiple profiles available. Run command with appropriate year argument. Profiles should be ordered alphabetically in the table.

Ways to build a better product?
- Create unit tests, so that specific code "chunks" can be tested quickly, making sure you didn't break something in the process of changing/improving the code. Estimation for current code (not including configuration of unit test environment): 2 hours
- Add logging of exceptions, so that we know which exception happened in production and can "fix" the issue ASAP. Estimation: 2 hours
- Use Doctrine DBAL queryBuilder -> makes code a bit nicer and more readable in my opinion. Estimation: 1 hour
    
