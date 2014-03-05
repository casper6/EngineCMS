$(function() {
  	$.datepicker.regional['ru'] = {
	    closeText: 'Закрыть',
	    prevText: '&#x3c;Предыдущий',
	    nextText: 'Следующий&#x3e;',
	    currentText: 'Сегодня',
	    monthNames: ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'],
	    monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
	    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
	    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
	    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
	    weekHeader: 'Не',
	    dateFormat: 'dd.mm.yy',
	    firstDay: 1,
	    isRTL: false,
	    showMonthAfterYear: false,
	    yearSuffix: ''
	};
    $.datepicker.setDefaults($.datepicker.regional['ru']);
});