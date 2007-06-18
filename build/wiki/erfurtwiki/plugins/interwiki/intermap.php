<?php


 # this is an array of InterWiki links to replace the short one inside
 # ewiki.php (you must include() this)
 # please see also http://usemod.com/intermap.txt, which is commonly
 # used as base


 $ewiki_config["interwiki"] = array_merge(
 @$ewiki_config["interwiki"],
 array(
	"ALife"		=>	"http://news.alife.org/wiki/index.php?",
	"AbbeNormal"	=>	"http://www.ourpla.net/cgi-bin/pikie.cgi?",
	"AcadWiki"	=>	"http://xarch.tu-graz.ac.at/autocad/wiki/",
	"Acronym"	=>	"http://www.acronymfinder.com/af-query.asp?String=exact&Acronym=",
	"Advogato"	=>	"http://www.advogato.org/",
	"AndStuff"	=>	"http://andstuff.org/wiki.php?",
	"Dictionary"	=>	"http://www.dictionary.com/cgi-bin/dict.pl?term=",
	"BcWireless"	=>	"http://www.bcwireless.net/wiki-moinmoin/moin.cgi/",
	"Annotation"	=>	"http://bayle.stanford.edu/crit/nph-med.cgi/",
	"AnnotationWiki"=>	"http://www.seedwiki.com/page.cfm?wikiid=368&doc=",
	"AwarenessWiki"	=>	"http://taoriver.net/aware/",
	"BenefitsWiki"	=>	"http://www.benefitslink.com/cgi-bin/wiki.cgi?",
	"BrainBench"	=>	"http://www.brainbench.com/transcript.jsp?pid=",
	"BridgesWiki"	=>	"http://c2.com/w2/bridges/",
	"C2find"	=>	"http://c2.com/cgi/wiki?FindPage&value=",
	"CLiki"		=>	"http://www.telent.net/cliki/",
	"Cache"		=>	"http://www.google.com/search?q=cache:",
	"CmWiki"	=>	"http://www.ourpla.net/cgi-bin/wiki.pl?",
	"CreationMatters"=>	"http://www.ourpla.net/cgi-bin/wiki.pl?",
	"DejaNews"	=>	"http://www.deja.com/=dnc/getdoc.xp?AN=",
	"DocBook"	=>	"http://docbook.org/wiki/moin.cgi/",
	"DolphinWiki"	=>	"http://www.object-arts.com/wiki/html/Dolphin/",
	"DseWiki"	=>	"http://www.wikiservice.at/dse/wiki.cgi?",
	"ErfurtWiki"	=>	"http://erfurtwiki.sourceforge.net/?id=",
	"EfnetCeeWiki"	=>	"http://wiki.rm-f.net/moin.cgi/",
	"EfnetCppWiki"	=>	"http://purl.net/wiki/cpp/",
	"EfnetPythonWiki"=>	"http://purl.net/wiki/python/",
	"EfnetXmlWiki"	=>	"http://purl.net/wiki/xml/",
	"EljWiki"	=>	"http://elj.sourceforge.net/phpwiki/index.php/",
	"EmacsWiki"	=>	"http://www.emacswiki.org/cgi-bin/wiki.pl?",
	"FOLDOC"	=>	"http://foldoc.doc.ic.ac.uk/foldoc/foldoc.cgi?query=",
	"Foldoc"	=>	"http://www.foldoc.org/foldoc/foldoc.cgi?",
	"FoxWiki"	=>	"http://fox.wikis.com/wc.dll?Wiki~",
	"FreeBSDman"	=>	"http://www.FreeBSD.org/cgi/man.cgi?apropos=1&query=",
	"FreshMeat"	=>	"http://freshmeat.net/",
	"FreeNetworks"	=>	"http://www.freenetworks.org/index.cgi/",
	"Google"	=>	"http://www.google.com/search?q=",
	"GoogleGroups"	=>	"http://groups.google.com/groups?q=",
	"HammondWiki"	=>	"http://www.dairiki.org/HammondWiki/index.php3?",
	"Haribeau"	=>	"http://wiki.haribeau.de/cgi-bin/wiki.pl?",
	"IAWiki"	=>	"http://www.IAwiki.net/",
	"IMDB"		=>	"http://us.imdb.com/Title?",
	"ISBN"		=>	"http://www.amazon.com/exec/obidos/ISBN=",
	"JargonFile"	=>	"http://sunir.org/apps/meta.pl?wiki=JargonFile&redirect=",
	"JiniWiki"	=>	"http://www.cdegroot.com/cgi-bin/jini?",
	"JspWiki"	=>	"http://www.ecyrd.com/JSPWiki/Wiki.jsp?page=",
	"JuraWiki"	=>	"http://jurawiki.de/",
	"KnowHow"	=>	"http://www2.iro.umontreal.ca/~paquetse/cgi-bin/wiki.cgi?",
	"LegoWiki"	=>	"http://www.object-arts.com/wiki/html/Lego-Robotics/",
	"LinuxWiki"	=>	"http://linuxwiki.de/",
	"MathSongsWiki"	=>	"http://SeedWiki.com/page.cfm?wikiid=237&doc=",
	"MbTest"	=>	"http://www.usemod.com/cgi-bin/mbtest.pl?",
	"MeatBall"	=>	"http://www.usemod.com/cgi-bin/mb.pl?",
	"UseMod"	=>	"http://www.usemod.com/cgi-bin/wiki.pl?",
	"MetaWiki"	=>	"http://sunir.org/apps/meta.pl?",
	"MoinMaster"	=>	"http://moinmaster.wikiwikiweb.de/",
	"MoinMoin"	=>	"http://moinmoin.wikiwikiweb.de/",
	"MoinPurl"	=>	"http://purl.net/wiki/moin/",
	"MuWeb"		=>	"http://www.dunstable.com/scripts/MuWebWeb?",
	"OpenWiki"	=>	"http://openwiki.com/?",
	"OrgPatterns"	=>	"http://www.bell-labs.com/cgi-user/OrgPatterns/OrgPatterns?",
	"PPR"		=>	"http://c2.com/cgi/wiki?",
	"PangalacticOrg"=>	"http://www.pangalactic.org/Wiki/",
	"PersonalTelco"	=>	"http://www.personaltelco.net/index.cgi/",
	"PhpWiki"	=>	"http://phpwiki.sourceforge.net/phpwiki/index.php3?",
	"PhpFunction"	=>	"http://www.php.net/manual/en/function.",
	"PhpManual"	=>	"http://www.php.net/manual-lookup.php?pattern=",
	"Pikie"		=>	"http://pikie.darktech.org/cgi/pikie?",
	"PolitizenWiki"	=>	"http://www.politizen.com/wiki.asp?",
	"PurlNet"	=>	"http://purl.oclc.org/NET/",
	"PyWiki"	=>	"http://www.voght.com/cgi-bin/pywiki?",
	"KmWiki"	=>	"http://www.voght.com/cgi-bin/pywiki?",
	"PythonInfo"	=>	"http://www.python.org/cgi-bin/moinmoin/",
	"PythonWiki"	=>	"http://www.pythonwiki.de/",
	"RFC"		=>	"http://www.ietf.org/rfc/rfc",
	"SVGWiki"	=>	"http://www.protocol7.com/svg-wiki/default.asp?",
	"SeaPig"	=>	"http://www.seapig.org/",
	"SeattleWireless"=>	"http://seattlewireless.net/?",
	"SenseisLibrary"=>	"http://senseis.xmp.net/?",
	"SourceForge"	=>	"http://sourceforge.net/",
	"Squeak"	=>	"http://minnow.cc.gatech.edu/squeak/",
	"StrikiWiki"	=>	"http://ch.twi.tudelft.nl/~mostert/striki/teststriki.pl?",
	"TMwiki"	=>	"http://www.EasyTopicMaps.com/?page=",
	"TWiki"		=>	"http://twiki.sourceforge.net/cgi-bin/view/",
	"TwikiOrg"	=>	"http://twiki.org/cgi-bin/view/",
	"TamTam"	=>	"http://boo.mi2.hr:10000/TamTam/",
	"Tavi"		=>	"http://tavi.sourceforge.net/index.php?",
	"Thinki"	=>	"http://www.thinkware.se/cgi-bin/thinki.cgi/",
	"TwistedWiki"	=>	"http://purl.net/wiki/twisted/",
	"VisualWorks"	=>	"http://wiki.cs.uiuc.edu/VisualWorks/",
	"WebDevWikiNL"	=>	"http://www.promo-it.nl/WebDevWiki/index.php?page=",
	"Why"		=>	"http://clublet.com/c/c/why?",
	"Wiki"		=>	"http://c2.com/cgi/wiki?",
	"WikiPedia"	=>	"http://www.wikipedia.com/wiki.cgi?",
	"WikiPediaOld"	=>	"http://www.wikipedia.com/wiki.phtml?title=",
	"ZWiki"		=>	"http://www.zwiki.org/",
	"Faqs"		=>	"http://www.faqs.org/rfcs/rfc",
	"Thesaurus"	=>	"http://www.thesaurus.com/cgi-bin/search?config=roget&words="
));

?>