class EventManager {
    constructor() {
        this.urlBase = "/events"
        this.obtenerDataInicial()
        this.inicializarFormulario()
        this.guardarEvento()
    }

    obtenerDataInicial() {
        let url = this.urlBase + "/all/" + localStorage.email
        $.get(url, (response) => {
            this.inicializarCalendario(response)
        })
    }

    eliminarEvento(evento) {
        let eventId = evento.id
        $.post('/events/delete/', {
            id: eventId,
            email: localStorage.email
        }, (response) => {
            alert(response)
        })
    }

    guardarEvento() {
        $('.addButton').on('click', (ev) => {
            ev.preventDefault()
            let nombre = $('#titulo').val(),
                start = $('#start_date').val(),
                title = $('#titulo').val(),
                end = '',
                start_hour = '',
                end_hour = '';
            var allDay = 1;

            if (!$('#allDay').is(':checked')) {
                end = $('#end_date').val()
                start_hour = $('#start_hour').val()
                end_hour = $('#end_hour').val()
                start = start + 'T' + start_hour + '.000Z'
                end = end + 'T' + end_hour + '.000Z'
                allDay = 0
            }
            let url = this.urlBase + "/new"
            if (title != "" && start != "") {
                let ev = {
                    email: localStorage.email,
                    title: title,
                    start: start,
                    end: end,
                    allDay: allDay
                }
                $.post(url, ev, (response) => {
                    if (response.id != null) {
                        ev['id'] = response.id;
                        $('.calendario').fullCalendar('renderEvent', ev)
                        this.inicializarFormulario()
                    }
                })
            } else {
                alert("Complete los campos obligatorios para el evento")
            }
        })
    }

    actualizarEvento(evento) {
        let allDay = evento.allDay ? 1 : 0;

        let url = this.urlBase + "/update"
        let ev = {
            id: evento.id,
            email: localStorage.email,
            title: evento.title,
            start: moment(evento.start).format('YYYY-MM-DD') + 'T' + moment(evento.start).format('HH:mm:ss') + '.000Z',
            end: moment(evento.end).format('YYYY-MM-DD') + 'T' + moment(evento.end).format('HH:mm:ss') + '.000Z',
            allDay: allDay
        }
        $.post(url, ev, (response) => {
            alert(response)
        })
    }

    inicializarFormulario() {
        $('#start_date, #titulo, #end_date').val('');
        $('#start_date, #end_date').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm:ss',
            interval: 30,
            minTime: '5',
            maxTime: '23:59:59',
            defaultTime: '',
            startTime: '5:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        $('#allDay').on('change', function() {
            if (this.checked) {
                $('.timepicker, #end_date').attr("disabled", "disabled")
            } else {
                $('.timepicker, #end_date').removeAttr("disabled")
            }
        })
        $('#start_hour, #end_hour').val('');
        $('#allDay').attr("checked", false);
        $('.timepicker, #end_date').removeAttr("disabled")
    }

    inicializarCalendario(eventos) {
        $('.calendario').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,basicDay'
            },
            defaultDate: new Date(),
            navLinks: true,
            editable: true,
            eventLimit: true,
            droppable: true,
            dragRevertDuration: 0,
            timeFormat: 'H:mm',
            eventDrop: (event) => {
                this.actualizarEvento(event)
            },
            events: eventos,
            eventDragStart: (event, jsEvent) => {
                $('.delete').find('img').attr('src', "img/trash-open.png");
                $('.delete').css('background-color', '#a70f19')
            },
            eventDragStop: (event, jsEvent) => {
                var trashEl = $('.delete');
                var ofs = trashEl.offset();
                var x1 = ofs.left;
                var x2 = ofs.left + trashEl.outerWidth(true);
                var y1 = ofs.top;
                var y2 = ofs.top + trashEl.outerHeight(true);
                if (jsEvent.pageX >= x1 && jsEvent.pageX <= x2 &&
                    jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
                    this.eliminarEvento(event)
                    $('.calendario').fullCalendar('removeEvents', event.id);
                }
                $('.delete').find('img').attr('src', "img/delete.png");
            }
        })
    }
}

const Manager = new EventManager()

$(function() {
    $('.logout-container').on('click', function() {
        localStorage.removeItem('email');
        window.location.href = "http://localhost:3000/index.html";
    });
});
