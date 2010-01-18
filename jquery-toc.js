jQuery(document).ready(function () {
  jQueryTOC.toc_started = false;
  jQuery(jQueryTOC.source_selector +' '+ jQueryTOC.header_tag).each(function (index) {

    // Create div if needed
    if(!jQueryTOC.toc_started) {
      jQuery(jQueryTOC.source_selector).prepend('<div id="'+jQueryTOC.output_id+'"><a name="top"></a><p><strong>'+ jQueryTOC.output_title +'</strong></p><ul></ul></div>');
      jQueryTOC.toc_started = true;
    }

    // Manipulate h* tag
    var header = jQuery(this);
    var headerId = 'header-'+index;
    header.attr('id', headerId);

    // Manipulate list
    var li = jQuery('<li></li>').appendTo('#' + jQueryTOC.output_id + ' ul');
    jQuery('<a></a>').text(header.text()).attr({ 'title': 'Jump to '+header.text(), 'href': '#'+headerId }).appendTo(li);
  });
  jQuery(jQueryTOC.source_selector +' '+ jQueryTOC.header_tag).wrapInner('<a title="Back to top" href="#top"></a>');
});