<?php
require_once __DIR__ . '/../include/auth.php'; 
require_once __DIR__ . '/../config/db.php';  

// Fetch approved visits from visit_calendar
$visits = [];
$sql = "SELECT * FROM visit_calendar";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Try to find the school name column (could be schoolName or school_name)
        $schoolName = isset($row['schoolName']) ? $row['schoolName'] : (isset($row['school_name']) ? $row['school_name'] : 'Unknown School');
        // Try to find the visit date column (could be visitDate or visit_date)
        $visitDate = isset($row['visitDate']) ? $row['visitDate'] : (isset($row['visit_date']) ? $row['visit_date'] : date('Y-m-d'));

        $visits[] = [
            'title' => $schoolName,
            'start' => $visitDate
        ];
    }
}
$visits_json = json_encode($visits);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Calendar</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
</head>

<body class="calendar-page">
    <div class="container">
        <div class="header">
            <h1>📅 Visit Calendar</h1>
            <p>View all approved school visit schedules</p>
        </div>

        <div class="controls">
            <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
            <div class="filter-group">
                <span class="view-label">View:</span>
                <button class="filter-btn active" onclick="filterCalendar('all')">All Events</button>
                <button class="filter-btn" onclick="filterCalendar('upcoming')">Upcoming</button>
                <button class="filter-btn" onclick="filterCalendar('past')">Past</button>
            </div>
        </div>

        <div class="calendar-wrapper">
            <div id="calendar"></div>
        </div>

        <div class="event-details" id="eventDetails">
            <h3>Event Details</h3>
            <p><strong>School:</strong> <span id="detailSchool">-</span></p>
            <p><strong>Date:</strong> <span id="detailDate">-</span></p>
            <p><strong>Contact:</strong> <span id="detailContact">-</span></p>
            <p><strong>Email:</strong> <span id="detailEmail">-</span></p>
            <p><strong>Phone:</strong> <span id="detailPhone">-</span></p>
            <p><strong>Number of Students:</strong> <span id="detailStudents">-</span></p>
            <p><strong>Purpose:</strong> <span id="detailPurpose">-</span></p>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-dot legend-approved"></div>
                <span>Approved Visits</span>
            </div>
        </div>
    </div>

    <script>
        const allVisits = <?= $visits_json ?>;
        let calendar;
        let allEventsData = [];

        document.addEventListener('DOMContentLoaded', function() {
            fetchEventDetails();

            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                events: allVisits,
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                datesSet: function() {
                    filterCalendar('all');
                },
                height: 'auto',
                contentHeight: 'auto'
            });

            calendar.render();
        });

        function fetchEventDetails() {
            // This would ideally fetch more details from the server
            // For now, we'll work with what we have from the events
        }

        function showEventDetails(event) {
            const details = document.getElementById('eventDetails');
            document.getElementById('detailSchool').textContent = event.title;
            document.getElementById('detailDate').textContent = event.start ? new Date(event.start).toLocaleDateString() : '-';

            // In a real scenario, fetch additional details from the server
            details.classList.add('show');
        }

        function filterCalendar(type) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            let filteredEvents = allVisits;

            if (type === 'upcoming') {
                filteredEvents = allVisits.filter(e => new Date(e.start) >= today);
            } else if (type === 'past') {
                filteredEvents = allVisits.filter(e => new Date(e.start) < today);
            }

            calendar.removeAllEvents();
            calendar.addEventSource(filteredEvents);
        }
    </script>

</body>

</html>