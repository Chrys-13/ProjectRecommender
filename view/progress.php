<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
}
$user_id = $_SESSION['user_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>User Progress</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php require 'head.php'; ?>
    <div class="container mt-1">
        <input type="hidden" id="userId" value="<?php echo $user_id; ?>">
        <h2 style="color:green">Progress List</h2>
        <table class="table table-striped table-bordered rounded">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Project Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Difficulty</th>
                    <th scope="col">Estimated Time (Hours)</th>
                    <th scope="col">Prerequisites</th>
                    <th scope="col">Resources</th>
                    <th scope="col">Tags</th>
                    <th scope="col">Progress Percentage</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="progressList">
                <!-- Progress data will be appended here -->
            </tbody>
        </table>

        <style>
            .table {
                border-radius: 10px;
                overflow: hidden;
            }

            .table thead th {
                border-bottom: 2px solid #dee2e6;
            }

            .table tbody tr {
                border-bottom: 1px solid #dee2e6;
            }
        </style>
    </div>
    </div>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Submit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedbackForm">
                        <input type="hidden" id="feedbackProjectId" name="project_id">
                        <input type="hidden" id="feedbackUserId" name="user_id">
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedbackText" name="feedback" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const apiUrl = '../api/user_progress.php';
            const userId = $('#userId').val();

            // Fetch and display user progress
            function fetchProgress() {
                $.ajax({
                    url: apiUrl,
                    type: 'GET',
                    data: {
                        operation: 'fetchSpecificUserProgressAllProjects',
                        user_id: userId
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#progressList').empty();
                        response.forEach(progress => {
                            $('#progressList').append(`    <tr>
                                                <td>${progress.title}</td>
                                                <td>${progress.description}</td>
                                                <td>${progress.difficulty}</td>
                                                <td>${progress.estimated_time}</td>
                                                <td>${progress.prerequisites}</td>
                                                <td>${progress.resources}</td>
                                                <td>${progress.tag_names}</td>
                                                <td>${progress.progress_percentage}%</td>
                                                <td>
                                                    ${progress.progress_percentage < 100 ? `
                                                    <button class="btn btn-primary mark-complete-button" data-project-id="${progress.project_id}" data-user-id="<?php echo $user_id; ?>">
                                                        Are you done with this project?
                                                    </button>` : ''}
                                                </td>
                                            </tr>
                                        `);
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'An error occurred while fetching progress.', 'error');
                    }
                });
            }

            $(document).on('click', '.mark-complete-button', function() {
                var projectId = $(this).data('project-id');
                var userId = $(this).data('user-id');
                $('#feedbackProjectId').val(projectId);
                $('#feedbackUserId').val(userId);
                $('#feedbackModal').modal('show');
            });

            $('#feedbackForm').on('submit', function(event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: '../api/projects.php',
                    type: 'GET',
                    data: formData + '&operation=completeProject',
                    success: function(response) {
                        var jsonResponse = response;
                        if (jsonResponse.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Feedback submitted and project marked as complete!',
                            }).then(() => {
                                $('#feedbackModal').modal('hide');
                                fetchProgress();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to submit feedback. Please try again.',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to submit feedback. Please try again.',
                        });
                    }
                });
            });
            // Initial fetch
            fetchProgress();
        });
    </script>
</body>

</html>