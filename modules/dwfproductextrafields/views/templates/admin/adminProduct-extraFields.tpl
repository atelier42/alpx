{**
 *   2009-2021 ohmyweb!
 *
 *   @author	ohmyweb <contact@ohmyweb.fr>
 *   @copyright 2009-2021 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
*}

{assign var='languageTabActive' value='active show'}

{function dwfproductextrafields_price}
  <div class="input-group money-type">
    <input type="text"
           name="dwfproductextrafields[{$field.name}]"
           id="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}"
           value="{toolsConvertPrice price=$field.value|string_format:'%.2f'}" onkeyup="this.value = this.value.replace(/,/g, '.');"
           class="form-control"
      {if isset($field.size)} size="{$field.size}"{/if}
      {if isset($field.maxchar) && $field.maxchar} data-maxchar="{$field.maxchar|intval}"{/if}
      {if isset($field.maxlength) && $field.maxlength} maxlength="{$field.maxlength|intval}"{/if}
      {if isset($field.readonly) && $field.readonly} readonly="readonly"{/if}
      {if isset($field.disabled) && $field.disabled} disabled="disabled"{/if}
      {if isset($field.autocomplete) && !$field.autocomplete} autocomplete="off"{/if}
      {if isset($field.required) && $field.required } required="required" {/if}
      {if isset($field.placeholder) && $field.placeholder } placeholder="{$field.placeholder}"{/if}
    />
    <span class="input-group-text"> &euro;</span>
  </div>
{/function}

{function dwfproductextrafields_number}
  <input type="text"
         id="{$field.id}"
    {if isset($field.field_group)}
      name="{$field.field_group}[{$field.name}]"
    {else}
      name="dwfproductextrafields[{$field.name}]"
    {/if}
         class="{if isset($field.class)}{$field.class}{/if} form-control"
         value="{if isset($field.string_format) && $field.string_format}{$field.value|string_format:$field.string_format}{else}{$field.value}{/if}"
         onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
    {if isset($field.size)} size="{$field.size}"{/if}
    {if isset($field.maxchar) && $field.maxchar} counter="{$field.maxchar|intval}"{/if}
    {if isset($field.readonly) && $field.readonly} readonly="readonly"{/if}
    {if isset($field.disabled) && $field.disabled} disabled="disabled"{/if}
    {if isset($field.autocomplete) && !$field.autocomplete} autocomplete="off"{/if}
    {if isset($field.required) && $field.required} required="required" {/if}
    {if isset($field.placeholder) && $field.placeholder} placeholder="{$field.placeholder}"{/if} />
{/function}

{function dwfproductextrafields_checkbox}
  <div class="checkbox">
      <input
        id="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}"
        {if isset($field.field_group)}
          name="{$field.field_group}[{$field.name}]"
        {else}
          name="dwfproductextrafields[{$field.name}]"
        {/if}
        value="1"
        {if $field.value == 1} checked="checked"{/if}
        type="checkbox" />
      <label for="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}">{$label}</label>
  </div>
{/function}

{function dwfproductextrafields_selector}
  <select
    id="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}"
    name="dwfproductextrafields[{$field.name}]{if $field.multiple}[]{/if}"
    class="form-control"
    {if $field.multiple} multiple{/if}>
    {foreach $field.choices as $choice}
      <option value="{$choice.key}"{if $choice.key|in_array:$field.value} selected="selected"{/if}>{$choice.name}</option>
    {/foreach}
  </select>
{/function}

{function dwfproductextrafields_date}
  <div class="input-group datepicker">
    <input
      id="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}"
      type="text"
      class="form-control"
      name="dwfproductextrafields[{$field.name}]"
      value="{$field.value}" />
    <span class="input-group-text"><span class="material-icons">date_range</span></span>
  </div>
{/function}

{function dwfproductextrafields_datetime}
  <div class="input-group">
    <input
      id="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}"
      type="text"
      class="form-control"
      name="dwfproductextrafields[{$field.name}]"
      value="{$field.value}" />
    <span class="input-group-text"><span class="material-icons">date_range</span></span>
  </div>


  <script type="text/javascript">
    <!--
    $(document).ready(function() {ldelim}
      if($('#product-extraFields #{if isset($field.id)}{$field.id}{else}{$field.name}{/if}').length > 0) {ldelim}
        $('#product-extraFields #{if isset($field.id)}{$field.id}{else}{$field.name}{/if}').datetimepicker({ldelim}
          locale: full_language_code,
          format: 'YYYY-MM-DD HH:mm',
          sideBySide: true
        {rdelim});
      {rdelim}
    {rdelim});
    //-->
  </script>
{/function}

