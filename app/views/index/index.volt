
{% for post in posts %}


{% if post.media != NULL %}
	<div class="col-md-6">
		<div class="card fluid">
			<figure>
				 <img class="section media" width="90%" alt="" style="margin: 0 auto; display:block; height: auto; width: auto; max-width: 300px; max-height: 300px;" alt="" src="{{ post.media.url }}">
<!--<div class="section media" alt="" style="background-image: url({{ post.media.url }}); width:100%;"></div>-->
				<figcaption>Créditos: </figcaption>
			</figure>
		<span class="label-cat rounded-top">
			<a href="{{ post.category.url }}">{{ post.category.name }}</a>
		</span>
		<h2 class="title-t1"> <a href="{{ post.url }}">{{ post.name }}</a></h2>
		<p>{{post.description}}</p>
	
	</div>
	</div>
{% endif %}

{% endfor %}

{% for post in posts %}

{% if post.media == NULL %}
<div class="col-md-6">
		<div class="card fluid">
			<h2 class="title"> <a href="{{ post.url }}">{{ post.name }}</a></h2>
			<span class="label-cat rounded-top">
				<a href="{{ post.category.url }}">{{ post.category.name }}</a>
			</span>
	
		<p>{{post.description}}</p>
	</div>
</div>
{% endif %}

{% endfor %}


