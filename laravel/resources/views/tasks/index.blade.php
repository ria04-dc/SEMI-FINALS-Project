<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Task Manager</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #d8e2fd;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #1a1a1a;
            padding: 40px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Top Header Navigation */
        .top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .header-banner {
            background-color: #7895e3;
            color: #ffffff;
            padding: 24px 36px;
            border-radius: 20px;
            font-size: 38px;
            font-weight: 500;
            letter-spacing: -0.5px;
            box-shadow: 0 4px 15px rgba(120, 149, 227, 0.2);
            flex-grow: 1;
            max-width: 500px;
        }

        .nav-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            background-color: #f4f7ff;
            border: 2px solid #7895e3;
            border-radius: 30px;
            padding: 12px 24px;
            font-size: 16px;
            font-family: inherit;
            color: #333;
            outline: none;
            width: 250px;
            box-shadow: 2px 3px 0px #7895e3;
        }

        .btn-add-task {
            background-color: #7895e3;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-size: 20px;
            font-weight: 500;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(120, 149, 227, 0.3);
        }

        .btn-add-task:hover {
            background-color: #6482d3;
            transform: translateY(-2px);
        }

        /* Task Flow Section */
        .section-title {
            font-size: 28px;
            font-weight: 500;
            margin: 35px 0 20px 0;
            color: #0d0d0d;
        }

        .cards-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: #7895e3;
            border: 3px solid #ffffff;
            border-radius: 16px;
            height: 130px;
            padding: 20px 25px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(120, 149, 227, 0.25);
        }

        .stat-card .label {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 8px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
        }

        /* Success Flash Message */
        .alert-success {
            background-color: #ffffff;
            color: #4361ee;
            border: 2px solid #7895e3;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Custom Table Design */
        .task-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 8px;
        }

        .task-table th {
            background-color: #7895e3;
            color: #ffffff;
            font-size: 20px;
            font-weight: normal;
            padding: 16px 20px;
            text-align: left;
            border-radius: 8px;
            border: 1px solid #ffffff;
        }

        .task-table td {
            background-color: #ebf1ff;
            border: 2px solid #ffffff;
            color: #2b2b2b;
            font-size: 16px;
            padding: 16px 20px;
            border-radius: 8px;
        }

        .action-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-toggle {
            background-color: #7895e3;
            color: white;
        }

        .btn-edit {
            background-color: #ffffff;
            color: #7895e3;
            border: 1px solid #7895e3;
        }

        .btn-delete {
            background-color: #ff6b6b;
            color: white;
        }

        .badge-pending {
            color: #d97706;
            font-weight: bold;
        }

        .badge-completed {
            color: #16a34a;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <!-- Top Navigation Header -->
    <div class="top-nav">
        <div class="header-banner">
            Personal Task Manager
        </div>

        <div class="nav-controls">
            <div class="search-box">
                <input type="text" id="searchInput" class="search-input" placeholder="Search task..." onkeyup="filterTasks()">
            </div>
            <a href="{{ route('tasks.create') }}" class="btn-add-task">+ Add Task</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Task Flow Cards -->
    <h2 class="section-title">My task flow</h2>

    <div class="cards-row">
        <div class="stat-card">
            <div class="label">Total Tasks</div>
            <div class="number">{{ $tasks->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending Tasks</div>
            <div class="number">{{ $tasks->where('status', 'Pending')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Completed Tasks</div>
            <div class="number">{{ $tasks->where('status', 'Completed')->count() }}</div>
        </div>
    </div>

    <!-- Task Data Table -->
    <table class="task-table" id="tasksTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Description</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td><strong>{{ $task->task_name }}</strong></td>
                    <td>{{ $task->description ?? '-' }}</td>
                    <td>
                        <span class="{{ $task->status === 'Pending' ? 'badge-pending' : 'badge-completed' }}">
                            {{ $task->status }}
                        </span>
                    </td>
                    <td>{{ $task->due_date ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-toggle" title="Toggle Status">Status</button>
                            </form>

                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn-action btn-edit">Edit</a>

                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; padding: 25px;">
                        No tasks created yet. Click "+ Add Task" to get started!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script>
    function filterTasks() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.querySelectorAll('#tasksTable tbody tr');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    }
</script>

</body>
</html>