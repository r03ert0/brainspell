var	tasks;
var	cognitive;
var	behavioural;
var	concept;
var	minVotes=0;	// Minimum number of votes a tag must have to be displayed	
var meta;
var exp;
var ArticlePMID;
var EmptyArticle;

function updateArticle()
{
	console.log("[updateArticle] Login update at article level (Login: "+(loggedin?"Yes":"No")+")");
	
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
function updateExperiment(iExp)
{
	console.log("[updateExperiment] Login update at experiment "+iExp+" (Login: "+(loggedin?"Yes":"No")+")");
	
	/*
		Experiment-level display
		------------------------
	*/
	if(loggedin)
	{
		$(".experiment#"+iExp+" div.tableAlert").hide();
		$(".experiment#"+iExp+" div.tableMark").show();
		$(".experiment#"+iExp+" td.coordinate").attr("contentEditable","true");
		$(".experiment#"+iExp+" th.input").show();
		$(".experiment#"+iExp+" td.input").show();

		// Experiment table mark
		var prevMark=findPreviousTableMarkByUser(iExp);
		var markBadTable=exp[iExp].markBadTable;
		if(markBadTable && (markBadTable.bad+markBadTable.ok>=minVotes))
		{
			console.log("[updateExperiment] iexp:",iExp,"markbad:",markBadTable);
			$(".experiment#"+iExp+":first span#Yes").html('('+markBadTable.ok+')');
			$(".experiment#"+iExp+":first span#No").html('('+markBadTable.bad+')');
		
			if(markBadTable.bad>markBadTable.ok)
				$(".experiment#"+iExp+":first .tableAlert ").html('<img src="/img/alert.svg" style="height:0.8rem;display:inline;position:relative;top:0rem"/>This table may be incorrect');
			else
				$(".experiment#"+iExp+":first .tableAlert ").html("");
		}
		else
		{
			$(".experiment#"+iExp+":first span#Yes").html('');
			$(".experiment#"+iExp+":first span#No").html('');			
		}
		if(prevMark>-1)
		{
			$(".experiment#"+iExp+" input:radio[value='Yes']").attr('checked',prevMark==0);
			$(".experiment#"+iExp+" input:radio[value='No']").attr('checked',prevMark==1);
		}
		else
		{
			$(".experiment#"+iExp+" input:radio[value='Yes']").removeAttr('checked');
			$(".experiment#"+iExp+" input:radio[value='No']").removeAttr('checked');
		}
	
		// Experiment tags (specific)
		if(exp[iExp].tags)
			for(j=0;j<exp[iExp].tags.length;j++)
			{
				var rtag=exp[iExp].tags[j];
				rtag.agree=(rtag.agree)?parseInt(rtag.agree):0;
				rtag.disagree=(rtag.disagree)?parseInt(rtag.disagree):0;
			
				// Show tags only if there are at least minVotes votes,
				// or if the current user voted for it
				var	prevVote=findPreviousVoteByUser(iExp,rtag);
				if(rtag.agree+rtag.disagree>=minVotes || prevVote!=0)
					addTag(iExp,rtag);
				else
					removeTag(iExp,rtag);
			}
	}
	else
	{
		// Experiment table mark (global)
		$(".experiment#"+iExp+" div.tableAlert").show();
		$(".experiment#"+iExp+" div.tableMark").hide();
		$(".experiment#"+iExp+" td.coordinate").removeAttr("contentEditable");
		$(".experiment#"+iExp+" th.input").hide();
		$(".experiment#"+iExp+" td.input").hide();
	}
}
function initBrainSpellArticle()
{
	// Is JS enabled? Is it a touch device?
	var htmlTag = document.getElementsByTagName('html')[0];
	htmlTag.className = (htmlTag.className + ' ' || '') + 'hasJS';
	if ('ontouchstart' in document.documentElement)
		htmlTag.className = (htmlTag.className + ' ' || '') + 'isTouch';
	
	$.get(rootdir+"templates/cogatlas-tasks.html",function(data){tasks=data;console.log("[init] Tasks loaded");});
	$.get(rootdir+"templates/cogatlas-cognitive.html",function(data){cognitive=data;console.log("[init] Cognitive domains loaded");});
	$.get(rootdir+"templates/brainmap-behavioural.html",function(data){behavioural=data;console.log("[init] Behavioural domains loaded");});
	$.get(rootdir+"templates/concept.html",function(data){concept=data;console.log("[init] Concept loaded");});

	subscribeToLoginUpdates(updateArticle);

	animate();

	ArticlePMID=$("a#pmid").attr("href").split("/")[4];
	console.log("Article's PMID",ArticlePMID);

	if(exp_string=="<!--Experiments-->")
	{
		console.log("[initBrainSpellArticle] Article not in DB, search pubmed");
		$("#new-article-warning").show();			
		downloadArticleXML();
	}
	else
	{
		exp=$.parseJSON(exp_string);
		configureExperiments();

		if(meta_string)
			meta=$.parseJSON(meta_string);
		else
			meta={};
		if(meta.meshHeadings==undefined || meta.meshHeadings.length==0)
		{
			console.log("[initBrainSpellArticle] Article without MeSH tags, search pubmed");
			downloadArticleXML(function(){
				saveMetadata();
				logKeyValue("UserTriggeredAction",JSON.stringify({"action":"Update","element":"MeSH"}));
			});
		}
		else
		{
			configureMetadata();
			updateArticle();
		}
	}
}
function downloadArticleXML(callback)
{
	console.log("[downloadArticleXML]");
	$.get("http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi",
		{"db":"pubmed","id":ArticlePMID,"report":"xml"},
		function(xml) {
			parseArticleXML(xml);
						
			// Display information in webpage
			$("h2.paper-title").html(EmptyArticle.title);
			$("div.abstract").html(EmptyArticle.abstract);
			$("#reference").html(EmptyArticle.reference);
			$("a#doi").attr("href","http://dx.doi.org/"+EmptyArticle.doi);
			
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
	EmptyArticle.neurosynth="";

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
	var nexp=parseInt($("input#numNewExperiments").val());
	var	i;
	
	if(nexp>0)
	{
		$("#new-article-warning").hide();

		// add new experiments
		exp=[];
		for(i=0;i<nexp;i++)
			exp.push({"title":"","caption":"","locations":["0,0,0"]});
		
		// store empty article in database
		var result=$.ajax({
			type: "GET",
			url: "/php/brainspell.php",
			data: {
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
			},
			async: false
		}).done(function( msg ){
			console.log(msg);
		});
		
		configureExperiments();
		
		logKeyValue("UserAction",JSON.stringify("AddEmptyArticle"));
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
		$("div.comments").append("</p><a href='"+rootdir+"user/"+user+"'>"+user+"</a> ("+time.toLocaleString()+")<br \>");
		$("div.comments").append(comment);
		$("div.comments").append("</p><br \>");
	}
	
	console.log("[configureMetadata] metadata configured");
}
function configureMeSHDescriptors()
{
	var	metadata="";
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
function configureExperiments()
{
	console.log("[configureExperiments]");
	for(i=0;i<exp.length;i++)
	{
		if(exp[i].locations.length==0)
			continue;
		$(".experiments").append($('<div class="experiment" id="'+i+'">').load(rootdir+"templates/experiment.html",addExperiment(i)));
	}
	console.log("[configureExperiments] experiments configured");
}
function addExperiment(iExp)
{
	return function(responseText, textStatus, XMLHttpRequest){

		// Configure legend (title and caption)
		//-------------------------------------
		var title=(exp[iExp].title)?exp[iExp].title:"(Empty)";
		var caption=(exp[iExp].caption)?exp[iExp].caption:"(Empty)";
		$(".experiment#"+iExp+" .stored.title").append(title);
		$(".experiment#"+iExp+" .stored.caption").append(caption);
		$('.stored').on( 'keydown',function(e) {	// Intercept enter on 'stored' fields
			if(e.which==13&&e.shiftKey==false) {	// enter (without shift)
				store(this);
				return false;
			}
			if(e.which==9) {						// tab
				store(this);
			}
		});

		// Configure locations to 3D view
		//-------------------------------
		initTranslucentBrain(iExp);
		exp[iExp].render.spheres = new THREE.Object3D();
		exp[iExp].render.scene.add(exp[iExp].render.spheres);

		// Configure stereotaxic table
		//----------------------------
		var table=$(".experiment#"+iExp+" .xyztable table");
		table.click(function(e){if(e.target.tagName=="TD") clickOnTable(e.target)});
		table.keydown(function(e){keydownOnTable(e.target)});

		// Add experiment locations to table
		if(!exp[iExp].locations)
			return;
		var html="";
		var table=$(".experiment#"+iExp+" .xyztable table")[0];
		for(j=0;j<exp[iExp].locations.length;j++)
		{
			var coords=exp[iExp].locations[j].split(",");
			exp[iExp].locations[j]={};
			exp[iExp].locations[j].x=coords[0];
			exp[iExp].locations[j].y=coords[1];
			exp[iExp].locations[j].z=coords[2];
			var new_row = table.insertRow(j);
			new_row.innerHTML=[
					"<td class='coordinate'>"+coords[0]+"</td>",
					"<td class='coordinate'>"+coords[1]+"</td>",
					"<td class='coordinate'>"+coords[2]+"</td>",
					"<td class='input'><input type='image' class='del' src='/img/minus-circle.svg' onclick='delRow(this)'/></td>",
					"<td class='input'><input type='image' class='add' src='/img/plus-circle.svg' onclick='addRow(this)'/></td>"].join("\n");
		}

		// Intercept enter on table cells
		$(".experiment#"+iExp+" .xyztable table td").on( 'keydown',function(e) {
			if(e.which==13&&e.shiftKey==false) {	// enter (without shift)
				parseTable(this,iExp);
				return false;
			}
			if(e.which==9) {	// tab
				parseTable(this,iExp);
			}
		});

		// Parse locations: add locations to
		// 3d view and to stereotaxic table
		//----------------------------------
		parseTable("",iExp);

		// Add ontologies
		$(".experiment#"+iExp+" .ontologies").append(addOntology(iExp,"tasks"));
		$(".experiment#"+iExp+" .ontologies").append(addOntology(iExp,"cognitive"));
		$(".experiment#"+iExp+" .ontologies").append(addOntology(iExp,"behavioural"));
		
		// Configure radio button groups for table marks
		$(".experiment#"+iExp+" input:radio[value='Yes']").attr('name',"radio"+iExp);
		$(".experiment#"+iExp+" input:radio[value='No']").attr('name',"radio"+iExp);
				
		// Adjust locations table height
		var padd,legendheight,xyzhdrheight,ontheight;
		padd=parseInt($('.experiment#'+iExp).css('padding-top'));
		legendheight=$('.experiment#'+iExp+' .experiment-title').innerHeight();
		legendheight+=$('.experiment#'+iExp+' .experiment-caption').innerHeight();
		xyzhdrheight=$('.experiment#'+iExp+' .xyzheader').innerHeight();
		ontheight=$('.experiment#'+iExp+' .ontologies').innerHeight();
		badTableHeight=$(".experiment#"+iExp+" input.badTable").innerHeight()+10;
		$('.experiment#'+iExp+' .xyztable').css({"height":300,"max-height":300-badTableHeight-padd});

		updateExperiment(iExp);
		subscribeToLoginUpdates(function(){updateExperiment(iExp)});
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
	var iExp=div.attr('id');
	var	index=$(row).index();
	
	if(index>=0 && targetClass!="del" && targetClass!="add")
		selectRow(row,iExp);
}
function keydownOnTable(target)
{
	console.log("keydown:",target);
}
function selectRow(row,iExp) {
	$(".experiment#"+iExp+" .xyztable table td").css({'background-color':''});
	exp[iExp].render.spheres.children.forEach(function( sph ) { sph.material.color.setRGB( 1,0,0 );});
	if($(row).index()>=0) {
		$(row).children('td.coordinate').css({'background-color':'lightGreen'});
		exp[iExp].locations[$(row).index()].sph.material.color.setRGB(0,1,0);
	}
}
function enterRow(row,iExp) {
	$(row).on('keydown',function(e) {
		if(e.which==13&&e.shiftKey==false) {	// enter (without shift)
			parseTable(row,iExp);
			return false;
		}
		if(e.which==9) {	// tab
			parseTable(row,iExp);
		}				
	});
}
function delRow(row)
{
	var	par=$(row).closest(".experiment");
	var iExp=par.attr('id');
    var i=$(row).closest("tr").index();
    
    $(".experiment#"+iExp+" .xyztable table")[0].deleteRow(i);

    exp[iExp].render.scene.remove(exp[iExp].locations[i].sph);
    exp[iExp].render.spheres.remove(exp[iExp].locations[i].sph);
    exp[iExp].locations.splice(i,1);
	save(iExp,"locations",JSON.stringify(exp[iExp].locations,["x","y","z"]));
	selectRow(row,iExp); // this will erase any existing selection
}
function addRow(row)
{
	var	par=$(row).closest(".experiment");
	var iExp=par.attr('id');
    var i=$(row).closest('tr').index();
	var table=$(".experiment#"+iExp+" .xyztable table")[0];
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
	$(new_row).find('td[contentEditable]').each(function(){enterRow(this,iExp);});

	if(!exp[iExp].locations)
		exp[iExp].locations=[];
	exp[iExp].locations.splice(j,0,{"x":0,"y":0,"z":0});
	parseTable($(new_row),iExp);
	selectRow(new_row,iExp);
}

//============
// 3D Viewer and Table
//=============
function parseTable(row,iExp)
{
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

			exp[iExp].locations[i].x=x;
			exp[iExp].locations[i].y=y;
			exp[iExp].locations[i].z=z;
			
			save(iExp,"locations",JSON.stringify(exp[iExp].locations,["x","y","z"]));
		}
	}
		
	// Add location spheres
	var geometry = new THREE.SphereGeometry(1,16,16);
	var	color=0xff0000;
	for(j=0;j<exp[iExp].locations.length;j++)
	{
		if(exp[iExp].locations[j].sph)
		{
		    color=exp[iExp].locations[j].sph.material.color;
		    exp[iExp].render.spheres.remove(exp[iExp].locations[j].sph);
		}

		var x=exp[iExp].locations[j].x;
		var y=exp[iExp].locations[j].y;
		var z=exp[iExp].locations[j].z;
		var sph = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial({color: color}));
		sph.position=new THREE.Vector3(x*0.14,y*0.14+3,z*0.14-2);
		exp[iExp].render.spheres.add(sph);
		exp[iExp].locations[j].sph=sph;
	}
}
function store(obj)
{
	var	iExp=$(obj).closest(".experiment").attr("id");
	var	name=$(obj).attr('class').split(" ")[1];
	var	value=$(obj).text();
	var value2=value.replace(/[^ a-zA-Z0-9\(\)\[\]]/g, ""); // Filter out
	$(obj).text(value2);
	save(iExp,name,value);
}
function save(iExp,name,value)
{
	if(name=="title")
		exp[iExp].title=value;
	if(name=="caption")
		exp[iExp].caption=value;
	
	saveExperiments();

	// log user action
	logKeyValue("UserAction",JSON.stringify({"action":"Edit","experiment":iExp,"element":name}));
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
		type: "GET",
		url: "/php/brainspell.php",
		data: {
			action:"add_article",
			command:"experiments",
			Experiments:experiments_string,
			PMID:ArticlePMID
		},
		async: false
	}).done(function( msg ){
		console.log("[saveExperiments]",msg);
	});
}
function saveMetadata()
{
	// stringify experiments
	var metadata_string=JSON.stringify(meta);
	
	// save to database
	var result=$.ajax({
		type: "GET",
		url: "/php/brainspell.php",
		data: {
			action:"add_article",
			command:"metadata",
			Metadata:metadata_string,
			PMID:ArticlePMID
		},
		async: false
	}).done(function( msg ){
		console.log("[saveMetadata]",msg);
	});
}
function addOntology(iExp,ontology)
{
	var	str="";
	var ct="",cts=new Array({ontology:"tasks",title:"Cognitive Atlas Tasks"},{ontology:"cognitive",title:"Cognitive Atlas Cognitive Domains"},{ontology:"behavioural",title:"BrainMap Behavioural Domains"});
	var	cl;
	
	for(i=0;i<cts.length;i++)
		if(cts[i].ontology==ontology)
			ct=cts[i];

	str+='<div class="ontology" id="'+ontology+'" style="padding-left:5px;padding-bottom:5px">\n';
	str+='<div class="ontology-name"><span class="tag" style="top:-0.2em"><a onclick="openOntologyModal('+iExp+',\''+ontology+'\',\'+\');">+</a></span><b style="padding-left:4px;font-family:sans-serif">'+ct.title+'</b></div>\n';
	str+='<ul class="ontology-tags">\n';
	str+='</ul>\n';
	str+='</div>\n';

	return str;
}
function addTag(iExp,rtag)
{
	var prevTag=$("div.experiment#"+iExp+" div.ontology#"+rtag.ontology+" li:contains('"+rtag.name+"')").length;
	if(prevTag!=0)
		return;
		
	var tag=$("<li>",{class:'tag'});
	tag.html(rtag.name);
	tag.click({rtag:rtag},function(e){openTagModal(iExp,e.data.rtag)});
	updateTagColor(tag,rtag);
	$("div.experiment#"+iExp+" div.ontology#"+rtag.ontology+" ul").append(tag);
	return tag;
}
function removeTag(iExp,rtag)
{
	$("div.experiment#"+iExp+" div.ontology#"+rtag.ontology+" li:contains('"+rtag.name+"')").remove();
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
function findPreviousVoteByUser(iExp,rtag)
{
	var found=0,vote=0;

	if(loggedin)
	{
		var result=$.ajax({
			type: "GET",
			url: rootdir+"php/brainspell.php",
			data: {
				action:"get_log",
				type:"Vote",
				UserName:username,
				TagName:rtag.name,
				TagOntology:rtag.ontology,
				Experiment:iExp,
				PMID:ArticlePMID
			},
			async: false
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
function findPreviousTableMarkByUser(iExp)
{
	var found=0,mark=-1;

	if(loggedin)
	{
		result=$.ajax({
			type: "GET",
			url: rootdir+"php/brainspell.php",
			data: {
				action:"get_log",
				type:"MarkTable",
				UserName:username,
				Experiment:iExp,
				PMID:ArticlePMID
			},
			async: false
		}).done(function( msg ){
			console.log("[findPreviousTableMarkByUser] ",msg);
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
			url: rootdir+"php/brainspell.php",
			data: {
				action:"get_log",
				type:"StereoSpace",
				UserName:username,
				PMID:ArticlePMID
			},
			async: false
		}).done(function( msg ){
			console.log("[findPreviousStereoSpaceByUser] ",msg);
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
			url: rootdir+"php/brainspell.php",
			data: {
				action:"get_log",
				type:"NSubjects",
				UserName:username,
				PMID:ArticlePMID
			},
			async: false
		}).done(function( msg ){
			console.log("[findPreviousNSubjectsByUser] ",msg);
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
function openOntologyModal(iExp,ontology)
{
	if(ontology=="tasks")
		$('body').append(tasks);
	else if(ontology=="cognitive")
		$('body').append(cognitive);
	else if(ontology=="behavioural")
		$('body').append(behavioural);
	$('#light').show();
	$('#fade').show();
	$('#light #iExp').html(parseInt(iExp));
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
function openTagModal(iExp,rtag)
{
	console.log("[openTagModal]");
	var	obj=0;
	
	if(iExp>-1)
		obj=findTag(exp[iExp].tags,rtag.name,rtag.ontology);
	else
		obj=findTag(meta.meshHeadings,rtag.name,rtag.ontology);
	if(obj)
		rtag=obj;
	$('body').append(concept);
	$('#box').show();
	$('#boxfade').show();
	$('#box #iExp').html(iExp);
	$('#box #ontology').html(rtag.ontology);
	
	configureTagModal(rtag);
	resizeTagModal();
}
function closeTagModal(vote)
{
	var	iExp=parseInt($('#box div#iExp').text());
	var	agree=parseInt($('#box span.agree').text());
	var disagree=parseInt($('#box span.disagree').text());
	var	name=$('#box h2.name').text();
	var ontology=$('#box #ontology').text();
	var	tag;
	
	// Vote -2:retract, 0:cancel, -1:down-vote or 1:up-vote
	if(vote!=0)	
	{
		// find the tag
		if(iExp>=0)	// iExp is >=0 for tables, =-1 for article-level tags
		{
			// check whether there were already any tags associated with this
			// experiment
			if(!(exp[iExp].tags))
				exp[iExp].tags=new Array;
			
			// check whether the currently voted tag was already among
			// the experiment's tags
			obj=findTag(exp[iExp].tags,name,ontology);
			if(obj)
			{
				rtag=obj;
				agree=rtag.agree;
				disagree=rtag.disagree;
			}
			else
			{
				var	rtag=new Object({"name":name,"ontology":ontology,"agree":0,"disagree":0});
				exp[iExp].tags.push(rtag);
				// addTag(iExp,rtag);
			}

			if(vote==-1 || vote==1)
				addTag(iExp,rtag); // addTag only adds the tag if it wasn't already added
		}
		else
			rtag=findTag(meta.meshHeadings,name,ontology);

		// compute total tag votes
		var prevVote=findPreviousVoteByUser(iExp,rtag);
		if(prevVote!=0)
		{
			if(vote==-2)	// retraction
			{
				if(prevVote==-1)
					rtag.disagree--;
				if(prevVote==1)
					rtag.agree--;
				if(iExp>=0 && rtag.disagree==0 && rtag.agree==0)
					removeTag(iExp,rtag); // remove tag from document
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
		if(iExp>=0)
		{
			tag=$("div.experiment#"+iExp+" div.ontology#"+rtag.ontology+" li").filter(function(){return $(this).text()==rtag.name});
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
		logVote(iExp,rtag,vote);
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
function logVote(iExp,rtag,vote)
{
	console.log("[logVote] Log vote "+vote+" of user "+username);

	var	obj={	action:"add_log",
				type:"Vote",
				PMID:ArticlePMID,
				UserName:username,
				TagName:rtag.name,
				TagOntology:rtag.ontology,
				TagVote:vote,
				Experiment:iExp};
	$.get(rootdir+"php/brainspell.php",obj,function(data){
		console.log("[logVote]",data);
	});
}
function logMarkTable(sender)
{
	var	iExp=$(sender).closest("div.experiment").attr('id');
	var mark=$(".experiment#"+iExp+" input:radio[value='No']").is(':checked')?1:0;
	var	obj={	action:"add_log",
				type:"MarkTable",
				PMID:ArticlePMID,
				UserName:username,
				Mark:mark,
				Experiment:iExp};
	$.get(rootdir+"php/brainspell.php",obj,function(data){
		var out=$.parseJSON(data);
		exp[iExp].markBadTable={"bad":out.result.bad,"ok":out.result.ok};
		if(out.result.ok+out.result.bad>=minVotes)
		{
			$(".experiment#"+iExp+":first span#Yes").html('('+out.result.ok+')');
			$(".experiment#"+iExp+":first span#No").html('('+out.result.bad+')');
		}
	});
}
function logStereoSpace(sender)
{
	var	space=$(sender).val();
	console.log("[logStereoSpace] space",space);
	var	obj={	action:"add_log",
				type:"StereoSpace",
				PMID:ArticlePMID,
				UserName:username,
				StereoSpace:space};
	$.get(rootdir+"php/brainspell.php",obj,function(data){
		var out=$.parseJSON(data);
		console.log("[logStereoSpace] out",out.result);
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
	console.log("[logNSubjects] nsubjects",nsubjects);
	var	obj={	action:"add_log",
				type:"NSubjects",
				PMID:ArticlePMID,
				UserName:username,
				NSubjects:nsubjects};
	$.get(rootdir+"php/brainspell.php",obj,function(data){
		var out=$.parseJSON(data);
		console.log("[logNSubjects] out",out.result);

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
	console.log("[logComment]",content,txt);
	var	time=new Date().getTime();
	var	comment='{"comment":'+txt+',"user":"'+username+'","time":"'+time+'"}';
	console.log("[logComment] comment",comment);
	var	obj={	action:"add_log",
				type:"Comment",
				PMID:ArticlePMID,
				UserName:username,
				Comment:comment};
	$.get(rootdir+"php/brainspell.php",obj,function(data){
		console.log("[logComment]",data);
		var out=$.parseJSON(data);
		var	comment=$.parseJSON(out.result);
		var	time=new Date();
		time.setTime(comment.time);
		$("div.comments").append("</p><a href='"+rootdir+"user/"+username+"'>"+username+"</a> ("+time.toLocaleString()+")<br \>");
		$("div.comments").append(comment.comment);
		$("div.comments").append("</p><br \>");
		$("textarea").val("");
	});
}
function logKeyValue(key,value)
{
	console.log("[logKeyValue]",key,value);
	var	obj={	action:"add_log",
				type:"KeyValue",
				PMID:ArticlePMID,
				UserName:username,
				Key:key,
				Value:value};
	$.get(rootdir+"php/brainspell.php",obj,function(data){
		console.log("[logKeyValue]",data);
	});
}

// Translucent Viewer
function initTranslucentBrain(iExp)
{
	exp[iExp].render={};
	exp[iExp].render.container=$(".experiment#"+iExp+" div.metaCoords");

	// Init rendered
	if( Detector.webgl ){
		exp[iExp].render.renderer = new THREE.WebGLRenderer({
			antialias				: true,	// to get smoother output
			preserveDrawingBuffer	: true	// to allow screenshot
		});
		exp[iExp].render.renderer.setClearColor( 0xffffff, 0 );
	}else{
		exp[iExp].render.renderer = new THREE.CanvasRenderer();
	}

	var container=exp[iExp].render.container;
	var	width=container.width();
	var	height=container.height();
	exp[iExp].render.renderer.setSize( width, height );
	container.append(exp[iExp].render.renderer.domElement);

	// create a scene
	exp[iExp].render.scene = new THREE.Scene();
	
	// create projector (for hit detection)
	exp[iExp].render.projector = new THREE.Projector();
	exp[iExp].render.renderer.domElement.addEventListener( 'mousedown', function(e){onDocumentMouseDown(e,iExp);}, false );

	// put a camera in the scene
	exp[iExp].render.camera	= new THREE.PerspectiveCamera(40,width/height,25,50);
	exp[iExp].render.camera.position.set(0, 0, 40);
	exp[iExp].render.scene.add(exp[iExp].render.camera);

	// create a camera control
	exp[iExp].render.cameraControls=new THREE.TrackballControls(exp[iExp].render.camera,exp[iExp].render.container.get(0) )
	exp[iExp].render.cameraControls.noZoom=true;
	exp[iExp].render.cameraControls.addEventListener( 'change', function(){exp[iExp].render.light.position.copy( exp[iExp].render.camera.position );} );

	// allow 'p' to make screenshot
	//THREEx.Screenshot.bindKey(renderer);
	
	// Add lights
	var	light	= new THREE.AmbientLight( 0x3f3f3f);
	exp[iExp].render.scene.add(light );
	exp[iExp].render.light	= new THREE.PointLight( 0xffffff,2,80 );
	//var	light	= new THREE.DirectionalLight( 0xffffff);
	//light.position.set( Math.random(), Math.random(), Math.random() ).normalize();
	exp[iExp].render.light.position.copy( exp[iExp].render.camera.position );
	//light.position.set( 0,0,0 );
	exp[iExp].render.scene.add(exp[iExp].render.light );

	// Load mesh (ply format)
	var oReq = new XMLHttpRequest();
	oReq.open("GET", "/data/lrh3.ply", true);
	oReq.responseType="text";
	oReq.onload = function(oEvent)
	{
		var tmp=this.response;
		var modifier = new THREE.SubdivisionModifier(1);
		
		exp[iExp].render.material=new THREE.ShaderMaterial({
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
								'varying vec4	vFragColor;',
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
								'varying vec4	vFragColor;',
								'void main(){',
								'	vec3 worldCameraToVertex= vVertexWorldPosition - cameraPosition;',
								'	vec3 viewCameraToVertex	= (viewMatrix * vec4(worldCameraToVertex, 0.0)).xyz;',
								'	viewCameraToVertex	= normalize(viewCameraToVertex);',
								'	float intensity		= pow(coeficient + dot(vVertexNormal, viewCameraToVertex), power);',
								'	gl_FragColor		= vec4(glowColor, intensity);',
								'}',
							].join('\n'),
			transparent	: true,
			depthWrite	: false,
		});
		
		exp[iExp].render.geometry=new THREE.PLYLoader().parse(tmp);
		exp[iExp].render.geometry.sourceType = "ply";
		
		modifier.modify(exp[iExp].render.geometry);
		for(i=0;i<exp[iExp].render.geometry.vertices.length;i++)
		{
			exp[iExp].render.geometry.vertices[i].x*=0.14;
			exp[iExp].render.geometry.vertices[i].y*=0.14;
			exp[iExp].render.geometry.vertices[i].z*=0.14;
			exp[iExp].render.geometry.vertices[i].y+=3;
			exp[iExp].render.geometry.vertices[i].z-=2;
		}

		exp[iExp].render.brainmesh=new THREE.Mesh(exp[iExp].render.geometry,exp[iExp].render.material);
		exp[iExp].render.scene.add(exp[iExp].render.brainmesh);
	};
	oReq.send();
}
// hit detection
function onDocumentMouseDown( event,iExp ) {
	event.preventDefault();
	var	x,y,i;
	var r = event.target.getBoundingClientRect();

	projector = new THREE.Projector();
	mouseVector = new THREE.Vector3();
	mouseVector.x= ((event.clientX-r.left) / event.target.clientWidth ) * 2 - 1;
	mouseVector.y=-((event.clientY-r.top) / event.target.clientHeight ) * 2 + 1;
	
	var raycaster = projector.pickingRay( mouseVector.clone(), exp[iExp].render.camera );
	var intersects = raycaster.intersectObjects( exp[iExp].render.spheres.children );

	if(intersects.length==0)
		return;
	exp[iExp].render.spheres.children.forEach(function( sph ) { sph.material.color.setRGB( 1,0,0 );});
	intersects[0].object.material.color.setRGB(0,1,0);
	$(".experiment#"+iExp+" .xyztable table td").css({'background-color':''});
	for(i=0;i<exp[iExp].locations.length;i++)
		if(exp[iExp].locations[i].sph==intersects[0].object)
			$(".experiment#"+iExp+" .xyztable tr:eq("+i+") td.coordinate").css({"background-color":"lightGreen"});
}

// animation loop
function animate()
{
	requestAnimationFrame( animate );
	for(iExp in exp)
		if(exp[iExp].render)
			if(exp[iExp].render.renderer)
				render(iExp);
}
// render the scene
function render(iExp) {
	// update camera controls
	exp[iExp].render.cameraControls.update();
	
	// actually render the scene
	exp[iExp].render.renderer.render(exp[iExp].render.scene,exp[iExp].render.camera );
}
