<template>
    <div class="card-body device_box" id="calender">
        <FullCalendar :options='calendarOptions'>
        </FullCalendar>
    </div>
</template> 
<script>
import {
  reactive,
  ref,
  onMounted,
  computed,
  watch,
} from '../../commonImports';

import axios from 'axios';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import moment from 'moment';
export default {
    props: {
        patientId: Number,
        deviceID: Number
    },
    components: {
        FullCalendar, // make the <FullCalendar> tag available
    },

    setup(props, { emit }) {
    const calendarOptions = reactive({
      plugins: [dayGridPlugin, interactionPlugin],
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      eventLimit: true,
      views: {
        timeGridMonth: {
          eventLimit: 1 // adjust to 6 only for timeGridWeek/timeGridDay
        }
      },
      eventTimeFormat: { 
          hour: '2-digit',
          minute: '2-digit',
          hour12:false
      },
      events: [],
      eventMouseEnter: handleEventMouseEnter,
      eventMouseLeave: handleEventMouseLeave,
      selectable: true,
      selectHelper: true,
      datesSet: handleMonthChange
    });

    const loadEvents = () => {
      axios.get(`/rpm/calender-data/${props.patientId}/${props.deviceID}`)
        .then(response => {
          calendarOptions.events = response.data;
        })
        .catch(error => {
          console.error("Error fetching events:", error);
        });
    };

    onMounted(loadEvents);
    watch([() => props.patientId, () => props.deviceID], loadEvents);

    function handleEventMouseEnter({ event, el }) {
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltipevent';
      tooltip.style.width = 'auto';
      tooltip.style.borderStyle = 'solid';
      tooltip.style.borderColor = '#2cb8e';
      tooltip.style.background = '#fff';
      tooltip.style.color = '#2cb8e';
      tooltip.style.position = 'absolute';
      tooltip.style.zIndex = '10001';
      tooltip.innerHTML = `Title: ${event.title}<br>Time: ${moment(event.start).format('MM-DD-YYYY HH:mm a')}`;
      document.body.appendChild(tooltip);

      el.addEventListener('mousemove', e => {
        tooltip.style.top = `${e.pageY + 10}px`;
        tooltip.style.left = `${e.pageX + 20}px`;
      });

      el.addEventListener('mouseout', () => {
        if (tooltip.parentNode) {
          tooltip.parentNode.removeChild(tooltip);
        }
      });
    }

    function handleEventMouseLeave({ event, el }) {
      const tooltip = document.querySelector('.tooltipevent');
      if (tooltip && tooltip.parentNode) {
        tooltip.parentNode.removeChild(tooltip);
      }
    }

    function handleMonthChange() {
      emit('callParentMethod');
    }

    return {
      calendarOptions
    };
  }
};
</script>
<style>
.tooltipevent {
  
  z-index: 10001;
}
.fc-event {
    background: aliceblue !important;
}
</style>