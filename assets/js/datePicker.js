document.addEventListener('DOMContentLoaded', function () {
  const options = {
    format: 'dd-mm-yyyy',
    autoClose: true,
    yearRange: 100,
    maxDate: new Date(),
    i18n: {
      cancel: 'Annuler',
      done: 'Enregistrer',
      previousMonth: '‹',
      nextMonth: '›',
      months: [
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Juillet',
        'Aout',
        'Septembre',
        'Octobre',
        'Novembre',
        'Decembre'
      ],
      monthsShort: [
        'Jan',
        'Fév',
        'Mar',
        'Avr',
        'Mai',
        'Jui',
        'Jui',
        'Aou',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
      ],
      weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
      weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
      weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']
    },
  };
  const elems = document.querySelector('.datepicker');
  M.Datepicker.init(elems, options);
});
