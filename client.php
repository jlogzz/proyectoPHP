<html>
	<head>
		<style>
			.imgClick:hover{
				cursos: pointer;
			}
		</style>
	</head>
	<body>
		<div style="width:30%; float:left;">
			<div enctype='multipart/form-data'>
				<fieldset>
	    			<legend>PUSH:</legend>
					Name:<input type="text" id="name" name="name" /> <br/>
					File:<input type="file" id="img" name="imagen" /><br/>
					<button id="submit">Upload</button>
				</fieldset>
			</div>
			<div enctype='multipart/form-data'>
				<fieldset>
	    			<legend>GET:</legend>
					Name:<input type="text" id="searchName" name="name" /> <br/>
					<button id="search">Search</button>
					<h1 id="response" ></h1>
				</fieldset>
			</div>
		</div>
		<div style="width:70%; float:left;">
			<div id="imgholder"></div>
		</div>
		
		<script src="jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#submit").click(function(){
					var data = new FormData();
					var img = $("#img")[0].files[0];
					var name = $("#name").val();
					
					data.append("imagen", img);
					data.append("name", name);
					
					//*/
					$.ajax({
					    url: '/index.php?action=put',
					    data: data,
					    cache: false,
					    contentType: false,
					    processData: false,
						dataType: "json",
					    type: 'POST',
					    success: function(data){
					        if(data.status){
								alert("Image added!");
								$("#img").val("");
								$("#name").val("");
							}else{
								alert(data.error);
							}
					    }
					});
					//*/
				});
				
				$("#searchName").keyup(function(){
					
					var data = new FormData();
					var name = $("#searchName").val();
					
					if(name!=""){
						data.append("name",name);
						$.ajax({
						    url: '/index.php?action=search',
						    data: data,
						    cache: false,
						    contentType: false,
						    processData: false,
							dataType: "json",
						    type: 'POST',
						    success: function(data){
						        if(data.status){
									var imgs = data.data;
									var img;
									$("#response").fadeOut();
									$("#response").html("");
									for(img in imgs){
										$("#response").append("<a href='#'><div class='imgClick' data-id='"+imgs[img].id+"' >"+imgs[img].name+"</div></a>");
									}
									$("#response").fadeIn();
								}else{
									$("#response").html("Imagen no encontrada");
								}
						    }
						});
					}else{
						$("#response").html("Escriba su busqueda");
					}
				});
				
				$("#response").on("click", ".imgClick", function(){
					
					var vid = $(this).data('id');
					
					$.ajax({
						    url: '/index.php?action=get&type=tag',
						    data: {id: vid},
						    cache: false,
							dataType: "json",
						    type: 'POST',
						    success: function(data){
						        if(data.status){
									$("#imgholder").html(data.data);
								}else{
									$("#imgholder").html("Imagen no encontrada");
								}
						    }
						});
				});
			});
		</script>
	</body>
</html>