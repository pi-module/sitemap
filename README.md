sitemap
=======

Features
----------------------
* Generat sitemap.xml file manaul 
* Generat sitemap.xml from added links ( add manual link )
* Generat sitemap.xml after add items on each module by API

ToDo
----------------------
* Generat siteman.xml file by cronjob
* Generat module-sitemap.xml and module-table-sitemap.xml 
* Add needed setting options
* Add zip and html sitemap

Use on modules
----------------------
Add this code after save new row
```
if (Pi::service('module')->isActive('sitemap')) {
	$link = array();
	$link['loc'] = Pi:url('YOUR ROTE URL');
	$link['lastmod'] = date("Y-m-d H:i:s"); // Or set empty
	$link['changefreq'] = 'daily'; // Or set empty
	$link['priority'] = 1; // Or set empty
	Pi::api('sitemap', 'sitemap')->add($module, $table, $link);
}
```