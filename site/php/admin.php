<html>

<head>
<script src="../js/jquery-1.10.2.min.js"></script>
</head>

<body>

<h1>Admin</h1>

<b>Upload file with DOI codes (in json format) 7</b><br/>
<input type="file" id="file" name="files[]"/><br/>
<button onclick="updateDOI();">Start</button>

<script>

function updateDOI()
{
	var	file=document.getElementById("file").files[0];
	var reader=new FileReader();
	reader.onload=function(){
		var	str=this.result;
		var	data=$.parseJSON(str);
		var	i;
		
		for(i=0;i<data.length;i++)
		{
			console.log(i,data[i].pmid,data[i].doi);
			
			$.ajax({
				type: "GET",
				url: "brainspell.php",
				data: {
					action:"admin_updateDOI",
					command:"user-action",
					pmid:data[i].pmid,
					doi:data[i].doi
				},
				async: true,
				error: function(jqXHR, textStatus, errorThrown){ console.log("DOI ERROR: "+textStatus);},
				success:function(data, textStatus, jqXHR){console.log("DOI SUCCESS: "+textStatus);}
			});
		}
	};
	reader.readAsText(file);
}

</script>
</body>
</html>