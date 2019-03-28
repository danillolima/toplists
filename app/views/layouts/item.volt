{% if(item) %}
<div class="col-md-12">

    <h1> {{ item.name }} </h1>
    <p>{{ item.description }}</p>
    <h2>Galeria</h2>

    <div class="row">
    {% for media in item.media %}
    <div class="col-md-4">
        <img width="300" src="..{{media.url}}"/>
    </div>
    {% endfor %}
    </div>
</div>
{% else %}
Não encontrado.
{% endif %}