{function dwfproductextrafields_text}
  {if !empty($languages) AND $languages}
    <div class="translations tabbable" id="{if isset($field.id)}{$field.id}{else}{$field.name}{/if}">
      <div class="translationsFields tab-content">
        {foreach $languages as $language}
          {if isset($field.value[$language.id_lang])}
            {assign var='value_text' value=$field.value[$language.id_lang]}
          {else}
            {assign var='value_text' value=''}
          {/if}

          <div class="translationsFields-{if isset($field.id)}{$field.id}_{$language.id_lang|intval}{else}{$field.name}_{$language.id_lang|intval}{/if} tab-pane{if $language.id_lang == $defaultFormLanguage} {$languageTabActive}{/if} translation-field translation-label-{$language.iso_code}">

            {if isset($field.prefix) || isset($field.suffix)}
            <div class="input-group">
              {/if}

              {if isset($field.prefix)}
                <span class="input-group-text">{$field.prefix}</span>
              {/if}

              <input type="text"
                     id="{$field.id}_{$language.id_lang|intval}"
                {if isset($field.field_group)}
                  name="{$field.field_group}[{$field.name}_{$language.id_lang|intval}]"
                {else}
                  name="dwfproductextrafields[{$field.name}_{$language.id_lang|intval}]"
                {/if}
                     class="{if isset($field.class)}{$field.class}{/if} form-control"
                     value="{if isset($field.string_format) && $field.string_format}{$value_text|string_format:$field.string_format}{else}{$value_text}{/if}"
                     onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                {if isset($field.size)} size="{$field.size}"{/if}
                {if isset($field.maxchar) && $field.maxchar} counter="{$field.maxchar|intval}"{/if}
                {if isset($field.readonly) && $field.readonly} readonly="readonly"{/if}
                {if isset($field.disabled) && $field.disabled} disabled="disabled"{/if}
                {if isset($field.autocomplete) && !$field.autocomplete} autocomplete="off"{/if}
                {if isset($field.required) && $field.required} required="required" {/if}
                {if isset($field.placeholder) && $field.placeholder} placeholder="{$field.placeholder}"{/if} />

              {if isset($field.maxchar) && $field.maxchar}
                <span class="maxLength">
                                <span class="currentLength">0</span> {l s='of' mod='dwfproductextrafields'} <span class="currentTotalMax">{$field.maxchar|intval}</span> {l s='characters allowed' mod='dwfproductextrafields'}
                            </span>
              {/if}

              {if isset($field.suffix)}
                <span class="input-group-text">{$field.suffix}</span>
              {/if}

              {if isset($field.prefix) || isset($field.suffix)}
            </div>
            {/if}
          </div>
        {/foreach}
      </div>
    </div>
  {/if}
{/function}

