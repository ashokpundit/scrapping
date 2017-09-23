require('shelljs/global');
var MongoClient = require('mongodb').MongoClient
    , format = require('util').format;
MongoClient.connect('mongodb://127.0.0.1:27017/scrap',scrap);

function scrap(err,db)
{

        cd('jabong');
        rm('sitemapxml');
        rm('*.gz');
        exec('wget http://www.jabong.com/sitemapgen/sitemapxml/');
        //wget('http://www.myntra.com/sitemap-index.xml.gz');
        //exec('gunzip sitemap-index.xml.gz')
        //gunzip('sitemap-index.xml.gz');
        var to = require('to');
        var myntraCollection = db.collection('jabong');
        var xmlindexdoc = to.format.xml.load('index.html');
        var count=0;
        xmlindexdoc.sitemapindex.sitemap.forEach(function(entry)
            {
		if(!(entry.loc.indexOf("product")>0)) 
			return;
              var filename='index'+(count++);
            exec('wget -O '+filename+" "+entry.loc);
//            var parts=entry.loc.split("/");
//            exec('gunzip '+parts[parts.length-1]);
            
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
