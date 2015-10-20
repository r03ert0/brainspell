var	cmap;
var	flagDataLoaded=false;
var brain;

function handleFileSelect(evt)
{
	console.log($("#file").attr('files'));
	var file = evt.target.files[0];
	var filename=file.name;
	var reader = new FileReader();
	reader.onload = function() {
		var volume=new Float32Array(45*54*45);
		var	dv=new DataView(this.result);
		for(i=0;i<45*54*45;i++)
			volume[i]=dv.getFloat32(i*4,true);
		cmap={data:volume, dims:[45,54,45], level:0.06};
		updateMesh(cmap);
		flagDataLoaded=true;
	};
	reader.readAsArrayBuffer(file);
}

function createTestData()
{
	var oReq = new XMLHttpRequest();
//	oReq.open("GET", "sum.img", true);
	oReq.open("GET", "5.average-phir.img", true);
	oReq.responseType = "arraybuffer";
	oReq.onload = function(oEvent)
	{
		var volume=new Float32Array(45*54*45);
		var	dv=new DataView(this.response);
		for(i=0;i<45*54*45;i++)
//			volume[i]=dv.getInt16(i*2,true)-700;
			volume[i]=dv.getFloat32(i*4,true);
		cmap={data:volume, dims:[45,54,45], level:0.06};
		updateMesh(cmap);
		flagDataLoaded=true;
	};
	oReq.send();
}
var cube_edges = new Int32Array(24);
var edge_table = new Int32Array(256);
var k = 0;
for(var i=0; i<8; ++i) {
	for(var j=1; j<=4; j<<=1) {
		var p = i^j;
		if(i <= p) {
			cube_edges[k++] = i;
			cube_edges[k++] = p;
		}
	}
}
for(var i=0; i<256; ++i) {
	var em = 0;
	for(var j=0; j<24; j+=2) {
		var a = !!(i & (1<<cube_edges[j]));
		var b = !!(i & (1<<cube_edges[j+1]));
		em |= a !== b ? (1 << (j >> 1)) : 0;
	}
	edge_table[i] = em;
}
var buffer = new Int32Array(4096);
function SurfaceNets(data, dims, level)
{ 
	var vertices = [];
	var faces = [];
	var n = 0;
	var x = new Int32Array(3);
	var R = new Int32Array([1, (dims[0]+1), (dims[0]+1)*(dims[1]+1)]);
	var grid = new Float32Array(8);
	var buf_no = 1;

	if(R[2] * 2 > buffer.length)
		buffer = new Int32Array(R[2] * 2);

	for(x[2]=0; x[2]<dims[2]-1; ++x[2], n+=dims[0], buf_no ^= 1, R[2]=-R[2])
	{
		var m = 1 + (dims[0]+1) * (1 + buf_no * (dims[1]+1));
		for(x[1]=0; x[1]<dims[1]-1; ++x[1], ++n, m+=2)
		for(x[0]=0; x[0]<dims[0]-1; ++x[0], ++n, ++m)
		{
			var mask = 0, g = 0, idx = n;
			for(var k=0; k<2; ++k, idx += dims[0]*(dims[1]-2))
			for(var j=0; j<2; ++j, idx += dims[0]-2)      
			for(var i=0; i<2; ++i, ++g, ++idx)
			{
				var p = data[idx]-level;
				grid[g] = p;
				mask |= (p < 0) ? (1<<g) : 0;
			}
			if(mask === 0 || mask === 0xff)
				continue;
			var edge_mask = edge_table[mask];
			var v = [0.0,0.0,0.0];
			var e_count = 0;
			for(var i=0; i<12; ++i)
			{
				if(!(edge_mask & (1<<i)))
					continue;
				++e_count;
				var e0 = cube_edges[ i<<1 ];       //Unpack vertices
				var e1 = cube_edges[(i<<1)+1];
				var g0 = grid[e0];                 //Unpack grid values
				var g1 = grid[e1];
				var t  = g0 - g1;                  //Compute point of intersection
				if(Math.abs(t) > 1e-6)
					t = g0 / t;
				else
					continue;
				for(var j=0, k=1; j<3; ++j, k<<=1)
				{
					var a = e0 & k;
					var b = e1 & k;
					if(a !== b)
						v[j] += a ? 1.0 - t : t;
					else
						v[j] += a ? 1.0 : 0;
				}
			}
			var s = 1.0 / e_count;
			for(var i=0; i<3; ++i)
				v[i] = x[i] + s * v[i];
			buffer[m] = vertices.length;
			vertices.push(v);
			for(var i=0; i<3; ++i)
			{
				if(!(edge_mask & (1<<i)) )
					continue;
				var iu = (i+1)%3;
				var iv = (i+2)%3;
				if(x[iu] === 0 || x[iv] === 0)
					continue;
				var du = R[iu];
				var dv = R[iv];
				if(mask & 1)
				{
					faces.push([buffer[m], buffer[m-du-dv], buffer[m-du]]);
					faces.push([buffer[m], buffer[m-dv], buffer[m-du-dv]]);
				}
				else
				{
					faces.push([buffer[m], buffer[m-du-dv], buffer[m-dv]]);
					faces.push([buffer[m], buffer[m-du], buffer[m-du-dv]]);
				}
			}
		}
	}
	return { vertices: vertices, faces: faces };
}

