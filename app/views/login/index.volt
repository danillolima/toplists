<div style="width: 70%;  margin-left:auto; margin-right:auto">

{{ flash.output() }}
<form method="post" onsubmit="AJAXSubmit(this)" action="/login/verify">

  <fieldset>
      <legend>Login</legend>
    <div class="row responsive-label">
        <div class="col-md-3 col-sm-12">
            <label for="user">Usuário	</label>
        </div>
        <div class="col-md">
            <input name="user" id="user" class="form-control" type="text"/>
        </div>
      </div>
      <div class="row responsive-label">
       <div class="col-md-3 col-sm-12">
          <label for="pass">Senha 	</label>
      </div>
      <div class="col-md">
      <input name="pass" class="form-control" id="pass" type="password" />
    </div>
   </div>
   <div class="row responsive-label">
      <div class="col-md ">
          <button type="submit" class="btn btn-primary">Deixe-me entrar!</button>

  </div>
  <div class="row responsive-label">
  <div id="response"></div>
  </div>
  <div class="col-md ">
      <p><a href="/entrar/esqueci-minha-senha">Esqueceu sua senha?</a></p>

</div> </div>

<div class="row responsive-label">
<div class="col-md ">
    <p>Não se cadastrou ainda? <a href="/signup">Cadastre-se </a></p>
  </div>
</div>
</fieldset>
</form>
</div>
