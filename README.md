[![Stop Web Crawlers Logo](http://threenine.co.uk/wp-content/uploads/2016/06/Stop-Web-Crawlers-github-1.png)](http://threenine.co.uk/product/stop-web-crawlers/)
[![PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://www.paypal.me/geekiam)

A free WordPress plugin to block referer spammers from your WordPress Blog.

## Development 
** Important : ** This is the main development branch and source code repository for the plugin. If you choose to clone or download code from this repository
please take note that it may not necessarily supported.

## Release version
The release version of this plugin is available  from the WordPress.org Plugin Directory:

[Stop Web Crawlers - WordPress.com Plugin Directory](https://wordpress.org/plugins/stop-web-crawlers)

For more information regarding the plugin visit our plug-in home page

[Stop Web Crawlers ](http://threenine.co.uk/plugins/stop-web-crawlers/)

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
		
4. Merge changes from Subversion to GitHub

		$ git checkout svnsync
		
		$ git svn rebase
		
		$ git checkout master
		
		$ git merge svnsync
		
		$ git push origin master
		
5. Merge changes from GitHub and publish to SubVersion

		$ git checkout master
		
		$ git pull origin master
		
		$ git checkout svnsync
		
		$ git svn rebase
		
		$ git merge --no-ff master
		
		$ git commit
		
		$ git svn dcommit
		
### Tagging Releases
Tagging a release in Git is very simple:

	$ git tag v1.0.2

To create an SVN tag, simply:

	$ git svn tag 1.0.2

This will create `/tags/1.0.2` in the remote SVN repository and copy all the files from the remote `/trunk` into that tag, so be sure to push all the latest code to `/trunk` before creating an SVN tag.

### Subversion tagging

It appears that there is now an issue with git svn tagging. We now have to tag using subversion directly.
Download code from svn Repo

		$ svn checkout https://plugins.svn.wordpress.org/stop-web-crawlers/
		$ svn cp https://plugins.svn.wordpress.org/stop-web-crawlers/trunk https://plugins.svn.wordpress.org/stop-web-crawlers/tags/1.3.1


		
