SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 8 hours


Solution
--------
Comments on your solution.

I've added the possibility to run the command ```bin/console report:profiles:yearly``` adding the year as parameter.

```
$> bin/console report:profiles:yearly 2016
```

If the user specified a year that is not available at all get a message "The year specified has no results".


Tests
-------

GIVEN: the user request the report for a specific year
WHEN: I execute the Yearly Views report 
THEN: I expect that the table include the specified year

GIVEN: The profile table is empty
WHEN: I execute the Yearly Views report 
THEN: The user get the message "The profile table is empty"

GIVEN: The views table is empty
WHEN: I execute the Yearly Views report 
THEN: The user get the message "The profile table is empty"

GIVEN: The user doesn't specify the year
WHEN: I execute the Yearly Views report 
THEN: I expect that the table include all the available years

GIVEN that there is historical data available
WHEN I execute the Yearly Views report specifiying a year that is not available
THEN I expect to get this message "The year specified has no results"
