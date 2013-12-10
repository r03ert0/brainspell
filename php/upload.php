<?php include $_SERVER['DOCUMENT_ROOT']."/php/base.php"; ?>
<!DOCTYPE HTML>
<html lang>
<head>
    <meta charset = "UTF-8" />
    <script src="/js/jquery-1.8.2.min.js"></script>
    <script>
    function addarticle(data)
    {
        console.log("Adding article PMID "+data.PMID);
        $.ajax({
            type: "GET",
            url: "/php/brainspell.php",
            data: {
            	action:"add_article",
            	command:"new",
                Title:data.Title,
                Authors:data.Authors,
                Abstract:data.Abstract,
                PMID:data.PMID,
                Year:data.Year,
                Journal:data.Journal,
                Reference:data.Reference,
                NeuroSynthID:data.NeuroSynthID,
                Experiments:data.Experiments,
                Metadata:data.Metadata
            },
            async: false,
            error: function(jqXHR, textStatus, errorThrown){ console.log("ERROR: "+textStatus);},
            success:function(data, textStatus, jqXHR){console.log("SUCCESS: "+textStatus);}
        }).done(function( msg ){
            console.log("MESSAGE: ["+msg+"]");
        });
        console.log("");
    }
    function indexarticle(data)
    {
        console.log("Indexing article PMID "+data.PMID);
        $.ajax({
            type: "GET",
            url: "/php/brainspell.php",
            data: {
            	action:"index_lucene",
                Title:data.Title,
                Authors:data.Authors,
                Abstract:data.Abstract,
                PMID:data.PMID,
                Year:data.Year,
                Journal:data.Journal,
                Reference:data.Reference,
                MeshHeadings:data.MeshHeadings
            },
            async: false,
            error: function(jqXHR, textStatus, errorThrown){ console.log("ERROR: "+textStatus);},
            success:function(data, textStatus, jqXHR){console.log("SUCCESS: "+textStatus);}
        }).done(function( msg ){
            console.log("MESSAGE: ["+msg+"]");
        });
        console.log("");
    }
    function process()
    {
        var xml = $("#xml").val();
        var xmlDoc = $.parseXML( xml );
        //console.log(xmlDoc);
        console.log("Just do it");
        $xml = $( xmlDoc );
        $xml.find('paper').each(
            function(i,e){
                pmid=$(e).find('Medline_number').text();
                jQuery.ajax({
                    url: "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi",
                    data: {
                        db:'pubmed',
                        id:pmid,
                        report:'xml'
                    },
                    dataType:'xml',
                    async: false,
                    success: function(data){
                        console.log(data);
                        var ref="";
                        var authorsArray=new Array;
                        var authors="";
                        
                        // Authors
                        $(data).find('AuthorList').find('Author').each(
                            function(i,e){
                                authorsArray.push($(e).find('LastName').text()+" "+$(e).find('Initials').text());
                            }
                        );
                        
                        // Reference
                        for(j=0;j<authorsArray.length;j++)
                        {
                            if(j==5)
                            {
                                ref+=" et al.";
                                break;
                            }
                            ref+=authorsArray[j];
                            if(j<Math.min(4,authorsArray.length-1))
                                ref+=", ";
                        }
                        ref+=" ("+$(data).find('PubDate').find('Year').text()+") ";
                        ref+=$(data).find('ISOAbbreviation').text()+" ";
                        ref+=$(data).find('JournalIssue').find('Volume').text();
                        ref+="("+$(data).find('JournalIssue').find('Issue').text()+"):";
                        ref+=$(data).find('Pagination').find('MedlinePgn').text();
                        
                        // Experiments
                        var experiments=new Array;
                        $(e).find('experiment').each(function(){
                            var experiment=new Object;
                            experiment.title=$(this).find('title').text();
                            experiment.caption=$(this).find('caption').text();
                            experiment.locations=new Array;
                            $(this).find('location').each(function(){
                                experiment.locations.push($(this).text());});
                            experiments.push(experiment);
                        });
                        
                        // Metadata
                        var metadata=new Object;
                        // space
                        metadata.space=$(e).find('neurosynth').find('space').text();
                        // mesh headings
                        var meshHeadings=new Array;
                        $(data).find('MeshHeadingList').find('MeshHeading').find('DescriptorName').each(function(){
                            var mesh=new Object;
                            mesh.name=$(this).text();
                            mesh.majorTopic=$(this).attr('MajorTopicYN');
                            meshHeadings.push(mesh);
                        });
                        metadata.meshHeadings=meshHeadings;
                        
                        // Make query
                        var query=new Object;
                        query.Title=$(data).find('Article').find('ArticleTitle').text();
                        query.Authors=authorsArray.toString();
                        query.Abstract=$(data).find('Article').find('Abstract').find('AbstractText').text();
                        query.Year=$(data).find('PubDate').find('Year').text();
                        query.Journal=$(data).find('ISOAbbreviation').text();
                        query.PMID=pmid;
                        query.Reference=ref;
                        query.NeuroSynthID=$(e).find('neurosynth').find('id').text();
                        query.Experiments=JSON.stringify(experiments);
                        query.Metadata=JSON.stringify(metadata);
                        query.MeshHeadings=meshHeadings.toString();
                        
                        addarticle(query);
                        indexarticle(query);
                        
                        /*
                        fmri,vbm
                        citation: 
                        submitter
                        prose description
                        [subjects]: subjects group name, diagnosis (icd code), concurrent diagnosis, short description, N, gender (F,M,mixed), handedness (L,R,mixed), native language, min/mean/max age
                        [conditions]: properties (stimulus modality (auditory, gustatory, visual, ...), stimulus type (3d objects, abstract patterns, acupuncture), stimulus prose description, response modality (arm, foot, hand, leg,...), response type (blink, breath hold, button press, ...), response prose description, instructions (attend, choose, count,...), instructions prose description),
                                    external variable (none, accuracy, behavioural data, ), prose description
                        [sessions]: single,multiple,prose
                        brain template: afni, fsl, spm2, 5, 96, 97, 99, ..., prose description
                        [experiments]: context (experiment name, context (age, disease, drug, gender, ...)),
                                     functional imaging (fmri, pet, spect, meg, eeg), prose
                                     subjects ({select}),
                                     conditions ({select}, activations/deactivations, high/low level control),
                                     sessions ({select}),
                                     contrast (stimulus modality (0/1), stimulus type (0/1), response modality (0/1), response type (0,1), instruction (0/1), external variable (0/1), experience-dependent change (0/1), group (0/1), session (0/1), prose),
                                     paradigm class (action observation, acupuncture, affective pictures, anti-saccades, ...),
                                     behavioural domain (action, cognition, emotion, interoception, perception, action.execution, ...),
                                     locations (x,y,z, value, unit, extent
                        results synopsis
                        feedback
                        
                        others, not in brainmap:
                        p-value
                        effect size
                        multiple comparisons correction method
                        motion correction method
                        scanner brand
                        scanner field strength
                        */
                    }
                });
            }
        );
    }
    </script>