{function dwfproductextrafields_textarea}
  {if !empty($languages) AND $languages|count}
    <div class="translations tabbable" id="{$field.id}">
      <div class="translationsFields tab-content">
        {foreach $languages as $language}
          {if isset($field.value[$language.id_lang])}
            {assign var='value_text' value=$field.value[$language.id_lang]}
          {else}
            {assign var='value_text' value=''}
          {/if}

          <div class="translationsFields-{$field.id}_{$language.id_lang|intval} tab-pane{if $language.id_lang == $defaultFormLanguage} {$languageTabActive}{/if} translation-field translation-label-{$language.iso_code}">

            {if isset($field.prefix) || isset($field.suffix)}
            <div class="input-group">
              {/if}

              {if isset($field.prefix)}
                <span class="input-group-text">{$field.prefix}</span>
              {/if}

              <textarea
                {if isset($field.field_group)}
                  name="{$field.field_group}[{$field.name}_{$language.id_lang|intval}]"
                {else}
                  name="dwfproductextrafields[{$field.name}_{$language.id_lang|intval}]"
                {/if}
                id="{$field.id}_{$language.id_lang|intval}"
                class="{if isset($autoload_rte) && $autoload_rte}autoload_rte_{$field.id}{else}form-control textarea-autosize{/if}{if isset($field.class)} {$field.class}{/if}"
                {if isset($field.readonly) && $field.readonly} readonly="readonly"{/if}
                {if isset($field.maxchar) && $field.maxchar} counter="{$field.maxchar|intval}"{/if}>{$value_text}</textarea>

              {if isset($field.maxchar) && $field.maxchar}
                <span class="maxLength">
                                <span class="currentLength">0</span> {l s='of' mod='dwfproductextrafields'} <span class="currentTotalMax">{$field.maxchar|intval}</span> {l s='characters allowed' mod='dwfproductextrafields'}
                            </span>
              {/if}

              {if isset($field.suffix)}
                <span class="input-group-text">{$field.suffix}</span>
              {/if}

              {if isset($field.prefix) || isset($field.suffix)}
            </div>
            {/if}
          </div>
        {/foreach}
      </div>
    </div>

    {if isset($autoload_rte) && $autoload_rte && !isset($new_item)}
      <script type="text/javascript">
        $(document).ready(function() {ldelim}
          tinySetup({ldelim}
            editor_selector :"autoload_rte_{$field.id}",
            setup : function(ed) {ldelim}
              ed.on('blur', function(ed) {ldelim}
                tinyMCE.triggerSave();
                {rdelim});
              {rdelim}
            {rdelim});
          {rdelim});
      </script>
    {/if}
  {else}
    {if isset($field.value)}
        {assign var='value_text' value=$field.value}
    {else}
        {assign var='value_text' value=''}
    {/if}

    {if isset($field.prefix) || isset($field.suffix)}
      <div class="input-group">
    {/if}

    {if isset($field.prefix)}
      <span class="input-group-text">{$field.prefix}</span>
    {/if}

    <textarea
      {if isset($field.field_group)}
        name="{$field.field_group}[{$field.name}]"
      {else}
        name="dwfproductextrafields[{$field.name}]"
      {/if}
      id="{$field.id}"
      class="{if isset($autoload_rte) && $autoload_rte}autoload_rte_{$field.id}{else}form-control textarea-autosize{/if}{if isset($field.class)} {$field.class}{/if}"
      {if isset($field.readonly) && $field.readonly} readonly="readonly"{/if}
      {if isset($field.maxchar) && $field.maxchar} counter="{$field.maxchar|intval}"{/if}>{$value_text}</textarea>

    {if isset($field.maxchar) && $field.maxchar}
      <span class="maxLength">
        <span class="currentLength">0</span> {l s='of' mod='dwfproductextrafields'} <span class="currentTotalMax">{$field.maxchar|intval}</span> {l s='characters allowed' mod='dwfproductextrafields'}
      </span>
    {/if}

    {if isset($field.suffix)}
      <span class="input-group-text">{$field.suffix}</span>
    {/if}

    {if isset($field.prefix) || isset($field.suffix)}
      </div>
    {/if}
  {/if}
{/function}

{function dwfproductextrafields_color}
  <div class="form-group">
    <div class="col-lg-2">
      <div class="row">
        <div class="input-group">
          <input type="color"
            data-hex="true"
            class="color mColorPickerInput"
            name="dwfproductextrafields[{$field.name}]"
            value="{if isset($field.value)}{$field.value}{/if}"
          />
        </div>
      </div>
    </div>
  </div>
{/function}

