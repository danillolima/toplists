{% if( category !== false ) %}
<h1>{{ category.name }}</h1>
<ul>
{% for list in lists %}
<li> <a title="{{list.name}}" href="{{ list.url }}"> {{ list.name }} </a></li>
{% endfor %}
</ul>
{% else %}
Categoria não existe!
{% endif %}
