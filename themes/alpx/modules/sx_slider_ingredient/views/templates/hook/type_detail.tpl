{if $ingredientslider.slides}
    <div class="section-body-ingredients">

      <div class="title-ingredients">
        <h2 class="text-secondary text-center ingredients__title ">{l s='Liste des ingrédients' d='Shop.Theme.Catalog'}</h2>
        <div class="text-center ingredients__text">{$totcustomfields_display_description_ingredient nofilter}</div>
      </div>

      <div class="card-body-ingredients">
          <div class="row align-items-center">
              {foreach from=$ingredientslider.slides item=slide}
                  <div id="{$slide.id_slide}" class="col-md-4 col-xs-12 box-ingredient">
                      <div class="ingredients__image">
                        <img src="{$slide.image_url}" class="img-fluid rounded-circle" alt="image">
                      </div>
                      <div class="col-box">
                        <div class="card-body ingredients__details">
                          <h3 class="card-title">{$slide.title}</h3>
                          <div class="card-text">{$slide.description nofilter}</div>
                        </div>
                      </div>
                    </div>
               {/foreach}
          </div>
      </div>

    </div>
{/if}