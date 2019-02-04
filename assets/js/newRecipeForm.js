$( "#addStep" ).click(function() {
  addPrototypeForm($('#recipeSteps'));
});

$( "#addIngredient" ).click(function() {
  addPrototypeForm($('#ingredients'));
});

$(document).on('click', '.remove', function() {
  $(this).parent().remove();
});

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
