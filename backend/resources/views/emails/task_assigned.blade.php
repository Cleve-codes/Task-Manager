<h2>New Task Assigned</h2>
<p>Hello,</p>
<p>A new task has been assigned to you:</p>
<ul>
    <li><strong>Title:</strong> {{ $task->title }}</li>
    <li><strong>Description:</strong> {{ $task->description }}</li>
    <li><strong>Status:</strong> {{ $task->status }}</li>
    <li><strong>Deadline:</strong> {{ $task->deadline }}</li>
</ul>
<p>Please log in to your dashboard to view more details.</p>
