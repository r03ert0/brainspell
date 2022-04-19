var debug=0;

var	tasks;
var	cognitive;
var	behavioural;
var	concept;
var	minVotes=0;	// Minimum number of votes a tag must have to be displayed	
var meta;
var exp;
var ArticlePMID;
var EmptyArticle;
var flagIsNIDM;
var renderer;

function updateArticle()
{
	if(debug) console.log("[updateArticle] Login update at article level (Login: "+(loggedin?"Yes":"No")+")");
	
	/*
		Article-level display
		---------------------
	*/
	if(loggedin)
	{
		// Metadata MeSH tags
		/*
		TO DO!
		if(exp[-1].tags)
			for(j=0;j<exp[-1].tags.length;j++)
			{
				var rtag=exp[-1].tags[j];
				rtag.agree=(rtag.agree)?parseInt(rtag.agree):0;
				rtag.disagree=(rtag.disagree)?parseInt(rtag.disagree):0;
				
				var	prevVote=findPreviousVoteByUser(-1,rtag);
				if(rtag.agree+rtag.disagree>=minVotes || prevVote!=0)
					addTagColor(iExp,rtag);
				else
					removeTagColor(iExp,rtag);
			}
		*/

		// New article warning
		$("#new-article-warning button").removeAttr('disabled');
		$("#new-nidm-article-warning button").removeAttr('disabled');
		
		// Metadata stereotaxic space
		$("span#Talairach").show();
		$("span#MNI").show();
		$("input:radio[value='Talairach']").removeAttr('disabled');
		$("input:radio[value='MNI']").removeAttr('disabled');
		if(meta.stereo)
		{
			// own info
			var	prevSpace=findPreviousStereoSpaceByUser();
			if(prevSpace!=-1)
				$("input:radio[value='"+prevSpace+"']").attr('checked',true);

			// others info
			if(meta.stereo.Talairach+meta.stereo.MNI>=minVotes)
			{
				$("span#Talairach").html('('+meta.stereo.Talairach+')');
				$("span#MNI").html('('+meta.stereo.MNI+')');
			}
		}
		
		// Metadata number of subjects
		$("input#nSubjects").removeAttr('disabled');
		$("span#nSubjects").show();
		if(meta.nsubjects)
		{
			// own info
			var	nPrevNSubjects=findPreviousNSubjectsByUser();
			if(nPrevNSubjects!=-1)
				$("input#nSubjects").val(nPrevNSubjects);
			else
				$("input#nSubjects").val("");

			// others info
			if(meta.nsubjects.length>=minVotes)
			{
				var	i,x,min,max,n=meta.nsubjects.length;
				min=max=meta.nsubjects[0];
				for(i=0;i<n;i++){	x=parseInt(meta.nsubjects[i]);
									if(x<min) min=x;
									if(x>max) max=x;}
				if(min!=max)
					$("span#nSubjects").text("("+n+" values, min="+min+", max="+max+")");
				else
					$("span#nSubjects").text("("+n+" value"+((n>1)?"s)":")"));
			}
		}

		// Concept modal
		if($("#box"))
		{
			$('button#agree').removeAttr('disabled');
			$('button#disagree').removeAttr('disabled');
			$('.buttonsThing div#login-message').remove();
		}
				
		// Discussion text area
		$("button#comment").removeAttr('disabled');
		$("span#commentWarning").hide();
	}
	else
	{
		// New article warning
		$("#new-article-warning button").attr('disabled',true);
		
		// Metadata stereotaxic space
		$("input:radio[value='Talairach']").attr('disabled',true);
		$("input:radio[value='MNI']").attr('disabled',true);
		if(meta.stereo && (meta.stereo.Talairach+meta.stereo.MNI>=minVotes))
		{
			if(meta.stereo.Talairach>meta.stereo.MNI)
				$("input:radio[value='Talairach']").attr('checked',true);
			else
				$("input:radio[value='MNI']").attr('checked',true);
			$("span#Talairach").show();
			$("span#MNI").show();
			$("span#Talairach").html('('+meta.stereo.Talairach+')');
			$("span#MNI").html('('+meta.stereo.MNI+')');
		}
		else
		{
			$("input:radio[value='Talairach']").removeAttr('checked');
			$("input:radio[value='MNI']").removeAttr('checked');
			$("span#Talairach").hide();
			$("span#MNI").hide();
		}
		
		// Metadata number of subjects
		$("input#nSubjects").attr('disabled',true);
		if(meta.nsubjects && meta.nsubjects.length>=minVotes)
		{
			$("span#nSubjects").show();
			var	i,x,mean=0,min,max,n=meta.nsubjects.length;
			min=max=meta.nsubjects[0];
			for(i=0;i<n;i++){	x=parseInt(meta.nsubjects[i]);
								mean+=x/n;
								if(x<min) min=x;
								if(x>max) max=x;}
			$("input#nSubjects").val(mean.toFixed(1));
			if(min!=max)
				$("span#nSubjects").text("("+n+" values, min="+min+", max="+max+")");
			else
				$("span#nSubjects").text("("+n+" values)");
		}
		else
		{
			$("input#nSubjects").val("");
			$("span#nSubjects").hide();
		}

		// Discussion text area
		$("button#comment").attr('disabled',true);
		$("span#commentWarning").show();
	}
}
function findExperimentByEID(eid) {
	var i;
	for(i=0;i<exp.length;i++)
		if(exp[i].id==eid)
			break;
	if(i==exp.length) {
		console.log("ERROR: eid "+eid+" not found!");
	}
	return exp[i];
}
function findIndexOfExperimentByEID(eid) {
	var i;
	for(i=0;i<exp.length;i++)
		if(exp[i].id==eid)
			break;
	if(i==exp.length) {
		console.log("ERROR: eid "+eid+" not found!");
		i=-1;
	}
	return i;
}
function updateExperiment(eid)
{
	if(debug) console.log("[updateExperiment] Login update at experiment "+eid+" (Login: "+(loggedin?"Yes":"No")+")");
	
	/*
		Experiment-level display
		------------------------
	*/
	if(loggedin)
	{
		$(".experiment#"+eid+" .title").addClass('stored');
		$(".experiment#"+eid+" .caption").addClass('stored');
		$(".experiment#"+eid+" div.tableAlert").hide();
		$(".experiment#"+eid+" div.tableMark").show();
		$(".experiment#"+eid+" td.coordinate").attr("contentEditable","true");
		$(".experiment#"+eid+" th.input").show();
		$(".experiment#"+eid+" td.input").show();
		$(".experiment#"+eid+" .tableActions").show();

		// Stored text fields (title, caption)
		$(".stored").click(function() {
			$("div.stored").removeAttr('contentEditable');
			if(loggedin)$(this).attr('contentEditable','true');
		});
		$(".stored").focusout(function() {
			store(this);
			$(this).removeAttr('contentEditable');
		});
		$('.stored').on('keydown',function(e) {	// Intercept enter on 'stored' fields
			if(e.which==13&&e.shiftKey==false) {	// enter (without shift)
				store(this);
				$(this).removeAttr('contentEditable');
				$(this).blur();
				return false;
			}
		});

		// Experiment table mark
		var ex=findExperimentByEID(eid);
		var prevMark=findPreviousTableMarkByUser(eid);
		var markBadTable=ex.markBadTable;
		if(markBadTable && (markBadTable.bad+markBadTable.ok>=minVotes))
		{
			if(debug) console.log("[updateExperiment] eid:",eid,"markbad:",markBadTable);
			$(".experiment#"+eid+":first span#Yes").html('('+markBadTable.ok+')');
			$(".experiment#"+eid+":first span#No").html('('+markBadTable.bad+')');
		
			if(markBadTable.bad>markBadTable.ok)
				$(".experiment#"+eid+":first .tableAlert ").html('<img src="/img/alert.svg" style="height:0.8rem;display:inline;position:relative;top:0rem"/>This table may be incorrect');
			else
				$(".experiment#"+eid+":first .tableAlert ").html("");
		}
		else
		{
			$(".experiment#"+eid+":first span#Yes").html('');
			$(".experiment#"+eid+":first span#No").html('');			
		}
		if(prevMark>-1)
		{
			$(".experiment#"+eid+" input:radio[value='Yes']").attr('checked',prevMark==0);
			$(".experiment#"+eid+" input:radio[value='No']").attr('checked',prevMark==1);
		}
		else
		{
			$(".experiment#"+eid+" input:radio[value='Yes']").removeAttr('checked');
			$(".experiment#"+eid+" input:radio[value='No']").removeAttr('checked');
		}
	
		// Experiment tags (specific)
		if(ex.tags)
			for(j=0;j<ex.tags.length;j++)
			{
				var rtag=ex.tags[j];
				rtag.agree=(rtag.agree)?parseInt(rtag.agree):0;
				rtag.disagree=(rtag.disagree)?parseInt(rtag.disagree):0;
			
				// Show tags only if there are at least minVotes votes,
				// or if the current user voted for it
				var	prevVote=findPreviousVoteByUser(eid,rtag);
				if(rtag.agree+rtag.disagree>=minVotes || prevVote!=0)
					addTag(eid,rtag);
				else
					removeTag(eid,rtag);
			}
	}
	else
	{
		$(".stored").removeAttr('contentEditable');
		$(".experiment#"+eid+" .stored").removeClass('stored');

		// Experiment table mark (global)
		$(".experiment#"+eid+" div.tableAlert").show();
		$(".experiment#"+eid+" div.tableMark").hide();
		$(".experiment#"+eid+" td.coordinate").removeAttr("contentEditable");
		$(".experiment#"+eid+" th.input").hide();
		$(".experiment#"+eid+" td.input").hide();
		$(".experiment#"+eid+" .tableActions").hide();
	}
	
	// Adjust locations table height
	var padd,legendheight,xyzhdrheight,ontheight,tableActionsHeight;
	padd=parseInt($('.experiment#'+eid).css('padding-top'));
	legendheight=$('.experiment#'+eid+' .experiment-title').innerHeight();
	legendheight+=$('.experiment#'+eid+' .experiment-caption').innerHeight();
	xyzhdrheight=$('.experiment#'+eid+' .xyzheader').innerHeight();
	ontheight=$('.experiment#'+eid+' .ontologies').innerHeight();
	badTableHeight=$(".experiment#"+eid+" input.badTable").innerHeight()+10;
	if(loggedin)
		tableActionsHeight=$(".experiment#"+eid+" .tableActions").outerHeight();
	else
		tableActionsHeight=0;
	$('.experiment#'+eid+' .xyztable').css({"height":300,"max-height":300-badTableHeight-padd-tableActionsHeight});
}
function initBrainSpellArticle()
{
	// Is JS enabled? Is it a touch device?
	var htmlTag = document.getElementsByTagName('html')[0];
	htmlTag.className = (htmlTag.className + ' ' || '') + 'hasJS';
	if ('ontouchstart' in document.documentElement)
		htmlTag.className = (htmlTag.className + ' ' || '') + 'isTouch';
	
	$.get("/templates/cogatlas-tasks.html",function(data){tasks=data;if(debug) console.log("[init] Tasks loaded");});
	$.get("/templates/cogatlas-cognitive.html",function(data){cognitive=data;if(debug) console.log("[init] Cognitive domains loaded");});
	$.get("/templates/brainmap-behavioural.html",function(data){behavioural=data;if(debug) console.log("[init] Behavioural domains loaded");});
	$.get("/templates/concept.html",function(data){concept=data;if(debug) console.log("[init] Concept loaded");});

	subscribeToLoginUpdates(updateArticle);

	// Init renderer
	$("body").append("<canvas id='3d' style='position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none'>");
	if( Detector.webgl ){
		renderer = new THREE.WebGLRenderer({
			canvas: document.getElementById( "3d" ),
			antialias				: true,	// to get smoother output
			preserveDrawingBuffer	: true,	// to allow screenshot
			alpha: true
		});
		renderer.setClearColor( 0xffffff, 0 );
		renderer.setPixelRatio(window.devicePixelRatio ? window.devicePixelRatio : 1);
	} else {
		console.log("ERROR: No WebGLRenderer available");
		renderer = new THREE.CanvasRenderer();
	}
	animate();

	$("#pmid").click(function(){window.location=$(this).attr('href')});
	$("#doi").click(function(){window.location=$(this).attr('href')});
	$("#neurosynth").click(function(){window.location=$(this).attr('href')});
	$("#download").click(function(){downloadArticle()});
	
	ArticlePMID=$("#pmid").attr("href").split("/")[4];
	if(debug) console.log("Article's PMID",ArticlePMID);
	
	flagIsNIDM=(ArticlePMID.substring(0,5)=="NIDM_");

	if(exp_string=="<!--Experiments-->")
	{
		if(debug) console.log("[initBrainSpellArticle] Article not in DB, add it");
		
		if(flagIsNIDM) {
			// make and empty NIDM template
			$("#new-nidm-article-warning").show();
			createEmptyNIDMTemplate(ArticlePMID);
			$(".title,.reference,.abstract,.metadata,.experiments,.discussion").hide();
		} else {
			if(debug) console.log("[initBrainSpellArticle] Not an NIDM article, look it up in PubMed");
			$("#new-article-warning").show();
			$(".experiments,.discussion").hide();
			downloadArticleXML();
		}
	}
	else
	{
		exp=$.parseJSON(exp_string);
		configureExperiments();

		if(flagIsNIDM) {
			$(".reference,.abstract").hide();
		} else {
			
			if(meta_string)	meta=$.parseJSON(meta_string);
			else			meta={};
			
			if(meta.meshHeadings==undefined || meta.meshHeadings.length==0)
			{
				if(debug) console.log("[initBrainSpellArticle] Article without MeSH tags, search pubmed");
				downloadArticleXML(function(){
					saveMetadata();
					logKeyValue(-1,"UserTriggeredAction",JSON.stringify({"action":"Update","element":"MeSH"}));
				});
			}
			else
			{
				configureMetadata();
				updateArticle();
			}
		}
	}
}
function createEmptyNIDMTemplate(ArticlePMID) {
	EmptyArticle={};
	EmptyArticle.title="Temporary Title";
	EmptyArticle.abstract="Temporary Abstract";
	EmptyArticle.authors="Temporary Authors list";
	EmptyArticle.reference="Temporary Reference";
	EmptyArticle.pmid=ArticlePMID;
	EmptyArticle.doi="Temporary DOI";
	EmptyArticle.neurosynth=ArticlePMID;
	meta={};

	// Display information in webpage
	$("h2.title").html(EmptyArticle.title);
	$(".abstract").html(EmptyArticle.abstract);
	$("#reference").html(EmptyArticle.reference);
	$("#doi").attr("href","http://dx.doi.org/"+EmptyArticle.doi);
	
	configureMetadata();
	updateArticle();
}
function downloadArticle() {
	/*
		Download article in CSV format to local hard disk
	*/
	var i,j;
	var str=[
		$(".title").text(),
		$("#reference").text(),
		$("#pmid").text(),
		$("#doi").text(),
		"Abstract: "+$(".abstract").text(),
		""
	].join("\n");
	
	for(i=0;i<exp.length;i++) {
		var str1=[
			"Title: "+exp[i].title,
			"Caption: "+exp[i].caption,
			""
		].join("\n");
		for(j=0;j<exp[i].locations.length;j++)
			str1+=exp[i].locations[j].x+"\t"+exp[i].locations[j].y+"\t"+exp[i].locations[j].z+"\n";
		str+=str1+"\n";
	}
	var exportData = 'data:text/plain;charset=utf-8,' + encodeURIComponent(str);
	var a = document.createElement('a');
	a.href = exportData;
	a.download = ArticlePMID+'.txt';
	a.style.display='none';
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
}
function downloadArticleXML(callback)
{
	/*
		Download article metadata from PubMed
	*/
	if(debug) console.log("[downloadArticleXML]");
	$.get("http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi",
		{"db":"pubmed","id":ArticlePMID,"report":"xml"},
		function(xml) {
			parseArticleXML(xml);
						
			// Display information in webpage
			$("h2.title").html(EmptyArticle.title);
			$("div.abstract").html(EmptyArticle.abstract);
			$("#reference").html(EmptyArticle.reference);
			$("#doi").attr("href","http://dx.doi.org/"+EmptyArticle.doi);
			
			configureMetadata();
			updateArticle();
			
			if(callback)
				callback();
		});

}
function parseArticleXML(xml)
{
	// init EmptyArticle object to hold information about an article
	// not in the database
	EmptyArticle={};

	var title=$(xml).find("ArticleTitle").text();
	var abstract=$(xml).find("AbstractText").text();
	var authors=$(xml).find("Author");
	var authors_string="";
	var	last,fore;
	var reference_string="";
	var	pubdate=$(xml).find("PubDate");
	var year=$(pubdate).find("Year").text();
	var journal=$(xml).find("ISOAbbreviation").text();
	var volume=$(xml).find("Volume").text();
	var issue=$(xml).find("Issue").text();
	var pages=$(xml).find("MedlinePgn").text();
	var mesh=$(xml).find("DescriptorName");
	var doi=$(xml).find("ArticleId[IdType='doi']").text();
	
	// title
	EmptyArticle.title=title;
	
	// abstract
	EmptyArticle.abstract=abstract;
	
	// authors
	for(i=0;i<authors.length;i++)
	{
		last=$(authors[i]).find("LastName").text();
		fore=$(authors[i]).find("ForeName").text();
		authors_string+=last+" "+fore.charAt(0);
		if(i<authors.length-1)
			authors_string+=", ";
	}
	EmptyArticle.authors=authors_string;
	
	// reference
	for(i=0;i<Math.min(5,authors.length);i++)
	{
		last=$(authors[i]).find("LastName").text();
		fore=$(authors[i]).find("ForeName").text();
		reference_string+=last+" "+fore.charAt(0);
		if(i<Math.min(5,authors.length-1))
			reference_string+=", ";
	}
	if(authors.length>5)
		reference_string+="et al."
	reference_string+=" ("+year+") "+journal+" "+volume+"("+issue+"):"+pages;
	EmptyArticle.reference=reference_string;

	// pmid
	EmptyArticle.pmid=ArticlePMID;
	
	// doi
	EmptyArticle.doi=doi;
	
	// neurosynth
	EmptyArticle.neurosynth=ArticlePMID;

	// MeSH headings
	if(meta==undefined)
		meta={};
	if(meta.meshHeadings==undefined)
		meta.meshHeadings=[];
	for(i=0;i<mesh.length;i++)
		meta.meshHeadings[i]={"name":$(mesh[i]).text(),"majorTopic":$(mesh[i]).attr("MajorTopicYN")};
}
function addEmptyExperiments(obj)
{
	var nexp,
		i;
	
	if(flagIsNIDM) {
		nexp=parseInt($("#new-nidm-article-warning .numNewExperiments").val());
		EmptyArticle.title=html_sanitize($("#new-nidm-article-warning .temporaryTitle").val());
	} else {
		nexp=parseInt($("#new-article-warning .numNewExperiments").val());
	}
	
	if(nexp>0)
	{
		$("#new-article-warning, #new-nidm-article-warning").hide();

		// add new experiments
		exp=[];
		for(i=0;i<nexp;i++)
			exp.push({"id":100000+i,"title":"","caption":"","locations":["0,0,0"]}); // using 100000 to distinguish from neurosynth/fix ids
		
		// store empty article in database
		var obj={
				action:"add_article",
				command:"new",
				Title:EmptyArticle.title,
				Authors:EmptyArticle.authors,
				Abstract:EmptyArticle.abstract,
				Reference:EmptyArticle.reference,
				PMID:EmptyArticle.pmid,
				DOI:EmptyArticle.doi,
				NeuroSynthID:EmptyArticle.neurosynth,
				Experiments:JSON.stringify(exp),
				Metadata:JSON.stringify(meta)
		};
		var result=$.ajax({
			type: "POST",
			url: "/php/brainspell.php",
			data: obj
		}).done(function( msg ){
			if(debug)
				console.log(msg);
		});
		
		configureExperiments();
		
		if(flagIsNIDM) {
			$(".title").text(EmptyArticle.title);
			$(".title,.metadata,.experiments,.discussion").show();
		} else {
			$(".title,.reference,.abstract,.metadata,.experiments,.discussion").show();
		}
		
		logKeyValue(-1,"UserAction",JSON.stringify("AddEmptyArticle"));
	}
}

