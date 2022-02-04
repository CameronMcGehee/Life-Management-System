# v0.1

## v0.1.0 - unknown date
- UI deisigned, backend classes for managing database tables done

## v0.1.1 - 11-24-2021
- Goodies
    - Mostly working login, register, and edit business pages
    - Fully working register page
    - Mostly working edit business page
    - Working but not good looking switch business page
- Techies
    - PHP fuctions for managing authTokens
    - js function for shaking input fields (or any element)
    - js functions for showing/hiding form error messages

## v0.1.2 - 11-24-2021
- Techies
    - Fixed wrong create account url after installing

## v0.1.3 - 11-24-2021
- Techies
    - Fixed createbusiness script not adding to the table

## v0.1.4 - 11-24-2021
- Techies
    - Fixed username and email being case sensitive

## v0.1.5 - 11-25-2021
- Goodies
    - New global font
    - Admin: Mobile Nav bar and desktop sidebar now show "More" button instead of "Inventory" button. When this button is clicked, a menu will apear with the extra options
- Techies
    - Changed the way business plans are stored (again)
    - Move standalone pages in admin directory into their own folders

## v0.1.6 - 11-28-2021
- Goodies
    - Admin: Login page now shows errors under inputs instead of just reloading page
    - Admin: Edit business page now shows status of changes and alerts that there are unsaved changes
- Techies
    - Admin: Login page now uses asyncronus script (ajax) instead of a redirect to a script
    - Removed extName attributes in business settings database and class as they will not be used
    - Removed surname from any table/class that had it as it will not be used
    - Many fields in database changed from varchar to text for UX and encryption purposes (not in update file, just drop and rebuild the database with /public/install.php)
    - Data encryption for sensitive data using AES-256-cbc

## v0.1.7 - 11.29.2021
- Goodies
    - Smaller sidebar buttons, to fit more in the list on smaller displays
    - Sidebar "People" button is now "Customers" button
    - Themed color and animated text for links
    - Customers page now shows a list of customers among other things
- Techies
    - render class
    - customerTable render class

# v0.2

## v0.2.1
- Goodies
    - Customer view/edit page now functional

## v0.2.2
- Goodies
    - Customer table improvements
    - Phone numbers no longer require three segments, will be split automatically when output if 10 digits but otherwise will just show the number to allow any type of phone number
    - When making a new customer, a random password is automatically generated and put into the field so it will not give an error every time
    - Job calendar design
    - Start of job view/edit page
- Techies
    - Constraints in createTables.sql now cascade on delete

## v0.2.3
- Goodies
    - Popups after creating a business to alert you it was successful
- Techies
    - Popups Handler system