{function dwfproductextrafields_image}
  <ul id="{$field.id}-new_item" style="display:none;">
    <li class="thumb-%FIELD_BASENAME%">
      %FIELD_FILE%
      <p>{l s='File size' mod='dwfproductextrafields'} %FIELD_SIZE%</p>
      <p>
        <a data-rel="%FIELD_VALUE%" class="{$field.id}-delete btn btn-primary" href="#">
          <i class="icon-trash"></i> {l s='Delete' mod='dwfproductextrafields'}
        </a>
      </p>
    </li>
  </ul>

  <div id="{$field.id}-container" style="position:relative;">
    <div class="row">
      <div class="overlay-spinner" style="display:none;background: rgba(255,255,255,.8);position: absolute;top: 0;right: 0;left: 0;bottom: 0;z-index: 5;"><div class="spinner btn-primary-reverse onclick" style="position:absolute; top:calc(50% - 20px); left:calc(50% - 20px);"></div></div>
      <div class="col-lg-6 mb-2">
        <div class="form-inline">
          <input type="hidden"
            {if isset($field.field_group)}
              name="{$field.field_group}[{$field.name}_files]"
            {else}
              name="dwfproductextrafields[{$field.name}_files]"
            {/if}
                 id="{$field.id}_files"
                 value="{if isset($field.value)}{$field.value}{/if}"
          />
          <input type="file"
            {if isset($field.field_group)}
              name="{$field.field_group}"
            {else}
              name="dwfproductextrafields[{$field.name}]"
            {/if}
                 id="{$field.id}"
                 class="hide"
          />
          <div class="dummyfile input-group">
                        <span class="input-group-text">
                            <span class="material-icons">image</span>
                        </span>
            <input id="{$field.id}-name" type="text" name="filename" class="form-control" readonly />
          </div>
          <button id="{$field.id}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-normal">
            <i class="material-icons m-r-1">library_add</i>{l s='Add file' mod='dwfproductextrafields'}
          </button>
        </div>
      </div>
      <div class="col-lg-3">
        <ul class="clearfix" id="{$field.id}-images-thumbnails" style="list-style:none; padding:0;{if !isset($field.image) || !$field.image}  display:none;{/if}">
          {if isset($field.image) && $field.image}
            <li class="thumb-{$field.basename}">
              {$field.image}
              <p class="mb-0">
                {if isset($field.image_zoom)}
                <a href="{$field.image_zoom}" class="btn btn-link btn-sm open-image" target="_blank">
                  <i class="material-icons">zoom_in</i> {l s='Zoom' d='Admin.Catalog.Feature'}
                </a>
                {/if}
                {if isset($field.size)}<span class="text-muted small-text">{l s='File size' mod='dwfproductextrafields'} {$field.size|escape:'htmlall':'UTF-8'}</span>{/if}
              </p>

              {if isset($field.delete_url)}
                <a data-rel="{$field.value}" class="{$field.id}-delete btn btn-primary" href="{$field.delete_url}">
                  <i class="icon-trash"></i> {l s='Delete' mod='dwfproductextrafields'}
                </a>
              {/if}
            </li>
          {/if}
        </ul>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    var {$field.id}_max_files = 1;

    $(document).ready(function() {ldelim}
      $('#{$field.id}').change(function(e) {ldelim}
        var file_data = $(this).prop("files")[0];   // Getting the properties of file from file field
        var form_data = new FormData();                  // Creating object of FormData class
        form_data.append("{$field.id}", file_data)              // Appending parameter named file with properties of file_field to form_data
        form_data.append("action", 'callAddImage')                 // Adding extra parameters to form_data
        form_data.append("field", '{$field.id}')                 // Adding extra parameters to form_data
        form_data.append("fieldname", '{$field.name}')                 // Adding extra parameters to form_data
        $.ajax({ldelim}
          url: baseAdminDir+'index.php?id_product={$product_id}&controller=AdminModules&configure=dwfproductextrafields&ajax=1&token={$token_modules}',
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,                         // Setting the data attribute of ajax with file_data
          type: 'post',
          beforeSend: function() {ldelim}
            $('#{$field.id}-container .overlay-spinner').show();
            $('.js-spinner').show();
            $('#submit').attr('disabled', 'disabled');
            $('.btn-submit').attr('disabled', 'disabled');
            {rdelim},
          complete: function() {ldelim}
            $('#{$field.id}-container .overlay-spinner').hide();
            $('.js-spinner').hide();
            $('#submit').removeAttr('disabled');
            $('.btn-submit').removeAttr('disabled');
            {rdelim},
          success: function(data) {ldelim}
            $('#{$field.id}-name').val('');
            var files = [];
            $.each(data.{$field.id}, function(k, el) {ldelim}
              files.push(el.file);
              $newItem = $('#{$field.id}-new_item').html();
              $newItem = $newItem.replace(/%FIELD_FILE%/g, el.thumbnail);
              $newItem = $newItem.replace(/%FIELD_VALUE%/g, el.file);
              $newItem = $newItem.replace(/%FIELD_BASENAME%/g, el.basename);
              $newItem = $newItem.replace(/%FIELD_SIZE%/g, el.size_kb);
              $('#{$field.id}-images-thumbnails').append($newItem);
              {rdelim});

            $('#{$field.id}_files').val(files.join());
            if($('#{$field.id}_files').val() == '') {ldelim}
              $('#{$field.id}-images-thumbnails').hide();
              {rdelim} else {ldelim}
              $('#{$field.id}-images-thumbnails').show();
              {rdelim}
            {rdelim}
          {rdelim})
        {rdelim});

      $(document).on('click', '.{$field.id}-delete', function(e) {ldelim}
        e.preventDefault();
        $.ajax({ldelim}
          url: baseAdminDir+'index.php?id_product={$product_id}&controller=AdminModules&configure=dwfproductextrafields&ajax=1&token={$token_modules}',
          dataType: 'json',
          cache: false,
          data: {
            'action': 'callDeleteImage',
            'field': '{if isset($field.field_group)}{$field.id}{else}{$field.name}{/if}',
            {if isset($field.reference)}
            'reference': '{$field.reference}',
            {/if}
            'file': $(this).data('rel')
            {rdelim},
          type: 'post',
          beforeSend: function() {ldelim}
            $('#{$field.id}-container .overlay-spinner').show();
            $('.js-spinner').show();
            $('#submit').attr('disabled', 'disabled');
            $('.btn-submit').attr('disabled', 'disabled');
            {rdelim},
          complete: function() {ldelim}
            $('#{$field.id}-container .overlay-spinner').hide();
            $('.js-spinner').hide();
            $('#submit').removeAttr('disabled');
            $('.btn-submit').removeAttr('disabled');
            {rdelim},
          success: function(data) {ldelim}
            $("#{$field.id}-images-thumbnails .thumb-"+data.basename).remove();

            var files = [];
            $.each(data.{$field.id}, function(k, el) {ldelim}
              files.push(el.file);
              {rdelim});
            $("#{$field.id}_files").val(files.join());
            if($("#{$field.id}_files").val() == '') {ldelim}
              $("#{$field.id}-images-thumbnails").hide();
              {rdelim} else {ldelim}
              $("#{$field.id}-images-thumbnails").show();
              {rdelim}
            {rdelim}
          {rdelim});
        {rdelim});

      $('#{$field.id}-selectbutton').click(function(e) {ldelim}
        $enoughtImages = false;
        if (typeof {$field.id}_max_files !== 'undefined') {ldelim}
          if($('#{$field.id}_files').val() != '') {ldelim}
            var $files = $('#{$field.id}_files').val().split(',');
            if($files.length >= {$field.id}_max_files) {ldelim}
              $enoughtImages = true,
                alert('{l s='You can upload a maximum of %s files'|sprintf:1 mod='dwfproductextrafields' js=1}');
              {rdelim}
            {rdelim}

          {rdelim}
        if(!$enoughtImages) {ldelim}
          $('#{$field.id}').trigger('click');
          {rdelim}
        {rdelim});

      $('#{$field.id}-name').click(function(e) {ldelim}
        $enoughtImages = false;
        if (typeof {$field.id}_max_files !== 'undefined') {ldelim}
          if($('#{$field.id}_files').val() != '') {ldelim}
            var $files = $('#{$field.id}_files').val().split(',');
            if($files.length >= {$field.id}_max_files) {ldelim}
              $enoughtImages = true,
                alert('{l s='You can upload a maximum of %s files'|sprintf:1 mod='dwfproductextrafields' js=1}');
              {rdelim}
            {rdelim}

          {rdelim}
        if(!$enoughtImages) {ldelim}
          $('#{$field.id}').trigger('click');
          {rdelim}
        {rdelim});

      $('#{$field.id}-name').on('dragenter', function(e) {ldelim}
        e.stopPropagation();
        e.preventDefault();
        {rdelim});

      $('#{$field.id}-name').on('dragover', function(e) {ldelim}
        e.stopPropagation();
        e.preventDefault();
        {rdelim});

      $('#{$field.id}-name').on('drop', function(e) {ldelim}
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;
        $('#{$field.id}')[0].files = files;
        $(this).val(files[0].id);
        {rdelim});

      $('#{$field.id}').change(function(e) {ldelim}
        if ($(this)[0].files !== undefined) {ldelim}
          var files = $(this)[0].files;
          var name  = '';

          $.each(files, function(index, value) {ldelim}
            name += value.name+', ';
            {rdelim});

          $('#{$field.id}-name').val(name.slice(0, -2));
          {rdelim}
        else {ldelim} // Internet Explorer 9 Compatibility
          var name = $(this).val().split(/[\\/]/);
          $('#{$field.id}-name').val(name[name.length-1]);
          {rdelim}
        {rdelim});

      if (typeof {$field.id}_max_files !== 'undefined' && !$('#{$field.id}').length) {ldelim}
        $('#{$field.id}').closest('form').on('submit', function(e) {ldelim}
          if ($('#{$field.id}')[0].files.length > {$field.id}_max_files) {ldelim}
            e.preventDefault();
            alert('{l s='You can upload a maximum of %s files'|sprintf:1 mod='dwfproductextrafields' js=1}');
            {rdelim}
          {rdelim});
        {rdelim}
      {rdelim});
  </script>
{/function}

{function dwfproductextrafields_repeater}
  <div id="repeater_area_{$field.name}">
    <div class="row">
      <ul id="repeater_{$field.name}" class="col-12 col-xl-10">
        {foreach from=$field.values item=rep_fields name=fields}
          <li id="repeater_{$field.name}_{$smarty.foreach.fields.index}" class="card card-secondary repeater_item">
            <div class="card-header" id="heading_{$field.name}_{$smarty.foreach.fields.index}">
              <span class="btn float-left"><i class="material-icons m-0">reorder</i></span>
              <button type="button" class="js-deleteRepeaterEntry-{$field.name} btn btn-primary float-right"><i class="material-icons m-0">delete</i></button>
              <h5 class="mb-0">
                <button type="button" class="btn btn-link{if $field.collapsed_default} collapsed{/if}" data-toggle="collapse" data-target="#collapse_{$field.name}_{$smarty.foreach.fields.index}" aria-expanded="true" aria-controls="collapse_{$field.name}_{$smarty.foreach.fields.index}">
                  {$rep_fields[0].name} - {if $rep_fields[0].value|is_array}{$rep_fields[0].value[$defaultFormLanguage|intval]}{else}{$rep_fields[0].value}{/if}
                </button>
              </h5>
            </div>
            <div id="collapse_{$field.name}_{$smarty.foreach.fields.index}" class="collapse{if !$field.collapsed_default} show{/if}" aria-labelledby="heading_{$field.name}_{$smarty.foreach.fields.index}">
              <div class="card-body pb-2">
                {foreach from=$rep_fields item=field_value}
                  <div class="mb-2">
                  {assign var="current_field" value=['field_group' => "dwfproductextrafields[`$field.name`][`$smarty.foreach.fields.index`]", 'name' => $field_value.id, 'id' => "`$field.name`_`$smarty.foreach.fields.index`_`$field_value.id`", 'reference' => "`$field.name`|`$smarty.foreach.fields.index`|`$field_value.id`", 'value' => $field_value.value ]}

                  {if $field_value.type != 'checkbox'}
                    <label class="form-control-label" for="{$current_field.id}">
                      {$field_value.name}
                    </label>
                  {/if}

                  {if $field_value.type == 'checkbox'}
                    {dwfproductextrafields_checkbox
                    field=$current_field
                    label=$field_value.name
                    }

                  {elseif $field_value.type == 'image'}
                    {if $field_value.value}
                      {assign var="current_field" value=$current_field|array_merge:[ 'basename' => $field_value.basename, 'image' => $field_value.image, 'image_zoom' => $field_value.image_zoom, 'size' => $field_value.size, 'delete_url' => $field_value.delete_url ]}
                    {else}
                      {assign var="current_field" value=$current_field|array_merge:[ 'basename' => null, 'image' => null, 'image_zoom' => null, 'size' => 0, 'delete_url' => null ]}
                    {/if}

                    {dwfproductextrafields_image
                    languages=$languages
                    field=$current_field
                    }

                  {elseif $field_value.type == 'text'}
                    {dwfproductextrafields_text
                    languages=$languages
                    field=$current_field
                    }

                  {elseif $field_value.type == 'textarea'}
                    {dwfproductextrafields_textarea
                    languages=$languages
                    field=$current_field
                    }

                  {elseif $field_value.type == 'textarea_mce'}
                    <div class="form-control">
                      {dwfproductextrafields_textarea
                      autoload_rte=true
                      languages=$languages
                      field=$current_field
                      }
                    </div>
                  {/if}
                  </div>
                {/foreach}
              </div>
            </div>
          </li>
        {/foreach}
      </ul>
    </div>

    <div style="display:none;" id="repeater_{$field.name}_newLine">
      <div class="card-header" id="heading_{$field.name}_LINEID">
        <span class="btn float-left"><i class="material-icons m-0">reorder</i></span>
        <button class="js-deleteRepeaterEntry-{$field.name} btn btn-primary float-right" type="button"><i class="material-icons m-0">delete</i></button>
        <h5 class="mb-0">
          <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse_{$field.name}_LINEID" aria-expanded="true" aria-controls="collapse_{$field.name}_LINEID">
            {l s='New element (#%s)'|sprintf:'LINEID' mod='dwfproductextrafields'}
          </button>
        </h5>
      </div>
      <div id="collapse_{$field.name}_LINEID" class="collapse show" aria-labelledby="heading_{$field.name}_LINEID">
        <div class="card-body pb-2">
          {foreach from=$field.elements item=el}
            <div class="mb-2">
            {assign var="current_field" value=['field_group' => "dwfproductextrafields[`$field.name`][LINEID]", 'name' => $el.id, 'id' => "`$field.name`_LINEID_`$el.id`", 'reference' => "`$field.name`|LINEID|`$el.id`" ]}

              {if $el.type != 'checkbox'}
                <label class="form-control-label" for="{$current_field.id}">
                  {$el.name}
                </label>
              {/if}

              {if $el.type == 'checkbox'}
                {dwfproductextrafields_checkbox
                field=$current_field
                label=$field_value.name
                }

              {elseif $el.type == 'image'}
                {dwfproductextrafields_image
                languages=$languages
                field=$current_field|array_merge:[ 'basename' => null, 'image' => null, 'size' => 0, 'delete_url' => null ]
                }

              {elseif $el.type == 'text'}
                {dwfproductextrafields_text
                languages=$languages
                field=$current_field
                }

              {elseif $el.type == 'non-translatable_text'}
                {dwfproductextrafields_textarea
                languages=false
                field=$current_field
                }

              {elseif $el.type == 'textarea'}
                {dwfproductextrafields_textarea
                languages=$languages
                field=$current_field
                }

              {elseif $el.type == 'textarea_mce'}
                <div class="form-control">
                  {dwfproductextrafields_textarea
                  autoload_rte=true
                  languages=$languages
                  field=$current_field
                  new_item=true
                  }

                </div>
              {/if}
            </div>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="clearfix">
      <button id="{$field.name}-repeaterAddButton" type="button" name="submitAddRepeater" class="btn btn-normal">
        <i class="material-icons">add</i> {l s='Add element block' mod='dwfproductextrafields'}
      </button>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){ldelim}
      if($('#product-extraFields #repeater_area_{$field.name}').length > 0) {ldelim}
        $(document).on('click', ".js-deleteRepeaterEntry-{$field.name}", function(e) {ldelim}
          $(this).closest('li.repeater_item').hide( "slow", function() {ldelim}
            $(this).remove();
            {rdelim});
          {rdelim});

        $(document).on('click', "#{$field.name}-repeaterAddButton", function(e) {ldelim}
          var new_line = $('#product-extraFields #repeater_{$field.name}_newLine').html();
          new_line = new_line.replace(/LINEID/gm, $('#repeater_{$field.name} > li').length);
          $('#product-extraFields #repeater_{$field.name}').append('<li id="repeater_{$field.name}_' + $('#repeater_{$field.name} > li').length + '" class="card card-secondary repeater_item">' + new_line  + '</li>');

          $(new_line).find('textarea[class^="autoload_rte_"]').each(function() {ldelim}
            var editor_selector = $(this).attr('class');
            tinySetup({ldelim}
              editor_selector: editor_selector,
              setup : function(ed) {ldelim}
                ed.on('blur', function(ed) {ldelim}
                  tinyMCE.triggerSave();
                  {rdelim});
                {rdelim}
              {rdelim});
            {rdelim});
          {rdelim});

        $('#repeater_{$field.name}').sortable({ldelim}
          axis: 'y',
          placeholder: "ui-state-highlight card",
          forcePlaceholderSize: true
          {rdelim});
        {rdelim}
      {rdelim});
  </script>
{/function}


