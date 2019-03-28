<div class="container">
  <div class="row">
      <div class="col-sm-12">
              <a class="button" href="/profile/{{ identity['user'] }}">Ver meu perfil</a>
              <a class="button" href="/profile/{{ identity['user'] }}/listas">Listas enviadas</a>
              <a class="button" href="/profile/{{ identity['user'] }}/editar">Editar perfil</a>
       </div>
      <div class="justify-content-center col-sm-3 ">
          <img class="d-block mx-auto" src="/uploads/profile/user.svg" width="160" height="160">
      </div>


{% if ( params === 'listas' ) %}

    <div class="col-sm-9">
        <h2> Listas feitas por {{ identity['user']}}</h2>
        <ul>
          {% for list in userLists %}
          <li>{{list.name}}</li>
          {% endfor %}
        </ul>
    </div>

  
{% elseif ( params === 'editar' ) %}

    <div class="col-sm-9">
        <h2> {{ identity['user'] }}</h2>
        <form class="form-enviar-lista">
<div class="row responsive-label">

    <div class="col-md-3">
        <label>Nome: </label>
    </div>
    
    <div class="col-md-9">
        <input class="form-control" type="text">
    </div>

    <div class="col-md-3">
        <label>Data de nascimento: </label>
    </div>
    
    <div class="col-md-9">
        <input type="date" max="2007-01-01">
    </div>


    <div class="col-md-3">
        <label>Sobre: </label>
    </div>
    
    <div class="col-md-9">
        <textarea class="form-control" rows="5"></textarea>
    </div>
    <div class="col-md-12">
    <input type="submit" class="x-auto inverse"></div>
</div>
</form>
    </div>
  
{% else %}
   
    <div class="col-sm-5">
        <h2> {{ identity['user'] }}</h2>
        <strong> Sobre  </strong>
        <div style="width: 100%; backgound-color: #232323;">(Atualmente seu sobre está vazio)</div>
    </div>
   <div class="col-sm-4">
   <!--
        <strong> Atividades  </strong>
        <ul class="list-group">
          <li class="list-group-item list-group-item-dark">Clara seguiu você.</li>
          <li class="list-group-item list-group-item-light">José comentou no seu perfil.</li>
          <li class="list-group-item list-group-item-dark">José seguiu você.</li>
          <li class="list-group-item list-group-item-light">Votou na lista: <a href="">Melhores filmes do Leonardo DiCaprio. </a></li>
          <li class="list-group-item list-group-item-dark">Você criou sua conta. 😃</li>
        </ul>-->
    </div>
    <div class="col-sm-12">
    <form class="mx-auto" style="width:50%;">
    <div class="form-group" >
        <label for="comentario">    <strong>Comentários:	</strong></label>
        <textarea name="comentario" id="comentario" class="form-control" rows="3"></textarea>
        <button class="btn btn-link" id="enviar-item" type="submit">Enviar</button>
    </div>
    </div>
{% endif %}
  </div>
</div>
