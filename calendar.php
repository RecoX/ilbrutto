<?php 
include 'includes/header.php'; 
?>

<div class="calendar-container">
    <h2 class="calendar-heading">Event Calendar</h2>
    <div id="calendar" class="calendar"></div>
</div>

<!-- Load FullCalendar and its dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', async function () {
    const calendarEl = document.getElementById('calendar');

    try {
        const response = await fetch('events.json');
        if (!response.ok) throw new Error('Failed to load events data.');
        
        const events = await response.json();

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'standard',
            aspectRatio: 1.5,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            events: events.map(event => ({
                title: `${event.title} (with ${event.artists.join(', ')})`,
                start: event.date,
                extendedProps: {
                    image: event.image,
                    artists: event.artists,
                    description: event.description,
                    url: event.url
                }
            })),
            eventClick: function (info) {
                const { extendedProps } = info.event;
                const { url } = extendedProps;
                
                if (url) {
                    // Open the buy tickets link in a new tab
                    window.open(url, '_blank');
                } else {
                    alert('No tickets available for this event.');
                }
            },
            loading: function (isLoading) {
                if (isLoading) {
                    calendarEl.classList.add('loading');
                } else {
                    calendarEl.classList.remove('loading');
                }
            }
        });

        calendar.render();
    } catch (error) {
        console.error('Error fetching events:', error);
        const calendarContainer = document.querySelector('.calendar-container');
        calendarContainer.innerHTML = '<p class="error-message">Failed to load events. Please try again later.</p>';
    }
});

</script>

<?php 
include 'includes/footer.php'; 
?>
