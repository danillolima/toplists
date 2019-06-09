<div style="width: 70%;  margin-left:auto; margin-right:auto">
                <form class="form-1" method="post" action="signup">
                        <fieldset>
                        <div class="msgs">            
                          <ul>
                          {% if messages is defined %}
                            {% for index in messages|keys %}
                              {% for subind in messages[index]|keys %}
                               {% if index == 'form' %}
                                 <li> <mark> {{ messages[index][subind] }}</mark></li>
                                {% endif %}
                              {% endfor %}
                            {% endfor %}
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
                                <div class="col-md-12 msgResp">
                                  {% if messages['user']['PresenceOf'] is defined %}
                                    <mark class="erro"> {{ messages['user']['PresenceOf'] }} </mark>
                                  {% else %}
                                  {% if messages['user']['TooShort'] is defined %}
                                    <mark class="erro"> {{ messages['user']['TooShort'] }} </mark>
                                  {% endif %}{% endif %}
                                </div>
                            </div>
                    
                            <div class="row responsive-label">
                            <div class="col-sm-12 col-md-3">
                            <label for="email">E-mail</label>
                            </div>
                            <div class="col-md">
                            <input type="email" name="email" class="form-control">
                            </div>

                          <div class="col-md-12 msgResp">
                          {% if messages['email']['PresenceOf'] is defined %}
                              <mark class="erro"> {{ messages['email']['PresenceOf'] }} </mark>
                          {% else %}
                            {% if messages['email']['Email'] is defined %}
                              <mark class="erro"> {{ messages['email']['Email'] }} </mark>
                            {% endif %}
                          {% endif %}
                          </div>

                            </div>
                            <div class="row responsive-label">
                            <div class="col-md-3 col-sm-12">
                            <label for="pass">Senha</label>    
                              </div> 
                            <div class="col-md">
                            <input type="password" name="pass" class="form-control">
                            </div>          
                        <div class="col-md-12 msgResp">
                             {% if messages['pass']['PresenceOf'] is defined %}
                              <mark class="erro"> {{ messages['email']['PresenceOf'] }} </mark>
                          {% else %}
                            {% if messages['pass']['TooShort'] is defined %}
                              <mark class="erro"> {{ messages['pass']['TooShort'] }} </mark>
                            {% endif %}
                          {% endif %}
                        </div> 
                            </div>
                            <button type="submit" class="x-auto">Cadastrar</button>
                            Já tem uma conta? <a href="/login" title="Fazer login na página"> Entre!</a>
                        </fieldset>
                    </form>
</div>