</head>
<body>
    <form action="javascript:process()">
        <fieldset>
        <label>Paste xml articles</label><br>
        <textarea id = "xml" rows = "10" cols = "80">
<papers>
<paper>
	<id>NA</id>
	<doi>10.1093/cercor/bhi097</doi>
	<Medline_number>15858160</Medline_number>
	<authors>
		<author>Abe N</author>
		<author> Suzuki M</author>
		<author> Tsukiura T</author>
		<author> Mori E</author>
		<author> Yamaguchi K</author>
		<author> Itoh M</author>
		<author> Fujii T</author>
	</authors>
	<title><![CDATA[Dissociable roles of prefrontal and anterior cingulate cortices in deception.]]></title>
	<year>2006</year>
	<journal>Cerebral cortex (New York, N.Y. : 1991)</journal>
	<PubMed>
		<Abstract><![CDATA[Recent neuroimaging studies have shown the importance of the prefrontal and anterior cingulate cortices in deception. However, little is known about the role of each of these regions during deception. Using positron emission tomography (PET), we measured brain activation while participants told truths or lies about two types of real-world events: experienced and unexperienced. The imaging data revealed that activity of the dorsolateral, ventrolateral and medial prefrontal cortices was commonly associated with both types of deception (pretending to know and pretending not to know), whereas activity of the anterior cingulate cortex (ACC) was only associated with pretending not to know. Regional cerebral blood flow (rCBF) increase in the ACC was positively correlated with that in the dorsolateral prefrontal cortex only during pretending not to know. These results suggest that the lateral and medial prefrontal cortices have general roles in deception, whereas the ACC contributes specifically to pretending not to know.]]></Abstract>
		<Mesh><![CDATA[Adolescent; Adult; Brain Mapping/methods; *Deception; Dissociative Disorders/*physiopathology/*radionuclide imaging; Evoked Potentials; Gyrus Cinguli/*physiopathology/*radionuclide imaging; Humans; Lie Detection; Male; Prefrontal Cortex/*physiopathology/*radionuclide imaging]]></Mesh>
		<Source>Cereb Cortex. 2006 Feb;16(2):192-9. Epub 2005 Apr 27. </Source>
		<DateOfPublication>2006 Feb</DateOfPublication>
		<Pages>192-9</Pages>
	</PubMed>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>1</id>
		<title><![CDATA[ Brain regions showing activation in a main effect of deception]]></title>
		<caption><![CDATA[]]></caption>
		<numPeaks>3</numPeaks>
		<location>-26,54,14</location>
		<location>52,18,12</location>
		<location>10,16,32</location>
	</experiment>
	<neurosynth>
		<id>1</id>
		<space>MNI</space>
	</neurosynth>
</paper>
<paper>
	<id>NA</id>
	<doi>10.1093/cercor/bhn037</doi>
	<Medline_number>18372290</Medline_number>
	<authors>
		<author>Abe N</author>
		<author> Okuda J</author>
		<author> Suzuki M</author>
		<author> Sasaki H</author>
		<author> Matsuda T</author>
		<author> Mori E</author>
		<author> Tsukada M</author>
		<author> Fujii T</author>
	</authors>
	<title><![CDATA[Neural correlates of true memory, false memory, and deception.]]></title>
	<year>2008</year>
	<journal>Cerebral cortex (New York, N.Y. : 1991)</journal>
	<PubMed>
		<Abstract><![CDATA[We used functional magnetic resonance imaging (fMRI) to determine whether neural activity can differentiate between true memory, false memory, and deception. Subjects heard a series of semantically related words and were later asked to make a recognition judgment of old words, semantically related nonstudied words (lures for false recognition), and unrelated new words. They were also asked to make a deceptive response to half of the old and unrelated new words. There were 3 main findings. First, consistent with the notion that executive function supports deception, 2 types of deception (pretending to know and pretending not to know) recruited prefrontal activity. Second, consistent with the sensory reactivation hypothesis, the difference between true recognition and false recognition was found in the left temporoparietal regions probably engaged in the encoding of auditorily presented words. Third, the left prefrontal cortex was activated during pretending to know relative to correct rejection and false recognition, whereas the right anterior hippocampus was activated during false recognition relative to correct rejection and pretending to know. These findings indicate that fMRI can detect the difference in brain activity between deception and false memory despite the fact that subjects respond with "I know" to novel events in both processes.]]></Abstract>
		<Mesh><![CDATA[Brain/anatomy &amp; histology/*physiology; *Brain Mapping; *Deception; Functional Laterality; Head Movements; Humans; Japan; Judgment; Language; Magnetic Resonance Imaging; Male; Memory/*physiology; Neurons/*physiology; Patient Selection; Prefrontal Cortex/*physiology; Recognition (Psychology)/*physiology; Reference Values; *Repression, Psychology; Semantics; Speech/physiology]]></Mesh>
		<Source>Cereb Cortex. 2008 Dec;18(12):2811-9. Epub 2008 Mar 27. </Source>
		<DateOfPublication>2008 Dec</DateOfPublication>
		<Pages>2811-9</Pages>
	</PubMed>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>1</id>
		<title><![CDATA[ Percent correct and reaction time for all conditions]]></title>
		<caption><![CDATA[Note: The accuracy of subjects' responses to "False targets" was assessed by the rate of correct rejection ("new" responses), but the reaction time was based on the trials of false recognition ("old" responses).]]></caption>
		<numPeaks>0</numPeaks>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>2</id>
		<title><![CDATA[ Brain regions showing main effect of making a deceptive responses {(LT - TR) + (LN - CR)}]]></title>
		<caption><![CDATA[Note: Only the most significant peaks within each area of activation are reported in this table.]]></caption>
		<numPeaks>19</numPeaks>
		<location>12,57,-6</location>
		<location>33,21,15</location>
		<location>24,15,60</location>
		<location>42,6,51</location>
		<location>24,-3,57</location>
		<location>15,-27,0</location>
		<location>24,-69,-12</location>
		<location>18,-75,39</location>
		<location>-45,48,-15</location>
		<location>-12,21,57</location>
		<location>-30,21,9</location>
		<location>-48,9,6</location>
		<location>-27,3,60</location>
		<location>-54,-6,39</location>
		<location>-42,-27,3</location>
		<location>-30,-42,45</location>
		<location>-27,-57,54</location>
		<location>-51,-60,36</location>
		<location>-30,-69,30</location>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>3</id>
		<title><![CDATA[ Brain regions showing greater responses during TR compared with FR]]></title>
		<caption><![CDATA[Note: Only the most significant peaks within each area of activation are reported in this table.]]></caption>
		<numPeaks>5</numPeaks>
		<location>66,-21,-3</location>
		<location>0,60,24</location>
		<location>0,-45,-42</location>
		<location>-48,-9,-15</location>
		<location>-60,-18,-12</location>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>4</id>
		<title><![CDATA[ Brain regions showing greater responses during LN compared with CR]]></title>
		<caption><![CDATA[Note: Only the most significant peaks within each area of activation are reported in this table.]]></caption>
		<numPeaks>7</numPeaks>
		<location>24,15,39</location>
		<location>18,-27,3</location>
		<location>-12,21,54</location>
		<location>-27,21,12</location>
		<location>-42,18,39</location>
		<location>-27,9,42</location>
		<location>-51,-57,36</location>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>5</id>
		<title><![CDATA[ Brain regions showing greater responses during FR compared with CR]]></title>
		<caption><![CDATA[Note: Only the most significant peaks within each area of activation are reported in this table.]]></caption>
		<numPeaks>9</numPeaks>
		<location>9,6,-9</location>
		<location>36,-9,-15</location>
		<location>-9,33,-18</location>
		<location>-21,27,-24</location>
		<location>-42,27,0</location>
		<location>-24,18,39</location>
		<location>-27,18,12</location>
		<location>-12,3,9</location>
		<location>-27,-63,54</location>
	</experiment>
	<neurosynth>
		<id>2</id>
		<space>MNI</space>
	</neurosynth>
</paper>
<paper>
	<id>NA</id>
	<doi>10.1016/j.neuroimage.2008.10.018</doi>
	<Medline_number>19015036</Medline_number>
	<authors>
		<author>Abel S</author>
		<author> Dressel K</author>
		<author> Bitzer R</author>
		<author> Kummerer D</author>
		<author> Mader I</author>
		<author> Weiller C</author>
		<author> Huber W</author>
	</authors>
	<title><![CDATA[The separation of processing stages in a lexical interference fMRI-paradigm.]]></title>
	<year>2009</year>
	<journal>NeuroImage</journal>
	<PubMed>
		<Abstract><![CDATA[In picture-word interference paradigms, the picture naming process is influenced by an additional presentation of linguistic distractors. Naming response times (RTs) are speeded (facilitation) by associatively-related and phonologically-related words when compared to unrelated words, while they are slowed down by categorically-related words (inhibition), given that distractor onsets occur at appropriate stimulus onset asynchronies (SOAs). In the present study with healthy subjects, we for the first time integrated all four auditorily presented distractor types into a single paradigm at an SOA of -200 ms, in order to directly compare behavioral and neural interference effects between them. The behavioral study corroborated results of previous studies and revealed that associatively-related distractors speeded RTs even more than phonologically-related distractors, thereby becoming equally fast as naming without distractors. Distractors were assumed to specifically enhance activation of brain areas corresponding to processing stages as determined in a cognitive model of word production (Indefrey, P., Levelt, W.J.M., 2004. The spatial and temporal signatures of word production components. Cognition 92, 101-144.). Functional magnetic resonance imaging (fMRI) at 3 T revealed activation of left superior temporal gyrus exclusively for phonologically-related distractors, and activation of left or right lingual gyrus exclusively for associatively-related and categorically-related distractors, respectively. Moreover, phonologically-related distractors elicited phonological-phonetic networks, and both semantic distractors evoked areas associated with mental imagery, semantics, and episodic memory retrieval and associations. While processes involved in distractor inhibition (e.g., conflict/competition monitoring) and high articulatory demands were observed for categorically-related distractors, priming of articulatory planning was revealed for associatively-related distractors. We conclude that activations of neural networks as obtained by the fMRI interference paradigm can be predicted from a cognitive model.]]></Abstract>
		<Mesh><![CDATA[Adult; Brain/*physiology; Brain Mapping/*methods; Cognition/*physiology; Female; Humans; *Language Development; Magnetic Resonance Imaging/*methods; Male; Young Adult]]></Mesh>
		<Source>Neuroimage. 2009 Feb 1;44(3):1113-24. Epub 2008 Oct 29. </Source>
		<DateOfPublication>2009 Feb 1</DateOfPublication>
		<Pages>1113-24</Pages>
	</PubMed>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>1</id>
		<title><![CDATA[]]></title>
		<caption><![CDATA[]]></caption>
		<numPeaks>0</numPeaks>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>2</id>
		<title><![CDATA[]]></title>
		<caption><![CDATA[]]></caption>
		<numPeaks>3</numPeaks>
		<location>-56,-37,15</location>
		<location>-12,-81,2</location>
		<location>21,-82,-3</location>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>3</id>
		<title><![CDATA[]]></title>
		<caption><![CDATA[]]></caption>
		<numPeaks>14</numPeaks>
		<location>-56,-42,41</location>
		<location>-56,-37,16</location>
		<location>50,0,-8</location>
		<location>-53,-37,13</location>
		<location>62,-24,37</location>
		<location>68,-29,12</location>
		<location>-12,-84,2</location>
		<location>-36,-74,29</location>
		<location>-12,-82,2</location>
		<location>0,-18,53</location>
		<location>33,-41,-11</location>
		<location>45,29,-12</location>
		<location>21,-85,-3</location>
		<location>6,-32,-6</location>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>4</id>
		<title><![CDATA[]]></title>
		<caption><![CDATA[]]></caption>
		<numPeaks>6</numPeaks>
		<location>-39,-67,48</location>
		<location>-62,-41,-5</location>
		<location>-59,-44,2</location>
		<location>-45,40,-15</location>
		<location>-33,20,-14</location>
		<location>-30,29,-6</location>
	</experiment>
	<experiment>
		<name>NA</name>
		<BehavioralDomains>
		</BehavioralDomains>
		<id>5</id>
		<title><![CDATA[]]></title>
		<caption><![CDATA[]]></caption>
		<numPeaks>8</numPeaks>
		<location>-12,-85,-1</location>
		<location>36,-70,1</location>
		<location>33,23,-4</location>
		<location>-39,-76,4</location>
		<location>-9,-82,-1</location>
		<location>-30,-17,59</location>
		<location>-27,-19,20</location>
		<location>15,-84,15</location>
	</experiment>
	<neurosynth>
		<id>3</id>
		<space>MNI</space>
	</neurosynth>
</paper>
</papers>
        </textarea>
        <input type="submit" value="Submit">
        </fieldset>
    </form>
</body>
</html>

<!--
        $dbhost = "localhost"; // this will ususally be 'localhost', but can sometimes differ
        $dbname = "brainspell"; // the name of the database that you are going to use for this project
        $dbuser = "root"; // the username that you created, or were given, to access your database
        $dbpass = "beo8hkii"; // the password that you created, or were given, to access your database
        mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
        mysql_select_db($dbname) or die("MySQL Error: " . mysql_error());

     	$registerquery = mysql_query("INSERT INTO ".$dbname." (Username, Password, EmailAddress) VALUES('".$username."', '".$password."', '".$email."')");

     	$query="INSERT INTO ".$dbname." (Title, Authors, Abstract, Reference, PMID, NeuroSynthID, Experiments, Metadata) VALUES(";
     	$query='".$title."', ";
     	$query='".$authors."', ";
     	$query='".$abstract."', ";
     	$query='".$reference."', ";
     	$query='".$pmid."', ";
     	$query='".$neurosynthid."', ";
      	$query='".$experiments."', ";
     	$query='".$metadata."'";
    	$registerquery = mysql_query($query);
-->