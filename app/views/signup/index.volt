
<div style="width: 70%;  margin-left:auto; margin-right:auto">

                <form class="needs-validation" method="post" action="signup">
                        <fieldset>
                        <div class="msgs">
                        <ul>
                          {% if messages is defined %}
                            {% for index in messages|keys %}
                                {% for subind in messages[index]|keys %}
                                 <li> <mark> {{ messages[index][subind] }}</mark></li>
                            {% endfor %}{% endfor %}
                          {% endif %}
                          </ul>
                        </div>
                                  <legend>Cadastre-se</legend>
                            <div class="row responsive-label">
                                <div class="col-md-3 col-sm-12">
                                <label for="username">Usuário</label>
                                </div>
                                <div class="col-md">
                                <input type="text" name="user" class="form-control">
                                </div>
                            </div>
                    
                            <div class="row responsive-label">
                            <div class="col-sm-12 col-md-3">
                            <label for="email">E-mail</label>
                            </div>
                            <div class="col-md">
                            <input type="email" name="email" class="form-control">
                            </div> </div>
                            <div class="row responsive-label">
                            <div class="col-md-3 col-sm-12">
                            <label for="pass">Senha</label>    
                              </div> 
                            <div class="col-md">
                            <input type="password" name="pass" class="form-control">
                            </div>  </div>
                            <button type="submit" class="x-auto">Cadastrar</button>
                            Já tem uma conta? Entre!
                        </fieldset>
                    </form>
</div>
