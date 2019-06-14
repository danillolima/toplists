{% if lists.count() === 0 %}
    <h3>Não encontramos nenhuma lista com o termo: "{{search}}"</h3>
{% else %}
    <h3>Foram encontrada(s): {{lists.count()}} listas.</h3>
{% endif %}

<ul>
{% for list in lists %}

    <li><a href="{{list.url}}"> {{list.name}} </a></li>
{% endfor %}
</ul>