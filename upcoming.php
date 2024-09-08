<?php include 'includes/header.php'; ?>

<div id="next-event" class="vip-event-section">
    <h2 class="vip-heading">Next Upcoming Show</h2>
    <div id="event-details" class="event-details-container">
        <!-- Event details will be loaded here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        try {
            const response = await fetch('events.json');
            if (!response.ok) throw new Error('Failed to load events data.');

            const events = await response.json();
            const upcomingEvent = getNextUpcomingEvent(events);

            if (upcomingEvent) {
                document.getElementById('event-details').innerHTML = `
                    <div class="event-card">
                        ${upcomingEvent.image ? `<img src="${upcomingEvent.image}" alt="${upcomingEvent.title}" class="event-image">` : ''}
                        <div class="event-info">
                            <h3 class="event-name">${upcomingEvent.title}</h3>
                            <p class="event-date"><strong>Date:</strong> ${new Date(upcomingEvent.date).toLocaleDateString()}</p>
                            ${upcomingEvent.artists.length ? `<p class="event-artists"><strong>Artists:</strong> ${upcomingEvent.artists.join(', ')}</p>` : ''}
                            ${upcomingEvent.description ? `<p class="event-description"><strong>Description:</strong> ${upcomingEvent.description}</p>` : ''}
                            ${upcomingEvent.url ? `<button class="buy-tickets-btn" onclick="window.open('${upcomingEvent.url}', '_blank')">Buy Tickets</button>` : ''}
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('event-details').innerHTML = '<p class="no-events">No upcoming events found.</p>';
            }
        } catch (error) {
            console.error('Error fetching events:', error);
            document.getElementById('event-details').innerHTML = '<p class="error-message">Failed to load events. Please try again later.</p>';
        }
    });

    function getNextUpcomingEvent(events) {
        const now = new Date();
        return events
            .map(event => ({ ...event, date: new Date(event.date) }))
            .filter(event => event.date >= now)
            .sort((a, b) => a.date - b.date)[0];
    }
</script>

<?php include 'includes/footer.php'; ?>
