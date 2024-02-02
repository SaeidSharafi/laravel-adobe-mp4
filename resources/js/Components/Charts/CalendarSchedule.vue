<template>
    <FullCalendar :options="calendarOptions" ref="calendar"/>
</template>

<script setup>
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import FullCalendar from "@fullcalendar/vue3";
import {useI18n} from "vue-i18n";
import {createVNode, onMounted, reactive, ref, render} from 'vue';
import {Link} from '@inertiajs/vue3';

const calendar = ref(null);
const {t} = useI18n({});
const props = defineProps({
    schedules: {
        type: Array,
        default: null,
        required: true
    },
    vrangeStart: {
        type: Array,
        default: null,
    },
    vrangeEnd: {
        type: Array,
        default: null,
    },
    rangeStart: {
        type: String,
        default: null,
    },
    rangeEnd: {
        type: String,
        default: null,
    },
    firstDay: {
        type: Number,
        default: 6,
    }
})
const customDayCellContent = (arg) => {
    console.log(arg)
    const dayNumber = arg.date.toLocaleString('fa',{day:'numeric'});
    const monthName = arg.date.toLocaleString('fa',{month:'short'});

    if (arg.view.type==='dayGrid') {
        return `${dayNumber} ${monthName}`;
    }
    return '';
};
const calendarOptions = reactive({
    plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
    initialView: 'dayGrid',
    headerToolbar: {
        center: 'dayGrid,dayGridWeek',
        right: 'today,customprev,customnext',
    },
    buttonText: {
        today: t('app.calendar.today'),
        month: t('app.calendar.month'),
        week: t('app.calendar.week'),
        day: t('app.calendar.day'),
        list: t('app.calendar.list'),
    },
    views: {
        dayGrid: {
            buttonText: t('app.calendar.month'),
            titleFormat: {year: 'numeric'},
            dayHeaderFormat: {weekday: 'short', omitCommas: true},
            headerToolbar: {
                center: 'dayGrid,dayGridWeek',
                right: 'customtoday,customprev,customnext',
            },
            dayCellContent: customDayCellContent,
        },
        dayGridWeek: {
            titleFormat: {year: 'numeric', month: '2-digit', day: '2-digit'},
            dayHeaderFormat: {weekday: 'short', month: 'numeric', day: 'numeric', omitCommas: true},
            headerToolbar: {
                center: 'dayGrid,dayGridWeek',
                right: 'today,prev,next',
            },
        },
    },
    eventOrder: false,
    events: props.schedules,
    locale: 'fa',
    direction: 'rtl',
    // firstDay: props.firstDay,
    customButtons: {
        customtoday: {
            text: t('app.calendar.today'),
            click: function () {
                calendar.value.getApi().prev();
                calendarOptions.visibleRange = {
                    start: props.vrangeStart[0],
                    end: props.vrangeEnd[0]
                }
                console.log('today')
            }
        },
        customprev: {
            text: '<',
            click: function () {
                calendar.value.getApi().prev();
                calendarOptions.visibleRange = {
                    start: props.vrangeStart[0],
                    end: props.vrangeEnd[0]
                }
                console.log('prev')
            }
        },
        customnext: {
            text: '>',
            click: function () {
                calendar.value.getApi().next();
                calendarOptions.visibleRange = {
                    start: props.vrangeStart[1],
                    end: props.vrangeEnd[1]
                }
            }
        },
    },
    visibleRange: {
        start: props.vrangeStart[0],
        end: props.vrangeEnd[0]
    },
    validRange: {
        start: props.rangeStart,
        end: props.rangeEnd
    },
    eventContent: (info) => {
        let items = info.event.title.split("|");

        const linkComponent = createVNode(Link, {
            href: route('treatment.infertility.appointment.edit', {id: info.event.id}),
            class: "text-right flex items-stretch text-sm",
            style: "direction: rtl"
        }, [
            createVNode('div', {class: 'border-l px-1 py-1'}, items[1]),
            createVNode('div', {class: 'border-r px-1 py-1'}, items[0])
        ]);

        const container = document.createElement('div');
        render(linkComponent, container);

        return {domNodes: Array.from(container.childNodes)};
    },
})
onMounted(() => {
})

</script>

<style>
.fc-direction-ltr .fc-timegrid-more-link {
    right: auto;
    left: 0;
}

.fc-timegrid-more-link {
    background: #0000007d;
    color: #fff;
    padding: 7px;
    width: 30px;
    border-color: #000;
    box-shadow: 0 0 0 1px #000;
}

</style>
