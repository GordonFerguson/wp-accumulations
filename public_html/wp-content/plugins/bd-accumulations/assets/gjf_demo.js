
/* Instrument the accumulation form so selecting a challenge will
 * set show the current accumulation for it.
* */
jQuery(document ).ready(init_accumulator)

function init_accumulator() {
    $ = jQuery.noConflict(true);
    console.log ('gjf_demo probe 0' )
    const the_block_jq = $('.mdg-accumulation-form')
    // todo check that we have an accumulator to manage
    if (the_block_jq.length > 0) {
        const acc_select_jq = $("#challenge")
        // alert('found' + acc_select_jq.length )
        acc_select_jq.on('change', gjf_reset_val)
    }
}
function gjf_reset_val() {
    const challenge_js = $(this). children("option:selected");
    const soFar = challenge_js.data('hours');
    console.log('reset val', soFar);
    $('#accumulator').val(soFar);


}
