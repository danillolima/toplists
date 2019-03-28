{% if( category !== false ) %}
<h1>{{ category.name }}</h1>
<ul>
{% for list in lists %}
<li> {{ list.name }} </li>
{% endfor %}
</ul>
{% else %}
Categoria não existe!
{% endif %}
