<div id="ingredients">
    <ul class="owl-carousel owl-theme">
        {foreach from=$ingredientslider.slides item=slide}
            <li class="item">
               <a href="#{$slide.id_slide}">
                    <img src="{$slide.image_url}" alt="{$slide.title}" />
                    <h2 style="background:{$slide.legend}">{$slide.title}</h2>
               </a>
            </li>
        {/foreach}
    </ul>
</div>

<script>
    $(document).ready(function () {

    });
</script>