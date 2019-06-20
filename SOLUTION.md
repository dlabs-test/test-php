SOLUTION
========

Estimation
----------
Estimated: 1 hours

Spent: 3 hours


Solution
--------
As this is fairly logic un-intense task so I prepared one base service, which covers all the business requirements. I made this step mainly due to the fact I wanted a command class to be smaller and cleaner. As I prefer to inject all necessary dependencies through the constructor method of specific classes, I added a separate interface for our base service. In a similar way I prepared a repository class (not to be fixed to single data source - completely over engineered I know) which handles data retrieval logic.

In our services class we have 3 methods. In my "getTotalViewsPerProfile" method I call my repository which retrieves all the filtered, sorted data as stated in business requirements. In "formatHeaders" method I prepare array of headers including pre-generated months headers. "formatContent" walks through retrieved profiles data and converts flat items to correlated columns (columns by months).

In command class I added optional "year" argument so a client can filter data by desired year.


Things to improve:
---------
DB level:
- table 'profiles' is missing a primary key or at least index on profile_id field
- table 'views' is missing a foreign key on 'profile_id' field
- table 'profiles' is missing an index on 'profile_name' field as business requirements states we need to sort profiles by names

APP level:
- design ReportYearlyService as abstract class so we can reuse it and handle different formatting and different data
- implement logger class and implement custom error handlers



Additional COAs
-------------
GIVEN there is no year argument supplied to a command
WHEN I execute the Yearly Views report
THEN I expect to get data for current year 

GIVEN there is an invalid year argument supplied to a command
WHEN I execute the Yearly Views report
THEN I expect to get message "invalid year argument" 


GIVEN there is no data available at all
WHEN I view the Yearly Views report
THEN I expect to see "no data available" message 

GIVEN there is a profile but has no views defined 
WHEN I view the Yearly Views report
THEN I expect to get profile listed with "n/a" values

GIVEN there is a profile but has negative values as views
WHEN I view the Yearly Views report
THEN I expect to get data listed with "n/a" values






