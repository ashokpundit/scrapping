var express = require('express');
var fs = require('fs');
var request = require('request');
var cheerio = require('cheerio');
var app     = express();
var builder = require('xmlbuilder');

var MongoClient = require('mongodb').MongoClient
    , format = require('util').format;
MongoClient.connect('mongodb://127.0.0.1:27017/scrap',scrap);
var myntraCollection=null;

function scrap(err,db)
{
    var url="http://www.myntra.com/sports-shoes/reebok/reebok-men-blue-realflex-speed-20-sports-shoes/342054/buy?src=search&uq=&q=men-sports-shoes&p=2";
    getItem(url);
      myntraCollection = db.collection('myntra');
    myntraCollection.find({"xml":{$exists:true}}).toArray(function(err, docs) {
        //fs.writeFile('output.json', '<?xml version="1.0"?><items>', function(err){});
   //docs.forEach(function(doc){
      // console.log(doc._id);
      var i=0;length=docs.length;
      for(i=0;i<length;i++)
      {
          console.log(docs[i]._id);

             getItem(docs[i]._id);
        }
       
  // });
    //fs.writeFile('output.json', '</items>', function(err){});
   
    //console.dir(docs)
    
  }); 
}


var jsonData={
 "url": "",
    "type": "html",
    "map": {
      "breadcrumb":{
        "selector":"ul.breadcrumb li",
        "extract":"text"
      },
        "title": {
            "selector": "h1.title",
            "extract": "text"
        },       
      
        "description":{
            "selector": "div.description",
            "extract": "text"
          
        },
		 "size": {
            "selector": ".size",
            "extract": "text"
        },
		
		 "Mrp": {
            "selector": "span.strike",
            "extract": "text"
        },
		"Discount":{
			"selector":"span.discount",
			"extract":"text"
			
		},
        "price": {
            "selector": "div.price",
            "extract": "text"
        },
		 "Availability": {
            "selector": "div.buy button.button",
            "extract": "text"
        },
        "image": {
            "selector": "img[height='64']",
            "extract": "data-blowup"
        },
        "brand": {
            "selector": "p.brand-logo a",
            "extract": "title"
        },
		 "product_id": {
            "selector": "h4.id",
            "extract": "text"
        }        
        
    }
};
 function getItem(url){
jsonData["url"]=url;
var data= JSON.stringify(jsonData);
	var options = {
			"url": "http://119.81.25.115:8888",
			
			"method": "POST",
			"headers":{
						'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0',
						'Content-Type': 'application/json',
						"Content-Length": Buffer.byteLength(data),
					}	
	};	
				var myntraReq=request(options, function(error, response, html){		
					if(!error){					
					var JsonData=JSON.parse(response.body);
						var responseData=JsonToXml(JsonData,url);
                                                console.log(responseData);
                                                myntraCollection.update({_id:url},{xml:responseData}, function(err, result) {if(err) console.log(err);});
					//fs.writeFile('output.json', JSON.stringify(JsonData, null, 4), function(err){})
					// Finally, we'll just send out a message to the browser reminding you that this app does not have a UI.
					//res.send('Check your console!')
			}
		});
		myntraReq.write(data);
		myntraReq.end();
                

}

function JsonToXml(Data,url)
{

	var rw='';
	var mrp=Data[0].results["Mrp"].toString().replace('Rs.','').replace(',','').trim();
	
	var price=Data[0].results["price"].toString().replace('Rs.','').replace(',','').trim();
	var discount=Data[0].results["Discount"].toString().replace('Rs.','').replace(',','').trim();
	
//	console.log("price="+price)
	//price=price.replace(discount,"");//.replace(mrp,'').replace('click for offer','').trim();
//	console.log("price="+price);
//	console.log("mrp="+mrp + "price="+price+"discount="+discount);
	rw=rw + "<id>";
	var id=Data[0].results["product_id"].toString();
        console.log("id="+id);
	id=id.replace('Product','').replace('Code','').replace(':','').trim();
	console.log("id="+id);
	rw=rw + id.trim() + "</id>";
	
	rw=rw+"<color>as shown</color>";
	rw=rw+"<url>"+url+"</url>";
	rw=rw+"<oid>"+"myntra_"+id+"</oid>";
	rw=rw+"<image_url>"+url+"</image_url>";
	rw=rw+"<sku>"+id+"</sku>";
	
	var dept=Data[0].results["breadcrumb"][1];
	var dept_Gender=Data[0].results["breadcrumb"][2];
	var Gender='';
        if(typeof dept_Gender!='undefined')
            Gender=dept_Gender.replace(dept,'');
	rw=rw+"<gender>"+Gender+"</gender>";
	rw=rw+"<host>www.myntra.com</host>";
	var brand=Data[0].results["brand"];
	rw=rw+"<brand>"+brand+"</brand>";
	//rw=rw+"<rank></rank>";
	
	var breadcrumb=Data[0].results["breadcrumb"];//.replace(',',' &gt');	
	rw=rw+"<breadcrumbs>" + breadcrumb +"</breadcrumbs>";
	
	rw=rw+"<category>";	
	for(var i=0;i<Data[0].results["breadcrumb"].length;i++)
	{
		rw=rw+"<level>"+Data[0].results["breadcrumb"][i]+"</level>";
	}
	rw=rw+"</category>";
	
	rw=rw+"<name>"+Data[0].results["title"]+"</name>";
	rw=rw+"<currency>INR</currency>";
	rw=rw+"<price>"+price+"</price>";
	rw=rw+"<discount>"+discount+"</discount>";
	rw=rw+"<inserted>"+Data[0].created+"</inserted>";
	//rw=rw+"<out>";
	var available=Data[0].results["Availability"];
	if(available=='Buy Now')
	{
			rw=rw+"<out>true</out>";
			rw=rw+"<deleted>false</deleted>";
	}
	else{
		rw=rw+"<out>false</out>";
		rw=rw+"<deleted>true</deleted>";
	}
	rw=rw+"<info>"+Data[0].results["description"][0]+"</info>";
	
	rw=rw+"<tags><images>";
	for(var i=0;i<Data[0].results["image"].length;i++)
	{
		rw=rw+"<tag>"+Data[0].results["image"][i]+"</tag>";
	}
	rw=rw+"</images>";
	rw=rw+"<material><tag></tag></material>";
	rw=rw+"<sizes>";
	for(var i=0;i<Data[0].results["size"].length;i++)
	{
		rw=rw+"<tag>"+Data[0].results["size"][i]+"</tag>";
	}
	rw=rw+"</sizes>";
	//rw=rw+"<thumbs><tag></tag></thumbs>";
	rw=rw+"</tags>";
	rw=rw+"<coupons><coupon>"+Data[0].results["Discount"];
	var offvalue=discount.replace('(','').replace(')','').replace('%','').replace('OFF','').trim();
	rw=rw+"<value>"+offvalue+"</value>";
	rw=rw+"<type>percent</type>";
	//rw=rw+"</coupon></coupons></item>";
	rw=rw+"</coupon></coupons></item>";
        return rw;
	//console.log(rw);
	//fs.writeFile('output.json', JSON.stringify(rw, null, 4), function(err){});
}
//app.listen('8081')
//console.log('Magic happens on port 8081');
//exports = module.exports = app;


