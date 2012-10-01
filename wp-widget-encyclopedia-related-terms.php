<?php If (!Class_Exists('wp_widget_encyclopedia_related_terms')){
Class wp_widget_encyclopedia_related_terms Extends WP_Widget {
  var $encyclopedia;

  Function __construct(){
    If (IsSet($GLOBALS['wp_plugin_encyclopedia']) && Is_Object($GLOBALS['wp_plugin_encyclopedia']))
      $this->encyclopedia = $GLOBALS['wp_plugin_encyclopedia'];
    Else
      return False;

    // Setup the Widget data
    parent::__construct (
      False,
      $this->t('Encyclopedia Related Terms'),
      Array('description' => $this->t('Displays encyclopedia terms which are related to the current one as list.'))
    );
  }

  Function t ($text, $context = ''){
    return $this->encyclopedia->t($text, $context);
  }

  Function Default_Options(){
    // Default settings
    return Array(
      'number'  => 5,
      'exclude' => False
    );
  }

  Function Load_Options($options){
    $options = (ARRAY) $options;

    // Delete empty values
    ForEach ($options AS $key => $value)
      If (!$value) Unset($options[$key]);

    // Load options
    $this->arr_option = Array_Merge ($this->Default_Options(), $options);
  }

  Function Get_Option($key, $default = False){
    If (IsSet($this->arr_option[$key]) && $this->arr_option[$key])
      return $this->arr_option[$key];
    Else
      return $default;
  }

  Function Set_Option($key, $value){
    $this->arr_option[$key] = $value;
  }

  Function Form ($settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);
    ?>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('title') ?>"><?php Echo $this->t('Title') ?></label>:
      <input type="text" id="<?php Echo $this->Get_Field_Id('title') ?>" name="<?php Echo $this->get_field_name('title')?>" value="<?php Echo HTMLSpecialChars($this->get_option('title')) ?>"><br>
      <small><?php Echo $this->t('Leave blank to use the widget default title.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('number') ?>"><?php Echo $this->t('Number') ?></label>:
      <input type="text" id="<?php Echo $this->Get_Field_Id('number') ?>" name="<?php Echo $this->get_field_name('number')?>" value="<?php Echo HTMLSpecialChars($this->get_option('number')) ?>" size="4"><br>
      <small><?php Echo $this->t('The number of related terms you want to show.') ?></small>
    </p>

    <?php
  }

  Function Widget ($args, $settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);

    // Load the Query
    $term_query = $this->encyclopedia->get_tag_related_terms(Null, $this->get_option('number'));
    If (!$term_query) return;

    // Display Widget
    Echo $args['before_widget'];
    Echo $args['before_title'] . Apply_Filters('widget_title', $this->get_option('title'), $settings, $this->id_base) . $args['after_title'];
    Echo $this->encyclopedia->Load_Template('encyclopedia-related-terms-widget.php', Array('term_query' => $term_query));
    Echo $args['after_widget'];

    // Reset Post data
    WP_Reset_Postdata();
  }

  Function Update ($new_settings, $old_settings){
    return $new_settings;
  }

} /* End of Class */
} /* End of If-Class-Exists-Condition */
/* End of File */