require('shelljs/global');
/*rm('sitemap-index.xml');
exec('wget http://www.myntra.com/sitemap-index.xml.gz');
//wget('http://www.myntra.com/sitemap-index.xml.gz');
exec('gunzip sitemap-index.xml.gz')
//gunzip('sitemap-index.xml.gz');*/
var to = require('to');
var xmlindexdoc = to.format.xml.load('sitemap-index.xml');
xmlindexdoc.sitemapindex.sitemap.forEach(function(entry){
exec('wget '+entry.loc);
var parts=entry.loc.split("/");
exec('gunzip '+parts[parts.length-1]);

console.log(entry.loc);

});

//console.log(xmlindexdoc.sitemapindex.sitemap);
