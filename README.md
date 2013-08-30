sitemap
=======

Features
----------------------
* Generat sitemap.xml file manaul 
* Generat sitemap.xml from added links ( add manual link )
* Generat sitemap.xml after add items on each module by API
* Generat module-table-sitemap.xml 

ToDo
----------------------
* Generat siteman.xml file by cronjob
* Add needed setting options
* Add zip and html sitemap

Use on modules
----------------------
Add this code after save new row
```
if (Pi::service('module')->isActive('sitemap')) {
	$link = array();
	$link['loc'] = Pi::url('YOUR ROTE URL');
	$link['lastmod'] = date("Y-m-d H:i:s"); // Or set empty
	$link['changefreq'] = 'daily'; // Or set empty
	$link['priority'] = 1; // Or set empty
	Pi::api('sitemap', 'sitemap')->add($module, $table, $link);
}
```
* $module : your module
* $table : your item table
* $link['loc'] : URL of the page. This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires it. This value must be less than 2,048 characters.
* $link['lastmod'] : The date of last modification of the file. This date should be in W3C Datetime format. This format allows you to omit the time portion, if desired, and use YYYY-MM-DD.
* $link['changefreq'] : How frequently the page is likely to change. This value provides general information to search engines and may not correlate exactly to how often they crawl the page.  Valid values are: ( always hourly daily weekly monthly yearly never )
* $link['priority'] : The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0. This value does not affect how your pages are compared to pages on other sites—it only lets the search engines know which pages you deem most important for the crawlers.

