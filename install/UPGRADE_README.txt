1.4 The 1.4 update to the CORAL Resources module includes the following enhancements:

	-Added Issue tracker feature that allows tracking of down resources.
	-Integrates closely with the Orginization module.
	-Sends email alerts.

	Database structure changes include:
	- Create Issue, IssueRelationship, IssueEntityType, IssueContact, IssueEmail, Downtime, DowntimeType
	
1.3 The 1.3 update to the CORAL Resources module includes the following enhancements:

	-Cost history tracking
	-Ability to import resources and organizations from a CSV file
	-A resource can have more than one parent
	-A resource can have more than one ISBN/ISSN
	-Option to display a "Get Statistics" button on a resource page if using the Reports module

1.2 The 1.2 update to the CORAL Resources module includes a number of enhancements:

	-Added coverage to the resource record.
	-Added an alternative URL to the resources record.
	-Add subject terms to the resource record.
	-Changed how the related products are displayed for the resource.		
	-Added defaultsort to the configuration.ini.  If used this changes the default sort order for the resources.  Example: defaultsort=\"TRIM(LEADING 'THE ' FROM UPPER(R.titleText)) asc\"				

	-This upgrade will connect to MySQL and run the CORAL Resources structure changes. No changes to the configuration file are required.  
	
	Database structure changes include:
    	
	-Create subjects tables: GeneralSubject, DetailedSubject, GeneralDetailSubjectLink, and ResourceSubject. (subjects are configurable in the admin)
	-Add coverageText, resourceAltURL columns to the Resource table.

	For a basic walk through of the upgrad refer to Coral_1_2_Update.pdf in the /install/ direcotry
	For the manual instal refer to manual_upgrade_1_2.txt in the /install/ direcotry 
	
	
1.1 The 1.1 update to the CORAL Resources module includes a number of enhancements:
     
	-Added a cataloging tab to resource records, allowing tracking of cataloging details and notes.
	-Search resources by active routing steps and cataloging status, as well as some minor performance enhancements to the search listings.
	-The export file has been completely revamped.  Clicking the Excel icon on the resources search page now downloads a CSV file which includes many more fields and should open much more quickly.

	-This upgrade will connect to MySQL and run the CORAL Resources structure changes. No changes to the configuration file are required.  Database structure changes include:</p>

	-Create table CatalogingStatus and CatalogingType (configurable in the admin)
	-Add cataloging columns to the Resource table
	-Numerous indexes to improve search performance

1.0 Initial Release  
