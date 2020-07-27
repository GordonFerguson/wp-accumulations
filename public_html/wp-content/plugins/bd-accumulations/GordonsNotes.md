I'm fiddling with git sharing stuff.
I can now push to  the GordonFerguson account on GitHib
from the wp3w Storm project. I'll want to solve:
'make a pull request' sometime soon.

What I've done:
In WP 'Dashboard'
1. Created a First.Student (user id 2) Subscriber,
2. Reactivated the plugin.
3. Created a demo Page including 3 shortcode blocks
    - A list of available Mantras.
    - A list of the student's challenges.
    - A form for entering accumulations.
 
Directly in the database
1. manually deleted lingering columns
2. preloaded two mantras and two related challenges

In code
1. First commit: added a bit of css to help me read the page.
2. Second commit: 'normalize' the table structure.
3. Third commit: Get the three sortcodes working with the new table structure
4. Also refine adding hours
    - assumming there is only one entry per challenge
    - so, add a soFar column to the challenge table
    - show existing so for selected challenge
    - update the challenge on submit.

