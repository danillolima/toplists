
<form action="/contact" method="post">

<fieldset>
<legend>Contato</legend>
<div class="row responsive-label">
<div class="col-md-12 msgResp">
        {{get_content()}}
        </div>
        <div class="col-md-3 col-sm-12">
           {{ form.label('email') }}	
        </div>
        <div class="col-md">{{ form.render('email') }}
        
        </div>  
        <div class="col-md-12 msgResp">
                {{form.messages('email')}}
                </div>
      </div>
<div class="row responsive-label">
        <div class="col-md-3 col-sm-12">
           {{ form.label('subject') }}	
        </div>
        <div class="col-md">{{ form.render('subject') }}
        </div>  

         <div class="col-md-12 msgResp">
                {{form.messages('subject')}}
                </div>
      </div>
<div class="row responsive-label">
        <div class="col-md-3 col-sm-12">
          

        {{ form.label('txt') }}
        </div>
        <div class="col-md">
        {{ form.render('txt') }}
        </div>  
         <div class="col-md-12 msgResp">
                {{form.messages('txt')}}
                </div>
      </div>

        {{ form.render('Enviar')}}
</fieldset>
</form>