<?php include 'includes/header.php'; ?>

<div id="events-list">
    <h2>Upcoming Events</h2>
    <div id="events-container">
        <!-- Events will be dynamically loaded here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const response = await fetch('events.json');
            if (!response.ok) throw new Error('Failed to load events data.');

            const events = await response.json();
            const upcomingEvents = getUpcomingEvents(events);

            if (upcomingEvents.length > 0) {
                document.getElementById('events-container').innerHTML = upcomingEvents.map(event => `
            <div class="event">
                ${event.image ? `<img src="${event.image}" alt="${event.title}">` : ''}
                <div class="event-info">
                    <h3>${event.title}</h3>
                    <p><strong>Date:</strong> ${new Date(event.date).toLocaleDateString()}</p>
                    ${event.artists.length ? `<p><strong>Artists:</strong> ${event.artists.join(', ')}</p>` : ''}
                    ${event.description ? `<p><strong>Description:</strong> ${event.description}</p>` : ''}
                    ${event.url ? `<button class="buy-tickets-btn" onclick="window.open('${event.url}', '_blank')">Buy Tickets</button>` : ''}
                </div>
            </div>
        `).join('');
            } else {
                document.getElementById('events-container').innerHTML = '<p>No upcoming events found.</p>';
            }
        } catch (error) {
            console.error('Error fetching events:', error);
            document.getElementById('events-container').innerHTML = '<p>Failed to load events. Please try again later.</p>';
        }

    });

    function getUpcomingEvents(events) {
        const now = new Date();
        const yesterday = new Date();
        yesterday.setDate(now.getDate() - 1);

        return events
            .map(event => ({
                ...event,
                date: new Date(event.date)
            }))
            .filter(event => {
                const eventDate = event.date;
                // Include events from yesterday onwards
                return eventDate >= yesterday;
            })
            .sort((a, b) => a.date - b.date);
    }
</script>

<style>
    #events-list {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        background-color: #1f1f1f;
        border-radius: 10px;
    }

    #events-list h2 {
        font-size: 2.5em;
        margin-bottom: 30px;
        color: #f4b400;
        text-align: center;
    }

    #events-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .event {
        background-color: #2a2a2a;
        border-radius: 10px;
        overflow: hidden;
        flex: 1 1 calc(33.333% - 20px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s;
    }

    .event:hover {
        transform: translateY(-5px);
    }

    .event img {
        width: 100%;
        height: auto;
    }

    .event-info {
        padding: 15px;
    }

    .event-info h3 {
        margin: 0 0 10px;
        font-size: 1.5em;
        color: #f4b400;
    }

    .event-info p {
        margin: 5px 0;
        font-size: 1.1em;
    }

    .event-info p strong {
        color: #f4b400;
    }
</style>

<?php include 'includes/footer.php'; ?>