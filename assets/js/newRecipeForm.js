$( '#ingredients').ready(function() {
    $( '.measuring-unit').each(function( index ) {
        helpMacro($(this));
    });
});
$( "#addStep" ).click(function() {
    addPrototypeForm($('#recipeSteps'));
});

$( "#addIngredient" ).click(function() {
    addPrototypeForm($('#ingredients'));
});

$(document).on('click', '.remove', function() {
    $(this).parent().remove();
});

$('#ingredients').on('change', '.measuring-unit', function() {
    helpMacro($(this));
});

function helpMacro(elt) {
    let id  = elt.attr('id');
    let  ProteinHelpId = id.replace(/_measuringUnit/g, '_protein_help');
    let  fatHelpId = id.replace(/_measuringUnit/g, '_fat_help');
    let  CarbohydrateHelpId = id.replace(/_measuringUnit/g, '_carbohydrate_help');

    let text = 'Pour ';
    if(elt.val() === 'g'|| elt.val() === 'kg' ) {
        text+='100g';
    } else if (elt.val() === 'mL') {
        text+='100ml'
    } else if (elt.val() === 'cL' || elt.val() === 'L') {
        text+='100cl'
    } else {
        text+='1 pièce'
    }

    $('#'+ProteinHelpId).text(text)
    $('#'+fatHelpId).text(text)
    $('#'+CarbohydrateHelpId).text(text)
}

function addPrototypeForm(collectionHolder) {
    let liNode = $('<li></li>');
    let prototype = collectionHolder.data('prototype');
    let index = collectionHolder.data('index') ;
    let newForm = prototype.replace(/__name__/g, index);
    let removeLink  = $('<button class="remove">Supprimer</button>');


    newForm = newForm.replace(/label__/g, '');
    collectionHolder.data('index', index + 1);

    liNode.append(newForm);
    liNode.append(removeLink);

    removeLink.on('click', function(e) {
        e.preventDefault();

        liNode.remove();
    });
    collectionHolder.append(liNode);

}