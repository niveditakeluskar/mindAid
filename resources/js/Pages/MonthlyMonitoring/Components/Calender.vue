<template>
    <div class="card-body device_box" id="calender">
        <FullCalendar :options='calendarOptions'>
        </FullCalendar>
    </div>
</template> 
<script>
import axios from 'axios';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction'
export default {
    props: {
        patientId: Number,
        deviceID: Number
    },
    components: {
        FullCalendar, // make the <FullCalendar> tag available
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                dateClick: this.handleDateClick,
                datesSet: this.handleMonthChange,
                headerToolbar: {
                    right: "today,prev,next",
                    left: "title",
                },
                weekends: true,
                events: '/rpm/calender-data/' + this.patientId + '/' + this.deviceID,
            },
        };
    },
    methods: {
        handleMonthChange: function () {
            this.$emit('callParentMethod');
        },
    }
};
</script>
<style>
.fc-event {
    background: aliceblue !important;
}
</style>