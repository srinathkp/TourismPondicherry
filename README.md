# TourismPondicherry
A sample tourism site made for web dev assignment - Web dev with HTML5, CSS3 and PHP7

- register.php : Registration form with required input fields

- login.php : Login page ( Login is required to view/book activities)

- index.php : works with/without login - A brief note about Puducherry/Pondicherry

- activities.php : Lists the activities available at the moment ( Activities are manually inserted into the db by the Admin)

- book_activities.php : Form to book an activity - requires data and no. of tickets - This validates the input and creates an entry in the booked activities table. After submitting the form, the user is redirected to booked_activities.php

- booked_activities.php : Lists the booked activities by the current user sorted by date.

- search_activities.php : Allows the user to search for activities by keyword. Options of AND and OR provided and searchtext from first textbox is highlighted using JS for convenience. 

- credits : List of websites referred to, as a part of web dev.

- logout.php : Destroys the session and the user is logged out.

- "include/" folder contains necessary files to connect to db and creating an instance of User Class that are included in main pages.

- "class/" folder contains User class and Activity class which contains the required methods to handle queries to and from customers, activities and booked_activities table.

- "assets/" folder has the necessary images, stylesheets and js file

- script.js : Used for search page to find and highlight search text.

- style.css : common style sheet to provide Styling
