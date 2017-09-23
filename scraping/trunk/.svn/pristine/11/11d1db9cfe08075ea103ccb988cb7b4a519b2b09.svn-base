var express = require('express');
var fs = require('fs');
var request = require('request');
var cheerio = require('cheerio');
var app     = express();
var builder = require('xmlbuilder');



	var options = {
			url: "http://119.81.25.115:8888?url=http://www.snapdeal.com/sitemap.xml&type=xml&selector=.loc",
			method: "POST",
			headers:{
						'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0'
					}	
	};	
		request(options, function(error, response, html){		
			if(!error){					
						var bodyy=response.body;
				var bodyArr=	JSON.parse(bodyy);
				var typeele=typeof bodyArr;
				//console.log(typeele);
				//console.log(bodyArr[0].results);
				var counter=0;
				var totalCount=bodyArr[0].results.length;
				//console.log("totalcount"+totalCount);
				for(counter=0;counter<1;counter++)
				{
					var url=bodyArr[0].results[counter];
					GetProductUrl(url);
					//console.log(ab);
				}
					//fs.writeFile('output.json', JSON.stringify(bodyy, null, 4), function(err){})
					// Finally, we'll just send out a message to the browser reminding you that this app does not have a UI.
					//res.send('Check your console!')
			}
		})

function GetProductUrl(Url)
{	
console.log('GetProductUrl');
	Url="http://119.81.25.115:8888?url=" + Url+"&type=xml&selector=.loc";
	console.log(Url);	
	var options = {
			//host: 'http://119.81.25.115:8888',//http://119.81.25.115:8888?url=" + Url+"&type=xml&selector=.loc",
			url:Url,
			type:'xml',
			//selector:'.loc',
			method: 'POST',
			
			headers:{
						'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0'
					}	
	};	
		request(options, function(error, response, html){		
			if(!error){					
			    var body2=response.body;
				var bodyArr2=	JSON.parse(body2);								
				for(counter=0;counter<1;counter++)
				{
					var url=bodyArr2[0].results[counter];
					GetProductAtt(url);					
				}
				//console.log(body2);
				//fs.writeFile('output.json', JSON.stringify(bodyArr2[0].results[0], null, 4), function(err){})
				
			}
		})
	
}
function GetProductAtt(Url)
{
	console.log('GetProductAtt');
	console.log(Url);
	

var jsonData={
  "url":Url,
    "type": "html",
    "map": {
      "breadcrumb":{
        "selector":"span[itemprop='title']",
        "extract":"text"
      },
        "title": {
            "selector": "h1[itemprop='name'] ",
            "extract": "text"
        },
        "brandId": {
            "selector": "#brndId",
            "extract": "text"
        },
        "category": {
            "selector": "#categoryId",
            "extract": "text"
        },
        "supc": {
            "selector": "#defaultSupc",
            "extract": "text"
        },
        "description":{
            "selector": ".details-content",
            "extract": "text"
          
        },
        "mrp": {
            "selector": "#original-price-id",
            "extract": "text"
        },
        "price": {
            "selector": "#selling-price-id",
            "extract": "text"
        },
		 
        "discount": {
            "selector": "#discount-id",
            "extract": "text"
        },
        "image": {
            "selector": "#product-thumbs li.imgthumb img",
            "extract": "src"
        },
		 "size": {
            "selector": "#productAttributesJson",
            "extract": "value"
        },
        "availability": {
            "selector": "link[itemprop='availability']",
            "extract": "href"
        }
        
    }
};
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
					console.log("call next last function");
						JsonToXml(JsonData,"");
					//fs.writeFile('output.json', JSON.stringify(JsonData, null, 4), function(err){})
					// Finally, we'll just send out a message to the browser reminding you that this app does not have a UI.
					res.send('Check your console!')
			}
		});
		myntraReq.write(data);
		myntraReq.end();


}
function JsonToXml(Data,url)
{
console.log('JsonToXml');
console.log(Data);
	var rw='';
	var Gender="";
	rw='<?xml version="1.0"?><item>';
	rw=rw + "<id>";
	var id=Data[0].results["supc"].toString();	
	rw=rw + id.trim() + "</id>";
	
	rw=rw+"<color>as shown</color>";
	rw=rw+"<url>"+url+"</url>";
	rw=rw+"<oid>"+"snapdeal_"+id+"</oid>";
	rw=rw+"<image_url>"+url+"</image_url>";
	rw=rw+"<sku>"+id+"</sku>";
	var dept=Data[0].results["breadcrumb"][1].toString().toLowerCase();
	if(dept.indexOf("men")>-1)
	{
		Gender="men";	
	}
	else if(dept.indexOf("women")>-1)
	{
		Gender="women";	
	}	
	var dept=dept.replace("men's",'').replace("women's",'').trim();	
	rw=rw+"<gender>"+Gender+"</gender>";
	rw=rw+"<host>www.snapdeal.com</host>";
	var brand=Data[0].results["brandId"];
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
	rw=rw+"<price>"+Data[0].results["mrp"]+"</price>";
	rw=rw+"<discount>"+Data[0].results["discount"]+"</discount>";
	rw=rw+"<inserted>"+Data[0].created+"</inserted>";
	//rw=rw+"<out>";
	var available=Data[0].results["availability"][0].toLowerCase();
	if(available.indexOf("instock")>-1)
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
	for(var i=0;i<Data[0].results["image"].length; i++)
	{
		if(Data[0].results["image"][i]!=null)
		{
			rw=rw+"<tag>"+Data[0].results["image"][i]+"</tag>";
		}
	}
	rw=rw+"</images>";
	//rw=rw+"<material><tag></tag></material>";
	console.log(Data[0].results["size"]);
	rw=rw+"<sizes>";
		
	var Data2=JSON.parse(Data[0].results["size"]);
	
	
	for(var i=0;i<Data2.length; i++)
	{
		var soldout=Data2[i]["soldOut"].toString().trim();
		var size=Data2[i]["value"].toString().trim();	
		console.log(soldout + size);	
		if(soldout.toLowerCase()=='false')
		{		 
		 rw=rw+"<tag>"+size+"</tag>";
		}
	}
	rw=rw+"</sizes>";
	//rw=rw+"<thumbs><tag></tag></thumbs>";
	rw=rw+"</tags>";
	rw=rw+"<coupons><coupon>"+Data[0].results["discount"]+"</coupon></coupons>";
	var offvalue=Data[0].results["discount"];
	rw=rw+"<value>"+offvalue+"</value>";
	rw=rw+"<type>percent</type>";
	//rw=rw+"</coupon></coupons></item>";
	rw=rw+"</item>";
	console.log(rw);
	//fs.writeFile('output.json', JSON.stringify(rw, null, 4), function(err){})
}
//app.listen('8081')
//console.log('Magic happens on port 8081');
//exports = module.exports = app;

