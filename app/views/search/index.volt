<ul>
{% for list in lists %}
    <li><a href="{{list.url}}"> {{list.name}} </a></li>
{% endfor %}
</ul>