var scene, renderer, composer;
var camera, cameraControl;
var geometry, surfacemesh, wiremesh;
function updateMesh(field)
{
	scene.remove( surfacemesh );
	scene.remove( wiremesh );

	//Create surface mesh
	geometry	= new THREE.Geometry();

	var start = (new Date()).getTime();
	var result = SurfaceNets(field.data,field.dims,field.level);
	var end = (new Date()).getTime();

	//Update statistics
	document.getElementById("resolution").value = field.dims[0] + 'x' + field.dims[1] + 'x' + field.dims[2];
	document.getElementById("vertcount").value = result.vertices.length;
	document.getElementById("facecount").value = result.faces.length;
	document.getElementById("meshtime").value = (end - start) / 1000.0;

	geometry.vertices.length = 0;
	geometry.faces.length = 0;

	for(var i=0; i<result.vertices.length; ++i)
	{
		var v = result.vertices[i];
		var	z=0.5;
		geometry.vertices.push(new THREE.Vector3(v[0]*z, v[1]*z, v[2]*z));
	}

	for(var i=0; i<result.faces.length; ++i) {
		var f = result.faces[i];
		if(f.length === 3) {
			geometry.faces.push(new THREE.Face3(f[0], f[1], f[2]));
		} else if(f.length === 4) {
			geometry.faces.push(new THREE.Face4(f[0], f[1], f[2], f[3]));
		} else {
			//Polygon needs to be subdivided
		}
	}

	var cb = new THREE.Vector3(), ab = new THREE.Vector3();
	cb.crossSelf=function(a){
		var b=this.x,c=this.y,d=this.z;
		this.x=c*a.z-d*a.y;
		this.y=d*a.x-b*a.z;
		this.z=b*a.y-c*a.x;
		return this;
	};
	
	for (var i=0; i<geometry.faces.length; ++i) {
		var f = geometry.faces[i];
		var vA = geometry.vertices[f.a];
		var vB = geometry.vertices[f.b];
		var vC = geometry.vertices[f.c];
		cb.subVectors(vC, vB);
		ab.subVectors(vA, vB);
		cb.crossSelf(ab);
		cb.normalize();
		f.normal.copy(cb)
	}

	geometry.verticesNeedUpdate = true;
	geometry.elementsNeedUpdate = true;
	geometry.normalsNeedUpdate = true;

	geometry.computeBoundingBox();
	geometry.computeBoundingSphere();

//	var material=new THREE.MeshNormalMaterial();
	var material=new THREE.MeshDepthMaterial({wireframe:false});
	surfacemesh=new THREE.Mesh( geometry, material );
	surfacemesh.doubleSided=true;
	var wirematerial = new THREE.MeshBasicMaterial({
		color : 0x909090,
		wireframe : true
	});
	wiremesh = new THREE.Mesh(geometry, wirematerial);
	wiremesh.doubleSided = true;
	scene.add( surfacemesh );
	scene.add( wiremesh );

	wiremesh.position.x = surfacemesh.position.x = -field.dims[0]/4.0;
	wiremesh.position.y = surfacemesh.position.y = -field.dims[1]/4.0;
	wiremesh.position.z = surfacemesh.position.z = -field.dims[2]/4.0;
}

