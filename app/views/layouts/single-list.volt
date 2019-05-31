{% if(query) %}

<div class="col-md-12">
{% if(params === "editar") %}
	<form method="post" id="form-edit" enctype="multipart/form-data" >
	<h1>Editar lista</h1>
	<div id="response" role="alert"></div>
	<a href="enviar-imagem">Enviar imagem </a>
		<fieldset>
			<div class="row responsive-label">
				<div class="col-md-3">
					<label for="name">Nome da lista: </label>
				</div>	
				<div class="col-md-9">
					<input type ="text" name="title" value="{{ query.name }}" class="form-control">
				</div>
				<div class="col-md-3">
					<label for="name">Categoria: </label>
				</div>
					<div class="col-md-9">
				<select class="form-control" id="category" name="category">

				{% for category in categorys %}
						{% if (category.id_parent === NULL) %}
								<option value="{{ category.id }}"> {{ category.name }}</option>

								{% for subCategory in subcategorys %}
									{% if (subCategory.id_parent === category.id) %}
											<option value="{{ subCategory.id }}">&emsp;{{ subCategory.name }} </option>
									{% endif %}
								{% endfor %}

							{% endif %}
				{% endfor %}

			</select>
			</div>
				<div class="col-md-3">
					<label for="description">Descrição:	</label>
					</div>	
				<div class="col-md-9">
					<textarea name="description" id="description" class="form-control" rows="5">{{ query.description }}</textarea>
				</div>
				<input type="hidden" name="key" value="{{query.id}}" >
				
				<div class="col-md-12">
				<button class="x-auto inverse" id="enviar-item" type="submit">Salvar</button>
				</div>
			</div>
		</fieldset>
	</form>

{% elseif(params === "enviar-imagem") %}
<div class="row">
<div class="col-md-6">
	{% if(query.media) %}
	<div id="crop">				
	</div>

	<script>
		var el = document.getElementById('crop');
		let croppieImg = new Croppie(el, {
			viewport: { width: 335, height: 189 },
			boundary: { width: 400, height: 300 },
			showZoomer: true,
			enableOrientation: true
		});
		croppieImg.bind({
			url: '{{query.media.url}}',
		});
		//on button click
		croppieImg.result('blob').then(function(blob) {
			// do something with cropped blob
		});
	</script>	
	{% endif %}
	
	</div>

<div class="col-md-6">
	<form method="post" action="/api/lists/insertImage/{{query.id}}" enctype="multipart/form-data" >
	{% if(query.media) %}
		<h1>Alterar imagem da lista</h1>
	{% else %}
		<h1>Inserir imagem na lista</h1>
	{% endif %}

	<div id="response" role="alert"></div>
		<fieldset>
			<div class="row responsive-label">
				<div class="col-md-3">
					<label for="name">Escolha uma imagem: </label>
				</div>	
				<div class="col-md-9">
					<input type="file" id="imagemLista" name="imagemLista" accept="image/png, image/jpeg">
				</div>
	
				<input type="hidden" name="author" value="{{user['user']}}" >
				
				<div class="col-md-12">
				<button class="x-auto inverse" id="enviar-item" type="submit">Salvar</button>
				</div>
			</div>
		</fieldset>
	</form>
		</div>
	
	</div>
{% else %}
<h1> {{ query.name }} </h1>
<p>  {{ query.description }} [<a href="{{ query.url }}/editar"> editar </a>]</p>

	
		{% for item_data in listitem %}

		
<!-- ITEM -->
	{% if item_data.id_list === query.id %}
		<div class="row item" id="item-{{item_data.id}}" >
			<div class="col-sm-4">
				{% for media in item_data.item.media %}
					{% if loop.index == 1 %}
						<img class="img-fluid" width="448" height="288" alt="" src="{{ media.url }}">
					{% endif %}
				{% endfor %}
			</div>

		<div class="col-sm-7">
			<div class="alert alert-warning hide" role="alert">
			</div>
			<div style="float: right">
				<button class="upbutton btn btn-success tooltip" onclick="voteOn({{ item_data.id }}, 1, '{{ identity['user'] }}')"> Concordo  </button>
				<button class="downbutton btn btn-danger tooltip" onclick="voteOn({{ item_data.id }}, -1, '{{ identity['user'] }}')">  Não Concordo </button>
			</div> 
			<h2><a href="{{ item_data.item.url }}">{{loop.index}}. {{ item_data.item.name }}</a> <span class="numero-votos"> ({{item_data.votes}} Votos) </span></h2>
			<p>{{ item_data.item.description }}</p>
			<div style="float: right"><a href="/api/lists/removeItem/{{query.id}}?item={{ item_data.item.id }}">Excluir</a></div>
		</div>
	</div>
			<!-- end ITEM -->


{% endif %}


{% endfor %}

<div class="col-sm-12 form-envio-item">
<div class="text-center">
{%  if (!identity) %}
	<a href="/login" class="button inverse" style="margin: auto;" > Enviar uma nova opção </a>
{% else %}
	<button id="addItem" class="button inverse x-auto shadowed rounded" onclick="toggleClass('#form-p')" > Adicionar um novo item</button>
{% endif %}
</div>
<div class="itemForm hidden" id="form-p">
 <div class="row">
<div class="col-md-12">
<!--id="form-item" -->
<form action="/api/lists/insertItem/{{query.id}}"  class="form-item" method="post" >
    <div class="row responsive-label ">
        <div id="response">
		</div>
        <div class="col-md-3">
            <label>Nome:</label>
        </div>    
        
        <div class="col-md-9" id="autoname">
            <input class="form-control" id="txtName"  type="text" placeholder="Nome do item que você quer adicionar à lista." name="name">
        </div>    
		<div class="col-md-12">
        <div class="row" id="hideD">
			<div class="col-md-3 input-enviar">
				<label>Adicionar mídia: 
				</label> 
				
			</div>
					
			<div class="col-md-9 input-enviar" style="min-height: 80px;">
				<input type="radio" checked="" value="1" name="media" onchange="toggleImg()">Imagem 
				<input type="radio" value="2" name="media" onchange="toggleVideo()">Video 
			
				<input class="form-control" style="display:none;" type="text" id="url-video" placeholder="Somente URL's do youtube por enquanto." name="video">
				<div id="img-form">
				<input class="form-control" id="up-img" type="file" name="img">
					<div class="demo-wrap upload-demo" id="croppieImg">
						<div class="upload-msg"> Insira uma imagem sobre o item da lista </div>
						<div class="upload-demo-wrap">
							<div class="croppie-container" id="upload-demo"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-3 input-enviar">
				<label>Descrição: </label>
			</div>    
			<div class="col-md-9 input-enviar">
				<textarea id="txtDescription" class="form-control" name="description"></textarea>
			</div>       
		</div>
	</div></div>
<input type="submit" class="x-auto">
</form>

</div>

<div class="col-md-12">
Pré-visualização:
	<div class="row">
		<div class="col-md-4">
			<img class="" id="img-preview" src="/img/icons/image.svg"  width="100%" />
			<div class="card fluid" style="display:none;" id="video-preview">
				<iframe id="video-youtube"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		<div class="col-md-8">
			<h2 id="title-preview">Adicione um título</h2>
			<p id="description-preview">Adicione uma descrição</p>
		</div>
	</div>
</div>


</div>
</div>
</div>
</div>

{% endif %}

{% else %}
 Lista não existe!
{% endif %}
