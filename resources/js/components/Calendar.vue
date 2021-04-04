<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Calendar</li>
                </ol>
            </nav>
        </div>
        <div class="row container">
            <div class="col-12">
                <FullCalendar :options="calendarOptions" />
            </div>
        </div>
    </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
export default {
    props: ['user'],
    name: "Calendar",
    components: {
        FullCalendar // make the <FullCalendar> tag available
    },
    data() {
        return {
            errors: [],
            calendarOptions: {
                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                events: []
            }
        }
    },
    methods:{
        renderPage(){
            axios.get('/api/calendar/'+this.user.id)
            .then(response => {
                this.calendarOptions.events = response.data;
            }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during data fetch!',
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        handleDateClick(arg){
        }
    },
    mounted() {
        this.renderPage();
    }
}
</script>

<style scoped>

</style>