init();
animate();

function handleLevelChange(e)
{
	var level=document.getElementById('level').value;
	cmap.level=parseFloat(level);
	updateMesh(cmap);
}

// init the scene
function init()
{
	// Add html elements
	var	dom.append("div#container");
	var info=dom.append("div#info");
	info.append("div.bottom#inlineDoc").html("[P]icture");
	info.append("input#level [type='text']").html("Isosurface level<br>");
	document.body.appendChild(dom);

	// Configure file upload
	document.getElementById('file').addEventListener('change', handleFileSelect, false);
	document.getElementById('level').addEventListener('change', handleLevelChange, false);
	
	// Init rendered
	if( Detector.webgl ){
		renderer = new THREE.WebGLRenderer({
			antialias				: true,	// to get smoother output
			preserveDrawingBuffer	: true	// to allow screenshot
		});
		renderer.setClearColorHex( 0xBBBBBB, 1 );
	}else{
		renderer = new THREE.CanvasRenderer();
	}

	renderer.setSize( window.innerWidth, window.innerHeight );
	document.getElementById('container').appendChild(renderer.domElement);

	// create a scene
	scene = new THREE.Scene();

	// put a camera in the scene
	camera	= new THREE.PerspectiveCamera(35, window.innerWidth / window.innerHeight, 20, 50 );
	camera.position.set(0, 0, 40);
	scene.add(camera);

	// create a camera contol
	cameraControls	= new THREE.TrackballControls( camera, document.getElementById('container') )

	// transparently support window resize
	THREEx.WindowResize.bind(renderer, camera);

	// allow 'p' to make screenshot
	THREEx.Screenshot.bindKey(renderer);
	// allow 'f' to go fullscreen where this feature is supported
	if( THREEx.FullScreen.available() ){
		THREEx.FullScreen.bindKey();
		document.getElementById('inlineDoc').innerHTML	+= " [F]ullscreen";
	}

	// Add objects
	var light	= new THREE.AmbientLight( Math.random() * 0xffffff );
	scene.add( light );
	var light	= new THREE.DirectionalLight( Math.random() * 0xffffff );
	light.position.set( Math.random(), Math.random(), Math.random() ).normalize();
	scene.add( light );

	var loader = new THREE.BinaryLoader(true);
	loader.load("lrh3.js", function(geom,material){
		var material	= new THREE.ShaderMaterial({
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
					value	: new THREE.Color('white')
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

		var modifier = new THREE.SubdivisionModifier(1);
		modifier.modify(geom);
		for(i=0;i<geom.vertices.length;i++)
		{
			geom.vertices[i].x*=0.14;
			geom.vertices[i].y*=0.14;
			geom.vertices[i].z*=0.14;
			geom.vertices[i].y+=3;
			geom.vertices[i].z-=2;
		}
		
		brain=new THREE.Mesh(geom,material);
		scene.add(brain);
	});


	document.getElementById("level").value = 0.06;

	//Update mesh
	createTestData();

	return false;
}

// animation loop
function animate()
{
	requestAnimationFrame( animate );

	if(flagDataLoaded)
		// do the render
		render();
}

// render the scene
function render() {
	// variable which is increase by Math.PI every seconds - usefull for animation
	var PIseconds	= Date.now() * Math.PI;

	// update camera controls
	cameraControls.update();

	surfacemesh.visible = document.getElementById("showfacets").checked;
	wiremesh.visible = document.getElementById("showedges").checked;

	// actually render the scene
	renderer.render( scene, camera );
}