/*
---------------------------------------------------------
		  Configuration of the article page
---------------------------------------------------------
*/
function configureMetadata()
{
	/*
	Load and configure article-level metadata: stereotaxic space, mesh headings, number of subjects
	*/

	// Stereotaxic space
	/*
	*/
	
	// Number of subjects
	/*
	*/
	
	configureMeSHDescriptors();
	
	// Discussion
	if(meta.comments)
	for(i=0;i<meta.comments.length;i++)
	{
		var comment=meta.comments[i].comment;
		var	user=meta.comments[i].user;
		var	time=new Date();
		time.setTime(meta.comments[i].time);
		$("div.comments").append("</p><a href='"+"/user/"+user+"'>"+user+"</a> ("+time.toLocaleString()+")<br \>");
		$("div.comments").append(comment);
		$("div.comments").append("</p><br \>");
	}
	
	if(debug) console.log("[configureMetadata] metadata configured");
}
function configureMeSHDescriptors()
{
	var	metadata="";
	if(meta.meshHeadings) {
		for(i=0;i<meta.meshHeadings.length;i++)
		{
			meta.meshHeadings[i].ontology="mesh";

			meta.meshHeadings[i].agree=(meta.meshHeadings[i].agree)?parseInt(meta.meshHeadings[i].agree):0;
			meta.meshHeadings[i].disagree=(meta.meshHeadings[i].disagree)?parseInt(meta.meshHeadings[i].disagree):0;
			var	rtag=meta.meshHeadings[i];
			var tag=$("<li>",{class:"tag"});
			if(meta.meshHeadings[i].majorTopic=="Y")
				tag.html('<b>'+rtag.name+'</b>');
			else
				tag.html(rtag.name);
			updateTagColor(tag,rtag);
			tag.click({rtag:rtag},function(e){openTagModal(-1,e.data.rtag)});
			$(".tag-list").append(tag);
		}
	}
}
function configureExperiments()
{
	if(debug) console.log("[configureExperiments]");
	$("#experiments").html("");
	for(i=0;i<exp.length;i++)
	{
		if(exp[i].locations.length==0)
			continue;
		$("#experiments").append($('<div class="experiment" id="'+exp[i].id+'">').load("/templates/experiment.html",addExperiment(exp[i].id)));
	}
	if(debug) console.log("[configureExperiments] experiments configured");
}
function addExperiment(eid)
{
	if(debug) console.log("[addExperiment]");
	return function(responseText, textStatus, XMLHttpRequest){

		// Configure legend (title and caption)
		//-------------------------------------
		var ex=findExperimentByEID(eid);
		var title=(ex.title)?ex.title:"(Empty)";
		var caption=(ex.caption)?ex.caption:"(Empty)";
		$(".experiment#"+eid+" .title").append(title);
		$(".experiment#"+eid+" .caption").append(caption);

		// Configure locations to 3D view
		//-------------------------------
		initTranslucentBrain(eid);
		ex.render.spheres = new THREE.Object3D();
		ex.render.scene.add(ex.render.spheres);

		// Configure stereotaxic table
		//----------------------------
		var table=$(".experiment#"+eid+" .xyztable table");
		if(table==undefined)
			console.log("ERROR: table undefined");
		table.click(function(e){if(e.target.tagName=="TD") clickOnTable(e.target)});
		table.keydown(function(e){keydownOnTable(e.target)});

		// Add experiment locations to table
		if(!ex.locations)
			return;
		var html="";
		table=$(".experiment#"+eid+" .xyztable table")[0];
		for(j=0;j<ex.locations.length;j++)
		{
			var coords=[];
			if(typeof ex.locations[j] == "string") {
				coords=ex.locations[j].split(",");
				ex.locations[j]={
					x:coords[0],
					y:coords[1],
					z:coords[2]
				}
			} else {
				coords[0]=ex.locations[j].x;
				coords[1]=ex.locations[j].y;
				coords[2]=ex.locations[j].z;
			}
			var new_row = table.insertRow(j);
			new_row.innerHTML=[
				"<td class='coordinate'>"+coords[0]+"</td>",
				"<td class='coordinate'>"+coords[1]+"</td>",
				"<td class='coordinate'>"+coords[2]+"</td>",
				"<td class='input'><input type='image' class='del' src='/img/minus-circle.svg' onclick='delRow(this)'/></td>",
				"<td class='input'><input type='image' class='add' src='/img/plus-circle.svg' onclick='addRow(this)'/></td>"
			].join("\n");
		}

		// Intercept enter on table cells
		$(".experiment#"+eid+" .xyztable table td").on( 'keydown',function(e) {
			if(e.which==13&&e.shiftKey==false) {	// enter (without shift)
				parseTable(this,eid);
				return false;
			}
			if(e.which==9) {	// tab
				parseTable(this,eid);
			}
		});
		
		// Table actions
		//--------------
		// Split table
		$(".experiment#"+eid+" .button.split").click(function() {
			splitTable(eid,ex.selectedRow);
		});
		// Import table
		$(".experiment#"+eid+" .button.import").click(function() {
			importTable(eid);
		});

		// Parse locations: add locations to
		// 3d view and to stereotaxic table
		//----------------------------------
		parseTable("",eid);

		// Add ontologies
		$(".experiment#"+eid+" .ontologies").append(addOntology(eid,"tasks"));
		$(".experiment#"+eid+" .ontologies").append(addOntology(eid,"cognitive"));
		$(".experiment#"+eid+" .ontologies").append(addOntology(eid,"behavioural"));
		
		// Configure radio button groups for table marks
		$(".experiment#"+eid+" input:radio[value='Yes']").attr('name',"radio"+eid);
		$(".experiment#"+eid+" input:radio[value='No']").attr('name',"radio"+eid);

		updateExperiment(eid);
		subscribeToLoginUpdates(function(){updateExperiment(eid)});
	}
}
//========================================================================================
// Stereotaxic table
//========================================================================================
function clickOnTable(target)
{
	var	targetClass=$(target).attr('class');
	var row=$(target).closest("tr");
	var	div=$(target).closest(".experiment");
	var eid=div.attr('id');
	var	index=$(row).index();
	
	if(index>=0 && targetClass!="del" && targetClass!="add")
		selectRow(row,eid);
}
function keydownOnTable(target)
{
	if(debug) console.log("keydown:",target);
}
function selectRow(row,eid) {
	var ex=findExperimentByEID(eid);
	var i=$(row).index();
	$(".experiment#"+eid+" .xyztable table td").css({'background-color':''});
	ex.render.spheres.children.forEach(function( sph ) { sph.material.color.setRGB( 1,0,0 );});
	if(i>=0) {
		$(row).children('td.coordinate').css({'background-color':'lightGreen'});
		ex.locations[i].sph.material.color.setRGB(0,1,0);
		ex.selectedRow=i;
	}
	else
		ex.selectedRow=undefined;
}
function enterRow(row,eid) {
	$(row).on('keydown',function(e) {
		if(e.which==13&&e.shiftKey==false) {	// enter (without shift)
			parseTable(row,eid);
			return false;
		}
		if(e.which==9) {	// tab
			parseTable(row,eid);
		}				
	});
}
function delRow(row)
{
	var	par=$(row).closest(".experiment");
	var eid=par.attr('id');
    var i=$(row).closest("tr").index();
    var ex=findExperimentByEID(eid);
    
    $(".experiment#"+eid+" .xyztable table")[0].deleteRow(i);

    ex.render.scene.remove(ex.locations[i].sph);
    ex.render.spheres.remove(ex.locations[i].sph);
    ex.locations.splice(i,1);
	save(eid,"locations",JSON.stringify(ex.locations,["x","y","z"]));
	selectRow(row,eid); // this will erase any existing selection
}
function addRow(row)
{
	var	par=$(row).closest(".experiment");
	var eid=par.attr('id');
	var ex=findExperimentByEID(eid);
    var i=$(row).closest('tr').index();
	var table=$(".experiment#"+eid+" .xyztable table")[0];
    var	inputType=$(row).attr("class");
    var j,new_row;
    
    if(inputType=="addhead")	// if the user clicked on the plus sign in the header, add at the top of the table
    	j=0;
    else
    	j=i+1;
    new_row=table.insertRow(j);
    
    new_row.innerHTML=[
            "<td contentEditable>0</td>",
            "<td contentEditable>0</td>",
            "<td contentEditable>0</td>",
            "<td><input type='image' id='del' src='/img/minus-circle.svg' onclick='delRow(this)'/></td>",
            "<td><input type='image' id='add' src='/img/plus-circle.svg' onclick='addRow(this)'/></td>"].join("\n");
	
	// intercept enter
	$(new_row).find('td[contentEditable]').each(function(){enterRow(this,eid);});

	if(!ex.locations)
		ex.locations=[];
	ex.locations.splice(j,0,{"x":0,"y":0,"z":0});
	parseTable($(new_row),eid);
	selectRow(new_row,eid);
}
function splitTable(eid,irow) {
	var ex=findExperimentByEID(eid);
	var iExp=findIndexOfExperimentByEID(eid);
	if(debug) console.log("Split the table in experiment "+eid+" at row: "+ex.selectedRow);

	// clear sphere selection in 3d view
	ex.render.spheres.children.forEach(function( sph ) { sph.material.color.setRGB( 1,0,0 );});

	var	newLocations=ex.locations.splice(irow,ex.locations.length-irow);
	var i,new_eid;
	new_eid=exp[0].id;
	for(i=0;i<exp.length;i++)
		if(exp[i].id>new_eid)
			new_eid=exp[i].id;
	new_eid=(new_eid<100000)?100000:(new_eid+1); // Using 100000 to distinguish from automatic ids (neurosynth/fix)
	
	if(ex.tags==undefined)
		ex.tags="";
	
	var newExp={
		id: new_eid,
		title: ex.title,
		caption: ex.caption,
		tags:JSON.parse(JSON.stringify(ex.tags)),
		locations: newLocations
	};
	exp.splice(iExp+1, 0, newExp);
	
	configureExperiments();
	saveExperiments();

	// log user action
	logKeyValue(ex.id,"SplitTable",JSON.stringify({"newExperiment":new_eid}));
}
function importTable(eid) {
	var ex=findExperimentByEID(eid);
	var iExp=findIndexOfExperimentByEID(eid);
	if(debug) console.log("Import table from text in experiment "+eid);
	ex.render.spheres.children.forEach(function( sph ) { sph.material.color.setRGB( 1,0,0 );});
	var container=$("<div id='import-table'>");
	var lightbox=$("<div>");
	lightbox.addClass('light_content');
	$.get("/templates/stereoparse.html",function(data) {
		var i;
		var newLocations=[];

		// load html template
		lightbox.html(data);

		// init text with current table data
		for(i=0;i<ex.locations.length;i++)
			$("#text").append(ex.locations[i].x+","+ex.locations[i].y+","+ex.locations[i].z+"<br />\n");
		
		var parse_button=$("#parse_button");
		var import_button=$("#import_button");
		var	cancel_button=$("#cancel_button");
		import_button.css({margin:"10px",display:"none"});
		cancel_button.css({margin:"10px",display:"none"});
		parse_button.click(function() {
			var arr=$("#text").html().split(/<br>|<\/p>|<div>/);
			var i,j,k;
			var val,num;
	
			// (lines are easier to split from html(),
			// but values are easier to parse from text())
			for(i=0;i<arr.length;i++) {
				var tmp=$("<div>");
				tmp.html(arr[i]);
				arr[i]=tmp.text();
			}

			var table=$("<table style='width:100%;text-align:center'>");
			table.append("<tr><td><b>X</b></td><td><b>Y</b></td><td><b>Z</b></td></tr>\n");
			for(i=0;i<arr.length;i++) {
				val=arr[i].split(",");
				k=0;
				var xyz=[];		
				for(j=0;j<val.length;j++) {
					var num1=val[j].replace(/["a-zA-Z]/g, "").replace(/\u2212/g, "-"); // minus sign
					var num=parseFloat(num1);
					if(!isNaN(num)) {
						xyz[k++]=num;
						if(k==3) {
							newLocations.push({x:xyz[0],y:xyz[1],z:xyz[2]});
							break;
						}
					}
				}
				if(k==3)
					table.append("<tr><td align:center'>"+xyz[0]+"</td><td>"+xyz[1]+"</td><td>"+xyz[2]+"</td></tr>\n");
			}
			$("#table").html(table);
			$("#import_button").show();
			$("#cancel_button").show();
		});
		import_button.click(function() {
			var i;
			ex.locations=[];
			for(i=0;i<newLocations.length;i++)
				ex.locations.push({x:newLocations[i].x,y:newLocations[i].y,z:newLocations[i].z});
			configureExperiments();
			saveExperiments();
			logKeyValue(ex.id,"ImportTable",JSON.stringify({"Experiment":eid}));
			$("#import-table").remove();
		});
		cancel_button.click(function() {
			$("#import-table").remove();
		});
	});
	lightbox.show();
	var overlay=$("<div>");
	overlay.addClass('light_overlay');
	overlay.show();
	container.append(overlay);
	container.append(lightbox);
	$("body").append(container);
}
//============
// 3D Viewer and Table
//=============
function parseTable(row,eid)
{
	var ex=findExperimentByEID(eid);
	var	i=-1;
	var	tr=$(row).closest("tr")[0];
	if(tr)
	{
	    i=$(tr).index();
	    if(i>=0) {
			var cells=$(tr).find('td');
			var x=parseFloat(cells[0].textContent);
			var	y=parseFloat(cells[1].textContent);
			var z=parseFloat(cells[2].textContent);

			ex.locations[i].x=x;
			ex.locations[i].y=y;
			ex.locations[i].z=z;
			
			save(eid,"locations",JSON.stringify(ex.locations,["x","y","z"]));
		}
	}
		
	// Add location spheres
	var geometry = new THREE.SphereGeometry(1,16,16);
	var	color=0xff0000;
	for(j=0;j<ex.locations.length;j++)
	{
		if(ex.locations[j].sph)
		{
		    color=ex.locations[j].sph.material.color;
		    ex.render.spheres.remove(ex.locations[j].sph);
		}

		var x=ex.locations[j].x;
		var y=ex.locations[j].y;
		var z=ex.locations[j].z;
		var sph = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial({color: color}));
		sph.position.x=parseFloat(x)*0.14;
		sph.position.y=parseFloat(y)*0.14+3;
		sph.position.z=parseFloat(z)*0.14-2;
		ex.render.spheres.add(sph);
		ex.locations[j].sph=sph;
	}
}
function store(obj)
{
	var	eid=$(obj).closest(".experiment").attr("id");
	var	name=$(obj).attr('class').split(" ")[0];
	var	value=$(obj).text();
	$(obj).html(value);
	var value1=$(obj).html();
	var value2=value1.replace(/[^ a-zA-Z0-9<>=,.%&{}!@#$^*-_+"?':;~\/\|`\(\)\[\]]/g, ""); // Filter out
	var	value3=html_sanitize(value2);
	$(obj).html(value3);
	save(eid,name,value3);
}
function save(eid,name,value)
{
	var ex=findExperimentByEID(eid);
	if(name=="title")
		ex.title=value;
	if(name=="caption")
		ex.caption=value;
	
	saveExperiments();

	// log user action
	logKeyValue(eid,"Edit",JSON.stringify({"element":name}));
}
function saveExperiments()
{
	// stringify experiments
	var experiments_string=JSON.stringify(exp,function(key,val) {
		if(	key=="render")
			return undefined;
		else
		if(key=="locations")
		{
			var arr=[];
			for(i=0;i<val.length;i++)
				arr.push(val[i].x+","+val[i].y+","+val[i].z);
			return arr;
		}
		else
			return val;
	});
	
	// save to database
	var result=$.ajax({
		type: "POST",
		url: "/php/brainspell.php",
		data: {
			action:"add_article",
			command:"experiments",
			Experiments:experiments_string,
			PMID:ArticlePMID
		}
	}).done(function( msg ){
		if(debug) console.log("[saveExperiments]",msg);
	});
}
function saveMetadata()
{
	// stringify experiments
	var metadata_string=JSON.stringify(meta);
	
	// save to database
	var result=$.ajax({
		type: "POST",
		url: "/php/brainspell.php",
		data: {
			action:"add_article",
			command:"metadata",
			Metadata:metadata_string,
			PMID:ArticlePMID
		}
	}).done(function( msg ){
		if(debug) console.log("[saveMetadata]",msg);
	});
}
function addOntology(eid,ontology)
{
	var	str="";
	var ct="",cts=new Array({ontology:"tasks",title:"Cognitive Atlas Tasks"},{ontology:"cognitive",title:"Cognitive Atlas Cognitive Domains"},{ontology:"behavioural",title:"BrainMap Behavioural Domains"});
	var	cl;
	
	for(i=0;i<cts.length;i++)
		if(cts[i].ontology==ontology)
			ct=cts[i];

	str+='<div class="ontology" id="'+ontology+'" style="padding-left:5px;padding-bottom:5px">\n';
	str+='<div class="ontology-name"><span class="tag" style="top:-0.2em"><a onclick="openOntologyModal('+eid+',\''+ontology+'\',\'+\');">+</a></span><b style="padding-left:4px;font-family:sans-serif">'+ct.title+'</b></div>\n';
	str+='<ul class="ontology-tags">\n';
	str+='</ul>\n';
	str+='</div>\n';

	return str;
}
function addTag(eid,rtag)
{
	var prevTag=$("div.experiment#"+eid+" div.ontology#"+rtag.ontology+" li:contains('"+rtag.name+"')").length;
	if(prevTag!=0)
		return;
		
	var tag=$("<li>",{class:'tag'});
	tag.html(rtag.name);
	tag.click({rtag:rtag},function(e){openTagModal(eid,e.data.rtag)});
	updateTagColor(tag,rtag);
	$("div.experiment#"+eid+" div.ontology#"+rtag.ontology+" ul").append(tag);
	return tag;
}
function removeTag(eid,rtag)
{
	$("div.experiment#"+eid+" div.ontology#"+rtag.ontology+" li:contains('"+rtag.name+"')").remove();
}
function updateTagColor(tag,rtag)
{
	if(tag.hasClass('green')) tag.removeClass('green');
	if(tag.hasClass('orange')) tag.removeClass('orange');
	if(tag.hasClass('red')) tag.removeClass('red');

	if(rtag.agree==0&&rtag.disagree==0)
		return;
	
	if(rtag.agree-rtag.disagree>0)
		tag.addClass('green');
	else
	if(rtag.agree-rtag.disagree<0)
		tag.addClass('red');
	else
		tag.addClass('orange');
}
function findTag(tags,name,ontology)
{
	var	j,found=0;
	var	ob;
	
	if(tags)
	for(j=0;j<tags.length;j++)
	{
		ob=tags[j];
		if((ob.name==name)&&(ob.ontology==ontology))
		{
			found=1;
			break;
		}
	}
	if(found==0)
		return 0;
	return ob;
}
function findPreviousVoteByUser(eid,rtag)
{
	var found=0,vote=0;

	if(loggedin)
	{
		var result=$.ajax({
			type: "GET",
			url: "/php/brainspell.php",
			data: {
				action:"get_log",
				type:"Vote",
				UserName:username,
				TagName:rtag.name,
				TagOntology:rtag.ontology,
				Experiment:eid, // (iExp<0)?iExp:exp[iExp].id, // HAD TO CHANGE THIS !!!
				PMID:ArticlePMID
			}
		}).done(function( msg ){
			var xml=$.parseXML(msg);
			//var	tagVote=parseInt($.parseJSON($(xml).find("TagVote").text()));
			var	tagVote=parseInt($(xml).find("TagVote").text());
			if(tagVote)
				vote=tagVote;
		});
	}
	return vote;
}
function findPreviousTableMarkByUser(eid)
{
	var found=0,mark=-1;

	if(loggedin)
	{
		result=$.ajax({
			type: "GET",
			url: "/php/brainspell.php",
			data: {
				action:"get_log",
				type:"MarkTable",
				UserName:username,
				Experiment:eid, // (iExp<0)?iExp:exp[iExp].id, // HAD TO CHANGE THIS from iExp
				PMID:ArticlePMID
			}
		}).done(function( msg ){
			if(debug) console.log("[findPreviousTableMarkByUser] ",msg);
			var xml=$.parseXML(msg);
			var	markTable=$(xml).find("MarkTable").text();
			if(markTable)
				mark=parseInt(markTable);
		});
	}
	return mark;
}
function findPreviousStereoSpaceByUser()
{
	var found=0,resultSpace=-1;

	if(loggedin)
	{
		result=$.ajax({
			type: "GET",
			url: "/php/brainspell.php",
			data: {
				action:"get_log",
				type:"StereoSpace",
				UserName:username,
				PMID:ArticlePMID
			}
		}).done(function( msg ){
			if(debug) console.log("[findPreviousStereoSpaceByUser] ",msg);
			var xml=$.parseXML(msg);
			var	space=$(xml).find("StereoSpace").text();
			if(space)
				resultSpace=space;
		});
	}
	return resultSpace;
}
function findPreviousNSubjectsByUser()
{
	var found=0,resultN=-1;

	if(loggedin)
	{
		result=$.ajax({
			type: "GET",
			url: "/php/brainspell.php",
			data: {
				action:"get_log",
				type:"NSubjects",
				UserName:username,
				PMID:ArticlePMID
			}
		}).done(function( msg ){
			if(debug) console.log("[findPreviousNSubjectsByUser] ",msg);
			var xml=$.parseXML(msg);
			var	n=$(xml).find("NSubjects").text();
			if(n)
				resultN=n;
		});
	}
	return resultN;
}

/*
-----------------------------------------------------------------
		  Selection of tags
-----------------------------------------------------------------
*/
function openOntologyModal(eid,ontology)
{
	if(ontology=="tasks")
		$('body').append(tasks);
	else if(ontology=="cognitive")
		$('body').append(cognitive);
	else if(ontology=="behavioural")
		$('body').append(behavioural);
	$('#light').show();
	$('#fade').show();
	$('#light #eid').html(parseInt(eid));
	$('#light #ontology').html(ontology);
	
	configureOntologyModal();
	resizeOntologyModal();
}
function closeOntologyModal(isOk)
{
	$('#light').css('display','none');
	$('#fade').css('display','none');
	$('.lightbox').remove();
}
function resizeOntologyModal()
{
	if($('#light'))
	{
		var	hall,htitle,padd;
		hall=$('#light .allThing').height();
		htitle=$('#light .titleThing').height();
		padd=parseInt($('#light .allThing').css('padding-top')); // even though 'padding' is used in the style text, paddingTop has to be used here because jquery doesn't handle shortcuts (like 'padding')
		val=2*padd;
		$('#light .myThing').css('height',(hall-htitle-val)+'px');
	}
}
function openTagModal(eid,rtag)
{
	if(debug) console.log("[openTagModal]");
	var	obj=0;
	
	if(eid>-1) {
		var ex=findExperimentByEID(eid);
		obj=findTag(ex.tags,rtag.name,rtag.ontology);
	}
	else
		obj=findTag(meta.meshHeadings,rtag.name,rtag.ontology);
	if(obj)
		rtag=obj;
	$('body').append(concept);
	$('#box').show();
	$('#boxfade').show();
	$('#box #eid').html(eid);
	$('#box #ontology').html(rtag.ontology);
	
	configureTagModal(rtag);
	resizeTagModal();
}
function closeTagModal(vote)
{
	var	eid=parseInt($('#box div#eid').text());
	var	agree=parseInt($('#box span.agree').text());
	var disagree=parseInt($('#box span.disagree').text());
	var	name=$('#box h2.name').text();
	var ontology=$('#box #ontology').text();
	var	tag;
	
	// Vote -2:retract, 0:cancel, -1:down-vote or 1:up-vote
	if(vote!=0)	
	{
		// find the tag
		if(eid>=0)	// iExp is >=0 for tables, =-1 for article-level tags
		{
			var ex=findExperimentByEID(eid);
			// check whether there were already any tags associated with this
			// experiment
			if(!(ex.tags))
				ex.tags=new Array;
			
			// check whether the currently voted tag was already among
			// the experiment's tags
			obj=findTag(ex.tags,name,ontology);
			if(obj)
			{
				rtag=obj;
				agree=rtag.agree;
				disagree=rtag.disagree;
			}
			else
			{
				var	rtag=new Object({"name":name,"ontology":ontology,"agree":0,"disagree":0});
				ex.tags.push(rtag);
				// addTag(eid,rtag);
			}

			if(vote==-1 || vote==1)
				addTag(eid,rtag); // addTag only adds the tag if it wasn't already added
		}
		else
			rtag=findTag(meta.meshHeadings,name,ontology);

		// compute total tag votes
		var prevVote=findPreviousVoteByUser(eid,rtag);
		if(prevVote!=0)
		{
			if(vote==-2)	// retraction
			{
				if(prevVote==-1)
					rtag.disagree--;
				if(prevVote==1)
					rtag.agree--;
				if(eid>=0 && rtag.disagree==0 && rtag.agree==0)
					removeTag(eid,rtag); // remove tag from document
			}
			else
			if(vote!=prevVote)	// up-vote or down-vote
			{
				rtag.agree+=vote;
				rtag.disagree-=vote;
			}
		}
		else
		{
			if(vote>0)
				rtag.agree=agree+1;
			else
				rtag.disagree=disagree+1;
		}
		
		// Update tag display and save total tag votes (total agree/disagree stats)
		if(eid>=0)
		{
			tag=$("div.experiment#"+eid+" div.ontology#"+rtag.ontology+" li").filter(function(){return $(this).text()==rtag.name});
			updateTagColor(tag,rtag);
			tag=$(".classList span.tag").filter(function(){return $(this).text()==rtag.name});
			updateTagColor(tag,rtag);
		}
		else
		{
			tag=$(".tag-list li").filter(function(){return $(this).text()==rtag.name});
			updateTagColor(tag,rtag);
		}

		// save user vote (up-vote, down-vote or retraction)
		logVote(eid,rtag,vote);
	}
	$('#box').css('display','none');
	$('#boxfade').css('display','none');
	$('.box').remove();
}
function resizeTagModal()
{
	if($('#box'))
	{
		var	hall,htitle,padd;
		hall=$('#box .allThing').height();
		htitle=$('#box .titleThing').height();
		hbuttons=$('#box .buttonsThing').height();
		padd=parseInt($('#box .allThing').css('padding-top'));
		val=2*padd;
		$('#box .myThing').css('height',(hall-htitle-hbuttons-val)+'px');
	}
}
$(window).resize(function()
{
	resizeOntologyModal();
});

/*
---------------------------------------------------------
						 Save
---------------------------------------------------------
*/
function logVote(eid,rtag,vote)
{
	if(debug) console.log("[logVote] Log vote "+vote+" of user "+username);

	var	obj={
		action:"add_log",
		type:"Vote",
		PMID:ArticlePMID,
		UserName:username,
		TagName:rtag.name,
		TagOntology:rtag.ontology,
		TagVote:vote,
		Experiment:eid // (iExp<0)?iExp:exp[iExp].id // HAD TO CHANGE THIS FROM iExp
	};
	$.get("/php/brainspell.php",obj,function(data){
		if(debug) console.log("[logVote]",data);
	});
}
function logMarkTable(sender)
{
	var	eid=$(sender).closest("div.experiment").attr('id');
	var mark=$(".experiment#"+eid+" input:radio[value='No']").is(':checked')?1:0;
	var	obj={
		action:"add_log",
		type:"MarkTable",
		PMID:ArticlePMID,
		UserName:username,
		Mark:mark,
		Experiment:eid // (iExp<0)?iExp:exp[iExp].id // THIS IS WHAT I HAD TO CHANGE !!
	};
	$.get("/php/brainspell.php",obj,function(data){
		var out=$.parseJSON(data);
		var ex=findExperimentByEID(eid);
		ex.markBadTable={"bad":out.result.bad,"ok":out.result.ok};
		if(out.result.ok+out.result.bad>=minVotes)
		{
			$(".experiment#"+eid+":first span#Yes").html('('+out.result.ok+')');
			$(".experiment#"+eid+":first span#No").html('('+out.result.bad+')');
		}
	});
}
function logStereoSpace(sender)
{
	var	space=$(sender).val();
	if(debug) console.log("[logStereoSpace] space",space);
	var	obj={	action:"add_log",
				type:"StereoSpace",
				PMID:ArticlePMID,
				UserName:username,
				StereoSpace:space};
	$.get("/php/brainspell.php",obj,function(data){
		var out=$.parseJSON(data);
		if(debug) console.log("[logStereoSpace] out",out.result);
		meta.stereo=out.result;
		if(meta.stereo.Talairach+meta.stereo.MNI>=minVotes)
		{
			$("span#Talairach").html('('+meta.stereo.Talairach+')');
			$("span#MNI").html('('+meta.stereo.MNI+')');
		}
	});
}
function logNSubjects(sender)
{
	var	nsubjects=$(sender).val();
	if(debug) console.log("[logNSubjects] nsubjects",nsubjects);
	var	obj={	action:"add_log",
				type:"NSubjects",
				PMID:ArticlePMID,
				UserName:username,
				NSubjects:nsubjects};
	$.get("/php/brainspell.php",obj,function(data){
		var out=$.parseJSON(data);
		if(debug) console.log("[logNSubjects] out",out.result);

		meta.nsubjects=out.result;

		var	i,x,min,max,n=meta.nsubjects.length;
		min=max=meta.nsubjects[0];
		for(i=0;i<n;i++){	x=parseInt(meta.nsubjects[i]);
							if(x<min) min=x;
							if(x>max) max=x;}
		$("input#nSubjects").val(nsubjects);
		if(meta.nsubjects.length>=minVotes)
		{
			if(min!=max)
				$("span#nSubjects").text("("+n+" values, min="+min+", max="+max+")");
			else
				$("span#nSubjects").text("("+n+" value"+((n>1)?"s)":")"));
		}
	});
}
function logComment()
{
	var	content=html_sanitize($("textarea#comment").val());
	var	txt=JSON.stringify(content);
	if(debug) console.log("[logComment]",content,txt);
	var	time=new Date().getTime();
	var	comment='{"comment":'+txt+',"user":"'+username+'","time":"'+time+'"}';
	if(debug) console.log("[logComment] comment",comment);
	var	obj={	action:"add_log",
				type:"Comment",
				PMID:ArticlePMID,
				UserName:username,
				Comment:comment};
	$.get("/php/brainspell.php",obj,function(data){
		if(debug) console.log("[logComment]",data);
		var out=$.parseJSON(data);
		var	comment=$.parseJSON(out.result);
		var	time=new Date();
		time.setTime(comment.time);
		$("div.comments").append("</p><a href='"+"/user/"+username+"'>"+username+"</a> ("+time.toLocaleString()+")<br \>");
		$("div.comments").append(comment.comment);
		$("div.comments").append("</p><br \>");
		$("textarea").val("");
	});
}
function logKeyValue(eid,key,value)
{
	// eid: Experiment's unique id
	// key: a key. Will go to the 'Type' column in table Log of the DB
	// value: a value. Will go to the 'Data' column in table Log of the DB
	if(debug) console.log("[logKeyValue]",eid,key,value);
	var	obj={	action:"add_log",
				type:"KeyValue",
				PMID:ArticlePMID,
				UserName:username,
				Experiment:eid,
				Key:key,
				Value:value};
	$.get("/php/brainspell.php",obj,function(data){
		if(debug)
			console.log("[logKeyValue]",data);
	});
}

// Translucent Viewer
function initTranslucentBrain(eid)
{
	var ex=findExperimentByEID(eid);
	if(debug) console.log("ex:"+ex+" eid:"+eid);
	ex.render={};
	ex.render.container=$(".experiment#"+eid+" div.metaCoords");

	var container=ex.render.container;
	var	width=container.width();
	var	height=container.height();
	
	// create a scene
	ex.render.scene = new THREE.Scene();
	
	// create raycaster (for hit detection)
	container[0].addEventListener( 'mousedown', function(e){onDocumentMouseDown(e,eid);}, false );

	// put a camera in the scene
	ex.render.camera	= new THREE.PerspectiveCamera(40,width/height,25,50);
	ex.render.camera.position.set(0, 0, 40);
	ex.render.scene.add(ex.render.camera);

	// create a camera control
	ex.render.cameraControls=new THREE.TrackballControls(ex.render.camera,ex.render.container[0] )
	ex.render.cameraControls.noZoom=true;
	ex.render.cameraControls.addEventListener( 'change', function(){ex.render.light.position.copy( ex.render.camera.position );} );

	// allow 'p' to make screenshot
	//THREEx.Screenshot.bindKey(renderer);
	
	// Add lights
	var	light	= new THREE.AmbientLight( 0x3f3f3f);
	ex.render.scene.add(light );
	ex.render.light	= new THREE.PointLight( 0xffffff,2,80 );
	//var	light	= new THREE.DirectionalLight( 0xffffff);
	//light.position.set( Math.random(), Math.random(), Math.random() ).normalize();
	ex.render.light.position.copy( ex.render.camera.position );
	//light.position.set( 0,0,0 );
	ex.render.scene.add(ex.render.light );

	// Load mesh (ply format)
	var oReq = new XMLHttpRequest();
	oReq.open("GET", "/data/lrh3.ply", true);
	oReq.responseType="text";
	oReq.onload = function(oEvent)
	{
		var tmp=this.response;
		var modifier = new THREE.SubdivisionModifier(1);
		
		ex.render.material=new THREE.ShaderMaterial({
			uniforms: { 
				coeficient	: {
					type	: "f", 
					value	: 1.0
				},
				power		: {
					type	: "f",
					value	: 2
				},
				glowColor	: {
					type	: "c",
					value	: new THREE.Color('grey')
				},
			},
			vertexShader	: [ 'varying vec3	vVertexWorldPosition;',
								'varying vec3	vVertexNormal;',
								'void main(){',
								'	vVertexNormal	= normalize(normalMatrix * normal);',
								'	vVertexWorldPosition	= (modelMatrix * vec4(position, 1.0)).xyz;',
								'	gl_Position	= projectionMatrix * modelViewMatrix * vec4(position, 1.0);',
								'}',
								].join('\n'),
			fragmentShader	: [ 'uniform vec3	glowColor;',
								'uniform float	coeficient;',
								'uniform float	power;',
								'varying vec3	vVertexNormal;',
								'varying vec3	vVertexWorldPosition;',
								'void main(){',
								'	vec3 worldCameraToVertex=vVertexWorldPosition - cameraPosition;',
								'	vec3 viewCameraToVertex=(viewMatrix * vec4(worldCameraToVertex, 0.0)).xyz;',
								'	viewCameraToVertex=normalize(viewCameraToVertex);',
								'	float intensity=pow(coeficient + dot(vVertexNormal, viewCameraToVertex), power);',
								'	gl_FragColor=vec4(glowColor, intensity);',
								'}',
							].join('\n'),
			transparent	: true,
			depthWrite	: false,
		});
		
		ex.render.geometry=new THREE.PLYLoader().parse(tmp);
		ex.render.geometry.sourceType = "ply";
		
		modifier.modify(ex.render.geometry);
		for(i=0;i<ex.render.geometry.vertices.length;i++)
		{
			ex.render.geometry.vertices[i].x*=0.14;
			ex.render.geometry.vertices[i].y*=0.14;
			ex.render.geometry.vertices[i].z*=0.14;
			ex.render.geometry.vertices[i].y+=3;
			ex.render.geometry.vertices[i].z-=2;
		}

		ex.render.brainmesh=new THREE.Mesh(ex.render.geometry,ex.render.material);
		ex.render.scene.add(ex.render.brainmesh);
	};
	oReq.send();
}
// hit detection
function onDocumentMouseDown( event,eid ) {
	event.preventDefault();
	var ex=findExperimentByEID(eid);
	var	x,y,i;
	var r = event.target.getBoundingClientRect();

	mouseVector = new THREE.Vector3();
	mouseVector.x= ((event.clientX-r.left) / event.target.clientWidth ) * 2 - 1;
	mouseVector.y=-((event.clientY-r.top) / event.target.clientHeight ) * 2 + 1;
	
	var raycaster=new THREE.Raycaster();
	raycaster.setFromCamera(mouseVector.clone(), ex.render.camera);
	var intersects = raycaster.intersectObjects( ex.render.spheres.children );

	if(intersects.length==0)
		return;
	ex.render.spheres.children.forEach(function( sph ) { sph.material.color.setRGB( 1,0,0 );});
	intersects[0].object.material.color.setRGB(0,1,0);
	$(".experiment#"+eid+" .xyztable table td").css({'background-color':''});
	for(i=0;i<ex.locations.length;i++)
		if(ex.locations[i].sph==intersects[0].object)
			$(".experiment#"+eid+" .xyztable tr:eq("+i+") td.coordinate").css({"background-color":"lightGreen"});
}

// animation loop
function animate() {
	requestAnimationFrame( animate );
	updateSize();
	renderer.setClearColor( 0xffffff,0 );
	renderer.clear( true );
	renderer.enableScissorTest( true );
	for(iExp in exp)
		if(exp[iExp].render)
			render(iExp);
	renderer.enableScissorTest( false );
}

// render the scene
function render(iExp) {
	var scene=exp[iExp].render.scene;
	var camera=exp[iExp].render.camera;
	var trackball=exp[iExp].render.cameraControls;
	
	// update camera controls
	trackball.update();
	
	// the scene object contains the element object, which is the div in which
	// 3d data is displayed.
	var element = exp[iExp].render.container[0];
	var rect = element.getBoundingClientRect();
	if ( rect.bottom < 0 || rect.top  > renderer.domElement.clientHeight ||
		 rect.right  < 0 || rect.left > renderer.domElement.clientWidth ) {
	  return;  // it's off screen
	}
	// set the viewport
	var width  = rect.right - rect.left;
	var height = rect.bottom - rect.top;
	var left   = rect.left;
	var bottom = renderer.domElement.clientHeight - rect.bottom;
	
	// compensate for window springiness
	var dy=window.pageYOffset;
	if(dy<0) {
		bottom-=dy;
	} else {
		dy=window.pageYOffset-document.body.scrollHeight+window.innerHeight;
		if(dy>0) {
			bottom-=dy;
		}
	}
	
	// place viewport
	camera.aspect = width / height;
	camera.updateProjectionMatrix();
	renderer.setViewport( left, bottom, width, height );
	renderer.setScissor( left, bottom, width, height );
	
	// actually render the scene
	renderer.render( scene, camera );
}

function updateSize() {
	var canvas=$("#3d")[0];
	var width = canvas.clientWidth;
	var height = canvas.clientHeight;
	if ( canvas.width !== width || canvas.height != height ) {
		renderer.setSize ( width, height, false );
	}
}
