from PIL import Image;
from PIL import ImageStat;
import xml.etree.ElementTree as ET;
import sys;
from random import randint;
import os, os.path;
from base64 import b64encode;
import datetime;

dataPath = "../../data/";
genericPath = "../res/generic/";
genericImageList = os.listdir("../"+genericPath);
today = str(datetime.date.today());

logfile = "./" + today + ".log";

with open(logfile, 'a+') as log:
	log.write("\n\n--- " + str(datetime.datetime.now().time()) + "------(CUSTOMNEWS) SIZE FILTER PHASE");

	if(len(sys.argv) == 4):
		inputFileName = sys.argv[1];
		minw = int(sys.argv[2]);
		minh = int(sys.argv[3]);
		log.write("\nFILE : " + inputFileName + " Min size : " + str(minw) + "x" + str(minh));

		path = inputFileName;
		try:
			feed = ET.parse(path).getroot();
		except ET.ParseError, e:
			print "Error while parsing : " + str(e);
			log.write("\n Error while parsing : " + str(e));
			sys.exit();

		imgElement = feed.find("img_link");
		if(imgElement is not None and imgElement.text != "none"):
			log.write("img : " + str(imgElement.text));
			try:
				
				with Image.open("../" + imgElement.text) as img:
					w, h = img.size;
					if w < minw or h < minh:
#						os.remove(feed.find("img_link").text);
						randomName = genericImageList[randint(0,len(genericImageList)-1)];
								#dotIndex = randomName.rfind(".");
						feed.find("img_link").text = genericPath + randomName;
						stats = ImageStat.Stat(Image.open("../" + genericPath + randomName));
						log.write("size");
					else:
						stats = ImageStat.Stat(img);

					if(len(stats.mean) <= 2 ):
						if(stats.mean/255 <= 0.5):
							tcolor = "white";
						else:
							tcolor = "black";
						dominant = stats.mean[0];
					else:
						a = ( 0.299 * stats.mean[0] + 0.587 * stats.mean[1] + 0.114 * stats.mean[2])/255;
						if(a <= 0.5):
							tcolor = "white";
						else:
							tcolor = "black"
						dominant = str(stats.mean[0]) +","+str(stats.mean[1])+","+str(stats.mean[2]);
					log.write("\nASIDASODIAS");	
					feed.find("img_link").set("textColor", tcolor);
					feed.find("img_link").set("dominantColor", dominant);

			except Exception,e:
				print "Exception raised while processing image " + imgElement.text + " : " + str(e);
				log.write("\nException raised while processing image " + imgElement.text + " : " + str(e));
				randomName = genericImageList[randint(0,len(genericImageList)-1)];
				feed.find("img_link").text = genericPath + randomName;
				stats = ImageStat.Stat(Image.open("../" + genericPath + randomName));
				if(len(stats.mean) <= 2 ):
					if(stats.mean/255 <= 0.5):
						tcolor = "white";
					else:
						tcolor = "black";
					dominant = stats.mean[0];
				else:
					a = ( 0.299 * stats.mean[0] + 0.587 * stats.mean[1] + 0.114 * stats.mean[2])/255;
					if(a <= 0.5):
						tcolor = "white";
					else:
						tcolor = "black"
					dominant = str(stats.mean[0]) +","+str(stats.mean[1])+","+str(stats.mean[2]);	
				feed.find("img_link").set("textColor", tcolor);
				feed.find("img_link").set("dominantColor", dominant);
		else:	
			log.write("asdfin");
			randomName = genericImageList[randint(0,len(genericImageList)-1)];
			feed.find("img_link").text = genericPath + randomName;
			stats = ImageStat.Stat(Image.open("../" + genericPath + randomName));
			if(len(stats.mean) <= 2 ):
				if(stats.mean/255 <= 0.5):
					tcolor = "white";
				else:
					tcolor = "black";
				dominant = stats.mean[0];
			else:
				a = ( 0.299 * stats.mean[0] + 0.587 * stats.mean[1] + 0.114 * stats.mean[2])/255;
				if(a <= 0.5):
					tcolor = "white";
				else:
					tcolor = "black"
				dominant = str(stats.mean[0]) +","+str(stats.mean[1])+","+str(stats.mean[2]);	
			feed.find("img_link").set("textColor", tcolor);
			feed.find("img_link").set("dominantColor", dominant);
		dest = open(path+"_processed.xml", 'w');
		dest.write(ET.tostring(feed));
		dest.close();
		print "success";
	else:
		print "error Usage : python size_filter.py width height";
		log.write("\nMistaken invocation");
