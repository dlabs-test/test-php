SOLUTION
========

Estimation
----------
Estimated: 4 hours

Spent: 5 hours + 1 hour for fixes and docs 


Solution
--------
I did not want to over complicate things since this is a simple task, so I crated simple Repository and Service classes 
(and simple corresponding interfaces) to separate business logic from logic of retrieving data.

It took me a little bit of browsing on Symfony documentation and google to connect everything together hence a little
bigger estimate for task of this range.

Better Product
--------
Feature suggestions to improve product:
+ additional types of reports (monthly, per user, ..) [est: 2-4h per report]
+ database improvements: indexes, foreign keys [est: 1h]
+ front-end part of application with graphs (because Business Analysts usually like fancy things) [est: 6-8h]
+ send email report [est: 4-6h per report]

In terms of how this test task is built, I would implement a different approach should this be a real production 
analytics tool.

Since operating on "live" datasets can cripple our frontend applications I suggest a worker that would segment and copy
data to partitioned DB tables (per year or similar, depends how granulate data you have). Those would be structured for
optimized reading operations. Depending on level of urgency of this data, we could implement workers that copy data real 
time or on demand.

I would also need to implement error catching/logging.