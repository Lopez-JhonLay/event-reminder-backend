<h2>Hello {{ $event->user->first_name }},</h2>

<p>This is a friendly reminder that you have an event scheduled for <strong>tomorrow</strong>:</p>

<ul>
  <li><strong>Title:</strong> {{ $event->title }}</li>
  <li><strong>Description:</strong> {{ $event->description ?? 'No description' }}</li>
  <li><strong>Date:</strong> {{ $event->event_date }}</li>
  <li><strong>Time:</strong> {{ $event->start_time }} - {{ $event->end_time }}</li>
</ul>

<p>— Event Reminder System</p>
