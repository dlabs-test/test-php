GIVEN that there is no historical data available
WHEN I execute the Yearly Views report
THEN I expect to see "No views for requested year!" message

GIVEN that there is missing year parameter
WHEN I execute the Yearly Views report
THEN I expect to see error message "Not enough arguments (missing: "year")

GIVEN that there is historical data available
WHEN I execute send the Yearly Views report email
THEN I expect to receive email with said report

GIVEN that there is no historical data available
WHEN I execute send the Yearly Views report email
THEN I expect to see "No views for requested year! Email not sent." message

GIVEN that there is historical data available
WHEN I view analytics dashboard and click on user to see his yearly report
THEN I expect to see detail analytics with comparison charts