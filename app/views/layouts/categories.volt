
<div class="col-md-12">
<ul class="category-list">
{% for category in categorys %}
	<li><a href="{{ category.url }}"> {{ category.name }} </a></li>
{% endfor %}
</ul>
</div>