{if count($warnings)}
  <div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert">×</button>
    {l s='There are %d warnings.' mod='dwfproductextrafields' sprintf=count($warnings)}
    <ul {if count($warnings) > 1}style="display:none;"{/if} id="seeMore">
      {foreach $warnings as $warning}
        <li>{$warning}</li>
      {/foreach}
    </ul>
  </div>
{/if}

{if isset($product_id)}
  <input type="hidden" name="extraFields_loaded" value="1">
  <div id="product-extraFields" class="">
    <input type="hidden" name="ModuleDwfproductextrafields" value="1" />

    {foreach from=$fields item=field}
      <fieldset class="form-group">
        {if $field.type != 'checkbox'}
          <label class="form-control-label" for="{$field.name}">
            {if $field.type == 'image'}{$bullet_common_field} {/if}{$field.label[$defaultFormLanguage|intval]}
          </label>
        {/if}

        {if $field.hint[$defaultFormLanguage|intval]}<small class="text-muted"><em>({$field.hint[$defaultFormLanguage|intval]})</em></small>{/if}

        {if $field.type == 'checkbox'}
          {dwfproductextrafields_checkbox
          label=$field.label[$defaultFormLanguage|intval]
          field=$field
          }

        {elseif $field.type == 'selector'}
          {dwfproductextrafields_selector
          label=$field.label[$defaultFormLanguage|intval]
          field=$field
          }

        {elseif $field.type == 'repeater'}
          {dwfproductextrafields_repeater
          label=$field.label[$defaultFormLanguage|intval]
          field=$field
          }

        {elseif $field.type == 'color'}
          {dwfproductextrafields_color
          field=$field
          }

        {elseif $field.type == 'image'}
          {dwfproductextrafields_image
          languages=$languages
          field=$field
          }

        {elseif $field.type == 'date'}
          {dwfproductextrafields_date
          languages=$languages
          field=$field
          }

        {elseif $field.type == 'datetime'}
          {dwfproductextrafields_datetime
          languages=$languages
          field=$field
          }

        {elseif $field.type == 'integer' || $field.type == 'decimal'}
          {dwfproductextrafields_number
          field=$field
          }

        {elseif $field.type == 'price'}
          {dwfproductextrafields_price
          field=$field
          }

        {elseif $field.type == 'text'}
          {dwfproductextrafields_text
          languages=$languages
          field=$field
          }

        {elseif $field.type == 'non-translatable_text'}
          {dwfproductextrafields_textarea
          languages=false
          field=$field
          }

        {elseif $field.type == 'textarea'}
          {dwfproductextrafields_textarea
          languages=$languages
          field=$field
          }

        {elseif $field.type == 'textarea_mce'}
          <div class="form-control">
            {dwfproductextrafields_textarea
            autoload_rte=true
            languages=$languages
            field=$field
            }
          </div>
        {/if}

      </fieldset>
    {/foreach}

  </div>


  <style type="text/css">
    .bootstrap-datetimepicker-widget .btn .glyphicon::before {ldelim} font-family:'Material Icons'; {rdelim}
    .bootstrap-datetimepicker-widget .btn .glyphicon.glyphicon-chevron-up::before {ldelim} content:"keyboard_arrow_up"; {rdelim}
    .bootstrap-datetimepicker-widget .btn .glyphicon.glyphicon-chevron-down::before {ldelim} content:"keyboard_arrow_down"; {rdelim}
  </style>


  <script type="text/javascript">
    <!--
    if (!!$.prototype.mColorPicker) {ldelim}
      $.fn.mColorPicker.defaults.imageFolder = baseDir + 'img/admin/';
    {rdelim}

    var iso = '{$iso|addslashes|escape:'htmlall':'UTF-8'}';
    var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes|escape:'htmlall':'UTF-8'}';
    var ad = '{$ad|addslashes|escape:'htmlall':'UTF-8'}';

    $('#product-extraFields').magnificPopup({ldelim}
      delegate: 'a.open-image',
      type: 'image'
    {rdelim});
    //-->
  </script>
{/if}
