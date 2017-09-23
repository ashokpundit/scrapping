require('shelljs/global');
var MongoClient = require('mongodb').MongoClient
    , format = require('util').format;
MongoClient.connect('mongodb://127.0.0.1:27017/scrap',scrap);

function scrap(err,db)
{

        cd('myntra');
        rm('*.xml');
        rm('*.gz');
        exec('wget http://www.myntra.com/sitemap-index.xml.gz');
        //wget('http://www.myntra.com/sitemap-index.xml.gz');
        exec('gunzip sitemap-index.xml.gz')
        //gunzip('sitemap-index.xml.gz');
        var to = require('to');
        var myntraCollection = db.collection('myntra');
        var xmlindexdoc = to.format.xml.load('sitemap-index.xml');
        xmlindexdoc.sitemapindex.sitemap.forEach(function(entry)
            {
		if(!(entry.loc.indexOf("sitemap-pdp")>0)) 
			return;
            exec('wget '+entry.loc);
            var parts=entry.loc.split("/");
            exec('gunzip '+parts[parts.length-1]);
            var filename=parts[parts.length-1].replace(".gz","");
            subIndexDoc = to.format.xml.load(filename);
		console.log(subIndexDoc.urlset.url);
            subIndexDoc.urlset.url.forEach(function(entry1){
            console.log(entry1.loc);

                myntraCollection.insert({_id:entry1.loc}, function(err, docs) {

                  myntraCollection.count(function(err, count) {
                    
                  });
            });
             });
	});
            

} //function scrap end
//console.log(xmlindexdoc.sitemapindex.sitemap);
