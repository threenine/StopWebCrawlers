[![Stop Web Crawlers Logo](http://threenine.co.uk/wp-content/uploads/2016/06/Stop-Web-Crawlers-github-1.png)](http://threenine.co.uk/product/stop-web-crawlers/)

A free WordPress plugin to block referer spammers from your WordPress Blog.

## Development 
** Important : ** This is the main development branch and source code repository for the plugin. If you choose to clone or download code from this repository
please take note that it may not necessarily supported.

## Release version
The release version of this plugin is available  from the WordPress.org Plugin Directory:

[Stop Web Crawlers](https://wordpress.org/plugins/stop-web-crawlers)

## Getting Started
This pugin is hosted on the official WordPress plugin subversion directory, therefore the steps here outline the process
of synchronizing updates between the two repositories.

1. Clone the GitHub Repo 

	SSH

		$ git clone git@github.com:threenine/StopWebCrawlers.git
		
	HTTPS
		
		$ git clone https://github.com/threenine/StopWebCrawlers.git

 2. Change into the Directory
 
 
 		$ cd StopWebCrawlers
 		

3. Set Up a Subversion tracking branch
	
			
		$ git branch --no-track svnsync
		
		$ git svn init -s https://plugins.svn.wordpress.org/stop-web-crawlers/ --prefix=origin/
		
		$ git svn fetch  --log-window-size 10000    #CAUTION THIS LINE TAKES A LONG TIME TO COMPLETE
		
		$ git reset --hard origin/trunk