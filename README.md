Rainbow Social Network Project
=======
Rainbow Social Network ￼

For a free economy, a new system, a global awakening.

The NEED:

Social networks are changing the world. It seems like nowadays everyone has a Facebook account. People are spending more and more time interacting with this social networks to communicate with each other. So why then is there a need for the Agartha Social Network?

There is a need for an intentional social network to unite humans who are working on the creation of a new world. There is a need more than ever to redesign our current system/economy. To do this we need great interaction with each other to share solutions that work and discard does that not.

General social networks like Facebook are too general, convoluted and distracting. There is so much information about everything that many times important information that could inspire a human being to start a change can be easily lost. Facebook also is based on a corporate system based on making money by monitoring and profiling users personal information and pushing advertisement. It is not the ideal platform for supporting a social evolution.

There are some intentional networks already out there, but none of them focuses on creating a new alternative
	- Linked.com in: focuses on professionals based on an existing obsolete money/greed system.
	- Meetup.com: good for creating events and meeting with people, but is event oriented and it lacks the real time flow and 	  spontaneity of a interactive social network.
	- Care2: focuses on fund raising and activist events.. not a real social network 


The SOLUTION:

Rainbow Social offers a solution. It aims to do this through creating a virtual space (social network) for the interaction and sharing of information and resources of conscious human beings seeking a new alternative, and sharing a similar intention.. the creation of a new holistic system, a economy in harmony with the earth.

Rainbow Social promotes the following actvities: 
	-sharing and gifting of resources
	-community building for positive change
	-organic farming, creation of community gardens in cities
	-local consumption, local trade, work exchanges, barter. 
	-art, music, theater
	-love, spontaneity, harmony, peace

Rainbow Social is built specially to create a home network for the following groups:

	-Communities creating new alternatives of living in harmony with the environment
	-People who question the validity of the current system/economy
	-People living without money or in transition
	-Environment Activists, and warriors
	-Yoguis
	-Rainbow warriors
	-Organic farmers, woofers
	-Natural builders - visionary builders
	-Sacred circus performers, clowns, jugglers, aerial fliers, fire spinners, story tellers
	-Visionary artists, artisans, poets
	-Conscious Musicians, Singers, Dancers
	-Healers, light workers, body workers
	-Prophets, visionaries
	-Meditators
	-Nomads

INSTALLATION:
	Requirements: nginx, php5, mysql

	Steps.

	1. Download source files
	2. Setup nginx, under installation/nginx is the server file.. adjust as necessary
	3. Create database for installation on mysql
	4. Import sql file under installation folder into database
	5. Copy config.php into root directory and adjust as necessary for password and paths
	6. Register user and edit database row under users.roles update entry 'admin,root,translator' for roles for that user

	Thats it!


DEVELOPMENT

	The framework for this site is quite simple yet quite powerful... it is all based
	on the url of the page.. each url is mapped to the files it loads.

	For example for the url /feed the framework will look under pages/feed/feed.json and
	load that file. feed.json contains a list of php files that the framework loads for
	head, header, main body, left sidebar, right sidebar, footer... all specified by a layout. The layout determines if a page has one sidebar, two, or none

	From within each page there are actions called.. these actions are under the folder acction. If the file ends in xx_ajx.php it means it is an ajax call from within the page.

	Most ajax calls are under page/feed/head.php and messaging/messaging.js

	The only exception to the mapping happens by the / which is solved inside the page_controller() function under /lib/functions.php or if a page json mapping has a handler that returns a different routing.

	The approach for database layer sql calls is to do it within the same layer of a page or an action for transparency and simplicity. Although unusual (usually this is abstracted into back end layers) it makes debugging and coding much easier to understand.. so it is the recommended approach.

	Translation is built into the framework.. for every mapping there is an object called
	$babel that is global in the system. So when coding if i say $babel->say('hi') while excecuting the url /welcome then the framework will create a language file under /language/en/welcome.en with and entry hi => hi . If you have translator role you can edit this immediatly on the page itself by holding the ctrl button plus click on the text. 

	A video detailing the simple creation of a page is available here

	http://www.youtube.com/watch?v=E5PVy4DGcis


	 






















	￼




