PLATINUM FUNDRAISING - Customer Relationship Management (CRM) System

TOP-LEVEL FOLDERS
  ./config :: Contains Apache and PHP configuration files (if necessary)
  ./db     :: Contains database creation script
  ./htdocs :: Contains all web pages files (listed below)

HTDOCS WEB FILES
  ./htdocs/pf/index.php           :: Routes everything
  ./htdocs/pf/config.php          :: DB connection parameters/credentials
  ./htdocs/pf/Database.php        :: DB connector
  ./htdocs/pf/Customer.php        :: Customer class; Uses v_customer view (customers table w/loyalty points for each user)
  ./htdocs/pf/functions.php       :: Validation and data handling functions
  ./htdocs/pf/views/import.php    :: Import CSVs web view
  ./htdocs/pf/views/customers.php :: Show and filter customers web view
  ./htdocs/pf/views/report.php    :: Monthly report web view
  ./htdocs/pf/views/home.php      :: Main dashboard/landing page

DEPLOYMENT INSTRUCTIONS
  1. Create MySQL database by running "./db/DB.sql" script in MySQL.  This SQL file creates the database, tables, functions, views, role, user, and sets some options.
  2. Make sure web server (eg., Apache) is configured to run PHP code and that "index.php" is the first DirectoryIndex value.  The "config" folder contains copies of my PHP and Apache configuration files.  The file "./pf/config.php" defines the database connection parameter values.
  3. Copy the contents of "./htdocs" into web root (eg., c:/Apache24/htdocs/).
  4. Open a browser, then navigate to "http://localhost/pf".  If "index.php" has not been specified for DirectoryIndex index, you'll need to use "http://localhost/pf/index.php" instead.
